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

require_once(LIBRARY."/Application/Modules/AI/Context/QueryAwareContextBuilder.php");

class AirichiesteModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ai_richieste';
		$this->_idFields = 'id_ai_richiesta';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}

	public function relations() {
		return array(
			'messaggi' => array("HAS_MANY", 'AirichiestemessaggiModel', 'id_ai_richiesta', null, "RESTRICT", "L'elemento non è eliminabile perché ha dei messaggi collegati"),
			'contesti' => array("HAS_MANY", 'AirichiestecontestiModel', 'id_ai_richiesta', null, "CASCADE"),
			'modello' => array("BELONGS_TO", 'AimodelliModel', 'id_ai_modello',null,"RESTRICT","Si prega di selezionare il modello".'<div style="display:none;" rel="hidden_alert_notice">id_ai_modello</div>'),
		);
    }

	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Richiesta',
					'type'		 =>	'Textarea',
				),
				'id_ai_modello'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Modello di AI'),
					'options'	=>	$this->selectModelli($id),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
				),
				'id_c'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Parla di questa categoria'),
					'options'	=>	$this->buildAllCatSelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_page'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Parla di questa pagina'),
					'options'	=>	$this->selectLinkContenuto(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_marchio'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext("Parla di questo marchio"),
					'options'	=>	$this->selectMarchi(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
			),
		);

		if ($id)
			$this->formStruct["submit"] = [];
	}

	public function selectModelli($id)
	{
		$idModello = (int)$this->clear()->whereId((int)$id)->field("id_ai_modello");

		$aimModel = new AimodelliModel();

		$modelli = $aimModel->clear()->where(array(
			"OR"	=>	array(
				"id_ai_modello"	=>	(int)$idModello,
				"AND"	=>	array(
					"attivo"	=>	1,
				),
			),
			"tipo"	=>	"NLP",
		))->orderBy("predefinito desc")->send(false);

		$selectModelli = [];

		foreach ($modelli as $m)
		{
			$selectModelli[$m["id_ai_modello"]] = $m["titolo"] . " - contesto di max " . $m["numero_pagine"] . " pagine";
		}

		return $selectModelli;
	}

	public function titolo($id)
	{
		$clean["id"] = (int)$id;

		$record = $this->selectId($clean["id"]);

		$titolo = [];

		if ($record["id_c"])
			$titolo[] = CategoriesModel::g(false)->clear()->whereId((int)$record["id_c"])->field("title");

		if ($record["id_marchio"])
			$titolo[] = MarchiModel::g(false)->clear()->whereId((int)$record["id_marchio"])->field("titolo");

		if ($record["id_page"])
			$titolo[] = PagesModel::g(false)->clear()->whereId((int)$record["id_page"])->field("title");

		return gtext("Parla di").": <i>".implode("</i> - <i>", $titolo)."</i>";
	}

	public function titoloCrud($record)
	{
		return $this->titolo($record["ai_richieste"]["id_ai_richiesta"]);
	}

	public function estraiContesti($id)
	{
		$record = $this->selectId((int)$id);

		$arrayIds = [];

		if (!empty($record))
		{
			$idC = isset($this->values["id_c"]) ? (int)$this->values["id_c"] : 0;
			$idMarchio = isset($this->values["id_marchio"]) ? (int)$this->values["id_marchio"] : 0;
			$idPage = isset($this->values["id_page"]) ? (int)$this->values["id_page"] : 0;

			if ($idPage)
				$arrayIds[] = $idPage;

			$numeroMassimoContesti = AirichiesteModel::g(false)->numeroMassimoPagineContesto($id);

			if ($numeroMassimoContesti >= 50)
				$numeroMassimoContesti = 50;

			if ($idC || $idMarchio)
			{
				$idS = ProdottiModel::prodottiPiuVenduti($idC, $idMarchio, $numeroMassimoContesti);

				$arrayIds = array_merge($arrayIds, $idS);
			}

			$arrayIds = array_unique($arrayIds);
		}

		return $arrayIds;
	}

	public function numeroContesti($id)
	{
		return AirichiestecontestiModel::g()->where(array(
			"id_ai_richiesta"	=>	(int)$id,
		))->rowNumber();
	}

	public function inserisciContesti($id)
	{
		$idS = $this->estraiContesti($id);

		$aircModel = new AirichiestecontestiModel();

		// Inserisci tutti i contesti trovati senza verificare il numero
		AirichiestecontestiModel::$controllaNumeroPagineContesto = false;

		foreach ($idS as $idPage)
		{
			$aircModel->sValues(array(
				"id_ai_richiesta"	=>	(int)$id,
				"id_page"			=>	(int)$idPage,
			));

			$aircModel->insert();
		}
	}
	
	public function getChat($crea = false)
	{
		$idChat = 0;
		
		if (App::$isFrontend)
		{
			// Cerco la chat per id_user o cart_uid
			$record = $this->clear()->where(array(
				"OR"	=>	array(
					"AND"	=>	array(
						"id_user"	=>	(int)User::$id,
						"ne"		=>	array(
							"id_user"	=>	0,
						),
					),
					"cart_uid"	=>	sanitizeAll(User::$cart_uid),
				),
			))->record();
			
			if (empty($record))
			{
				if ($crea)
				{
					// La chat non esiste: la creo
					$this->values = array();
					$this->insert();
					
					$idChat = (int)$this->lId;
				}
			}
			else
			{
				$idChat = (int)$record["id_ai_richiesta"];
				
				// Controllo e in caso aggiungo id_user
				if (User::$id && !$record["id_user"])
				{
					$this->sValues(array(
						"id_user"	=>	(int)User::$id,
					));
					
					$this->update((int)$idChat);
				}
			}
		}
		
		return $idChat;
	}
	
	public function insert()
	{
		if (App::$isFrontend)
		{
			$this->values["id_user"] = User::$id;
			$this->values["id_ai_modello"] = (int)AimodelliModel::g(false)->getModelloPredefinito();
			$this->values["cart_uid"] = isset(User::$cart_uid) ? sanitizeAll(User::$cart_uid) : "";
			$this->values["zona"] = "Frontend";
		}
		else
		{
			$this->values["id_admin"] = User::$id;
			$this->values["zona"] = "Backend";
		}
		
		$this->values["ambito"] = sanitizeAll(v("assistente_ambito_default"));
		$this->values["ip"] = sanitizeAll(getIp());
		$this->values["user_agent"] = isset($_SERVER['HTTP_USER_AGENT']) ? sanitizeAll($_SERVER['HTTP_USER_AGENT']) : "";
		$this->values["user_agent_md5"] = isset($_SERVER['HTTP_USER_AGENT']) ? md5($_SERVER['HTTP_USER_AGENT']) : "";
		$this->values["session_id"] = sanitizeAll(session_id());
		
		$res = parent::insert();

		if ($res && !App::$isFrontend)
			$this->inserisciContesti($this->lId);

		return $res;
	}

	public function numeroMassimoPagineContesto($idRichiesta)
	{
		$idModello = $this->clear()->select("id_ai_modello")->whereId((int)$idRichiesta)->field("id_ai_modello");

		return (int)AimodelliModel::getModulo((int)$idModello, true)->getParam("numero_pagine");
	}

	public function messaggio($id, $messaggio = "")
	{
		$record = $this->selectId((int)$id);

		if (!empty($record))
		{
			// $messaggio = $_POST["messaggio"] ?? "";
			// $messaggio = htmlentitydecode(strip_tags(trim($messaggio)));
			
			if (trim($messaggio))
			{
				$airmModel = new AirichiestemessaggiModel();
				
				$contesto = AirichiestecontestiModel::g(false)->getContesto((int)$id);
				$istruzioni = "";
				
				$messaggi = array();
				$isRag = false;
				
				if (!App::$isFrontend && (trim($contesto) || !v("attiva_rag_in_richieste")))
				{
					$res = $airmModel->clear()->select("messaggio,ruolo")->where(array(
						"id_ai_richiesta"	=>	(int)$id,
					))->orderBy("data_creazione")->process()->send(false);
					
					foreach ($res as $r)
					{
						$messaggi[] = array(
							"role"		=>	$r["ruolo"],
							"content"	=>	htmlentitydecode($r["messaggio"]),
						);
					}

					$messaggioElaborato = AimodelliModel::getModulo((int)$record["id_ai_modello"], true)->setMessaggio($messaggio);
					
					$messaggi[] = $messaggioElaborato;
				}
				else
				{
					$isRag = true;
					
					list($intent, $messaggoRag, $istruzioni) = $this->rag($messaggio, $record["zona"], $record["ambito"], $record["lingua"], 5);
					
					$messaggioElaborato = AimodelliModel::getModulo((int)$record["id_ai_modello"], true)->setMessaggio($messaggoRag);
					
					$messaggi[] = $messaggioElaborato;
				}
				
				$airmModel->sValues(array(
					"messaggio"			=>	$messaggio,
					"id_ai_richiesta"	=>	(int)$id,
					"id_admin"			=>	(!App::$isFrontend) ? User::$id : 0,
					"id_user"			=>	App::$isFrontend ? User::$id : 0,
					"ruolo"				=>	"user",
				));

				if ($airmModel->insert())
				{
					$okRouting = false;
					
					if (isset($intent) && $intent)
						$okRouting = true;
					
					if (!$isRag || $okRouting)
						list($ris, $messaggio) = $this->richiesta($messaggi, $contesto, $istruzioni, (int)$record["id_ai_modello"], $okRouting);
					else
						list($ris, $messaggio) = array(0, gtext("Errore connessione"));
					
					$messaggio = strip_tags($messaggio);
					
					if (!$isRag || $okRouting)
						$messaggio = $this->elaboraRisposta($intent, $messaggio, $record["lingua"]);
					
					$airmModel->sValues(array(
						"messaggio"			=>	$messaggio,
						"id_ai_richiesta"	=>	(int)$id,
						"id_admin"			=>	User::$id,
						"ruolo"				=>	"assistant",
						"risultato_richiesta"	=>	(int)$ris,
					));
					
					$airmModel->insert();
				}
			}
		}
	}
	
	public function richiestaCompleta($messaggio, $zona = "Backend", $ambito = "Ecommerce", $lingua = "it", $numeroRisultati = 10)
	{
		list($intent, $messaggoRag, $istruzioni) = $this->rag($messaggio, $zona, $ambito, $lingua, $numeroRisultati);
		
		$okRouting = $intent ? true : false;
		
		$idModelloPredefinito = AimodelliModel::g(false)->getModelloPredefinito();
		
		$messaggioElaborato = AimodelliModel::getModulo($idModelloPredefinito, true)->setMessaggio($messaggoRag);
		
		list($ris, $risposta) = $this->richiesta(array($messaggioElaborato), "", $istruzioni, $idModelloPredefinito, $okRouting);
		
		if (isset($intent) && $intent)
			return $this->elaboraRisposta($intent, $risposta, $lingua);
		
		return "";
	}
	
	public function elaboraRisposta($intent, $messaggio, $lingua = "it")
	{
		$messaggioArray = json_decode($messaggio, true);
		
		$tpf = tpf("Elementi/AI/RAG/Intent/$intent/layout.txt");
		
		$layoutText = "";
		
		if (isset($tpf) && is_file($tpf))
		{
			$introText = $messaggioArray["intro_text"] ?? "";
			$text = $messaggioArray["text"] ?? "";
			$items = $messaggioArray["items"] ?? array();
			
			ob_start();
			include $tpf;
			$layoutText = ob_get_clean();
			
			$layoutText = str_replace("[INTRO_TEXT]", strip_tags($introText), $layoutText);
			$layoutText = str_replace("[TEXT]", strip_tags($text), $layoutText);
			
			$tpfItems = tpf("Elementi/AI/RAG/Intent/$intent/item.txt");
			
			if (is_file($tpfItems))
			{
				ob_start();
				include $tpfItems;
				$layoutItem = ob_get_clean();
				
				$itemsArray = array();
				
				foreach ($items as $item)
				{
					$id = isset($item["id"]) ? (int)$item["id"] : 0;
					$title = isset($item["title"]) ? strip_tags($item["title"]) : "";
					$comment = isset($item["comment"]) ? strip_tags($item["comment"]) : "";
					
					$tmp = $layoutItem;
					
					$tmp = str_replace("[TITLE]", $title, $tmp);
					$tmp = str_replace("[LINK]", "[LPAG_$id]", $tmp);
					$tmp = str_replace("[COMMENT]", $comment, $tmp);
					$tmp = str_replace("[IMAGE]", "[IPAG_$id]", $tmp);
					
					$itemsArray[] = $tmp;
				}
				
				// print_r($itemsArray);
				
				if (count($itemsArray) > 0)
					$layoutText = str_replace("[ITEMS]", implode("", $itemsArray), $layoutText);
				else
					$layoutText = str_replace("[ITEMS]", "", $layoutText);
			}
		}
		
		return $layoutText;
	}
	
	public function deletable($id)
	{
		$airmModel = new AirichiestemessaggiModel();

		if ($airmModel->getMessaggi($id, true))
			return false;

		return true;
	}

	public function numeroMessaggiCrud($record)
	{
		$airmModel = new AirichiestemessaggiModel();

		return $airmModel->getMessaggi($record["ai_richieste"]["id_ai_richiesta"], true);
	}

	public function cercaOCrea($idC, $idMarchio, $idPage)
	{
		$where = array(
			"id_c"			=>	(int)$idC,
			"id_marchio"	=>	(int)$idMarchio,
			"id_page"		=>	(int)$idPage,
		);

		$richiesta = $this->clear()->select("ai_richieste.id_ai_richiesta")->where($where)->orderBy("id_ai_richiesta desc")->send();

		$idRichiesta = 0;

		if (!empty($richiesta))
			$idRichiesta = (int)$richiesta[0]["ai_richieste"]["id_ai_richiesta"];
		else
		{
			$this->sValues($where);

			$modelli = $this->selectModelli(0);

			if (count($modelli) > 0)
			{
				reset($modelli);
				$idModello = key($modelli);

				$this->setValue("id_ai_modello", $idModello, "forceInt");

				if ($this->insert())
					$idRichiesta = $this->lId;
			}
		}

		return $idRichiesta;
	}
	
	public function rag($messaggio, $zona = "Backend", $ambito = "Ecommerce", $lingua = "it", $numeroRisultati = 5)
	{
		list($res, $routing) = $this->routing($messaggio, $zona, $ambito);
		
		if ($res)
		{
			$routingJson = json_decode($routing, true);
			// print_r($routingJson);
			// echo $routing."\n";
			$intent = $routingJson["intent"] ?? "";
			$confidence = $routingJson["confidence"] ?? "";
			$contents = array();
			$linguaRouting = $routingJson["language"] ?? "";
			$intentConosciuto = false;
			
			if ($linguaRouting && LingueModel::checkLinguaAttiva((string)$linguaRouting))
				$lingua = (string)$linguaRouting;
			
			// if ((float)$confidence > 0.6)
			// {
				switch($intent)
				{
					case "product_search":
						$emb = new EmbeddingsModel();
						$emb = $emb->select("distinct embeddings.id_embedding, embeddings.*")->inner(array("pagina"))->addWhereAttivo()->inner("combinazioni")->on("pages.id_page = combinazioni.id_page");
						// ->sWhere("exists (select 1 from combinazioni where combinazioni.id_page = pages.id_page)");
						
						// var_dump($routingJson);
						$productTitle = $routingJson["entities"]["product_title"]["value"] ?? "";
						$prezzoMinimo =  $routingJson["entities"]["price_range"]["min"] ?? null;
						$prezzoMassimo =  $routingJson["entities"]["price_range"]["max"] ?? null;
						$brand =  $routingJson["entities"]["brand"]["value"] ?? null;
						
						if ($prezzoMassimo)
						{
							$emb->aWhere(array(
								"lte"	=>	array(
									"combinazioni.price_scontato_ivato"	=> (int)$prezzoMassimo,
								),
							));
						}
						
						if ($prezzoMinimo)
						{
							$emb->aWhere(array(
								"gte"	=>	array(
									"combinazioni.price_scontato_ivato"	=> (int)$prezzoMinimo,
								),
							));
						}
						
						if ($brand)
						{
							$numero = MarchiModel::g(false)->clear()->where(array(
								"lk"	=>	array(
									"titolo"	=>	sanitizeAll($brand),
								)
							))->rowNumber();
							
							if ($numero)
								$emb->inner("marchi")->on("pages.id_marchio = marchi.id_marchio")->aWhere(array(
									"lk"	=>	array(
										"marchi.titolo"	=> sanitizeAll(nullToBlank($brand)),
									),
								));
						}
						
						if ($productTitle)
						{
							if ($lingua == Params::$defaultFrontEndLanguage)
							{
								$titleWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "title");
								$descWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "description");
							}
							else
							{
								$emb->addJoinTraduzione($lingua, "contenuti_tradotti", false, new PagesModel());
								
								$titleWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "title", "contenuti_tradotti");
								$descWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "description", "contenuti_tradotti");
							}
							
							$orWhere = array(
								"  OR"	=>	array(
									"AND"	=> $titleWhere,
									" AND"	=>	$descWhere,
								)
							);
							
							$emb->save();
							$emb->aWhere($descWhere);
							
							$queryArray = explode(" ", $productTitle);
							
							if ($brand)
								$queryArray[] = (string)$brand;
							
							if (isset($routingJson["entities"]["attributes"]) && is_array($routingJson["entities"]["attributes"]))
							{
								foreach ($routingJson["entities"]["attributes"] as $attr)
								{
									if (isset($attr["value"]))
									{
										$words = explode(" ", $attr["value"]);
										
										foreach ($words as $word)
										{
											$queryArray[] = $word;
										}
									}
								}
							}
							
							$queryArray = array_unique($queryArray);
							$messaggio = implode(" ", $queryArray);
						}
						
						$result = EmbeddingsModel::ricercaSemantica($messaggio, $emb, $lingua, $numeroRisultati);
						
						$idPages = $result["pages"];
						
						if (count($idPages) <= 0)
						{
							$emb->clear()->restore();
							$result = EmbeddingsModel::ricercaSemantica($messaggio, $emb, $lingua, $numeroRisultati);
							$idPages = $result["pages"];
						}
						
						// print_r($result);
						
						if (count($idPages) > 0)
						{
							$p = PagesModel::g(false)->where(array(
								"   in"	=>	array(
									"id_page"	=>	forceIntDeep($idPages),
								)
							));
							
							TraduzioniModel::sLingua($lingua, "front");
							$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, 0);
							TraduzioniModel::rLingua();
						}
						
						$intentConosciuto = true;
						
						break;
					case "policy_qa":
						$p = PagesModel::g(false)->where(array(
								"policy_ai"	=>	1,
							));
							
						TraduzioniModel::sLingua($lingua, "front");
						$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, 1, 0);
						TraduzioniModel::rLingua();
						
						$intentConosciuto = true;
						break;
					case "other":
						$intentConosciuto = true;
						break;
					default:
						$intent = "other";
						$intentConosciuto = true;
						break;
				}
			// }
			
			if ($intentConosciuto)
				$tpf = tpf("Elementi/AI/RAG/Intent/$intent/prompt.txt");
			
			if (isset($tpf) && is_file($tpf))
			{
				ob_start();
				include $tpf;
				$istruzioni = ob_get_clean();
				
				$istruzioni = str_replace("[NOME NEGOZIO]", Parametri::$nomeNegozio, $istruzioni);
				$istruzioni = str_replace("[LINGUA]", $lingua, $istruzioni);
				
				$contextItems = array();
				
				foreach ($contents as $c)
				{
					$lines = QueryAwareContextBuilder::extractRelevantSnippet($messaggio, stripTagsDecode($c["descrizione"]), 4);
					$compactDesc = implode(' | ', $lines);
					
					$contextItems[] = array(
						"id"		=>	$c["id_page"],
						"title"		=>	$c["titolo"],
						"description"	=>	$intent == "product_search" ? $compactDesc : stripTagsDecode($c["descrizione"]),
						"price"		=>	$c["prezzo_pieno"],
						"discounted_price"		=>	$c["prezzo_scontato"],
						"brand"		=>	$c["marchio"],
					);
				}
				
				$messaggioArray = array(
					"user_question"	=>	$messaggio,
					"intent"		=>	"product_search",
					"context_items"	=>	$contextItems
				);
				
				$messaggio = json_encode($messaggioArray);
				
				// echo $messaggio."\n";
				// echo $istruzioni."\n";
				
				return array($intent, $messaggio, $istruzioni);
			}
			
			return array("", "", "");
		}
		
		return array("", $messaggio, "");
	}
	
	public function routing($messaggio, $zona = "Backend", $ambito = "Ecommerce")
	{
		$tpf = tpf("Elementi/AI/RAG/Routing/$zona/$ambito/prompt.txt");
		
		if (is_file($tpf))
		{
			ob_start();
			include $tpf;
			$istruzioni = ob_get_clean();
			
			$istruzioni = str_replace("[NOME NEGOZIO]", Parametri::$nomeNegozio, $istruzioni);
			
			$messaggio = AimodelliModel::getModulo(AimodelliModel::g(false)->getModelloPredefinito(), true)->setMessaggio($messaggio);
			
			return $this->richiesta(array($messaggio), "", $istruzioni);
		}
		
		return array("", "");
	}
	
	public function checkRichiesta($messaggi)
	{
		$messaggio = $messaggi[count($messaggi) - 1]["content"];
		
		if (strlen($messaggio) <= (int)v("numero_massimo_caratteri_messaggio_ai"))
			return true;
		
		return false;
	}
	
	public function richiesta($messaggi, $contesto = "", $istruzioni = "", $idModello = null, $forza = false)
	{
		if (!isset($idModello))
			$idModello = AimodelliModel::g(false)->getModelloPredefinito();
		
		if (v("ai_attiva_cache"))
		{
			$cache = AirichiestecacheModel::g()->get($messaggi, $contesto, $istruzioni, $idModello);
			
			if ($cache)
				return array(1,$cache);
		}
		
		if (!$forza && !$this->checkRichiesta($messaggi))
			list($res, $output) = array(0, gtext("La richiesta è troppo lunga, riprovi con una domanda più corta"));
		else
			list($res, $output) = AimodelliModel::getModulo($idModello, true)->chat($messaggi, $contesto, $istruzioni);
		
		// echo $output."\n\n\n";
		
		if (v("ai_attiva_cache"))
			AirichiestecacheModel::g()->set($messaggi, $contesto, $istruzioni, $idModello, $output);
		
		return array($res, $output);
	}
}