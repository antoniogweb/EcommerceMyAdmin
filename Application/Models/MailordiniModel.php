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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('EG')) die('Direct access not allowed!');

class MailordiniModel extends GenericModel
{
	const CLIENTE = 'CLIENTE';
	const NEGOZIO = 'NEGOZIO';
	const SISTEMISTA = 'SISTEMISTA';
	
	public static $mailInstance = null;
	public static $idMailInviate = array();
	public static $variabiliTema = array();
	
	public $checkLimitiInvio = true;
	
	public function __construct() {
		$this->_tables = 'mail_ordini';
		$this->_idFields = 'id_mail';
		
		parent::__construct();
	}
	
	public function insert()
	{
		$this->values["time_creazione"] = time();
		
		$bccArray = array();
		
		if (isset($this->values["bcc"]) && $this->values["bcc"] && trim($this->values["bcc"]))
			$bccArray = explode(",", trim($this->values["bcc"]));
		
		$this->values["numero_inviate"] = (count($bccArray) + 1);
		
		if (VariabiliModel::checkNumeroMailInviate())
		{
			$this->checkLimitiInvio = true;
			
			$this->db->beginTransaction();
			
			$timeOra = time() - 3600;
			$timeGiorno = time() - (3600 * 24);
			
			// CHECK ORA
			$resOra = $this->query(array("select numero_inviate from mail_ordini where inviata = 1 and time_creazione > ? for update",array((int)$timeOra)));
			
			$numero = 0;
			foreach ($resOra as $r)
			{
				$numero += (int)$r["mail_ordini"]["numero_inviate"];
			}
			
			// $numero = isset($resOra[0]["aggregate"]["NUMERO"]) ? (int)$resOra[0]["aggregate"]["NUMERO"] : 0;
			$numero += $this->values["numero_inviate"];
			
			if ($numero > v("max_numero_email_ora"))
			{
				$this->checkLimitiInvio = false;
				$this->values["superato_limite_orario"] = 1;
			}
			
			if ($this->checkLimitiInvio)
			{
				// CHECK GIORNO
				$resOra = $this->query(array("select numero_inviate from mail_ordini where inviata = 1 and time_creazione > ? for update",array((int)$timeGiorno)));
				
				$numero = 0;
				foreach ($resOra as $r)
				{
					$numero += (int)$r["mail_ordini"]["numero_inviate"];
				}
				// $numero = isset($resOra[0]["aggregate"]["NUMERO"]) ? (int)$resOra[0]["aggregate"]["NUMERO"] : 0;
				$numero += $this->values["numero_inviate"];
				
				if ($numero > v("max_numero_email_giorno"))
				{
					$this->checkLimitiInvio = false;
					$this->values["superato_limite_giornaliero"] = 1;
				}
			}
		}
		
		$res = parent::insert();
		
		if (VariabiliModel::checkNumeroMailInviate())
			$this->db->commit();
		
		return $res;
	}
	
	public static function loadTemplate($oggetto, $body)
	{
		$template = v("mail_template");
		
		$path = tpf("/Email/".$template.".php");
		
		if (file_exists($path))
		{
			TestiModel::$mostraIconaEdit = false;
			
			ob_start();
			include ($path);
			$body = ob_get_clean();
			
			TestiModel::$mostraIconaEdit = true;
		}
		
		return $body;
	}
	
	public static function inviaMailLog($oggetto, $testo, $tipologia, $variabile = "email_log_errori")
	{
		if (v($variabile))
		{
			$emails = explode(",", v($variabile));
			
			self::inviaMail(array(
				"emails"	=>	$emails,
				"oggetto"	=>	$oggetto,
				"testo"		=>	$testo,
				"tipologia"	=>	$tipologia,
				"usa_template"	=>	false,
			));
		}
	}
	
	public static function setBcc($mail, $emails)
	{
		$arrayBcc = array();
		
		if (ImpostazioniModel::$valori["bcc"] && !in_array(ImpostazioniModel::$valori["bcc"], $emails))
		{
			$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
			$arrayBcc[] = ImpostazioniModel::$valori["bcc"];
		}
		
		if (defined("BCC") && is_array(BCC))
		{
			foreach (BCC as $emailBcc)
			{
				if (!in_array($emailBcc, $emails))
				{
					$mail->addBCC($emailBcc);
					$arrayBcc[] = $emailBcc;
				}
			}
		}
		
		return $arrayBcc;
	}
	
