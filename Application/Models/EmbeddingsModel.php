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
    
    public static function ricercaSemantica($query, $eModel = null, $lingua = null, $numeroMassimoRisultati = 10, $log = null)
	{
		$idModelloPredefinito = AimodelliModel::g(false)->getIdModelForEmbeddings();
		
		$idPages = array();
		$idMarchi = array();
		
		if (!$idModelloPredefinito)
			return array(
				"pages"		=>	array(),
				"marchi"	=>	array(),
			);
		
		if (trim($query))
		{
			$response = AimodelliModel::getModulo($idModelloPredefinito, true)->embeddings($query);
			
			if (trim($response))
			{
				$queryEmbedding = array_map('floatval',json_decode($response, true));
				
				if (!isset($eModel))
				{
					$eModel = new EmbeddingsModel();
					$eModel->clear();
				}
				
				$res = $eModel->aWhere(array(
					"lingua"	=>	sanitizeAll($lingua),
				))->send(false);
				// echo $eModel->getQuery()."\n";
				$scores = [];
				
				$maxScore = 0.0;
				
				foreach ($res as $r)
				{
					$emb = json_decode($r["embeddings"], true);
					
					$emb = array_map('floatval', $emb);
					
					$score = Vector::cosineSimilarity($emb, $queryEmbedding);
					
					// Cerco nelle search queries
					if (v("attiva_embeddings_su_informazioni_strutturate") && trim($r["embeddings_search_queries"]))
					{
						$embSq = json_decode($r["embeddings_search_queries"], true);
					
						$embSq = array_map('floatval', $embSq);
						
						$scoreSq = Vector::cosineSimilarity($embSq, $queryEmbedding);
						
						if ($scoreSq > $score)
							$score = $scoreSq;
					}
					
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
				
				// Ordina per score decrescente
				usort($scores, static fn($a, $b) => $b['score'] <=> $a['score']);
				
				// Troncamento ai topK
				if ($numeroMassimoRisultati > 0 && count($scores) > $numeroMassimoRisultati) {
					$scores = array_slice($scores, 0, $numeroMassimoRisultati);
				}
				
				if ($maxScore < 0.5)
					return array(
						"pages"		=>	array(),
						"marchi"	=>	array(),
					);
				
				$idPages = $idMarchi = array();
				
				foreach ($scores as $sc)
				{
					if ($sc["id_page"])
						$idPages[] = $sc["id_page"];
					
					if ($sc["id_marchio"])
						$idMarchi[] = $sc["id_marchio"];
				}
			}
		}
		
		return array(
			"pages"		=>	$idPages,
			"marchi"	=>	$idMarchi,
		);
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
		$idModelloPredefinitoEmbeddings = AimodelliModel::g(false)->getIdModelForEmbeddings();
		
		if (!$idModelloPredefinitoEmbeddings)
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
					
					$embeddingArray = $testoPerElaborazioneArray = array();
					
					if (trim($o["titolo"]))
					{
						$embeddingArray[] = strip_tags(htmlentitydecode($o["titolo"]));
						$testoPerElaborazioneArray[] = "Title: ".strip_tags(htmlentitydecode($o["titolo"]));
					}
					
					if (trim($o["marchio"]))
						$testoPerElaborazioneArray[] = "Brand: ".strip_tags(htmlentitydecode($o["marchio"]));
					
					if (isset($o["categorie"][0]) && count($o["categorie"][0]) > 0)
						$testoPerElaborazioneArray[] = "Categories: ".strip_tags(implode(" > ",htmlentitydecodeDeep($o["categorie"][0])));
					
					if (trim($o["sottotitolo"]))
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["sottotitolo"])));
					
					if (trim($o["descrizione"]))
					{
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["descrizione"])));
						$testoPerElaborazioneArray[] = "Description: ".strip_tags(htmlentitydecode($o["descrizione"]));
					}
					
					$embeddingText = implode(" ", $embeddingArray);
					$searchQueryEmbeddingText = $datiStrutturati = "";
					
					$testoPerElaborazione = implode("\n", $testoPerElaborazioneArray);
					$tpf = tpf("Elementi/AI/RAG/Embeddings/prompt.txt");
					
					if (v("attiva_embeddings_su_informazioni_strutturate") && trim($testoPerElaborazione) && is_file($tpf))
					{
						$idModelloPredefinito = AimodelliModel::g(false)->getIdPredefinito();
						
						if ($idModelloPredefinito)
						{
							ob_start();
							include $tpf;
							$istruzioni = ob_get_clean();
							$istruzioni = str_replace("[LINGUA]", $codice, $istruzioni);
							
							$messaggio = AimodelliModel::getModulo($idModelloPredefinito, true)->setMessaggio($testoPerElaborazione);
							
							list($res, $datiStrutturati) = AimodelliModel::getModulo($idModelloPredefinito, true)->chat(array($messaggio), "", $istruzioni, "low");
							
							$outputArray = json_decode($datiStrutturati, true);
							
							if (isset($outputArray["semantic_text"]) && trim($outputArray["semantic_text"]))
								$embeddingText = $outputArray["semantic_text"];
							
							if (isset($outputArray["search_queries"]) && is_array($outputArray["search_queries"]) && count($outputArray["search_queries"]) > 0)
								$searchQueryEmbeddingText = implode(" ", $outputArray["search_queries"]);
						}
					}
					
					if (!trim($embeddingText))
						return;

					$response = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($embeddingText);
					
					$responseSearchQuery = "";
					
					if (trim($searchQueryEmbeddingText))
						$responseSearchQuery = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($searchQueryEmbeddingText);
					
					if (trim($response) || trim($responseSearchQuery))
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
							"embeddings_search_queries"	=>	$responseSearchQuery,
							"dati_strutturati"	=>	$datiStrutturati,
						), "sanitizeDb");
						
						$this->insert();
					}
				}
			}
		}
	}
}
