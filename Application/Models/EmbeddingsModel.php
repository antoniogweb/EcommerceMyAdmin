<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class EmbeddingsModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='embeddings';
		$this->_idFields='id_embedding';
		
		$this->salvaDataModifica = true;
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'categoria' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
        );
    }
    
    public function getModello()
	{
		$idModelloPredefinito = (int)AimodelliModel::g(false)->getIdPredefinito();
		
		if (!AimodelliModel::getModulo($idModelloPredefinito)->isAttivo())
			return 0;
		
		return $idModelloPredefinito;
	}
    
    public function ricercaSemantica($query, $lingua = null, $numeroMassimoRisultati = 10, $log = null)
	{
		$idModelloPredefinito = $this->getModello();
		
		if (!$idModelloPredefinito)
			return array();
		
		if (trim($query))
		{
			$response = AimodelliModel::getModulo($idModelloPredefinito)->embeddings($query);
			
			if (trim($response))
			{
				$queryEmbedding = array_map('floatval',json_decode($response, true));
				
				$res = $this->clear()->where(array(
					"lingua"	=>	sanitizeAll($lingua),
				))->send(false);
				
				$scores = [];
				
				$maxScore = 0.0;
				
				foreach ($res as $r)
				{
					$emb = json_decode($r["embeddings"], true);
					
					// Assicurati che l'embedding sia numerico (in caso arrivi da JSON con stringhe)
					$emb = array_map('floatval', $emb);
					
					$score = Vector::cosineSimilarity($emb, $queryEmbedding);
					
					if ($score > $maxScore)
						$maxScore = $score;
					
					$scores[] = [
						'id'    => $r["id_embedding"],
						'score' => $score,
						'id_page'	=>	$r["id_page"],
						'id_marchio'	=>	$r["id_marchio"],
						'lingua'	=>	$r["lingua"],
					];
				}
				
				// Ordina per score decrescente (stable quanto basta per questo caso)
				usort($scores, static fn($a, $b) => $b['score'] <=> $a['score']);
				
				// Troncamento ai topK
				if ($numeroMassimoRisultati > 0 && count($scores) > $numeroMassimoRisultati) {
					$scores = array_slice($scores, 0, $numeroMassimoRisultati);
				}
				
				if ($maxScore < 0.4)
					return array();
				
				$idPages = $idMarchi = array();
				
				foreach ($scores as $sc)
				{
					if ($sc["id_page"])
						$idPages[] = $sc["id_page"];
					
					if ($sc["id_marchio"])
						$idMarchi[] = $sc["id_marchio"];
				}
				
				return array(
					"pages"		=>	$idPages,
					"marchi"	=>	$idMarchi,
				);
			}
		}
	}
    
    public function getCategoryEmbeddings($idCategory = 0, $lingua = null, $log = null)
	{
		$cModel = new CategoriesModel();
		
		$children = $cModel->children((int)$idCategory, true);
		$children = forceIntDeep($children);
		
		$pModel = new PagesModel();
		$idPages = $pModel->clear()->select("id_page")->addWhereAttivo()->aWhere(array(
			"in" => array("-id_c" => $children),
		))->toList("id_page")->send();
		
		foreach ($idPages as $idPage)
		{
			$this->getPageEmbeddings((int)$idPage, $lingua, $log);
		}
	}
    
    public function getPageEmbeddings($idPage = 0, $lingua = null, $log = null)
	{
		$idModelloPredefinito = $this->getModello();
		
		if (!$idModelloPredefinito)
			return;
		
		$pModel = new PagesModel();
		$record = $pModel->selectId((int)$idPage);
		
		if (!empty($record))
		{
			// Estraggo le lingue attive
			LingueModel::getValoriAttivi();
			
			// Estraggo la lingua pricipale del frontend
			$codiceLinguaPrincipale = LingueModel::getPrincipaleFrontend();
			
			$ctModel = new ContenutitradottiModel();
			
			foreach (LingueModel::$valoriAttivi as $codice => $descrizione)
			{
				// Se è impostata la lingua e non è la lingua corrente, continua
				if ($lingua && $lingua != $codice)
					continue;
				
				$codice = strtolower($codice);
				TraduzioniModel::sLingua($codice, "front");
				$strutturaProdotti = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti(null, (int)$idPage, 0, false, 0, 1);
				TraduzioniModel::rLingua();
				
				if (count($strutturaProdotti) > 0)
				{
					$o = $strutturaProdotti[0];
					
					$embeddingArray = array();
					
					if (trim($o["titolo"]))
						$embeddingArray[] = strip_tags(htmlentitydecode($o["titolo"]));
					
					if (trim($o["sottotitolo"]))
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["sottotitolo"])));
					
					if (trim($o["descrizione"]))
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["descrizione"])));
					
					$embeddingText = implode(" ", $embeddingArray);
					
					if (!trim($embeddingText))
						return;
					
					$response = AimodelliModel::getModulo($idModelloPredefinito)->embeddings($embeddingText);
					
					if (trim($response))
					{
						if ($log)
						{
							$logText = "EMBEDDINGS ID PAGE: ".(int)$idPage." - LINGUA: ".$codice;
							
							$log->writeString($logText);
							
							echo $logText."\n";
						}
						
						$this->del(null, array(
							"id_page"	=>	(int)$idPage,
							"lingua"	=>	$codice,
						));
						
						$this->sValues(array(
							"id_page"	=>	(int)$idPage,
							"lingua"	=>	$codice,
							"id_c"		=>	(int)$record["id_c"],
							"embeddings"	=>	$response,
						));
						
						$this->insert();
					}
				}
			}
		}
	}
}
