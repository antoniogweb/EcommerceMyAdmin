<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class OrdiniModel extends FormModel {

	public static $stati = array(
		"pending"	=>	"Ordine ricevuto",
		"completed"	=>	"Ordine pagato e in lavorazione",
		"closed"	=>	"Ordine completato e spedito",
		"deleted"	=>	"Ordine annullato",
	);
	
	public static $labelStati = array(
		"pending"	=>	"default",
		"completed"	=>	"primary",
		"closed"	=>	"success",
		"deleted"	=>	"danger",
	);
	
	public static function statiSuccessivi($stato)
	{
		switch ($stato)
		{
			case "pending":
				return array("completed","deleted");
			case "completed":
				return array("closed","deleted");
			case "closed":
				return array("deleted");
			case "deleted":
				return array("pending");
		}
	}
	
	public static function getTipoMail($tipo)
	{
		switch ($tipo)
		{
			case "A":
				return "Mail ordine annullato";
			case "F":
				return "Mail invio fattura";
			case "P":
				return "Mail pagamento avvenuto correttamente";
			case "C":
				return "Mail ordine consegnato";
			case "R":
				return "Mail ordine ricevuto";
			default:
				return "--";
		}
	}
	
	public $cart_uid = null;
	public $lId = null;
	
	public function __construct() {
		$this->_tables='orders';
		$this->_idFields='id_o';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'orders.id_order';
		$this->_lang = 'It';
		
		$this->_popupItemNames = array(
			'tipo_cliente'	=>	'tipo_cliente',
			'stato'	=>	'stato',
			'pagamento'	=>	'pagamento',
			'registrato'	=> 'registrato',
		);

		$this->_popupLabels = array(
			'stato'	=>	'STATO ORDINE',
			'tipo_cliente'	=>	'TIPO CLIENTE',
			'pagamento'	=>	'PAGAMENTO',
			'registrato'	=> 'UTENTE REGISTRATO',
		);

		$this->_popupFunctions = array(
			'stato'	=>	'statoOrdine',
			'pagamento'	=>	'metodoPagamento',
			'registrato'	=> 'getYesNo',
		);
		
		parent::__construct();
		
		self::$stati = array(
			"pending"	=>	gtext("Ordine ricevuto", false),
			"completed"	=>	gtext("Ordine pagato e in lavorazione", false),
			"closed"	=>	gtext("Ordine completato e spedito", false),
			"deleted"	=>	gtext("Ordine annullato", false),
		);
	}
	
	public function relations() {
        return array(
			'pages' => array("HAS_MANY", 'MailordiniModel', 'id_o', null, "CASCADE"),
        );
    }
    
	public function getAdminToken($id_order, $cart_uid)
	{
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["id_order"] = (int)$id_order;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_order"], "cart_uid" => $clean["cart_uid"] ))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["orders"]["admin_token"];
		}
		
		return "token";
	}
	
	//restituisci il codice fiscale o la partita iva
	public function getCFoPIva($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["orders"]["tipo_cliente"],"privato") === 0)
			{
				return "CF: ".$res[0]["orders"]["codice_fiscale"];
			}
			else
			{
				return "P.IVA: ".$res[0]["orders"]["p_iva"];
			}
		}
		
		return "";
	}
	
	//restituisci il nome del cliente dell'ordine (nome cognome o ragione sociale)
	public function getNome($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["orders"]["tipo_cliente"],"privato") === 0 || strcmp($res[0]["orders"]["tipo_cliente"],"libero_professionista") === 0)
			{
				return $res[0]["orders"]["nome"]." ".$res[0]["orders"]["cognome"];
			}
			else
			{
				return $res[0]["orders"]["ragione_sociale"];
			}
		}
		
		return "";
	}
	
	//get a unique uid
	public function getUniqueId($uid)
	{
		$clean["uid"] = sanitizeAll($uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["uid"]))->send();
		
		if (count($res) > 0)
		{
			$nUid = md5(randString(10).microtime().uniqid(mt_rand(),true));
			return $this->getUniqueId($nUid);
		}
		
		$this->cart_uid = $clean["uid"];
		return $clean["uid"];
	}
	
	public function insert()
	{
		$this->values["cart_uid"] = $this->getUniqueId($this->values["cart_uid"]);
		
		$this->values["lingua"] = Params::$lang;
		$this->values["nazione_navigazione"] = User::$nazioneNavigazione;
		
		if ($this->controllaCF())
			return parent::insert();
		
// 		$this->lId = (int)$this->lastId();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		if ($this->controllaCF())
			return parent::update($clean["id"]);
		
		return false;
	}
	
	public function cartUidAlreadyPresent($cart_uid)
	{
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	public function recordExists($id_o, $cart_uid)
	{
		$clean["id_o"] = (int)$id_o;
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		
		$res = $this->clear()->where(array("id_o"=>$clean["id_o"],"cart_uid"=>$clean["cart_uid"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	public function pulsanteFattura($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$fatt = new FattureModel();
		
		$res = $fatt->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			return "<a class='text_16' title='scarica fattura' href='http://".DOMAIN_NAME."/fatture/vedi/".$clean["id_o"]."'><b><i class='fa fa-file'></i></b></a>";
		}
		
		return "<a class='text_16' title='genera fattura' href='http://".DOMAIN_NAME."/fatture/crea/".$clean["id_o"]."'><b><i class='fa fa-refresh'></i></b></a>";
	}
	
	public function mandaMailGeneric($id_o, $oggetto, $template, $tipo, $fattura = false)
	{
		$clean["id_o"] = (int)$id_o;
		$this->baseUrl = Domain::$name;
		$sendPassword = false;
		$res = $this->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		$f = new FattureModel();
		
		if (count($res) > 0)
		{
			$notice = null;
			$ordine = $res[0]["orders"];
			
			$r = new RigheModel();
			
			$righeOrdine = $r->clear()->where(array("id_o"=>$clean["id_o"]))->send();

			try
			{
				$mail = new PHPMailer(true); //New instance, with exceptions enabled

				if (Parametri::$useSMTP)
				{
					$mail->IsSMTP();                         // tell the class to use SMTP
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
					$mail->Port       = 25;                    // set the SMTP server port
					$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
					$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
					$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
				}
				
				$mail->From       = Parametri::$mailFrom;
				$mail->FromName   = Parametri::$mailFromName;
				$mail->CharSet = 'UTF-8';
				
				$mail->ClearAddresses();
				$mail->AddAddress($ordine["email"]);
				$mail->AddReplyTo(Parametri::$mailFrom, Parametri::$mailFromName);
				
				// Imposto le traduzioni del front
				TraduzioniModel::$contestoStatic = "front";
				Params::$lang = $ordine["lingua"];
				$tradModel = new TraduzioniModel();
				$tradModel->ottieniTraduzioni();
				
				$oggetto = gtext($oggetto, false);
				$oggetto = str_replace("[ID_ORDINE]",$clean["id_o"], $oggetto);
				
				$mail->Subject  = Parametri::$nomeNegozio." - $oggetto";
				$mail->IsHTML(true);
				
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
				
				if (ImpostazioniModel::$valori["bcc"])
					$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
				
				$mail->addBCC(ImpostazioniModel::$valori["mail_invio_ordine"]);
				
				ob_start();
				$baseUrl = Url::getRoot();
				$baseUrl = str_replace("admin/", "", $baseUrl);
				$tipoOutput = "mail_al_cliente";
				include Domain::$parentRoot."/Application/Views/Ordini/$template.php";
				$output = ob_get_clean();
				
				// Imposto le traduzioni del back
				Params::$lang = null;
				TraduzioniModel::$contestoStatic = "back";
				$tradModel = new TraduzioniModel();
				$tradModel->ottieniTraduzioni();
				
				if ($fattura)
				{
					$fattura = $f->where(array(
						"id_o"	=>	(int)$ordine["id_o"]
					))->record();
					
					if (!empty($fattura) && file_exists(LIBRARY."/media/Fatture/".$fattura["filename"]))
					{
						$mail->AddAttachment(LIBRARY."/media/Fatture/".$fattura["filename"]);
					}
				}
				
				$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
				$mail->MsgHTML($output);
				
				$mail->Send();
				$this->aggiungiStoricoMail($clean["id_o"], $tipo);
				
				$this->notice = "<div class='alert alert-success'>Mail inviata con successo!</div>";
			} catch (Exception $e) {
				Params::$lang = null;
				TraduzioniModel::$contestoStatic = "back";
			}
		}
	}
	
	public function mandaMailFattura($id_o)
	{
		$this->mandaMailGeneric($id_o, "Invio fattura ordine N° [ID_ORDINE]", "mail-fattura", "F", true);
	}
	
	public function mandaMailCompleted($id_o)
	{
		$this->mandaMailGeneric($id_o, "Conferma pagamento ordine N° [ID_ORDINE]", "mail-completed", "P", false);
	}
	
	public function mandaMailClosed($id_o)
	{
		$this->mandaMailGeneric($id_o, "Ordine N° [ID_ORDINE] spedito e chiuso", "mail-closed", "C", false);
	}
	
	public function mandaMailDeleted($id_o)
	{
		$this->mandaMailGeneric($id_o, "Annullamento ordine N° [ID_ORDINE]", "mail-deleted", "A", false);
	}
	
	public function mandaMail($id_o)
	{
		$this->mandaMailGeneric($id_o, "Ordine N° [ID_ORDINE]", "resoconto-acquisto", "R", false);
	}
	
	public function aggiungiStoricoMail($id_o, $tipo = "F")
	{
		$mailOrdini = new MailordiniModel();
		
		$mailOrdini->setValues(array(
			"id_o"	=>	$id_o,
			"tipo"	=>	$tipo,
		));
		
		$mailOrdini->insert();
	}
	
	// Iscrivi a newsletter l'utente dell'ordine
	public function iscriviANewsletter($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$ordine = $this->clear()->where(array("id_o"=>$clean["id_o"]))->record();
		
		if (!empty($ordine))
		{
			// Iscrizione alla newsletter
			if (ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
			{
				$dataMailChimp = array(
					"email"	=>	$ordine["email"],
					"status"=>	"subscribed",
				);
				
				if ($ordine["tipo_cliente"] == "azienda")
				{
					$dataMailChimp["firstname"] = $ordine["ragione_sociale"];
					$dataMailChimp["lastname"] = $ordine["ragione_sociale"];
				}
				else
				{
					$dataMailChimp["firstname"] = $ordine["nome"];
					$dataMailChimp["lastname"] = $ordine["cognome"];
				}
				
				$code = syncMailchimp($dataMailChimp);
				
// 				echo $code;
			}
		}
	}
	
	//riempi la tabella righe con le righe relative all'ordine in questione
	//$id_o: id dell'ordine
	public function riempiRighe($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$c = new CartModel();
		$r = new RigheModel();
		
		$pages = $c->clear()->select("cart.*,pages.id_page")->inner("pages")->using("id_page")->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("id_cart ASC")->send();
		
		foreach ($pages as $p)
		{
			$r->values = $p["cart"];
			$r->values["id_o"] = $clean["id_o"];
			
			if (isset(IvaModel::$aliquotaEstera))
				$r->values["iva"] = IvaModel::$aliquotaEstera;
			
			if (isset(IvaModel::$idIvaEstera))
				$r->values["id_iva"] = IvaModel::$idIvaEstera;
			
			$r->delFields("id_cart");
			$r->delFields("id_order");
			
			$r->sanitize();
			$r->insert();
		}
	}
	
	public function totaleCrud($record)
	{
		return number_format($record["orders"]["total"],2,",",".");
	}
	
	public function vedi($record)
	{
		return "<a title='Elenco ordini dove è stato acquistato' class='iframe action_iframe' href='".Url::getRoot()."ordini/vedi/".$record["orders"]["id_o"]."?partial=Y&nobuttons=Y'>".$record["orders"]["id_o"]."</a>";
	}
	
}
