<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

require_once(LIBRARY.'/External/PHPMailer-master/src/Exception.php');
require_once(LIBRARY.'/External/PHPMailer-master/src/PHPMailer.php');
require_once(LIBRARY.'/External/PHPMailer-master/src/SMTP.php');

class MailordiniModel extends GenericModel
{
	const CLIENTE = 'CLIENTE';
	const NEGOZIO = 'NEGOZIO';
	const SISTEMISTA = 'SISTEMISTA';
	
	public static $mailInstance = null;
	public static $idMailInviate = array();
	public static $variabiliTema = array();
	
	public function __construct() {
		$this->_tables = 'mail_ordini';
		$this->_idFields = 'id_mail';
		
		parent::__construct();
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
		$mo = new MailordiniModel();
		
		$lingua = isset($params["lingua"]) ? $params["lingua"] : Params::$lang;
		$emails = $params["emails"];
		$oggetto = $params["oggetto"];
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
		
		self::$variabiliTema = $arrayVariabiliTema;
		
		$bckLang = Params::$lang;
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
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
			
			$oggetto = gtext($oggetto, false);
			$oggetto = str_replace("[ID_ORDINE]",$idO, $oggetto);
			
			// Segnaposti
			if (isset($arrayVariabili))
				$oggetto = SegnapostoModel::sostituisci($oggetto, $arrayVariabili, null);
			
			$mail->Subject  = Parametri::$nomeNegozio." - $oggetto";
			$mail->IsHTML(true);
			
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
			
// 			echo $testo;die();
			// Imposto le traduzioni del back
			Params::$lang = $bckLang;
			TraduzioniModel::$contestoStatic = $bckContesto;
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
			
			$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
			$mail->MsgHTML($testo);
			
// 			$mail->SMTPDebug = 2;
			
			foreach ($emails as $email)
			{
				$mail->ClearAddresses();
				$mail->AddAddress($email);
				
				$inviata = 0;
				
				if ($mail->Send())
					$inviata = 1;
				
				if ($tipologia == "ISCRIZIONE" || $tipologia == "ISCRIZIONE AL NEGOZIO" || $tipologia == "ORDINE" || $tipologia == "ORDINE NEGOZIO" || $tipologia == "FORGOT" || $tipologia == "LINK_CONFERMA")
					$testoClean = "";
				
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
				));
				
				if ($mo->insert())
					self::$idMailInviate[] = $mo->lId;
			}
			
			return true;
		} catch (Exception $e) {
			Params::$lang = $bckLang;
			TraduzioniModel::$contestoStatic = $bckContesto;
			return false;
		}
	}
}