	public static function inviaCredenziali($idUser, $variabili = array())
	{
		if (!isset($variabili["username"]) || !isset($variabili["password"]) || !$variabili["username"] || !$variabili["password"])
			return;
		
		$username = $clean["username"] = $variabili["username"];
		$password = $variabili["password"];
		$tokenConferma = isset($variabili["tokenConferma"]) ? $variabili["tokenConferma"] : "";
		$codiceVerifica = isset($variabili["codiceVerifica"]) ? $variabili["codiceVerifica"] : "";
		
		// MAIL AL CLIENTE
		ob_start();
		include tpf("/Regusers/mail_credenziali.php");
		$output = ob_get_clean();
		
		return MailordiniModel::inviaMail(array(
			"emails"	=>	array($username),
			"oggetto"	=>	"invio credenziali nuovo utente",
			"testo"		=>	$output,
			"tipologia"	=>	"ISCRIZIONE",
			"id_user"	=>	(int)$idUser,
			"id_page"	=>	0,
		));
	}
	
	public static function inviaMail($params)
	{
		require_once(LIBRARY.'/External/PHPMailer-master/src/Exception.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/PHPMailer.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/SMTP.php');
		
		$mo = new MailordiniModel();
		
		$lingua = isset($params["lingua"]) ? $params["lingua"] : Params::$lang;
		$country = isset($params["country"]) ? $params["country"] : Params::$country;
		$emails = $params["emails"];
		$oggetto = $params["oggetto"];
		$oggettoPlaceholder = isset($params["oggetto_placeholder"]) ? $params["oggetto_placeholder"] : "";
		$idO = isset($params["id_o"]) ? $params["id_o"] : 0;
		$idUser = isset($params["id_user"]) ? $params["id_user"] : 0;
		$testo = isset($params["testo"]) ? $params["testo"] : "";
		$pathTestoMail = isset($params["testo_path"]) ? $params["testo_path"] : "";
		$tipologia = isset($params["tipologia"]) ? $params["tipologia"] : "ORDINE";
		$idPage = isset($params["id_page"]) ? $params["id_page"] : 0;
		$replyTo = isset($params["reply_to"]) ? $params["reply_to"] : "";
		$idContatto = isset($params["id_contatto"]) ? $params["id_contatto"] : 0;
		$tipo = isset($params["tipo"]) ? $params["tipo"] : "A";
		$idEvento = isset($params["id_evento"]) ? $params["id_evento"] : 0;
		$usaTemplate = isset($params["usa_template"]) ? $params["usa_template"] : true;
		$arrayVariabili = isset($params["array_variabili"]) ? $params["array_variabili"] : null;
		$arrayVariabiliTema = isset($params["array_variabili_tema"]) ? $params["array_variabili_tema"] :  array();
		$allegati = (isset($params["allegati"]) && is_array($params["allegati"])) ? $params["allegati"] : array();
		$tabella = isset($params["tabella"]) ? $params["tabella"] : "";
		$idElemento = isset($params["id_elemento"]) ? $params["id_elemento"] : 0;
		$traduciOggetto = isset($params["traduci_oggetto"]) ? $params["traduci_oggetto"] : true;
		$numeroDocumento = isset($params["numero_documento"]) ? $params["numero_documento"] : 0;
		
		self::$variabiliTema = $arrayVariabiliTema;
		
		$bckLang = Params::$lang;
		$bckCountry = Params::$country;
		$bckContesto = TraduzioniModel::$contestoStatic;
		
		self::$idMailInviate = array();
		
		try
		{
			if (self::$mailInstance !== null)
				$mail = self::$mailInstance;
			else
				$mail = self::$mailInstance = new PHPMailer(true); //New instance, with exceptions enabled

			if (Parametri::$useSMTP)
			{
				$mail->IsSMTP();                         // tell the class to use SMTP
				$mail->Port       = Parametri::$SMTPPort;                    // set the SMTP server port
				$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
				
				if (Parametri::$SMTPUsername && Parametri::$SMTPPassword)
				{
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
					$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
					$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
				}
				
				if (ImpostazioniModel::$valori["smtp_secure"])
					$mail->SMTPSecure = ImpostazioniModel::$valori["smtp_secure"];
			}
			
			$mail->From       = Parametri::$mailFrom;
			$mail->FromName   = Parametri::$mailFromName;
			$mail->CharSet = 'UTF-8';
			
			if ($replyTo)
				$mail->AddReplyTo($replyTo);
			else
				$mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
			
			// Imposto le traduzioni del front
			TraduzioniModel::$contestoStatic = "front";
			Params::$lang = $lingua;
			
			if (v("attiva_nazione_nell_url"))
				Params::$country = $country;
			
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
			
			if ($traduciOggetto)
				$oggetto = gtext($oggetto, false);
			
			$oggetto = str_replace("[ID_ORDINE]",$idO, $oggetto);
			$oggetto = str_replace("[NUMERO_DOCUMENTO]",$numeroDocumento, $oggetto);
			$oggetto = str_replace("[OGGETTO_PLACEHOLDER]",$oggettoPlaceholder, $oggetto);
			
			// Segnaposti
			if (isset($arrayVariabili))
				$oggetto = SegnapostoModel::sostituisci($oggetto, $arrayVariabili, null);
			
			$mail->Subject  = Parametri::$nomeNegozio." - $oggetto";
			$mail->IsHTML(true);
			
			if (!ImpostazioniModel::$valori["smtp_verify_tls"])
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
			
			// Svuoto tutte le mail
			$mail->ClearAllRecipients();
			
			$arrayBcc = self::setBcc($mail, $emails);
			
			// Se imposto un file di tema per il testo della mail
			if ($pathTestoMail)
			{
				ob_start();
				include tpf($pathTestoMail);
				$testo = ob_get_clean();
				
				foreach ($arrayVariabiliTema as $k => $v)
				{
					$testo = str_replace("[$k]", $v, $testo);
				}
			}
			
			$testoClean = $testo;
			
			if ($usaTemplate)
				$testo = MailordiniModel::loadTemplate($oggetto, $testo);
			
			// Carico gli allegati
			foreach ($allegati as $nomeAllegato => $allegato)
			{
				if (file_exists($allegato))
				{
					if (is_numeric($nomeAllegato))
						$mail->AddAttachment($allegato);
					else
						$mail->AddAttachment($allegato, $nomeAllegato);
				}
			}
			
			// echo $testo;die();
			// Recupero le traduzioni
			Params::$lang = $bckLang;
			Params::$country = $bckCountry;
			TraduzioniModel::$contestoStatic = $bckContesto;
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
			
			$mail->AltBody = gtext("Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML");
			$mail->MsgHTML($testo);
			
// 			$mail->SMTPDebug = 2;
			
			$arrayErrori = array();
			
			foreach ($emails as $email)
			{
				$mail->ClearAddresses();
				$mail->AddAddress($email);
				
				$inviata = 0;
				
				$mo->setValues(array(
					"id_o"		=>	$idO,
					"id_user"	=>	$idUser,
					"email"		=>	$email,
					"oggetto"	=>	$oggetto,
					"testo"		=>	$testoClean,
					"inviata"	=>	$inviata,
					"tipologia"	=>	$tipologia,
					"id_page"	=>	$idPage,
					"reply_to"	=>	$replyTo,
					"id_contatto"	=>	$idContatto,
					"tipo"		=>	$tipo,
					"id_evento"	=>	$idEvento,
					"bcc"		=>	count($arrayBcc) > 0 ? implode(",",$arrayBcc) : "",
					"tabella"	=>	$tabella,
					"id_elemento"	=>	$idElemento,
				));
				
				$res = $mo->insert();
				
				if ($res)
				{
					self::$idMailInviate[] = $mo->lId;
					
					if ($mo->checkLimitiInvio && $mail->Send())
					{
						$mo->sValues(array(
							"inviata"	=>	1,
						));
						
						if ($tipologia == "ISCRIZIONE" || $tipologia == "ISCRIZIONE AL NEGOZIO" || $tipologia == "ORDINE" || $tipologia == "ORDINE NEGOZIO" || $tipologia == "FORGOT" || $tipologia == "LINK_CONFERMA" || $tipologia == "INVIO_CODICE_TWO")
							$mo->setValue("testo", "");
						
						$mo->update($mo->lId);
					}
					else
						$arrayErrori[] = false;
				}
				else
					$arrayErrori[] = false;
			}
			
			return (int)count($arrayErrori) === 0 ? true : false;
		} catch (Exception $e) {
			Params::$lang = $bckLang;
			Params::$country = $bckCountry;
			TraduzioniModel::$contestoStatic = $bckContesto;
			return false;
		}
	}
	
	// Restituisce il numero di email inviate per quell'elemento ($tabella) e quel record ($idElemento)
	public static function numeroInviate($tabella, $idElemento, $email = "")
	{
		$mo = new MailordiniModel();
		
		$mo->clear()->where(array(
			"tabella"		=>	sanitizeAll($tabella),
			"id_elemento"	=>	(int)$idElemento,
		));
		
		if ($email)
			$mo->aWhere(array(
				"email"	=>	sanitizeAll($email),
			));
		
		return $mo->rowNumber();
	}
	
	// Estrae tutte le mail dell'ordine
	public function estraiMailOrdine($idOrdine, $tipologia)
	{
		return $this->clear()->where(array(
			"id_o"	=>	(int)$idOrdine,
			"tipologia"	=>	sanitizeAll($tipologia),
		))->orderBy("data_creazione desc")->send(false);
	}
	
	public function estraiRigaTabellaIdRef($tabella, $idElemento)
	{
		$mo = new MailordiniModel();
		
		$mo->clear()->where(array(
			"tabella"		=>	sanitizeAll($tabella),
			"id_elemento"	=>	(int)$idElemento,
		));
		
		return $mo->send(false);
	}
}
