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

class BaseRegusersModel extends Model_Tree
{
	use CommonModel;
	
	public $lId = null;
	public $uploadFields = array();
	public static $uploadFileGeneric = true;
	public $modificataEmail = false;
	
	public function __construct()
	{
		$this->_tables='regusers';
		$this->_idFields='id_user';
		
// 		$this->orderBy = 'regusers.id_user desc';
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/".v("nome_cartella_immagine_utente"),
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg',
				'allowedMimeTypes'	=>	'image/jpeg,image/png',
				"createImage"	=>	true,
				"forza_randon_field_name"	=>	true,
				"maxFileSize"	=>	3000000,
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	100,
					'imgHeight'		=>	100,
					'defaultImage'	=>  null,
					'cropImage'		=>	'yes',
				),
			),
		);
		
		parent::__construct();
		
		$this->_resultString->string["executed"] = "<div class='".v("alert_success_class")."'>".gtext("operazione eseguita!")."</div>\n";
	}
	
	// 	Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT). 
	public function lastId($forzaDb = false)
	{
		return !$forzaDb ? $this->lId : $this->db->lastId();
	}
	
	public function insert()
	{
		$this->values['forgot_token'] = $this->getUniqueToken(md5(randString(20).microtime().uniqid(mt_rand(),true)));
		
		if (v("conferma_registrazione") || v("gruppi_inseriti_da_approvare_alla_registrazione"))
			$this->values["has_confirmed"] = 1;
		
		// Se serve la conferma via mail
		if (v("conferma_registrazione"))
			$this->values["ha_confermato"] = 0;
		
		$this->values["lingua"] = Params::$lang;
		
		$this->values["creation_time"] = time();
		$this->values["confirmation_time"] = time();
		$this->values["time_token_reinvio"] = time();
		
		if (!User::$nazioneNavigazione)
			User::$nazioneNavigazione = isset(Params::$country) ? strtoupper(Params::$country) : v("nazione_default");
		
		$this->values["nazione_navigazione"] = User::$nazioneNavigazione;
		
		$checkFiscale = v("insert_account_cf_obbligatorio") && v("abilita_codice_fiscale");
		
		if ($this->controllaCF($checkFiscale) && $this->controllaPIva(v("insert_account_p_iva_obbligatorio")))
		{
			$this->setProvinciaFatturazione();
			
			$this->sistemaMaiuscole();
			
			$res = parent::insert();
			
			$this->lId = $this->lastId(true);
			
			// Gruppi temporanei
			if ($res && v("gruppi_inseriti_da_approvare_alla_registrazione"))
			{
				$rgt = new RegusersgroupstempModel();
				$rg = new ReggroupsModel();
				
				$gruppiTemp = explode(",",v("gruppi_inseriti_da_approvare_alla_registrazione"));
				
				foreach ($gruppiTemp as $idgt)
				{
					$record = $rg->selectId((int)$idgt);
					
					if ((int)$idgt === -1 || !empty($record))
					{
						$rgt->setValues(array(
							"id_user"	=>	$this->lId,
							"id_group"	=>	$idgt > 0 ? $idgt : 0,
						));
						
						$rgt->insert();
					}
				}
			}
			
			// Aggiungo la notifica
			if ($res && v("attiva_agenti") && isset($this->values["agente"]) && $this->values["agente"])
			{
				$agente = $this->selectId((int)$this->lId);
				
				if (!empty($agente))
				{
					$n = new NotificheModel();
					
					$n->sValues(array(
						"titolo"	=>	"Iscrizione nuovo agente: <br /><b>".RegusersModel::getNominativo(htmlentitydecodeDeep($agente))."</b>",
						"contesto"	=>	"AGENTE",
						"url"		=>	"regusers/form/update/".(int)$this->lId."?agente=1",
						"classe"	=>	"text-primary",
						"icona"		=>	"fa-user",
						"condizioni"=>	"attiva_agenti=1",
					));
					
					$n->insert();
				}
			}
			
			return $res;
		}
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$checkFiscale = v("abilita_codice_fiscale");
		
		$this->sistemaMaiuscole();
		
		$oldEmail = $this->clear()->whereId($clean["id"])->field("username");
		
		if ($this->controllaCF($checkFiscale) && $this->controllaPIva())
		{
			$this->setProvinciaFatturazione();
			
			$res = parent::update($clean["id"]);
			
			$newEmail = $this->clear()->whereId($clean["id"])->field("username");
			
			if ($res && v("attiva_liste_regalo"))
				ListeregaloModel::sincronizzaEmailConAccount($clean["id"]);
			
			if ($oldEmail != $newEmail)
				$this->modificataEmail = true;
			
			return $res;
		}
		
		return false;
	}
	
	public function pUpdate($id = null, $where = null)
	{
		return parent::update($id, $where);
	}
	
