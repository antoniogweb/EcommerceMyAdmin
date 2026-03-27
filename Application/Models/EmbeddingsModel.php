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
	
	public static function normalizeEmbeddingJson($json)
	{
		if (!is_string($json) || !trim($json))
			return "";
		
		$decoded = json_decode($json, true);
		
		if (!is_array($decoded) || count($decoded) === 0)
			return "";
		
		$normalized = Vector::normalize(array_map('floatval', $decoded));
		
		foreach ($normalized as $i => $value)
			$normalized[$i] = round($value, 6);
		
		return json_encode($normalized);
	}

	public static function embeddingJsonToBinary($json)
	{
		if (!is_string($json) || !trim($json))
			return "";
		
		$decoded = json_decode($json, true);
		
		if (!is_array($decoded) || count($decoded) === 0)
			return "";
		
		$vector = array_map('floatval', array_values($decoded));
		
		return pack('g*', ...$vector);
	}

	public static function embeddingBinaryToArray($binary)
	{
		if (!is_string($binary) || $binary === "")
			return array();
		
		$decoded = unpack('g*', $binary);
		
		if (!is_array($decoded) || count($decoded) === 0)
			return array();
		
		return array_values($decoded);
	}
	
	public static function normalizzaVettori()
	{
		$model = new self();
		$limitStart = 0;
		$limit = 200;
		
		while ($res = $model->clear()->select("id_embedding,embeddings,embeddings_title,embeddings_body,embeddings_search_queries")->aWhere(array(
			"gt"	=>	array(
				"id_embedding"	=>	(int)$limitStart,
			),
		))->limit($limit)->orderBy("id_embedding")->send(false))
		{
			foreach ($res as $r)
			{
				$limitStart = (int)$r["id_embedding"];
				
				$model->sValues(array(
					"embeddings"	=>	self::normalizeEmbeddingJson($r["embeddings"]),
					"embeddings_title"	=>	self::normalizeEmbeddingJson($r["embeddings_title"]),
					"embeddings_body"	=>	self::normalizeEmbeddingJson($r["embeddings_body"]),
					"embeddings_search_queries"	=>	self::normalizeEmbeddingJson($r["embeddings_search_queries"]),
				), "sanitizeDb");
				
				$model->update((int)$r["id_embedding"]);
				
				echo "Normalizzato ID EMBEDDING ".(int)$r["id_embedding"]."\n";
			}
		}
	}

	public static function convertiVettoriBinari()
	{
		$model = new self();
		$limitStart = 0;
		$limit = 200;
		
		while ($res = $model->clear()->select("id_embedding,embeddings_title,embeddings_body")->aWhere(array(
			"gt"	=>	array(
				"id_embedding"	=>	(int)$limitStart,
			),
		))->limit($limit)->orderBy("id_embedding")->send(false))
		{
			foreach ($res as $r)
			{
				$limitStart = (int)$r["id_embedding"];
				
				$model->sValues(array(
					"embeddings_title_bin"	=>	self::embeddingJsonToBinary($r["embeddings_title"]),
					"embeddings_body_bin"	=>	self::embeddingJsonToBinary($r["embeddings_body"]),
				), "sanitizeDb");
				
				$model->update((int)$r["id_embedding"]);
				
				echo "Convertito in binario ID EMBEDDING ".(int)$r["id_embedding"]."\n";
			}
		}
	}

	protected static function normalizeSearchText($text)
	{
		$text = strip_tags((string)$text);
		$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$text = mb_strtolower($text, 'UTF-8');
		$text = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $text);
		
		return trim(preg_replace('/\s+/u', ' ', $text));
	}

	protected static function getQueryTokens($query)
	{
		$normalized = self::normalizeSearchText($query);
		
		if ($normalized === '')
			return array();
		
		$tokens = preg_split('/\s+/u', $normalized);
		$tokens = array_filter($tokens, function ($token) {
			return mb_strlen($token, 'UTF-8') >= 3;
		});
		
		return array_values(array_unique($tokens));
	}

	protected static function lexicalMatchScore($text, array $queryTokens, $normalizedQuery)
	{
		$normalizedText = self::normalizeSearchText(trim((string)$text));
		
		if ($normalizedText === '' || empty($queryTokens))
			return 0.0;
		
		$matches = 0;
		
		foreach ($queryTokens as $token)
		{
			if (preg_match('/(^|\s)'.preg_quote($token, '/').'($|\s)/u', $normalizedText))
				$matches++;
		}
		
		$coverage = $matches / count($queryTokens);
		$phraseBonus = ($normalizedQuery !== '' && mb_strpos($normalizedText, $normalizedQuery, 0, 'UTF-8') !== false) ? 1.0 : 0.0;
		
		return min(1.0, ($coverage * 0.7) + ($phraseBonus * 0.3));
	}

	protected static function compareEstrattiMatch(array $a, array $b)
	{
		$lexicalComparison = $b["lexical_score"] <=> $a["lexical_score"];
		
		if ($lexicalComparison !== 0)
			return $lexicalComparison;
		
		$scoreComparison = $b["score"] <=> $a["score"];
		
		if ($scoreComparison !== 0)
			return $scoreComparison;
		
		return mb_strlen($b["text"], 'UTF-8') <=> mb_strlen($a["text"], 'UTF-8');
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
			$queryTokens = self::getQueryTokens($query);
			$normalizedQueryText = self::normalizeSearchText($query);
			
			$response = $responseBody = AimodelliModel::getModulo($idModelloPredefinito, true)->embeddings($query);
			
			$queryBody = self::queryEscluseParoleTitolo($query, $lingua);
			
			if (trim($queryBody) != trim($query))
				$responseBody = AimodelliModel::getModulo($idModelloPredefinito, true)->embeddings($queryBody);
			
			if (trim($response))
			{
				$queryEmbedding = Vector::normalize(array_map('floatval',json_decode($response, true)));
				$queryEmbeddingBody = Vector::normalize(array_map('floatval',json_decode($responseBody, true)));
				
				if (!isset($eModel))
				{
					$eModel = new EmbeddingsModel();
					$eModel->clear()->select("id_embedding,id_page,embeddings_title,embeddings_body,embeddings_title_bin,embeddings_body_bin,testo,embeddings.title");
					
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
				$percScoreLexical = number_format(v("perc_score_lexical") / 100,2,".","");
				
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
					// echo $eModel->getQuery();
					
					foreach ($res as $r)
					{
						$limitStart = $r["id_embedding"];
// 						$emb = json_decode($r["embeddings"], true);
// 						$emb = array_map('floatval', $emb);
// 						
// 						$score = Vector::cosineSimilarity($emb, $queryEmbedding);
						
						$embTitle = self::embeddingBinaryToArray($r["embeddings_title_bin"] ?? '');
						
						// if (empty($embTitle))
						// 	$embTitle = json_decode($r["embeddings_title"], true);
						
						$scoreTitle = Vector::dotProduct($embTitle, $queryEmbedding);
						
						$embBody = self::embeddingBinaryToArray($r["embeddings_body_bin"] ?? '');
						
						// if (empty($embBody))
						// 	$embBody = json_decode($r["embeddings_body"], true);
						
						$scoreBody = Vector::dotProduct($embBody, $queryEmbeddingBody);
						
						$semanticScore = $percScoreTitolo * $scoreTitle + $percScoreBody * $scoreBody;
						$titleLexicalScore = self::lexicalMatchScore($r["title"] ?? '', $queryTokens, $normalizedQueryText);
						$textLexicalScore = self::lexicalMatchScore($r["testo"] ?? '', $queryTokens, $normalizedQueryText);
						
						$lexicalScore = ($percScoreTitolo * $titleLexicalScore) + ($percScoreBody * $textLexicalScore);
						
						$score = $semanticScore + ((1 - $semanticScore) * $percScoreLexical * $lexicalScore);
						
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
						
						$testoEstratto = trim($r["testo"] ?? '');
						
						if (isset($arrayIdPageIndice[$r["id_page"]]))
						{
							$indice = $arrayIdPageIndice[$r["id_page"]];
							
							if ($score > $scores[$indice]["score"])
								$scores[$indice]["score"] = $score;
							
							if ($testoEstratto !== "")
							{
								$giaPresente = false;
								
								foreach ($scores[$indice]["estratti_match"] as $estrattoMatch)
								{
									if ($estrattoMatch["text"] === $testoEstratto)
									{
										$giaPresente = true;
										break;
									}
								}
								
								if (!$giaPresente)
								{
									$scores[$indice]["estratti_match"][] = array(
										"score"	=>	$score,
										"lexical_score"	=>	$lexicalScore,
										"text"	=>	$testoEstratto,
									);
									
									usort($scores[$indice]["estratti_match"], array(__CLASS__, 'compareEstrattiMatch'));
									
									if (count($scores[$indice]["estratti_match"]) > 2)
										$scores[$indice]["estratti_match"] = array_slice($scores[$indice]["estratti_match"], 0, 2);
								}
								
								$scores[$indice]["numero"] = count($scores[$indice]["estratti_match"]);
								$scores[$indice]["estratto"] = implode(" ...", array_column($scores[$indice]["estratti_match"], "text"));
							}
							
							continue;
						}
						
						$arrayIdPageIndice[$r["id_page"]] = $indiceScore;
						
						$estrattiMatch = array();
						
							if ($testoEstratto !== "")
							{
								$estrattiMatch[] = array(
									"score"	=>	$score,
									"lexical_score"	=>	$lexicalScore,
									"text"	=>	$testoEstratto,
								);
							}
						
						$scores[$indiceScore] = [
							'id'    => $r["id_embedding"],
							'score' => $score,
							'id_page'	=>	$r["id_page"],
							'estratto'	=>	$testoEstratto,
							'numero'	=>	count($estrattiMatch),
							'estratti_match'	=>	$estrattiMatch,
							// 'lingua'	=>	$r["lingua"],
						];
						
						$indiceScore++;
					}
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

						$response = self::normalizeEmbeddingJson(AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($embeddingText));
						
						$responseBody = self::normalizeEmbeddingJson(AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($estratto));
						
						if (isset($backupEmbeddings[$title]))
						{
							$responseTitle = $backupEmbeddings[$title];
						}
						else
						{
							$responseTitle = self::normalizeEmbeddingJson(AimodelliModel::getModulo($idModelloPredefinitoEmbeddings, true)->embeddings($title));
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
								"title"		=>	$title,
								"testo"		=>	trim($estratto),
								"embeddings_title"	=>	$responseTitle,
								"embeddings_title_bin"	=>	self::embeddingJsonToBinary($responseTitle),
								"embeddings_body"	=>	$responseBody,
								"embeddings_body_bin"	=>	self::embeddingJsonToBinary($responseBody),
							), "sanitizeDb");
							
							$this->insert();
						}
					}
				}
			}
		}
	}
}
