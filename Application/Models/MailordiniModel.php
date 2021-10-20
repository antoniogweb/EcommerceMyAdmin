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

require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/Exception.php');
require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/PHPMailer.php');
require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/SMTP.php');

class MailordiniModel extends GenericModel
{
	public static $mailInstance = null;
	
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
	
	public static function inviaMailLog($oggetto, $testo, $tipologia)
	{
		if (v("email_log_errori"))
		{
			$emails = explode(",", v("email_log_errori"));
			
			self::inviaMail(array(
				"emails"	=>	$emails,
				"oggetto"	=>	$oggetto,
				"testo"		=>	$testo,
				"tipologia"	=>	$tipologia,
			));
		}
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
		$tipologia = isset($params["tipologia"]) ? $params["tipologia"] : "ORDINE";
		$idPage = isset($params["id_page"]) ? $params["id_page"] : 0;
		$replyTo = isset($params["reply_to"]) ? $params["reply_to"] : "";
		$idContatto = isset($params["id_contatto"]) ? $params["id_contatto"] : 0;
		$tipo = isset($params["tipo"]) ? $params["tipo"] : "A";
		
		$bckLang = Params::$lang;
		$bckContesto = TraduzioniModel::$contestoStatic;
		
		try
		{
			if (self::$mailInstance !== null)
				$mail = self::$mailInstance;
			else
				$mail = self::$mailInstance = new PHPMailer(true); //New instance, with exceptions enabled

			if (Parametri::$useSMTP)
			{
				$mail->IsSMTP();                         // tell the class to use SMTP
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Port       = Parametri::$SMTPPort;                    // set the SMTP server port
				$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
				$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
				$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
				
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
			
			if (ImpostazioniModel::$valori["bcc"] && !in_array(ImpostazioniModel::$valori["bcc"], $emails))
				$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
			
			if (defined("BCC") && is_array(BCC))
			{
				foreach (BCC as $emailBcc)
				{
					if (!in_array($emailBcc, $emails))
						$mail->addBCC($emailBcc);
				}
			}
			
			$testoClean = $testo;
			$testo = MailordiniModel::loadTemplate($oggetto, $testo);
// 				echo $output;die();
			// Imposto le traduzioni del back
			Params::$lang = $bckLang;
			TraduzioniModel::$contestoStatic = $bckContesto;
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
			
			$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
			$mail->MsgHTML($testo);
			
			foreach ($emails as $email)
			{
				$mail->ClearAddresses();
				$mail->AddAddress($email);
				
				$inviata = 0;
				
				if ($mail->Send())
					$inviata = 1;
				
				if ($tipologia == "ISCRIZIONE" || $tipologia == "ISCRIZIONE AL NEGOZIO" || $tipologia == "ORDINE" || $tipologia == "ORDINE NEGOZIO")
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
				));
				
				$mo->insert();
			}
			
			return true;
		} catch (Exception $e) {
			Params::$lang = $bckLang;
			TraduzioniModel::$contestoStatic = $bckContesto;
			return false;
		}
	}
}
