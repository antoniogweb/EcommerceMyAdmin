<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

require_once(LIBRARY."/Application/Modules/AI/Retrieval/ArticleChunker.php");

class EmbeddingsModel extends GenericModel
{
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
    
    public static function queryEscluseParoleTitolo($query, $lingua)
	{
		$paroleArray = explode(" ", $query);
		$paroleArrayNoTitle = array();
		
		if ($lingua == v("lingua_default_frontend"))
			$model = new PagesModel();
		else
			$model = new ContenutitradottiModel();
		
		foreach ($paroleArray as $parola)
		{
			$model->clear()->where(array(
				"lk"	=>	array(
					"title"	=>	sanitizeAll($parola),
				)
			));
			
			if ($lingua != v("lingua_default_frontend"))
				$model->aWhere(array(
					"lingua"	=>	sanitizeAll($lingua),
				));
				
			$numero = $model->rowNumber();
			
			if (strlen($parola) <= 4 || $numero <= 10)
				$paroleArrayNoTitle[] = $parola;
		}
		
		return implode(" ", $paroleArrayNoTitle);
	}
    
    public static function estraiIdMarchiDaQuery($query)
    {
		$paroleArray = explode(" ", $query);
		
		$mModel = new MarchiModel();
		
		$arrayIdMarchi = array();
		
		foreach ($paroleArray as $parola)
		{
			$idMarchio = (int)$mModel->clear()->select("id_marchio")->where(array(
				"titolo"	=>	sanitizeAll($parola),
			))->field("id_marchio");
			
			if ($idMarchio)
				$arrayIdMarchi[] = $idMarchio;
		}
		
		return $arrayIdMarchi;
	}
	
    public static function ricercaSemantica($query, $eModel = null, $lingua = null, $numeroMassimoRisultati = 10, $log = null)
	{
		$idModelloPredefinito = AimodelliModel::g(false)->getIdModelForEmbeddings();
		
		$idPages = array();
		$estratti = array();
		
		if (!$idModelloPredefinito)
			return array(
				"pages"		=>	array(),
				"estratti"	=>	array(),
			);
		
		if (trim($query))
		{
			$response = $responseBody = AimodelliModel::getModulo($idModelloPredefinito, true)->embeddings($query);
			
			$queryBody = self::queryEscluseParoleTitolo($query, $lingua);
			
			if (trim($queryBody) != trim($query))
				$responseBody = AimodelliModel::getModulo($idModelloPredefinito, true)->embeddings($queryBody);
			
			if (trim($response))
			{
				$queryEmbedding = array_map('floatval',json_decode($response, true));
				$queryEmbeddingBody = array_map('floatval',json_decode($responseBody, true));
				$queryEmbeddingNorm = Vector::l2Norm($queryEmbedding);
				$queryEmbeddingBodyNorm = Vector::l2Norm($queryEmbeddingBody);
				
				if (!isset($eModel))
				{
					$eModel = new EmbeddingsModel();
					$eModel->clear()->select("id_embedding,id_page,embeddings_title,embeddings_body,testo");
					
					// Cerco i marchi dalla query
					$arrayIdMarchi = self::estraiIdMarchiDaQuery($query);
					
					if (count($arrayIdMarchi) > 0)
					{
						$eModel->sWhere(array(
							"EXISTS ( select 1 from pages where pages.id_page = embeddings.id_page and pages.id_marchio in (".$eModel->placeholdersFromArray($arrayIdMarchi)."))",
							forceIntDeep($arrayIdMarchi)
						));
					}
				}
				
				$scores = [];
				
				$maxScore = 0.0;
				
				$scoreMinimo = number_format(v("score_minimo_ricerca_semantica") / 100,2,".","");
				$percScoreTitolo = number_format(v("perc_score_title_ricerca_semantica") / 100,2,".","");
				$percScoreBody = 1 - $percScoreTitolo;
				
				$limitStart = 0;
				$limit = 200;
				
				$indiceScore = 0;
				$arrayIdPageIndice = array();
				
				while ($res = $eModel->aWhere(array(
					"gt"	=>	array(
						"embeddings.id_embedding"	=>	(int)$limitStart,
					),
					"lingua"	=>	sanitizeAll($lingua),
				))->limit($limit)->orderBy("embeddings.id_embedding")->send(false))
				{
					// $a = microtime(true);
					// echo $eModel->getQuery();
					
					foreach ($res as $r)
					{
						$limitStart = $r["id_embedding"];
// 						$emb = json_decode($r["embeddings"], true);
// 						$emb = array_map('floatval', $emb);
// 						
// 						$score = Vector::cosineSimilarity($emb, $queryEmbedding);
						
						$embTitle = json_decode($r["embeddings_title"], true);
						// $embTitle = array_map('floatval', $embTitle);
						
						$scoreTitle = Vector::cosineSimilarityWithKnownNorm($embTitle, $queryEmbedding, $queryEmbeddingNorm);
						
						$embBody = json_decode($r["embeddings_body"], true);
						// $embBody = array_map('floatval', $embBody);
						
						$scoreBody = Vector::cosineSimilarityWithKnownNorm($embBody, $queryEmbeddingBody, $queryEmbeddingBodyNorm);
						
						$score = $percScoreTitolo * $scoreTitle + $percScoreBody * $scoreBody;
						
						if ($score < $scoreMinimo)
							continue;
						
						// Cerco nelle search queries
	// 					if (v("attiva_embeddings_su_informazioni_strutturate") && trim($r["embeddings_search_queries"]))
	// 					{
	// 						$embSq = json_decode($r["embeddings_search_queries"], true);
	// 					
	// 						$embSq = array_map('floatval', $embSq);
	// 						
	// 						$scoreSq = Vector::cosineSimilarity($embSq, $queryEmbedding);
	// 						
	// 						if ($scoreSq > $score)
	// 							$score = $scoreSq;
	// 					}
						
						if ($score > $maxScore)
							$maxScore = $score;
						
						if (isset($arrayIdPageIndice[$r["id_page"]]))
						{
							$indice = $arrayIdPageIndice[$r["id_page"]];
							
							if ($score > $scores[$indice]["score"])
							{
								if ($scores[$indice]["numero"] < 3)
								{
									$scores[$indice]["score"] = $score;
									$scores[$indice]["estratto"] .= " ...".($r["testo"] ?? '');
									$scores[$indice]["numero"]++;
									
									continue;
								}
								else
									unset($arrayIdPageIndice[$r["id_page"]]);
							}
							else
							{
								continue;
							}
							
						}
						
						$arrayIdPageIndice[$r["id_page"]] = $indiceScore;
						
						$scores[$indiceScore] = [
							'id'    => $r["id_embedding"],
							'score' => $score,
							'id_page'	=>	$r["id_page"],
							'estratto'	=>	$r["testo"] ?? '',
							'numero'	=>	1,
							// 'lingua'	=>	$r["lingua"],
						];
						
						$indiceScore++;
					}
					// echo "COS:".(microtime(true) - $a)."<br />";
				}
				
				// Ordina per score decrescente
				usort($scores, static fn($a, $b) => $b['score'] <=> $a['score']);
				
				// print_r($scores);
				
				// Troncamento ai topK
				if ($numeroMassimoRisultati > 0 && count($scores) > $numeroMassimoRisultati) {
					$scores = array_slice($scores, 0, $numeroMassimoRisultati);
				}
				
				if ($maxScore < $scoreMinimo)
					return array(
						"pages"		=>	array(),
						"estratti"	=>	array(),
					);
				
				foreach ($scores as $sc)
				{
					if ($sc["id_page"])
					{
						$idPages[] = $sc["id_page"];
						
						$estratti[$sc["id_page"]] = $sc["estratto"];
					}
				}
			}
		}
		
		return array(
			"pages"		=>	$idPages,
			"estratti"	=>	$estratti,
		);
	}
    
