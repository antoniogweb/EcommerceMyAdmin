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

require_once(LIBRARY."/Application/Modules/AI/Context/QueryAwareContextBuilder.php");

class AirichiesteModel extends GenericModel
{
	const ERROR_STATUS = 'FAILED';
	
	public static $fraseTroppeRichieste = "Il sistema sta ricevendo molte richieste in questo momento. Riprova tra un minuto.";
	public static $fraseTroppeRichiesteIp = "Hai superato il limite di richieste per ora. Riprova tra un'ora.";
	public static $fraseRichiestaTroppoLunga = "La richiesta è troppo lunga, riprovi con una domanda più corta";
	
	public static $idChat = 0; // Usato nel routing per recuperare altri messaggi dalla stessa chat
	
	public static $routingSchema = [
		'type' => 'object',
		'additionalProperties' => false,
		'properties' => [
			'intent' => [
				'type' => 'string',
				'enum' => [
					'product_search',
					'policy_qa',
					'other',
					'informational',
					'follow_up',
					'clarification',
				],
			],
			'confidence' => [
				'type' => 'number',
				'minimum' => 0,
				'maximum' => 1,
			],
			'language' => [
				'type' => 'string',
				'enum' => ['it', 'en', 'fr', 'de', 'es', 'other'],
			],
			'operation' => [
				'type' => 'string',
				'enum' => [
					'lookup',
					'compare',
					'recommend',
					'explain',
					'clarify',
					'other',
				],
			],
			'clarification_reason' => [
				'type' => ['string', 'null'],
				'enum' => [
					'missing_product',
					'ambiguous_product',
					'missing_details',
					'ambiguous_request',
					null,
				],
			],
			'question' => [
				'type' => ['string', 'null'],
			],
			'subjects' => [
				'type' => 'array',
				'items' => [
					'type' => 'object',
					'additionalProperties' => false,
					'properties' => [
						'embeddings_query' => [
							'type' => ['string', 'null'],
						],
						'entities' => [
							'type' => 'object',
							'additionalProperties' => false,
							'properties' => [
								'product_title' => [
									'type' => 'object',
									'additionalProperties' => false,
									'properties' => [
										'value' => [
											'type' => ['string', 'null'],
										],
										'confidence' => [
											'type' => 'number',
											'minimum' => 0,
											'maximum' => 1,
										],
									],
									'required' => [
										'value',
										'confidence',
									],
								],
								'SKU' => [
									'type' => 'object',
									'additionalProperties' => false,
									'properties' => [
										'value' => [
											'type' => ['string', 'null'],
										],
										'confidence' => [
											'type' => 'number',
											'minimum' => 0,
											'maximum' => 1,
										],
									],
									'required' => [
										'value',
										'confidence',
									],
								],
								'brand' => [
									'type' => 'object',
									'additionalProperties' => false,
									'properties' => [
										'value' => [
											'type' => ['string', 'null'],
										],
										'confidence' => [
											'type' => 'number',
											'minimum' => 0,
											'maximum' => 1,
										],
									],
									'required' => [
										'value',
										'confidence',
									],
								],
								'price_range' => [
									'type' => 'object',
									'additionalProperties' => false,
									'properties' => [
										'min' => [
											'type' => ['number', 'null'],
										],
										'max' => [
											'type' => ['number', 'null'],
										],
										'currency' => [
											'type' => 'string',
											'enum' => ['EUR'],
										],
										'confidence' => [
											'type' => 'number',
											'minimum' => 0,
											'maximum' => 1,
										],
									],
									'required' => [
										'min',
										'max',
										'currency',
										'confidence',
									],
								],
								'attributes' => [
									'type' => 'array',
									'items' => [
										'type' => 'object',
										'additionalProperties' => false,
										'properties' => [
											'value' => [
												'type' => 'string',
											],
											'confidence' => [
												'type' => 'number',
												'minimum' => 0,
												'maximum' => 1,
											],
										],
										'required' => [
											'value',
											'confidence',
										],
									],
								],
							],
							'required' => [
								'product_title',
								'SKU',
								'brand',
								'price_range',
								'attributes',
							],
						],
					],
					'required' => [
						'embeddings_query',
						'entities',
					],
				],
			],
			'order' => [
				'type' => 'object',
				'additionalProperties' => false,
				'properties' => [
					'order_id' => [
						'type' => ['string', 'null'],
					],
					'order_url' => [
						'type' => ['string', 'null'],
					],
				],
				'required' => [
					'order_id',
					'order_url',
				],
			],
			'customer' => [
				'type' => 'object',
				'additionalProperties' => false,
				'properties' => [
					'email' => [
						'type' => ['string', 'null'],
					],
					'phone' => [
						'type' => ['string', 'null'],
					],
				],
				'required' => [
					'email',
					'phone',
				],
			],
			'ticket' => [
				'type' => 'object',
				'additionalProperties' => false,
				'properties' => [
					'requested' => [
						'type' => 'boolean',
					],
					'subject' => [
						'type' => ['string', 'null'],
					],
				],
				'required' => [
					'requested',
					'subject',
				],
			],
		],
		'required' => [
			'intent',
			'confidence',
			'language',
			'operation',
			'subjects',
			'clarification_reason',
			'question',
			'order',
			'customer',
			'ticket',
		],
	];
	