// 	public function pUpdate($id)
// 	{
// 		$clean["id"] = (int)$id;
// 		
// 		return parent::update($clean["id"]);
// 	}

	//get a unique token
	public function getUniqueToken($forgotToken)
	{
		$clean["forgotToken"] = sanitizeAll($forgotToken);
		
		$res = $this->clear()->where(array("forgot_token"=>$clean["forgotToken"]))->send();
		
		if (count($res) > 0)
		{
			$nForgotToken = md5(randString(10).microtime().uniqid(mt_rand(),true));
			return $this->getUniqueToken($nForgotToken);
		}
		
		return $clean["forgotToken"];
	}
	
	public function setAllowedPasswordCharacters($evidenzia)
	{
		$this->addStrongCondition("both",'checkMatch|/^[a-zA-Z0-9\_'.v("password_regular_expression_caratteri_speciali").']+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>".gtext("Tutte le lettere, maiuscole o minuscole")." (a, A, b, B, ...)</li><li>".gtext("Tutti i numeri")." (0,1,2,...)</li><li>".gtext("I seguenti caratteri").": <b>_".v("password_regular_expression_caratteri_speciali")."</b></li></ul>$evidenzia");
	}

	public function setPasswordCondition()
	{
		$evidenzia = Output::$html ? "<span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>" : "";
		
		$this->addStrongCondition("both",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b>$evidenzia");
		
		$evidenzia = Output::$html ? "<span class='evidenzia'>class_password</span>" : "";
		
		$this->setAllowedPasswordCharacters($evidenzia);
		// $this->addStrongCondition("both",'checkMatch|/^[a-zA-Z0-9\_'.v("password_regular_expression_caratteri_speciali").']+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>".gtext("Tutte le lettere, maiuscole o minuscole")." (a, A, b, B, ...)</li><li>".gtext("Tutti i numeri")." (0,1,2,...)</li><li>".gtext("I seguenti caratteri").": <b>_".v("password_regular_expression_caratteri_speciali")."</b></li></ul>$evidenzia");

		if (v("attiva_controllo_robustezza_password"))
			$this->setPasswordStrengthCondition("strong");
	}
	
	public function getIndirizzoSpedizionePerAdd($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzo = $sp->where(array(
			"id_user"	=>	(int)$id_user,
			"da_usare"	=>	"1",
		))->record();
		
		if (!empty($indirizzo))
			return $indirizzo["id_spedizione"];
		
		$idSpedUltimoOrdine = $this->getIdSpedizioneUsatoNellUltimoOrdine($id_user);
		
		if ($idSpedUltimoOrdine> 0)
			return $idSpedUltimoOrdine;
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
		))->orderBy("id_spedizione desc")->limit(1)->send(false);
		
		if (count($indirizzi) > 0)
			return $indirizzi[0]["id_spedizione"];
		
		return 0;
	}
	
	public function getIdSpedizioneUsatoNellUltimoOrdine($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
			"ultimo_usato"	=>	"Y",
		))->record();
		
		if (!empty($indirizzi))
			return $indirizzi["id_spedizione"];
		
		return 0;
	}
	
	public function getIndirizziSpedizione($id_user)
	{
		$sp = new SpedizioniModel();
		
		return $sp->where(array(
			"id_user"	=>	(int)$id_user,
		))->orderBy("ultimo_usato, indirizzo_spedizione")->send(false);
	}
	
	public function setConditions($tipo_cliente, $queryType = "insert", $pec = "", $codiceDestinatario = "")
	{
		$campiObbligatoriAggiuntivi = "";
		
		if (strcmp($tipo_cliente,"privato") !== 0 && (v("insert_account_sdi_pec_obbligatorio") || $queryType == "update") && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			if (trim($codiceDestinatario) == "")
				$campiObbligatoriAggiuntivi .= ",codice_destinatario";
			
			if (trim($pec) == "")
				$campiObbligatoriAggiuntivi .= ",pec";
			
			if (trim($pec) != "" || trim($codiceDestinatario) != "")
				$campiObbligatoriAggiuntivi = "";
		}
		
		$campoObbligatoriProvincia = "dprovincia";
		
		// if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		if (isset($_POST["nazione"]) && in_array((string)$_POST["nazione"], NazioniModel::nazioniConProvince()))
			$campoObbligatoriProvincia = "provincia";
		
		$campiObbligatoriComuni = "tipo_cliente";
		
		if (v("insert_account_indirizzo_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",indirizzo";
		
		if (v("insert_account_citta_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",citta";
		
		if (v("insert_account_telefono_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",telefono";
		
		if (v("insert_account_nazione_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",nazione";
		
		if (v("insert_account_provincia_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",$campoObbligatoriProvincia";
		
		if (v("insert_account_cap_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",cap";
		
// 		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT" && (v("insert_account_cf_obbligatorio") || $queryType == "update") && v("abilita_codice_fiscale"))
		if (self::camboObbligatorio("codice_fiscale", "regusers", $queryType))
			$campiObbligatoriComuni .= ",codice_fiscale";
		
		$campoPIva = "";
		
		if (isset($_POST["nazione"]) && in_array($_POST["nazione"], NazioniModel::elencoNazioniConVat()) && (v("insert_account_p_iva_obbligatorio") || $queryType == "update") && $tipo_cliente != "privato")
			$campoPIva = "p_iva,";
		
		$campiObbligatoriConfermaAccount = "";
		
		if (v("account_attiva_conferma_username"))
			$campiObbligatoriConfermaAccount .= ",conferma_username";
		
		if (v("account_attiva_conferma_password"))
			$campiObbligatoriConfermaAccount .= ",confirmation";
		
		$campiNominativi = "";
		
		if (v("insert_account_nominativo_obbligatorio"))
			$campiNominativi = ($tipo_cliente != "azienda") ? ",nome,cognome" : ",ragione_sociale";
		
		$campiObbligatori = $campiObbligatoriComuni.$campiNominativi.",".$campoPIva."username".$campiObbligatoriAggiuntivi;
		
		if ($queryType == "insert")
		{
			$campiObbligatori .= $campiObbligatoriConfermaAccount.",accetto,password";
			
			if (v("attiva_accetto_2"))
				$campiObbligatori .= ",accetto_2";
		}
		
		$this->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		$evidenziaEmail = Output::$html ? "<div class='evidenzia'>class_username</div>" : "";
		
		$this->addStrongCondition("both",'checkMail',"username|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>").$evidenziaEmail);
		
		$evidenziaPec = Output::$html ? "<div class='evidenzia'>class_pec</div>" : "";
		
		$this->addSoftCondition("both",'checkMail',"pec|".gtext("Si prega di ricontrollare <b>l'indirizzo Pec</b>").$evidenziaPec);
		
		$evidenziaCD = Output::$html ? "<div class='evidenzia'>class_codice_destinatario</div>" : "";
		
		$this->addSoftCondition("both",'checkLength|7',"codice_destinatario|".gtext("Si prega di ricontrollare <b>il Codice Destinatario</b>").$evidenziaCD);
		
		if (strcmp($queryType,"insert") === 0)
		{
			if (v("account_attiva_conferma_username"))
			{
				$evidenziaConfermaUSer = Output::$html ? "<div class='evidenzia'>class_conferma_username</div>" : "";
				
				$this->addStrongCondition("both",'checkMail',"conferma_username|".gtext("Si prega di ricontrollare il campo <b>conferma dell'indirizzo Email</b>").$evidenziaConfermaUSer);
				
				$evidenziaEqual = Output::$html ? "<div class='evidenzia'>class_username</div><div class='evidenzia'>class_conferma_username</div>" : "";
				
				$this->addStrongCondition("both",'checkEqual',"username,conferma_username|<b>".gtext("I due indirizzi email non corrispondono")."</b>$evidenziaEqual");
			}
			
			$evidenziaAccetto = Output::$html ? "<div class='evidenzia'>class_accetto</div>" : "";
			
			$this->addStrongCondition("both",'checkIsStrings|accetto',"accetto|<b>".gtext("Si prega di accettare le condizioni di privacy")."</b>$evidenziaAccetto");
			
			if (v("attiva_accetto_2"))
			{
				$evidenziaAccetto2 = Output::$html ? "<div class='evidenzia'>class_accetto_2</div>" : "";
				
				$this->addStrongCondition("both",'checkIsStrings|accetto',"accetto_2|<b>".gtext("Si prega di accettare le condizioni aggiuntive")."</b>$evidenziaAccetto2");
			}
		}
		
		if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_UTENTE", "pagamento") && isset($_POST["nazione"]) && $_POST["nazione"])
			$this->addStrongCondition("both",'checkIsStrings|'.OrdiniModel::getPagamentiPermessi($_POST["nazione"]),"pagamento|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>")."<div class='evidenzia'>class_pagamento</div>");
		
		$this->addStrongCondition("both",'checkIsStrings|'.TipiclientiModel::getListaTipi(),"tipo_cliente|<b>".gtext("Si prega di indicare se siete un privato o un'azienda")."</b>");
		
		$this->addSoftCondition("both",'checkLength|300',"indirizzo_spedizione|<b>L'indirizzo di spedizione non può superare i 300 caratteri</b><div class='evidenzia'>class_indirizzo_spedizione</div>");
		
		$evidenziaE = Output::$html ? "<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>" : "";
		
		if (Output::$html)
		{
			if (isset($_POST["username"]) && RegusersModel::utenteDaConfermare($_POST["username"]))
				$erroreUtenteGiaPresente = "username|".gtext("Il suo account è già presente nel nostro sistema ma non è attivo perché non è mai stata completata la verifica dell'indirizzo e-mail.")."<br />".gtext("Proceda con la conferma del proprio account al seguente")." <a href='".Url::getRoot()."account-verification'>".gtext("indirizzo web")."</a>$evidenziaE";
			else
				$erroreUtenteGiaPresente = "username|".gtext("La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.<br />Se non ricorda la password può impostarne una nuova al seguente")." <a href='".Url::getRoot()."password-dimenticata'>".gtext("indirizzo web")."</a>$evidenziaE";
			
			$this->databaseConditions['insert'] = array(
				"checkUnique"		=>	$erroreUtenteGiaPresente,
			);
		}
		else
			$this->databaseConditions['insert'] = array(
				"checkUnique"		=>	"username|".gtext("La sua E-Mail è già presente nel nostro database, significa che è già registrato nel nostro sistema.<br /> Se non ricorda la password può impostarne una nuova."),
			);
		
		$evidenziaEU = Output::$html ? "<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>" : "";
		
		$this->databaseConditions['update'] = array(
			"checkUniqueCompl"		=>	"username|".gtext("Questa E-Mail è già usata da un altro utente e non può quindi essere scelta").$evidenziaEU,
		);
		
		$naz = new NazioniModel();
		
		$codiciNazioniAttive = implode(",",$naz->selectCodiciAttivi());
		
		if (v("insert_account_nazione_obbligatoria") || $queryType == "update")
			$this->addStrongCondition("both",'checkIsStrings|'.$codiciNazioniAttive,"nazione|".gtext("<b>Si prega di selezionare una nazione tra quelle permesse</b>"));
		
		if (strcmp($queryType,"insert") === 0)
		{
			if (v("account_attiva_conferma_password"))
			{
				$evidenziaP = Output::$html ? "<span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>" : "";
				
				$this->addStrongCondition("both",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b>".$evidenziaP);
			}
			
			$evidenziaPC = Output::$html ? "<span class='evidenzia'>class_password</span>" : "";
			
			$this->setAllowedPasswordCharacters($evidenziaPC);

			if (v("attiva_controllo_robustezza_password"))
				$this->setPasswordStrengthCondition("strong");
			
			// $this->addStrongCondition("both",'checkMatch|/^[a-zA-Z0-9\_\-\!\,\.]+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>".gtext("Tutte le lettere, maiuscole o minuscole")." (a, A, b, B, ...)</li><li>".gtext("Tutti i numeri")." (0,1,2,...)</li><li>".gtext("I seguenti caratteri").": <b>_ - ! , .</b></li></ul>$evidenziaPC");
		}
		
		$evidenziaT = Output::$html ? "<div class='evidenzia'>class_telefono</div>" : "";
		
		$this->addSoftCondition("both","checkMatch|/^[0-9\s\+]+$/","telefono|".gtext("Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche")."$evidenziaT");
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			$evidenziaCAP = Output::$html ? "<div class='evidenzia'>class_cap</div>" : "";
			
			$this->addSoftCondition("both","checkMatch|/^[0-9]+$/","cap|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche").$evidenziaCAP);
			
			$evidenziaCF = Output::$html ? "<div class='evidenzia'>class_codice_fiscale</div>" : "";
			
			if (v("abilita_codice_fiscale"))
				$this->addSoftCondition("both","checkMatch|/^[0-9a-zA-Z]+$/","codice_fiscale|".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>").$evidenziaCF);
			
			$evidenziaPIVA = Output::$html ? "<div class='evidenzia'>class_p_iva</div>" : "";
			
			$this->addSoftCondition("both","checkMatch|/^[0-9a-zA-Z]+$/","p_iva|".gtext("Si prega di controllare il campo <b>Partita Iva").$evidenziaPIVA);
		}
	}
	
	// Iscrivi a newsletter l'utente
	public function iscriviANewsletter($id_user)
	{
		$clean["id_user"] = (int)$id_user;
		
		$record = $this->clear()->where(array("id_user"=>$clean["id_user"]))->record();
		
		if (!empty($record))
		{
			// Iscrizione alla newsletter
			if (ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
			{
				$dataMailChimp = array(
					"email"	=>	$record["username"],
					"status"=>	"subscribed",
				);
				
				if ($record["tipo_cliente"] == "azienda")
				{
					$dataMailChimp["firstname"] = $record["ragione_sociale"];
					$dataMailChimp["lastname"] = $record["ragione_sociale"];
				}
				else
				{
					$dataMailChimp["firstname"] = $record["nome"];
					$dataMailChimp["lastname"] = $record["cognome"];
				}
				
				$code = syncMailchimp($dataMailChimp);
				
// 				echo $code;
			}
		}
	}
	
	public static function getUrlAccountEliminato($tokenEliminazione = "")
	{
		$idRedirect = PagineModel::gTipoPagina("ACCOUNT_ELIMINATO");
		
		$queryStringEliminazione = "";
		
		if (!v("elimina_record_utente_ad_autoeliminazione"))
			$queryStringEliminazione = "?".v("variabile_token_eliminazione")."=".(string)$tokenEliminazione;
		
		if ($idRedirect)
			return getUrlAlias($idRedirect).$queryStringEliminazione;
		else
			return 'account-cancellato.html'.$queryStringEliminazione;
	}
	
	public static function getUrlApprovazioneEliminata($tokenEliminazione = "")
	{
		$idRedirect = PagineModel::gTipoPagina("APPROVAZ_ELIMINATA");
		
		$queryStringEliminazione = "?".v("variabile_token_eliminazione")."=".(string)$tokenEliminazione;
		
		if ($idRedirect)
			return getUrlAlias($idRedirect).$queryStringEliminazione;
		else
			return ''.$queryStringEliminazione;
	}
	
	public function getIdUtenteDaIdApp($codiceApp, $idApp)
    {
		return RegusersintegrazioniloginModel::g()->getIdUtenteDaIdApp($codiceApp, $idApp);
    }
    
    public function accountDaEliminareANuovoOrdine($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			$numeroListe = ListeregaloModel::g()->where(array(
				"id_user"	=>	(int)$id,
			))->rowNumber();
			
			if ($numeroListe)
				return false;
			
			$ordiniUtente = OrdiniModel::g()->select("stato,pagamento")->where(array(
				"id_user"	=>	(int)$id,
			))->send(false);
			
			if ((int)count($ordiniUtente) === 0)
				return true;
			
			if ((int)count($ordiniUtente) === 1 && $ordiniUtente[0]["stato"] === "pending" && OrdiniModel::conPagamentoOnline($ordiniUtente[0]))
				return true;
		}
		
		return false;
    }
    
    public static function checkEdEliminaAccount()
    {
		if (!v("elimina_account_ad_ordine_se_parcheggiato"))
			return;
		
		if (!User::$logged && !VariabiliModel::confermaUtenteRichiesta() && isset($_POST["email"]))
		{
			$rModel = new RegusersModel();
			
			$utente = $rModel->clear()->where(array(
				"username"	=>	sanitizeAll($_POST["email"]),
			))->record();
			
			if (!empty($utente) && $rModel->accountDaEliminareANuovoOrdine($utente["id_user"]))
				$rModel->deleteAccount($utente["id_user"]);
		}
    }
    
    public function isCompleto($idCliente)
    {
		return (int)$this->clear()->where(array(
			"id_user"	=>	(int)$idCliente,
		))->field("completo");
    }
    
//     public function sincronizzaDaOrdine($idCliente, $idOrdine)
//     {
// 		$ordine = OrdiniModel::g()->selectId($idOrdine);
// 		
// 		if (!empty($ordine))
// 		{
// 			$campiDaCopiare = OpzioniModel::arrayValori("CAMPI_DA_COPIARE_DA_ORDINE_A_CLIENTE");
// 			
// 			$this->sValues(array(
// 				"completo"	=>	1,
// 			));
// 			
// 			foreach ($campiDaCopiare as $cdc)
// 			{
// 				$this->setValue($cdc, $ordine[$cdc], "sanitizeDb");
// 			}
// 			
// 			$this->pUpdate($idCliente);
// 		}
//     }
    
    public function haTelefono($idCliente)
    {
		$record = $this->clear()->selectId((int)$idCliente);
		
		if (!empty($record) && $record["telefono"])
			return true;
		
		return false;
    }
    
    public function checkNumeroTentativiVerifica($idUser)
	{
		$numero = (int)$this->clear()->where(array(
			"id_user"				=>	(int)$idUser,
			"has_confirmed"			=>	1,
			"ha_confermato"			=>	0,
			"bloccato"				=>	0,
		))->field("tentativi_verifica");
		
		if ($numero >= 3)
		{
			$tokenConferma = md5(randString(30).microtime().uniqid(mt_rand(),true));
			$tokenReinvio = md5(randString(30).microtime().uniqid(mt_rand(),true));
			$codiceConfermaRegistrazione = sanitizeAll(generateString(v("conferma_registrazione_numero_cifre_codice_verifica"), "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"));
			
			$this->sValues(array(
				"confirmation_token"	=>	$tokenConferma,
				"token_reinvio"			=>	$tokenReinvio,
				"codice_verifica"		=>	$codiceConfermaRegistrazione,
				"confirmation_time"		=>	0,
				"time_token_reinvio"	=>	0,
			));
			
			return false;
		}
		else
			return true;
	}
    
    public function checkCodice($idUser, $tokenConferma, $codice)
	{
		$record = $this->selectId((int)$idUser);
		
		$numero = $this->clear()->where(array(
			"confirmation_token"	=>	sanitizeAll($tokenConferma),
			"has_confirmed"			=>	1,
			"ha_confermato"			=>	0,
			"bloccato"				=>	0,
			"ne"	=>	array(
				"confirmation_token"	=>	"",
			),
			"codice_verifica"	=>	sanitizeAll($codice),
			" ne"	=>	array(
				"codice_verifica"	=>	"",
			),
		))->rowNumber();
		
		if (trim($codice) && $numero)
		{
			$this->sValues(array(
				"has_confirmed"	=>	0,
				"ha_confermato"	=>	1,
				"confirmation_time"		=>	0,
				"time_token_reinvio"	=>	0,
				"confirmation_token"	=>	"",
			));
			
			$res = true;
		}
		else
		{
			$this->sValues(array(
				"tentativi_verifica"	=> ((int)$record["tentativi_verifica"] + 1),
			));
			
			$res = false;
		}
		
		$this->pUpdate((int)$idUser);
		
		return $res;
	}
}