    public function getCategoryEmbeddings($idCategory = 0, $lingua = null, $withChunks = false, $log = null, $maxLen = 600, int $overlap = 100, $rigenera = 0)
	{
		$cModel = new CategoriesModel();
		
		$children = $cModel->children((int)$idCategory, true);
		$children = forceIntDeep($children);
		
		$pModel = new PagesModel();
		$idPages = $pModel->clear()->select("pages.id_page")
			->addWhereAttivo()
			->aWhere(array(
				"in" => array("-id_c" => $children),
			))
			->toList("pages.id_page");
		
		if (!$rigenera)
			$pModel->left("embeddings")->on("embeddings.id_page = pages.id_page")->sWhere("embeddings.id_page IS NULL");
		
		$idPages = $pModel->send();
		
		foreach ($idPages as $idPage)
		{
			$this->getPageEmbeddings((int)$idPage, $lingua, $withChunks, $log, $maxLen, $overlap);
		}
	}
    
    public function getPageEmbeddings($idPage = 0, $lingua = null, $withChunks = false, $log = null, int $maxLen = 600, int $overlap = 100)
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
					
					$embeddingArray = $testoPerElaborazioneArray = $testoPerChunksArray = array();
					$categoria = $responseSearchQuery = "";
					
					if (trim($o["titolo"]))
					{
						$embeddingArray[] = strip_tags(htmlentitydecode($o["titolo"]));
						$testoPerElaborazioneArray[] = "Title: ".strip_tags(htmlentitydecode($o["titolo"]));
						$testoPerChunksArray[] = "<h1>".htmlentitydecode($o["titolo"])."</h1>";
					}
					
					if (trim(nullToBlank($o["marchio"])))
						$testoPerElaborazioneArray[] = "Brand: ".strip_tags(htmlentitydecode($o["marchio"]));
					