	public function __construct()
	{
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
		
		if (empty($record))
			return "";
		
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
	
	private function getAssistantUid()
	{
		User::$assistant_uid = "";
		
		if (
			!isset($_COOKIE['assistant_uid']) 
			|| !isset($_COOKIE['assistant_uid_sig']) 
			|| strlen((string)$_COOKIE["assistant_uid"]) !== 32 
			|| !ctype_xdigit((string)$_COOKIE["assistant_uid"]) 
			|| !ValueSigner::verify($_COOKIE['assistant_uid'], $_COOKIE['assistant_uid_sig'], v("secret_key"))
		) {
			return '';
		}

		User::$assistant_uid = sanitizeAll(stripTagsSicuro($_COOKIE['assistant_uid']));
	}
	
	public function setAssistantUid()
	{
		User::$assistant_uid = randomToken();
		$time = time() + v("durata_cookie_chatbot");
		Cookie::set("assistant_uid", User::$assistant_uid, $time, "/", true, 'Lax', true, v("secret_key"));
	}
	
	private function getChatFromAssistantUid()
	{
		return $this->clear()->where(array(
			"assistant_uid"	=>	sanitizeAll(User::$assistant_uid),
			"id_user"		=> 0,
			"id_admin"		=>	0,
		))->orderBy("data_creazione desc,id_ai_richiesta desc")->record();
	}
	
	public function getChat($crea = false)
	{
		$idChat = 0;
		
		// Recupero il cookie del chatbot
		$this->getAssistantUid();
		
		if (App::$isFrontend)
		{
			$record = array();
			
			if (User::$id)
			{
				$record = $this->clear()->where(array(
					"id_user"	=>	(int)User::$id,
					"id_admin"	=>	0,
				))->orderBy("data_creazione desc,id_ai_richiesta desc")->record();
				
				if (empty($record) && User::$assistant_uid)
				{
					$record = $this->getChatFromAssistantUid();
					
					if (!empty($record))
					{
						$this->sValues(array(
							"id_user"	=>	(int)User::$id,
						));
						
						$this->update((int)$record["id_ai_richiesta"]);
					}
				}
			}
			else if (User::$assistant_uid)
				$record = $this->getChatFromAssistantUid();
			
			if (empty($record))
			{
				if ($crea)
				{
					if (!User::$assistant_uid)
						$this->setAssistantUid();
					
					// La chat non esiste: la creo
					$this->values = array();
					$this->insert();
					
					$idChat = (int)$this->lId;
				}
			}
			else
				$idChat = (int)$record["id_ai_richiesta"];
		}
		
		return $idChat;
	}
	
	protected function checkIfRag()
	{
		if ($this->values["id_admin"] && 
			(isset($this->values["id_c"]) && $this->values["id_c"] || 
			isset($this->values["id_marchio"]) && $this->values["id_marchio"] || 
			isset($this->values["id_page"]) && $this->values["id_page"])
		)
			return 0;
		else
			return 1;
	}
	
	public function insert()
	{
		if (App::$isFrontend)
		{
			$this->values["id_admin"] = 0;
			$this->values["id_user"] = User::$id;
			$this->values["id_ai_modello"] = (int)AimodelliModel::g(false)->getModelloPredefinito();
			$this->values["assistant_uid"] = isset(User::$assistant_uid) ? sanitizeAll(User::$assistant_uid) : "";
			$this->values["zona"] = "Frontend";
		}
		else
		{
			$this->values["id_admin"] = User::$id;
			$this->values["id_user"] = 0;
			$this->values["zona"] = "Backend";
		}
		
		$this->values["ambito"] = sanitizeAll(v("assistente_ambito_default"));
		$this->values["ip"] = sanitizeAll(getIp());
		$this->values["user_agent"] = isset($_SERVER['HTTP_USER_AGENT']) ? sanitizeAll($_SERVER['HTTP_USER_AGENT']) : "";
		$this->values["user_agent_md5"] = isset($_SERVER['HTTP_USER_AGENT']) ? md5($_SERVER['HTTP_USER_AGENT']) : "";
		$this->values["session_id"] = sanitizeAll(session_id());
		
		$this->values["rag"] = $this->checkIfRag();
		
		if (!App::$isFrontend && $this->values["rag"])
		{
			$this->result = false;
			$this->notice = '<div class="alert alert-danger">'.gtext("Si prega di selezionare una categoria, un marchio o una pagina").'</div>';
			return false;
		}
		
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
	
	public function isRag($id)
	{
		return $this->clear()->whereId((int)$id)->field("rag");
	}
	
	public function recuperaMessaggi($idChat, $limit = 0, $skipFailed = false)
	{
		$messaggi = array();
		
		$airmModel = new AirichiestemessaggiModel();
		
		$desc = $limit ? " DESC" : "";
		
		$airmModel->clear()->select("messaggio,ruolo")->where(array(
			"id_ai_richiesta"	=>	(int)$idChat,
		))->orderBy("data_creazione".$desc)->process();
		
		if ($limit)
			$airmModel->limit((int)$limit);
		
		if ($skipFailed)
			$airmModel->sWhere(array(
				"(status != ? OR status = '')",
				array(sanitizeAll(self::ERROR_STATUS))
			));
		
		$res = $airmModel->send(false);
		
		if ($limit)
			$res = array_reverse($res);
		
		foreach ($res as $r)
		{
			$messaggi[] = array(
				"role"		=>	$r["ruolo"],
				"content"	=>	preg_replace('/\s+/u',' ',stripTagsSicuro($r["messaggio"])),
			);
		}
		
		return $messaggi;
	}
	
	public function messaggio($id, $messaggio = "")
	{
		self::$idChat = (int)$id; // salva l'ID della chat
		
		$record = $this->selectId((int)$id);
		
		$risposta = "";
		
		if (!empty($record))
		{
			// $messaggio = $_POST["messaggio"] ?? "";
			// $messaggio = htmlentitydecode(stripTagsSicuro(trim($messaggio)));
			
			if (trim($messaggio))
			{
				$airmModel = new AirichiestemessaggiModel();
				
				$contesto = AirichiestecontestiModel::g(false)->getContesto((int)$id);
				$istruzioni = "";
				
				$messaggi = array();
				
				$isRag = $this->isRag($id);
				
				if (!$isRag)
				{
					$messaggi = $this->recuperaMessaggi($id);
					
					$messaggioElaborato = AimodelliModel::getModulo((int)$record["id_ai_modello"], true)->setMessaggio($messaggio);
					
					$messaggi[] = $messaggioElaborato;
				}
				else
				{
					if (strlen($messaggio) > (int)v("numero_massimo_caratteri_messaggio_ai"))
					{
						$airmModel->sValues(array(
							"messaggio"			=>	gtext("[Messaggio troppo lungo]"),
							"id_ai_richiesta"	=>	(int)$id,
							"id_admin"			=>	(!App::$isFrontend) ? User::$id : 0,
							"id_user"			=>	App::$isFrontend ? User::$id : 0,
							"ruolo"				=>	"user",
							"status"			=>	self::ERROR_STATUS,
							"reason"			=>	"TOO_LONG_REQUEST",
						));
						
						if ($airmModel->insert())
						{
							$airmModel->sValues(array(
								"messaggio"			=>	gtext(self::$fraseRichiestaTroppoLunga),
								"id_ai_richiesta"	=>	(int)$id,
								"id_admin"			=>	User::$id,
								"ruolo"				=>	"assistant",
								"risultato_richiesta"	=>	1,
								"status"			=>	self::ERROR_STATUS,
								"reason"			=>	"TOO_LONG_REQUEST",
								"id_rif"			=>	(int)$airmModel->lId,
							));
							
							$airmModel->insert();
						}
						
						// $this->sendEvent([
						// 	'type'	=>	'result',
						// 	'data'	=>	self::$fraseRichiestaTroppoLunga
						// ]);
						
						return;
					}
					
					$numeroProdotti = (int)v("numero_massimo_prodotti_estratti");
					
					list($intent, $messaggoRag, $istruzioni) = $this->rag($messaggio, $record["zona"], $record["ambito"], $record["lingua"], $numeroProdotti);
					
					$contesto = "";
					
					$additionalContext = $this->recuperaAdditionalContext(self::$idChat);
					
					if (count($additionalContext) > 0)
						$contesto = json_encode(array(
							"additional_context" => $additionalContext,
						));
					
					// $this->sendEvent([
					// 	'type'	=>	'status',
					// 	'phase'	=>	'understanding',
					// 	'text'	=>	gtext('Preparo la risposta...'),
					// ]);
					
					if ($intent == "follow_up")
						$messaggi = $this->recuperaMessaggi($id, v("numero_messaggi_storico_chat_da_riportare"), true);
					
					$messaggioElaborato = AimodelliModel::getModulo((int)$record["id_ai_modello"], true)->setMessaggio($messaggoRag);
					
					$messaggi[] = $messaggioElaborato;
				}
				
				$okRouting = false;
				
				AirichiesteresponseModel::$tipo = "GENERICA";
				
				if ($isRag)
				{
					if (isset($intent) && $intent)
					{
						$okRouting = true;
						
						AirichiesteresponseModel::$tipo = strtoupper($intent);
					}
					
					if ($okRouting)
					{
						if ($intent == "threshold_exceeded")
							list($ris, $risposta) = array(1, gtext(self::$fraseTroppeRichieste));
						else if ($intent == "threshold_exceeded_ip")
							list($ris, $risposta) = array(1, gtext(self::$fraseTroppeRichiesteIp));
						else if ($intent == "clarification")
							list($ris, $risposta) = array(1, $messaggoRag);
						else
						{
							list($ris, $risposta) = $this->richiesta($messaggi, $contesto, $istruzioni, (int)$record["id_ai_modello"], $okRouting, "low", array(), true);
							
							$risposta = stripTagsSicuro($risposta);
							
							$risposta = json_decode($risposta, true);
							
							$status = $risposta["status"] ?? "";
							$reason = isset($risposta["reason"]) ? (string)$risposta["reason"] : "";
							
							if ($intent == "other")
							{
								$status = self::ERROR_STATUS;
								$reason = "OUT_OF_SCOPE";
							}
							
							$risposta = $this->elaboraRisposta($intent, $risposta, $record["lingua"]);
						}
					}
					else
						list($ris, $risposta) = array(0, gtext("Non sono riuscito a elaborare la richiesta. Riprova."));
				}
				else
				{
					list($ris, $risposta) = $this->richiesta($messaggi, $contesto, $istruzioni, null, true, "high");
					
					$risposta = stripTagsSicuro($risposta);
				}
				
				$airmModel->sValues(array(
					"messaggio"			=>	$messaggio,
					"id_ai_richiesta"	=>	(int)$id,
					"id_admin"			=>	(!App::$isFrontend) ? User::$id : 0,
					"id_user"			=>	App::$isFrontend ? User::$id : 0,
					"ruolo"				=>	"user",
					"status"			=>	$status ?? "",
					"reason"			=>	$reason ?? "",
				));

				if ($airmModel->insert())
				{
					$airmModel->sValues(array(
						"messaggio"			=>	$risposta,
						"id_ai_richiesta"	=>	(int)$id,
						"id_admin"			=>	User::$id,
						"ruolo"				=>	"assistant",
						"risultato_richiesta"	=>	(int)$ris,
						"status"			=>	$status ?? "",
						"reason"			=>	$reason ?? "",
						"id_rif"			=>	(int)$airmModel->lId,
					));
					
					$airmModel->insert();
				}
			}
		}
		
		// $this->sendEvent([
		// 	'type'	=>	'result',
		// 	'data'	=>	$risposta
		// ]);
	}
	
	public function richiestaCompleta($messaggio, $zona = "Backend", $ambito = "Ecommerce", $lingua = "it", $numeroRisultati = 10)
	{
		list($intent, $messaggoRag, $istruzioni) = $this->rag($messaggio, $zona, $ambito, $lingua, $numeroRisultati);
		
		$okRouting = $intent ? true : false;
		
		$idModelloPredefinito = AimodelliModel::g(false)->getModelloPredefinito();
		
		$messaggioElaborato = AimodelliModel::getModulo($idModelloPredefinito, true)->setMessaggio($messaggoRag);
		
		if ($intent == "threshold_exceeded")
			return gtext(self::$fraseTroppeRichieste);
		else if ($intent == "threshold_exceeded_ip")
			return gtext(self::$fraseTroppeRichiesteIp);
		else
		{
			list($ris, $risposta) = $this->richiesta(array($messaggioElaborato), "", $istruzioni, $idModelloPredefinito, $okRouting);
			
			if (isset($intent) && $intent)
				return $this->elaboraRisposta($intent, json_decode($messaggio, true), $lingua);
		}
		
		return "";
	}
	
	public function elaboraRisposta($intent, $messaggioArray, $lingua = "it")
	{
		// $messaggioArray = json_decode($messaggio, true);
		
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
			
			// I messaggi assistant possono contenere HTML generato da elaboraRisposta().
			// Il decode e' ammesso solo per contenuti passati da stripTagsSicuro()
			// e/o template server-side controllati prima del salvataggio.
			
			$layoutText = str_replace("[INTRO_TEXT]", stripTagsSicuro($introText), $layoutText);
			$layoutText = str_replace("[TEXT]", stripTagsSicuro($text), $layoutText);
			
			$tpfItems = tpf("Elementi/AI/RAG/Intent/$intent/item.txt");
			
			if (is_file($tpfItems))
			{
				ob_start();
				include $tpfItems;
				$layoutItem = ob_get_clean();
				
				$itemsArray = array();
				
				$indice = 0;
				foreach ($items as $item)
				{
					$id = isset($item["id"]) ? (int)$item["id"] : 0;
					$title = isset($item["title"]) ? stripTagsSicuro($item["title"]) : "";
					$comment = isset($item["comment"]) ? stripTagsSicuro($item["comment"]) : "";
					$links = (isset($item["in_depth"]) && is_array($item["in_depth"]) && count($item["in_depth"]) > 0)? $item["in_depth"] : array();
					
					$tmp = $layoutItem;
					
					$tmp = str_replace("[TITLE]", $title, $tmp);
					$tmp = str_replace("[LINK]", "[LPAG_".(int)$id."]", $tmp);
					$tmp = str_replace("[COMMENT]", F::vitalizeTesto($comment), $tmp);
					$tmp = str_replace("[IMAGE]", "[IPAG_".(int)$id."]", $tmp);
					
					$inDepthHtml = "";
					
					if (count($links) > 0)
					{
						$linksArray = array();
						
						foreach ($links as $link)
						{
							if (isset($link["text"]) && isset($link["url"]) && trim($link["text"]) && trim($link["url"]))
							{
								$link["url"] = stripTagsSicuro($link["url"]);
								
								if (preg_match('/^https?:\/\/[a-zA-Z0-9._\/-]+$/', $link["url"]) && filter_var($link["url"], FILTER_VALIDATE_URL))
								{
									$li = "<a rel='noopener noreferrer' target='_blank' href='".$link["url"]."'>".stripTagsSicuro($link["text"])."</a>";
									
									if (isset($link["comment"]) && trim($link["comment"]))
										$li .= " ".stripTagsSicuro($link["comment"]);
									
									$linksArray[] = "<li>".$li."</li>";
								}
							}
						}
						
						if (count($linksArray) > 0)
						{
							$inDepthHtml = "<p><b>".gtext("Per approfondire:")."</b></p><ul class='uk-list'>".implode("\n", $linksArray)."</ul>";
						}
					}
					
					$tmp = str_replace("[APPROFONDIMENTO]", $inDepthHtml, $tmp);
					
					if ($intent == "informational" && $indice < (count($items)-1))
						$tmp .= '<hr class="uk-divider-icon">';
					
					$itemsArray[] = $tmp;
					
					$indice++;
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
	
	public function ultimaRichiestaCrud($record)
	{
		$airmModel = new AirichiestemessaggiModel();
		
		$messaggio = $airmModel->clear()->select("messaggio")->where(array(
			"id_ai_richiesta"	=>	(int)$record["ai_richieste"]["id_ai_richiesta"],
			"ruolo"				=>	"user",
		))->orderBy("id_ai_richiesta_messaggio desc")->limit(1)->field("messaggio");
		
		return $messaggio ?? "";
	}
	
	public function utenteCrud($record)
	{
		$idUser = (int)$record["ai_richieste"]["id_user"];
		
		if (!$idUser)
			return "--";
		
		$ruModel = new RegusersModel();
		
		$user = $ruModel->selectId($idUser);
		
		if (!empty($user))
		{
			$html = RegusersModel::getNominativo($user)." (".$user["username"].")";
			
			if (ControllersModel::checkAccessoAlController(array("regusers")))
				$html .= " <a class='text text-primary iframe' href='".Url::getRoot()."regusers/form/update/".$idUser."?partial=Y&nobuttons=Y'><i class='fa fa-eye'></i></a>";
			
			return $html;
		}
		
		return "";
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
	
	protected function estraiContents($messaggio, $routingJson, $lingua, $numeroRisultati, $soloProdotti = true)
	{
		$contents = array();
		
		$emb = new EmbeddingsModel();
		$emb = $emb->innerPages($lingua)->addWhereAttivo();
		
		$idCatStrutturaFeedProdotti = 1;
		
		if ($soloProdotti)
		{
			$idCatStrutturaFeedProdotti = 0;
			$emb->inner("combinazioni")->on("pages.id_page = combinazioni.id_page");
		}
		
		// var_dump($routingJson);
		$productTitle = $routingJson["entities"]["product_title"]["value"] ?? "";
		$productSKU = $routingJson["entities"]["SKU"]["value"] ?? "";
		$prezzoMinimo =  $routingJson["entities"]["price_range"]["min"] ?? null;
		$prezzoMassimo =  $routingJson["entities"]["price_range"]["max"] ?? null;
		$brand =  $routingJson["entities"]["brand"]["value"] ?? null;
		
		if ($prezzoMassimo && $soloProdotti)
		{
			$emb->aWhere(array(
				"lte"	=>	array(
					"combinazioni.price_scontato_ivato"	=> (int)$prezzoMassimo,
				),
			));
		}
		
		if ($prezzoMinimo && $soloProdotti)
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
		
		if ($productTitle && !$productSKU)
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
			$emb->aWhere($titleWhere);
			
			$queryArray = explode(" ", $productTitle);
			
			if ($brand)
				$queryArray[] = (string)$brand;
			
// 			if (isset($routingJson["entities"]["attributes"]) && is_array($routingJson["entities"]["attributes"]))
// 			{
// 				foreach ($routingJson["entities"]["attributes"] as $attr)
// 				{
// 					if (isset($attr["value"]))
// 					{
// 						$words = explode(" ", $attr["value"]);
// 						
// 						foreach ($words as $word)
// 						{
// 							$queryArray[] = $word;
// 						}
// 					}
// 				}
// 			}
			
			$queryArray = array_unique($queryArray);
			
			if ($soloProdotti)
				$messaggio = implode(" ", $queryArray);
		}
		else if ($productSKU && $soloProdotti)
		{
			$emb->aWhere(array(
				"    lk"	=>	array(
					"combinazioni.codice" => sanitizeAll($productSKU),
				)
			));
		}
		
		$result = EmbeddingsModel::ricercaSemantica($messaggio, $emb, $lingua, $numeroRisultati);
		
		$idPages = $result["pages"];
		
		if (count($idPages) <= 0 && $productTitle && !$productSKU)
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
			$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, $idCatStrutturaFeedProdotti);
			TraduzioniModel::rLingua();
		}
		else if ($productTitle || $productSKU)
		{
			if ($productSKU)
			{
				$p = PagesModel::g(false)->sWhere(array(
					"pages.id_page in (select distinct id_page from combinazioni where codice like ?)",
					array(sanitizeAll($productSKU))
				));
			}
			else
			{
				if ($lingua == Params::$defaultFrontEndLanguage)
					$titleWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "title");
				else
					$titleWhere = $emb->getWhereSearch(sanitizeAll($productTitle), 50, "title", "contenuti_tradotti");
				
				$p = PagesModel::g(false)->aWhere($titleWhere);
			}
			
			TraduzioniModel::sLingua($lingua, "front");
			$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, $idCatStrutturaFeedProdotti);
			TraduzioniModel::rLingua();
		}
		
		return $contents;
	}
	
	private function tieniInContesto($contesto, $fields = array())
	{
		foreach ($contesto as $key => $value)
		{
			if ($key == "righe" || $key == "pagamenti")
				continue;
			
			if (!in_array($key, $fields))
				unset($contesto[$key]);
		}
		
		return $contesto;
	}
	
	private function recuperaAdditionalContext($idChat)
	{
		$arcfModel = new AirichiestecontestifrontendModel();
		
		$contesti = $arcfModel->clear()->where(array(
			"id_ai_richiesta"	=>	(int)$idChat,
		))->orderBy("id_ai_richiesta_contesto_frontend desc")->send(false);
		
		$arrayContestiAggiuntivi = array();
		
		if (count($contesti) > 0)
		{
			foreach ($contesti as $contesto)
			{
				$contesto["contesto"] = json_decode(htmlentitydecode($contesto["contesto"]), true);
				$contesto["contesto"]["order_information"] = $this->tieniInContesto($contesto["contesto"]["order_information"], array("id_o", "data_creazione", "nome", "cognome", "ragione_sociale", "codice_fiscale", "indirizzo", "cap", "provincia", "dprovincia", "nazione", "citta", "telefono", "email", "pagamento", "total", "total_pieno", "tipo_cliente", "stato", "spedizione_ivato", "costo_pagamento_ivato", "codice_promozione", "nome_promozione", "usata_promozione", "valore_iva", "indirizzo_spedizione", "cap_spedizione", "provincia_spedizione", "nazione_spedizione", "citta_spedizione", "telefono_spedizione", "pec", "codice_destinatario", "data_pagamento", "fonte", "tipo_promozione", "prezzo_scontato_prodotti_ivato", "numero_prodotti"));
				
				$arrayContestiAggiuntivi[] = array(
					"type"		=>	$contesto["tipo"],
					"context"	=>	$contesto["contesto"],
				);
			}
		}
		
		return $arrayContestiAggiuntivi;
	}
	
	private function salvaOrderContext($orderLink)
	{
		preg_match_all('~(?:^|/)([a-f0-9]{32})(?=/|$|\?)~i', $orderLink, $matches);
		
		$cartUid = $matches[1][0] ?? null;
		$adminToken = $matches[1][1] ?? null;
		
		if ($cartUid && $adminToken)
		{
			$oModel = new OrdiniModel();
			$ordine = $oModel->clear()->where(array(
				"cart_uid" 		=>	sanitizeAll((string)$cartUid),
				"admin_token"	=>	sanitizeAll((string)$adminToken),
			))->record();
			
			if (!empty($ordine))
			{
				$strutturaProdotti = GestionaliModel::getModuloPadre()->infoOrdine((int)$ordine["id_o"]);
				
				$arrayContextOrdine = array(
					"order_id"	=>	(int)$ordine["id_o"],
					"order_url"	=>	(string)$orderLink,
					"order_information"	=>	$strutturaProdotti,
				);
				
				$arcfModel = new AirichiestecontestifrontendModel();
				
				$arcfModel->del(null, array(
					"id_ai_richiesta"	=>	(int)self::$idChat,
					"tipo"				=>	"ORDER",
				));
				
				$arcfModel->sValues(array(
					"id_ai_richiesta"	=>	(int)self::$idChat,
					"tipo"				=>	"ORDER",
					"contesto"			=>	json_encode($arrayContextOrdine),
				));
				
				$arcfModel->insert();
				
				return true;
			}
		}
		
		return false;
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
			$contents = $contentsAll = $contextItems = array();
			$linguaRouting = $routingJson["language"] ?? "";
			$intentConosciuto = false;
			$subjects = $routingJson["subjects"] ?? array();
			$operation = $routingJson["operation"] ?? "";
			$order = $routingJson["order"] ?? array();
			$customer = $routingJson["customer"] ?? array();
			$ticket = $routingJson["ticket"] ?? array();
			
			// Estraggo l'ordine
			if (count($order) > 0 && ( (isset($order["order_id"]) && trim((string)$order["order_id"]) ) || (isset($order["order_url"]) && trim((string)$order["order_url"]) )))
			{
				$intent = "translation";
				
				if (!isset($order["order_url"]) || !trim($order["order_url"]))
					$replyToTranslate = gtext("Per motivi di sicurezza, dobbiamo verificare che tu sia il titolare dell’ordine. Inserisci il link esatto dell’ordine che hai ricevuto via email.");
				else if (!preg_match('~/resoconto-acquisto/~i', (string)$order["order_url"]))
					$replyToTranslate = gtext("Il link inserito non sembra essere un link valido dell’ordine. Inserisci il link esatto dell’ordine che hai ricevuto via email.");
				else if (!$this->salvaOrderContext((string)$order["order_url"]))
					$replyToTranslate = gtext("Il link inserito non sembra riferirsi ad un ordine esistente. Inserisci il link esatto dell’ordine che hai ricevuto via email.");
				else
					$intent = "follow_up";
			}
			
			$mail = isset($customer["email"]) ? (string)$customer["email"] : "";
			$telefono = isset($customer["phone"]) ? (string)$customer["phone"] : "";
			
			$erroreMail = $erroreTelefono = false;

			// Estraggo i dati del cliente
			if ($mail || $telefono)
			{
				$intent = "translation";
				
				if ($mail && !checkMail($mail))
					$erroreMail = true;
				
				if ($telefono && !preg_match('/^[0-9\s\+]+$/', $telefono))
					$erroreTelefono = true;
				
				if ($erroreMail && $erroreTelefono)
					$replyToTranslate = gtext("L'indirizzo email e il telefono inseriti non sembrano essere corretti. Controlla e riprova.");
				else if ($erroreMail)
					$replyToTranslate = gtext("L'indirizzo email inserito non sembra essere corretto. Controllalo e riprova.");
				else if ($erroreTelefono)
					$replyToTranslate = gtext("Il telefono inserito non sembra essere corretto. Controllalo e riprova.");
				else
					$intent = "follow_up";
				
				$this->values = array();
				
				if ($mail && !$erroreMail)
					$this->setValue("email", $mail);
				
				if ($telefono && !$erroreTelefono)
					$this->setValue("telefono", $telefono);
				
				if (!empty($this->values))
					$this->update((int)self::$idChat);
			}
			
			// Estraggo i dati del ticket
			if (isset($ticket["requested"]) && $ticket["requested"])
			{
				$this->sValues(array(
					"ticket_richiesto"	=>	1,
					"oggetto_ticket"	=>	isset($ticket["subject"]) ? (string)$ticket["subject"] : "",
				));
				
				$this->update((int)self::$idChat);
			}
			
			$chat = $this->selectId((int)self::$idChat);
			
			if ( 
				( 
					($chat["ticket_richiesto"] && ($mail || $telefono)) || 
					(isset($ticket["requested"]) && $ticket["requested"]) 
				) 
				&& !$erroreMail 
				&& !$erroreTelefono
			)
			{
				$intent = "translation";
				
				if (!trim($chat["email"]) && !trim($chat["telefono"]))
					$replyToTranslate = gtext("Per poter essere ricontattato, indica un indirizzo email e un numero di telefono.");
				else if (!trim($chat["email"]))
					$replyToTranslate = gtext("Per poter essere ricontattato, indica anche un indirizzo email.");
				else if (!trim($chat["telefono"]))
					$replyToTranslate = gtext("Per poter essere ricontattato, indica anche un numero di telefono");
				else
				{
					$replyToTranslate = gtext("Il ticket è stato creato correttamente.")." ".gtext("Il negozio riceverà questa conversazione e verrai ricontattato dal servizio clienti.")." ".gtext("Ti abbiamo inviato una copia della chat via email come promemoria.")." ".gtext("Puoi chiudere questa chat oppure iniziarne una nuova.");
					
					$this->sValues(array(
						"ticket_creato"	=>	1,
					));
					
					$this->update((int)self::$idChat);
				}
			}
			
			// if (count($subjects) > 0)
			// 	$this->sendEvent([
			// 		'type'	=>	'status',
			// 		'phase'	=>	'understanding',
			// 		'text'	=>	gtext('Sto recuperando le informazioni...'),
			// 	]);
			
			if ($linguaRouting && LingueModel::checkLinguaAttiva((string)$linguaRouting))
				$lingua = (string)$linguaRouting;
			
			// if ((float)$confidence > 0.6)
			// {
				switch($intent)
				{
					case "product_search":
						
						foreach ($subjects as $subject)
						{
							$contents = array_merge($contents, $this->estraiContents($messaggio, $subject, $lingua, $numeroRisultati));
						}
						
						// if (count($contents) <= 0)
						// 	$intent = "other";
						
						break;
					case "informational":
						foreach ($subjects as $subject)
						{
							$embeddingQuery = trim($subject["embeddings_query"] ?? $messaggio);
						
							$contents = array_merge($contents, $this->estraiContents($embeddingQuery, $subject, $lingua, $numeroRisultati, false));
						}
						
						// if (count($contents) <= 0)
						// 	$intent = "other";
						
						break;
					case "policy_qa":
						foreach ($subjects as $subject)
						{
							$contentsAll = array_merge($contentsAll, $this->estraiContents($messaggio, $subject, $lingua, $numeroRisultati, false));
						}
						
						$arrayTipiIA = explode(",", v("tipi_pagine_come_testo_base_policy_qa"));
						$arrayTipiIA = sanitizeAllDeep($arrayTipiIA);
						
						$p = PagesModel::g(false)->where(array(
								"OR"	=>	array(
									"pages.policy_ai"	=>	1,
									"IN"	=>	array(
										"pages.tipo_pagina"	=>	$arrayTipiIA,
									)
								)
							));
							
						TraduzioniModel::sLingua($lingua, "front");
						$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, 1, 0);
						TraduzioniModel::rLingua();
						
						$contents = array_merge($contents, $contentsAll);
						
						if (count($contents) <= 0)
							$intent = "other";
						
						break;
					case "follow_up":
						foreach ($subjects as $subject)
						{
							$embeddingQuery = trim($subject["embeddings_query"] ?? $messaggio);
						
							$contents = array_merge($contents, $this->estraiContents($embeddingQuery, $subject, $lingua, $numeroRisultati, false));
						}
						break;
					case "clarification":
						$question = (isset($routingJson["question"]) && $routingJson["question"]) ? $routingJson["question"] : "";
						
						if (!$question)
							$intent = "other";
						
						break;
					case "translation":
						if (!isset($replyToTranslate))
							$intent = "other";
						
						break;
					case "other":
						break;
					case "threshold_exceeded":
						break;
					case "threshold_exceeded_ip":
						break;
					default:
						$intent = "other";
						break;
				}
			// }
			
			// Se clarification restituisci la question secca
			if ($intent === "clarification")
				return array($intent, $question, "");
			
			$tipoPrompt = v("prompt_assisted") ? "_assisted" : "";
			
			$promptFilename = "prompt";
			
			if ($intent === "informational" && $operation === "compare")
				$promptFilename = "prompt_compare";
			
			$tpf = tpf("Elementi/AI/RAG/Intent/$intent/$promptFilename".$tipoPrompt.".txt");
			
			if (!is_file($tpf))
				$tpf = tpf("Elementi/AI/RAG/Intent/$intent/$promptFilename.txt");
			
			if (is_file($tpf))
			{
				// Prompt intent
				ob_start();
				include $tpf;
				$istruzioni = ob_get_clean();
				
				$istruzioni = str_replace("[NOME NEGOZIO]", Parametri::$nomeNegozio, $istruzioni);
				$istruzioni = str_replace("[LINGUA]", $lingua, $istruzioni);
				$istruzioni = str_replace("[NUMERO_ITEM]", v("numero_massimo_prodotti_estratti"), $istruzioni);
				
				// prompt status
				ob_start();
				include tpf("Elementi/AI/RAG/Intent/prompt_status.txt");
				$promptStatus = ob_get_clean();
				
				$istruzioni.= "\n\n".$promptStatus;
				
				// Se clarification restituisci la question secca
				if ($intent === "translation")
					return array($intent, $replyToTranslate, $istruzioni);
				
				$pModel = new PagesModel();
				
				foreach ($contents as $c)
				{
					$varianti = $pModel->selectAttributiJson((int)$c["id_page"]);
					
					$descrizione = $c["descrizione"]." ".$c["descrizione_2"]." ".$c["descrizione_3"]." ".$c["descrizione_4"];
					
					$lines = QueryAwareContextBuilder::extractRelevantSnippet($messaggio, stripTagsDecode($descrizione), 4);
					$compactDesc = implode(' | ', $lines);
					
					$links = F::estraiLink(htmlentitydecode($descrizione));
					
					if (count($links) <= 0 && $operation == "compare")
					{
						$links[] = array(
							"url" 	=> $c["link"],
							"text" 	=> $c["titolo"],
						);
					}
					
					$temp = array(
						"id"		=>	$c["id_page"],
						"title"		=>	$c["titolo"],
						"SKU"		=>	$c["codice"],
						"description"	=>	$intent == "product_search" ? $compactDesc : stripTagsDecode($descrizione),
						"price"		=>	$c["prezzo_pieno"],
						"discounted_price"		=>	$c["prezzo_scontato"],
						"brand"		=>	$c["marchio"],
						"variants"	=>	$varianti,
					);
					
					if (count($links) > 0 && $intent == "informational")
						$temp["links"] = $links;
					
					$contextItems[] = $temp;
				}
				
				$messaggioArray = array(
					"user_question"	=>	$messaggio,
					"intent"		=>	$intent,
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
	
// 	public function getLastRoutingSubjects($idChat)
// 	{
// 		$lastResponce = AirichiesteresponseModel::getLast(array("ROUTING"), $idChat);
// 		
// 		$previousSubjects = array();
// 		
// 		if (!empty($lastResponce) && isset($lastResponce["response"]))
// 		{
// 			$responseArray = json_decode($lastResponce["response"], true);
// 			
// 			$output =  $responseArray["output_text"] ?? "";
// 			
// 			if (trim($output))
// 			{
// 				$outputArray = json_decode($output, true);
// 				
// 				$subjects = $outputArray["subjects"] ?? array();
// 				
// 				foreach ($subjects as $subject)
// 				{
// 					if (isset($subject["embeddings_query"]) && trim($subject["embeddings_query"]))
// 						$previousSubjects[] = $subject["embeddings_query"];
// 				}
// 			}
// 		}
// 		
// 		return $previousSubjects;
// 	}
	
// 	public function getLastContextItems($idChat)
// 	{
// 		$lastResponce = AirichiesteresponseModel::getLast(array("PRODUCT_SEARCH", "INFORMATIONAL", "POLICY_QA", "FOLLOW_UP"), $idChat);
// 		
// 		if (!empty($lastResponce) && isset($lastResponce["request"]))
// 		{
// 			$responseArray = json_decode($lastResponce["request"], true);
// 			
// 			$input =  $responseArray["input"] ?? array();
// 			
// 			if (count($input) > 0)
// 			{
// 				$lastInput = $input[count($input) - 1];
// 				
// 				if (isset($lastInput["role"]) && $lastInput["role"] == "user" && isset($lastInput["content"]) && trim($lastInput["content"]))
// 				{
// 					$contentArray = json_decode($lastInput["content"], true);
// 					
// 					return $contentArray["context_items"] ?? array();
// 				}
// 			}
// 		}
// 		
// 		return array();
// 	}
	
	public function routing($messaggio, $zona = "Backend", $ambito = "Ecommerce")
	{
		$tpf = tpf("Elementi/AI/RAG/Routing/$zona/$ambito/prompt.txt");
		
		// $this->sendEvent([
		// 	'type'	=>	'status',
		// 	'phase'	=>	'understanding',
		// 	'text'	=>	gtext('Sto pensando...'),
		// ]);
		
		$lingua = $this->getLinguaDefault();
		
		if (is_file($tpf))
		{
			ob_start();
			include $tpf;
			$istruzioni = ob_get_clean();
			
			$istruzioni = str_replace("[NOME NEGOZIO]", Parametri::$nomeNegozio, $istruzioni);
			$istruzioni = str_replace("[LINGUA_DEFAULT]", $lingua, $istruzioni);
			
			// prompt scope
			ob_start();
			include tpf("Elementi/AI/RAG/Routing/$zona/$ambito/prompt_routing_scope.txt");
			$promptRoutingScope = ob_get_clean();
			
			if ($promptRoutingScope !== '')
				$istruzioni.= "\n\n".$promptRoutingScope;
			
			$messaggi = $this->recuperaMessaggi(self::$idChat, v("numero_messaggi_storico_chat_da_riportare"), true);
			
			$additionalContext = $this->recuperaAdditionalContext(self::$idChat);
			
			$contestoPrecedenteArray = array();
			$contestoPrecedente = "";
			
			if (count($messaggi) > 0)
			{
				$contestoPrecedenteArray[] = array(
					"recent_chat" => $messaggi,
				);
			}
			
			if (count($additionalContext) > 0)
			{
				$contestoPrecedenteArray[] = array(
					"additional_context" => $additionalContext,
				);
			}
			
			if (count($contestoPrecedenteArray) > 0)
				$contestoPrecedente = json_encode($contestoPrecedenteArray);
			
			$messaggio = AimodelliModel::getModulo(AimodelliModel::g(false)->getModelloPredefinito(), true)->setMessaggio($messaggio);
			
			AirichiesteresponseModel::$tipo = "ROUTING";
			
			$airrModel = new AirichiesteresponseModel();
			$airrModel->db->beginTransaction();
			
			$superatoNumeroTotaleMinuto = AirichiesteresponseModel::limiteSuperato(60, v("numero_richieste_routing_al_minuto"));
			$superatoNumeroTotaleIpOra = AirichiesteresponseModel::limiteSuperato(3600, v("numero_richieste_per_ip_ogni_ora"), "ROUTING", true);
			
			if (!$superatoNumeroTotaleMinuto && !$superatoNumeroTotaleIpOra)
			{
				AirichiesteresponseModel::$idLastInsert = $airrModel->aggiungi("", "");
				$airrModel->db->commit();
				
				return $this->richiesta(array($messaggio), $contestoPrecedente, $istruzioni, null, false, "low", self::$routingSchema);
			}
			else
			{
				$airrModel->db->commit();
				
				// if (isset(Params::$lang))
				// 	$lingua = Params::$lang;
				// else
				// {
				// 	if (App::$isFrontend)
				// 		$lingua = v("lingua_default_frontend");
				// 	else
				// 		$lingua = v("default_backend_language");
				// }
				
				if ($superatoNumeroTotaleMinuto)
					return array(1, '{"intent":"threshold_exceeded","confidence":1,"language":"'.$lingua.'"}');
				else if ($superatoNumeroTotaleIpOra)
					return array(1, '{"intent":"threshold_exceeded_ip","confidence":1,"language":"'.$lingua.'"}');
			}
		}
		
		return array("", "");
	}
	
	private function getLinguaDefault()
	{
		if (isset(Params::$lang))
			$lingua = Params::$lang;
		else
		{
			if (App::$isFrontend)
				$lingua = v("lingua_default_frontend");
			else
				$lingua = v("default_backend_language");
		}
		
		return $lingua;
	}
	
	public function checkRichiesta($messaggi)
	{
		$messaggio = $messaggi[count($messaggi) - 1]["content"];
		
		if (strlen($messaggio) <= (int)v("numero_massimo_caratteri_messaggio_ai"))
			return true;
		
		return false;
	}
	
	public function richiesta($messaggi, $contesto = "", $istruzioni = "", $idModello = null, $forza = false, $reasoning = "low", $routingSchema = array(), $prenotaResponse = false)
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
			list($res, $output) = array(0, gtext(self::$fraseRichiestaTroppoLunga));
		else
		{
			if ($prenotaResponse)
			{
				$airrModel = new AirichiesteresponseModel();
				$airrModel->db->beginTransaction();
				AirichiesteresponseModel::$idLastInsert = $airrModel->aggiungi("", "");
				$airrModel->db->commit();
			}
			
			list($res, $output) = AimodelliModel::getModulo($idModello, true)->chat($messaggi, $contesto, $istruzioni, $reasoning, $routingSchema);
		}
		
		// echo $output."\n\n\n";
		
		if (v("ai_attiva_cache"))
			AirichiestecacheModel::g()->set($messaggi, $contesto, $istruzioni, $idModello, $output);
		
		return array($res, $output);
	}
	
	public function sendEvent(array $data): void
	{
		echo json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";

		if (ob_get_level() > 0)
			ob_flush();

		flush();
	}
}