					if (isset($o["categorie"][0]) && count($o["categorie"][0]) > 0)
					{
						$categoria = strip_tags(implode(" > ",htmlentitydecodeDeep($o["categorie"][0])));
						
						$testoPerElaborazioneArray[] = "Categories: ".$categoria;
					}
					
					if (trim($o["sottotitolo"]))
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["sottotitolo"])));
					
					if (trim($o["descrizione"]))
					{
						$embeddingArray[] = trim(strip_tags(htmlentitydecode($o["descrizione"])));
						$testoPerElaborazioneArray[] = "Description: ".strip_tags(htmlentitydecode($o["descrizione"]));
						$testoPerChunksArray[] = htmlentitydecode($o["descrizione"]);
					}
					
					if (trim($o["descrizione_2"]))
						$testoPerChunksArray[] = htmlentitydecode($o["descrizione_2"]);
					
					if (trim($o["descrizione_3"]))
						$testoPerChunksArray[] = htmlentitydecode($o["descrizione_3"]);
					
					if (trim($o["descrizione_4"]))
						$testoPerChunksArray[] = htmlentitydecode($o["descrizione_4"]);
					
					$embeddingText = implode(" ", $embeddingArray);
					$searchQueryEmbeddingText = $datiStrutturati = "";
					
					$testoPerElaborazione = implode("\n", $testoPerElaborazioneArray);
					$testoPerChunks = implode("\n\n", $testoPerChunksArray);
					
					if ($withChunks)
						$chunks = ArticleChunker::getChunksTextsForEmbeddings($testoPerChunks,$maxLen,$overlap,$categoria);
					else
						$chunks = array(
							"title"	=>	strip_tags(htmlentitydecode($o["titolo"])),
							"full"	=>	$embeddingText,
							"text"	=>	$embeddingText
						);
					
					$tpf = tpf("Elementi/AI/RAG/Embeddings/prompt.txt");
					
// 					if (v("attiva_embeddings_su_informazioni_strutturate") && trim($testoPerElaborazione) && is_file($tpf))
// 					{
// 						$idModelloPredefinito = AimodelliModel::g(false)->getIdPredefinito();
// 						
// 						if ($idModelloPredefinito)
// 						{
// 							ob_start();
// 							include $tpf;
// 							$istruzioni = ob_get_clean();
// 							$istruzioni = str_replace("[LINGUA]", $codice, $istruzioni);
// 							
// 							$messaggio = AimodelliModel::getModulo($idModelloPredefinito, true)->setMessaggio($testoPerElaborazione);
// 							
// 							list($res, $datiStrutturati) = AimodelliModel::getModulo($idModelloPredefinito, true)->chat(array($messaggio), "", $istruzioni, "low");
// 							
// 							$outputArray = json_decode($datiStrutturati, true);
// 							
// 							if (isset($outputArray["semantic_text"]) && trim($outputArray["semantic_text"]))
// 								$embeddingText = $outputArray["semantic_text"];
// 							
// 							if (isset($outputArray["search_queries"]) && is_array($outputArray["search_queries"]) && count($outputArray["search_queries"]) > 0)
// 								$searchQueryEmbeddingText = implode(" ", $outputArray["search_queries"]);
// 						}
// 					}
					
					$this->del(null, array(
						"id_page"	=>	(int)$idPage,
						"lingua"	=>	$codice,
					));
					
					$backupEmbeddings = array();
					
					foreach ($chunks as $chunk)
					{
						$embeddingText = $chunk["full"];
						$estratto = $chunk["text"];
						$title = $chunk["title"];
						
						if (!trim($embeddingText))
							continue;

						$response = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($embeddingText);
						
						$responseBody = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($estratto);
						
						if (isset($backupEmbeddings[$title]))
						{
							$responseTitle = $backupEmbeddings[$title];
						}
						else
						{
							$responseTitle = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($title);
							$backupEmbeddings[$title] = $responseTitle;
						}
						
						// echo $title."\n\n\n".$estratto."\n\n\n";continue;
						// if (trim($searchQueryEmbeddingText))
						// 	$responseSearchQuery = AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($searchQueryEmbeddingText);
						
						if (trim($response) || trim($responseTitle) || trim($responseBody))
						{
							if ($log)
							{
								$logText = "EMBEDDINGS ID PAGE: ".(int)$idPage." - LINGUA: ".$codice;
								
								$log->writeString($logText);
								
								echo $logText."\n";
							}
							
							$this->sValues(array(
								"id_page"	=>	(int)$idPage,
								"lingua"	=>	$codice,
								"id_c"		=>	(int)$record["id_c"],
								"embeddings"	=>	$response,
								"embeddings_search_queries"	=>	$responseSearchQuery,
								"dati_strutturati"	=>	$datiStrutturati,
								"testo"		=>	trim($estratto),
								"embeddings_title"	=>	$responseTitle,
								"embeddings_body"	=>	$responseBody,
							), "sanitizeDb");
							
							$this->insert();
						}
					}
				}
			}
		}
	}
}
