<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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
	
	public $campoTitolo = "id_o";
	
	public static $pagamentiSettati = false;
	
	public static $stati = array(
		"pending"	=>	"Ordine ricevuto",
		"completed"	=>	"Ordine pagato e in lavorazione",
		"closed"	=>	"Ordine completato e spedito",
		"deleted"	=>	"Ordine annullato",
	);
	
	public static $labelTipoOrdine = array(
		"W"	=>	"Web",
		"B"	=>	"Backend",
	);
	
	public static $pagamenti = array();
	public static $pagamentiFull = array();
	
	public static $elencoPagamenti = array(
		"bonifico"		=>	"Bonifico bancario",
		"contrassegno"	=>	"Contrassegno (pagamento alla consegna)",
		"paypal"		=>	"Pagamento online tramite PayPal",
		"carta_di_credito"	=>	"Pagamento online tramite carta di credito",
	);
	
	public static $labelStati = array(
		"pending"	=>	"default",
		"completed"	=>	"primary",
		"closed"	=>	"success",
		"deleted"	=>	"danger",
	);
	
	public static $isDeletable = null;
	
	public static function isPagato($idO)
	{
		$o = new OrdiniModel();
		
		$record = $o->selectId($idO);
		
		if (!empty($record) && ($record["stato"] == "completed" || $record["stato"] == "closed"))
			return true;
		
		return false;
	}
	
	public static function setPagamenti()
	{
		if (self::$pagamentiSettati)
			return;
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
		
		$p = new PagamentiModel();
		
		$p->clear()->where(array(
			"attivo"	=>	1
		))->orderBy("id_order")->addJoinTraduzione();
		
		if (App::$isFrontend && CartModel::soloProdottiSenzaSpedizione())
			$p->aWhere(array(
				"ne"	=>	array(
					"pagamenti.codice"	=>	"contrassegno",
				),
			));
		
		$res = $p->send();
		
		self::$elencoPagamenti = array();
		
		foreach ($res as $pag)
		{
			// Se nuovo o vecchio sistema
			$titoloPag = v("attiva_gestione_pagamenti") ? pfield($pag, "titolo") : gtext($pag["pagamenti"]["titolo"], false);
			
			self::$pagamenti[$pag["pagamenti"]["codice"]] = self::$elencoPagamenti[$pag["pagamenti"]["codice"]] = $titoloPag;
			self::$pagamentiFull[$pag["pagamenti"]["codice"]] = $pag;
		}
		
		VariabiliModel::$valori["pagamenti_permessi"] = Parametri::$metodiPagamento = implode(",", array_keys(self::$elencoPagamenti));
		
		self::$pagamentiSettati = true;
	}
	
	public static function statiSuccessivi($stato)
	{
		switch ($stato)
		{
			case "pending":
				return array("completed","deleted");
			case "completed":
				if (v("attiva_spedizione"))
					return array("closed","deleted");
				else
					return array("deleted");
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
		
		if (!v("attiva_spedizione"))
			unset(self::$stati["closed"]);
		
		if (!App::$isFrontend)
			self::setPagamenti();
// 		$pagamentiPermessi = explode(",", v("pagamenti_permessi"));
// 		
// 		foreach (self::$elencoPagamenti as $k => $v)
// 		{
// 			if (in_array($k, $pagamentiPermessi))
// 				self::$pagamenti[$k] = gtext($v, false);
// 		}
	}
	
	public function relations() {
        return array(
			'righe' => array("HAS_MANY", 'RigheModel', 'id_o', null, "RESTRICT", "L'elemento ha delle righe associate e non può essere eliminato"),
			'pages' => array("HAS_MANY", 'MailordiniModel', 'id_o', null, "CASCADE"),
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
        );
    }
    
    public static function getLabelTipoOrdine($tipo)
    {
		return isset(self::$labelTipoOrdine[$tipo]) ? self::$labelTipoOrdine[$tipo] : $tipo;
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
	
	//get a unique cod trans
	public function getUniqueCodTrans($uid)
	{
		$clean["uid"] = sanitizeAll($uid);
		
		$res = $this->clear()->where(array("codice_transazione"=>$clean["uid"]))->send();
		
		if (count($res) > 0)
		{
			$nUid = generateString(30);
			return $this->getUniqueCodTrans($nUid);
		}
		
		return $clean["uid"];
	}
	
	public function setAliquotaIva($idOrdine = 0)
	{
// 		if (!App::$isFrontend)
// 		{
// 			if (!$idOrdine || OrdiniModel::tipoOrdine($idOrdine) != "W")
// 			{
// 				if (isset($this->values["id_iva"]))
// 					$this->values["iva_spedizione"] = IvaModel::g(false)->getValore((int)$this->values["id_iva"]);
// 			}
// 		}
	}
	
	public function insert()
	{
		if (App::$isFrontend)
			$this->values["cart_uid"] = $this->getUniqueId($this->values["cart_uid"]);
		else
			$this->values["cart_uid"] = randomToken();
		
		$this->values["codice_transazione"] = $this->getUniqueCodTrans(generateString(30));
		
		if (!isset($this->values["lingua"]))
			$this->values["lingua"] = Params::$lang;
		
		if (!User::$nazioneNavigazione)
			User::$nazioneNavigazione = v("nazione_default");
		
		if (!isset($this->values["nazione_navigazione"]))
			$this->values["nazione_navigazione"] = User::$nazioneNavigazione;
		
		$checkFiscale = v("abilita_codice_fiscale");
		
		// Se non c'è la spedizione attiva
		if (!v("attiva_spedizione"))
		{
			$this->values["id_spedizione"] = 0;
			$this->values["id_corriere"] = 0;
		}
		
		if (!App::$isFrontend)
			$this->values["tipo_ordine"] = "B";
		
		$this->setAliquotaIva();
		
		$this->setProvince();
		
		if (!App::$isFrontend || ($this->controllaCF($checkFiscale) && $this->controllaPIva()))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$oldRecord = $this->selectId($clean["id"]);
		
		$checkFiscale = v("abilita_codice_fiscale");
		
		$this->setAliquotaIva($id);
		
		$this->setProvince();
		
		if (!App::$isFrontend || $this->controllaCF($checkFiscale))
		{
			$res = parent::update($clean["id"]);
			
			if ($res)
			{
				if (!App::$isFrontend)
				{
					$newRecord = $this->selectId($clean["id"]);
					
					if (!empty($oldRecord) && !empty($newRecord) && ($oldRecord["stato"] == "pending" || $newRecord["stato"] == "pending"))
						self::$isDeletable = true;
					
					$this->aggiornaTotali($id);
					
					self::$isDeletable = null;
				}
				
				$this->triggersOrdine($id);
				
				// Hook ad aggiornamento dell'ordine
				if (v("hook_update_ordine"))
					callFunction(v("hook_update_ordine"), $clean["id"], v("hook_update_ordine"));
				
				return true;
			}
		}
		
		return false;
	}
	
	public function triggersOrdine($idO)
	{
		if (v("attiva_gift_card"))
		{
			$ordine = $this->selectId((int)$idO);
			
			if (!empty($ordine))
			{
				$rModel = new RigheModel();
				$pModel = new PromozioniModel();
				
				$righe = $rModel->clear()->where(array(
					"id_o"		=>	(int)$idO,
					"gift_card"	=>	1,
				))->send(false);
				
				foreach ($righe as $r)
				{
					$pModel->aggiungiDaRigaOrdine($r["id_r"]);
				}
			}
		}
		
		if (v("attiva_liste_regalo"))
		{
			$ordine = !isset($ordine) ? $this->selectId((int)$idO) : $ordine;
			
			if (!empty($ordine) && $ordine["id_lista_regalo"] && trim($ordine["firma"]) && trim($ordine["dedica"]))
			{
				$lModel = new ListeregaloModel();
				$lreModel = new ListeregaloemailModel();
				
				$lista = $lModel->selectId((int)$ordine["id_lista_regalo"]);
				
				if (!empty($lista) && trim($lista["email"]) && checkMail($lista["email"]))
				{
					$lreModel->aggiungiDaOrdine($ordine);
				}
			}
		}
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
			
			$bckLang = Params::$lang;
			$bckCountry = Params::$country;
			$bckContesto = TraduzioniModel::$contestoStatic;
			
			try
			{
				$mail = new PHPMailer(true); //New instance, with exceptions enabled

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
				
				$mail->ClearAddresses();
				$mail->AddAddress($ordine["email"]);
				$mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
				
				// Imposto le traduzioni del front
				TraduzioniModel::$contestoStatic = "front";
				Params::$lang = $ordine["lingua"];
				
				if (v("attiva_nazione_nell_url"))
					Params::$country = $ordine["nazione_navigazione"] ? strtolower($ordine["nazione_navigazione"]) : strtolower(v("nazione_default"));
				
				$tradModel = new TraduzioniModel();
				$tradModel->ottieniTraduzioni();
				
				$oggetto = gtext($oggetto, false);
				$oggetto = str_replace("[ID_ORDINE]",$clean["id_o"], $oggetto);
				
				// Segnaposti
				$oggetto = SegnapostoModel::sostituisci($oggetto, $ordine, null);
				
				$mail->Subject  = Parametri::$nomeNegozio." - $oggetto";
				$mail->IsHTML(true);
				
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
				
				// Svuoto tutte le mail BCC
				$mail->ClearBCCs();
				$emails = array($ordine["email"]);
				$arrayBcc = MailordiniModel::setBcc($mail, $emails);
				
				ob_start();
				$baseUrl = Url::getRoot();
				$baseUrl = str_replace("admin/", "", $baseUrl);
				$tipoOutput = "mail_al_cliente";
				if ($tipo == "R" && file_exists(tpf("/Elementi/Mail/mail_ordine_ricevuto.php")))
					include tpf("/Elementi/Mail/mail_ordine_ricevuto.php");
				else
					include tpf("/Ordini/$template.php");
// 				include Domain::$parentRoot."/Application/Views/Ordini/$template.php";
				$output = ob_get_clean();
				$testoClean = $output;
				
				$output = MailordiniModel::loadTemplate($oggetto, $output);
// 				echo $output;die();
				// Imposto le traduzioni del back
				Params::$lang = $bckLang;
				Params::$country = $bckCountry;
				TraduzioniModel::$contestoStatic = $bckContesto;
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
				
				$params = array(
					"oggetto"	=>	$oggetto,
					"bcc"		=>	implode(",",$arrayBcc),
					"testo"		=>	$testoClean,
					"inviata"	=>	0,
				);
				
				$mailOrdini = $this->aggiungiStoricoMail($clean["id_o"], $tipo, $params);
				$lId = $mailOrdini->lId;
				
				if ($mailOrdini->checkLimitiInvio && $mail->Send())
				{
					$mailOrdini->sValues(array(
						"inviata"	=>	1,
					));
					
					if ($tipo == "R")
						$mailOrdini->setValue("testo", "");
					
					$mailOrdini->update($lId);
					
					$this->notice = "<div class='alert alert-success'>Mail inviata con successo!</div>";
				}
				else
				{
					$this->notice = "<div class='alert alert-danger'>Errore nell'invio della mail.</div>";
				}
			} catch (Exception $e) {
				Params::$lang = $bckLang;
				Params::$country = $bckCountry;
				TraduzioniModel::$contestoStatic = $bckContesto;
			}
		}
	}
	
	public function mandaMailFattura($id_o)
	{
		$this->mandaMailGeneric($id_o, "Invio fattura ordine N° [ID_ORDINE]", "mail-fattura", "F", true);
	}
	
	public function mandaMailCompleted($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_pagato"), "mail-completed", "P", false);
// 		$this->mandaMailGeneric($id_o, "Conferma pagamento ordine N° [ID_ORDINE]", "mail-completed", "P", false);
	}
	
	public function mandaMailClosed($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_spedito"), "mail-closed", "C", false);
// 		$this->mandaMailGeneric($id_o, "Ordine N° [ID_ORDINE] spedito e chiuso", "mail-closed", "C", false);
	}
	
	public function mandaMailDeleted($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_annullato"), "mail-deleted", "A", false);
// 		$this->mandaMailGeneric($id_o, "Annullamento ordine N° [ID_ORDINE]", "mail-deleted", "A", false);
	}
	
	public function mandaMail($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_ricevuto"), "resoconto-acquisto", "R", false);
// 		$this->mandaMailGeneric($id_o, "Ordine N° [ID_ORDINE]", "resoconto-acquisto", "R", false);
	}
	
	public function aggiungiStoricoMail($id_o, $tipo = "F", $params = array())
	{
		$ordine = $this->selectId((int)$id_o);
		
		$mailOrdini = new MailordiniModel();
		
		$mailOrdini->setValues(array(
			"id_o"	=>	$id_o,
			"tipo"	=>	$tipo,
			"email"	=>	isset($ordine["email"]) ? $ordine["email"] : "",
			"id_user"	=>	isset($ordine["id_user"]) ? $ordine["id_user"] : 0,
			"oggetto"	=>	isset($params["oggetto"]) ? $params["oggetto"] : "",
			"bcc"		=>	isset($params["bcc"]) ? $params["bcc"] : "",
			"testo"		=>	isset($params["testo"]) ? $params["testo"] : "",
			"inviata"	=>	isset($params["inviata"]) ? $params["inviata"] : 1,
		));
		
		$mailOrdini->insert();
		
		return $mailOrdini;
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
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$clean["id_o"] = (int)$id_o;
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$c = new CartModel();
		$r = new RigheModel();
		$re = new RigheelementiModel();
		
		$sconto = 0;
		if (hasActiveCoupon($id_o))
		{
			$p = new PromozioniModel();
			$coupon = $p->getCoupon(User::$coupon);
			
			if ($coupon["tipo_sconto"] == "PERCENTUALE")
				$sconto = $coupon["sconto"];
		}
		
		$pages = $c->getRighePerOrdine();
// 		$pages = $c->clear()->select("cart.*,pages.id_page")->inner("pages")->using("id_page")->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("id_cart ASC")->send();
		
		foreach ($pages as $p)
		{
			$r->values = $p["cart"];
			$r->values["id_o"] = $clean["id_o"];
			
			if (isset(IvaModel::$aliquotaEstera))
				$r->values["iva"] = IvaModel::$aliquotaEstera;
			
			if (isset(IvaModel::$idIvaEstera))
				$r->values["id_iva"] = IvaModel::$idIvaEstera;
			
			$r->values["price_ivato"] = number_format($r->values["price"] * (1 + ($r->values["iva"] / 100)),2,".","");
			$r->values["prezzo_intero_ivato"] = number_format($r->values["prezzo_intero"] * (1 + ($r->values["iva"] / 100)),2,".","");
			
// 			if (in_array($p["cart"]["id_page"], User::$prodottiInCoupon))
			if (PromozioniModel::checkProdottoInPromo($p["cart"]["id_page"]))
			{
				$r->values["prezzo_finale"] = number_format($r->values["price"] - ($r->values["price"] * ($sconto / 100)),v("cifre_decimali"),".","");
				$r->values["percentuale_promozione"] = $sconto;
			}
			else
				$r->values["prezzo_finale"] = number_format($r->values["price"],v("cifre_decimali"),".","");
			
			$r->values["prezzo_finale_ivato"] = number_format($r->values["prezzo_finale"] * (1 + ($r->values["iva"] / 100)),2,".","");
			
			$r->values["fonte"] = App::$isFrontend ? "W" : "B";
			$r->values["id_admin"] = App::$isFrontend ? 0 : User::$id;
			
			if (!App::$isFrontend && $r->values["id_rif"])
				$r->values["id_r"] = (int)$r->values["id_rif"];
			
			$r->delFields("data_creazione");
			$r->delFields("id_user");
			$r->delFields("email");
			$r->delFields("id_cart");
			$r->delFields("id_order");
			$r->delFields("id_rif");
			
			$r->sanitize();
			
			if ($r->insert())
			{
				// Salvo gli elementi del carrello
				$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
				
				foreach ($elementiCarrello as $elCart)
				{
					unset($elCart["id_cart_elemento"]);
					unset($elCart["data_creazione"]);
					unset($elCart["id_cart"]);
					
					$re->sValues($elCart, "sanitizeDb");
					$re->setValue("id_r", $r->lId);
					
					$re->insert();
				}
			}
		}
	}
	
	public function totaleCrudPieno($record)
	{
		return number_format($record["orders"]["subtotal_ivato"] + $record["orders"]["spedizione_ivato"],2,",",".");
	}
	
	public function totaleCrud($record)
	{
		return number_format($record["orders"]["total"],2,",",".");
	}
	
	public function vedi($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return "<a title='".gtext("Dettaglio ordine")."' class='iframe action_iframe' href='".Url::getRoot()."ordini/vedi/".$record["orders"]["id_o"]."$queryString'>".$record["orders"]["id_o"]."</a>";
	}
	
	public function vediFull($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return $this->vedi($record, "?partial=Y");
	}
	
	public function vediDaListe($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return $this->vedi($record, "?partial=Y&from=liste");
	}
	
	public static function getTotaliIva($id_o)
	{
		$o = new OrdiniModel();
		$r = new RigheModel();
		
		$ordine = $o->selectId((int)$id_o);
		
		$righe = $r->clear()->where(array(
			"id_o"	=>	(int)$id_o,
		))->send(false);
		
		$totaleIva = $totaleProdotti = $totaleProdottiPieno = $spedizione = $pagamento = $totaleIvaProdotti = $totaleIvaProdottiPieno = 0;
		
		$arrayTotali = $arrayTotaliProdotti = $arrayTotaliProdottiPieno = array();
		
		foreach ($righe as $r)
		{
			$subtotaleRiga = number_format($r["prezzo_finale"] * $r["quantity"],v("cifre_decimali"),".","");
			$totaleProdotti += $subtotaleRiga;
			
			if (isset($arrayTotali[$r["id_iva"]]))
				$arrayTotali[$r["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotali[$r["id_iva"]] = $subtotaleRiga;
			
			$subtotaleRiga = number_format($r["price"] * $r["quantity"],v("cifre_decimali"),".","");
			$totaleProdottiPieno += $subtotaleRiga;
			
			if (isset($arrayTotaliProdottiPieno[$r["id_iva"]]))
				$arrayTotaliProdottiPieno[$r["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotaliProdottiPieno[$r["id_iva"]] = $subtotaleRiga;
		}
		
		// salvo i totali dei prodotti
		$arrayTotaliProdotti = $arrayTotali;
		
		if (!empty($ordine))
		{
			if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "ASSOLUTO")
			{
				$subtotaleRiga = number_format($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)),v("cifre_decimali"),".","");
				
				if (isset($arrayTotali[$ordine["id_iva"]]))
					$arrayTotali[$ordine["id_iva"]] -= $subtotaleRiga;
				else
					$arrayTotali[$ordine["id_iva"]] = (-1)*$subtotaleRiga;
			}
			
			$subtotaleRiga = $spedizione = number_format($ordine["spedizione"],v("cifre_decimali"),".","");
			
			if (isset($arrayTotali[$ordine["id_iva"]]))
				$arrayTotali[$ordine["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotali[$ordine["id_iva"]] = $subtotaleRiga;
			
			$subtotaleRiga = $pagamento = number_format($ordine["costo_pagamento"],v("cifre_decimali"),".","");
			
			$arrayTotali[$ordine["id_iva"]] += $subtotaleRiga;
		}
		
		$arrayIva = array();
		
		$i = new IvaModel();
		
		foreach ($arrayTotali as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$arrayIva[$idAliquota] = $totale * ($aliquota / 100);
			$totaleIva += $totale * ($aliquota / 100);
		}
		
		foreach ($arrayTotaliProdotti as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$totaleIvaProdotti += $totale * ($aliquota / 100);
		}
		
		foreach ($arrayTotaliProdottiPieno as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$totaleIvaProdottiPieno += $totale * ($aliquota / 100);
		}
		
		$aliquotaOrdine = $i->getValore($ordine["id_iva"]);
		
		$subtotalIvato = $totaleProdottiPieno + $totaleIvaProdottiPieno;
		$prezzoScontatoIvato = $totaleProdotti + $totaleIvaProdotti;
		$spedizioneIvato = $spedizione * (1 + $aliquotaOrdine / 100);
		$costoPagamentoIvato = $pagamento * (1 + $aliquotaOrdine / 100);
		$total = $subtotalIvato + $spedizioneIvato + $costoPagamentoIvato;
		
		$arraySubtotali = array(
			"subtotal"	=>	$totaleProdottiPieno,
			"prezzo_scontato"	=>	$totaleProdotti,
			"spedizione"=>	$spedizione,
			"costo_pagamento"	=>	$pagamento,
			"subtotal_ivato"	=>	$subtotalIvato,
			"prezzo_scontato_ivato"	=>	$prezzoScontatoIvato,
			"spedizione_ivato"	=>	$spedizioneIvato,
			"costo_pagamento_ivato"	=>	$costoPagamentoIvato,
			"iva"			=>	$totaleIva,
			"total"			=>	$total,
			"total_pieno"	=>	$total,
		);
		
		return array($arrayIva, $arraySubtotali);
	}
	
	public function checkAggiorna($idOrdine)
	{
		if( !session_id() )
			session_start();
		
		if (isset($_SESSION["aggiorna_totali_ordine"]))
			$this->aggiornaTotali($idOrdine);
	}
	
	public function aggiornaTotali($idOrdine)
	{
		if (!App::$isFrontend && OrdiniModel::tipoOrdine($idOrdine) != "W" && OrdiniModel::isDeletable($idOrdine))
		{
			$ordine = $this->selectId((int)$idOrdine);
			
			Params::$setValuesConditionsFromDbTableStruct = false;
			Params::$automaticConversionToDbFormat = false;
			
			$c = new CartModel();
			$r = new RigheModel();
			$ruModel = new RegusersModel();
			
			$bckUserId = User::$id;
			
			User::$cart_uid = $ordine["cart_uid"];
			User::$id = (int)$ordine["id_user"];
			
			if ((int)$ordine["id_user"])
			{
				User::$dettagli = $ruModel->selectId($ordine["id_user"]);
				User::setClasseSconto();
			}
			
			$bckLang = Params::$lang;
			$lingua = $ordine["lingua"] ? $ordine["lingua"] : LingueModel::getPrincipaleFrontend();
			Params::$lang = $lingua;
			
			$bckAttivaGiacenza = v("attiva_giacenza");
			
			VariabiliModel::$valori["attiva_giacenza"] = 0;
			
			$_POST["nazione"] = $ordine["nazione"];
			$_POST["nazione_spedizione"] = $ordine["nazione_spedizione"];
			$_POST["tipo_cliente"] = $ordine["tipo_cliente"];
			$_POST["pagamento"] = $ordine["pagamento"];
			$_POST["email"] = $ordine["email"];
			
			IvaModel::getAliquotaEstera();
			
			$righe = $r->clear()->where(array(
				"id_o"	=>	(int)$idOrdine,
			))->orderBy("id_order")->send(false);
			
			// AGGIUNGO AL CARRELLO
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
// 			$righeCarrello = $c->getRighePerOrdine();
			
			$elementiPuliti = array();
			
			$c->del(null, "cart_uid = '".User::$cart_uid."'");
			
			foreach ($righe as $r)
			{
				$idCart = $c->add($r["id_page"], $r["quantity"], $r["id_c"], 0, array(), null, null, null, $r["id_r"]);
				
				$elementiRiga = RigheelementiModel::getElementiRiga($r["id_r"]);
				
				foreach ($elementiRiga as $elRiga)
				{
					$elRiga = htmlentitydecodeDeep($elRiga);
					
					$elementiPuliti["CART-".(int)$idCart][] = array(
						"email"	=>	(string)$elRiga["email"],
						"testo"	=>	(string)$elRiga["testo"],
					);
				}
			}
			
			$c->correggiPrezzi();
			$c->aggiornaElementi($elementiPuliti);
			
			if (v("usa_transactions"))
				$this->db->commit();
			
			CartModel::attivaDisattivaSpedizione((int)$idOrdine);
			
			// CONTROLLO LA PROMO
			$sconto = 0;
			
			if ($ordine["id_p"])
			{
				$coupon = PromozioniModel::g()->selectId((int)$ordine["id_p"]);
				
				if (!empty($coupon))
				{
					User::$coupon = $coupon["codice"];
					PromozioniModel::$staticIdO = $ordine["id_o"];
					
					if (hasActiveCoupon($ordine["id_o"]))
					{
						User::$prodottiInCoupon = PromozioniModel::g()->elencoProdottiPromozione(User::$coupon);
						
						if ($coupon["tipo_sconto"] == "PERCENTUALE")
							$sconto = $coupon["sconto"];
					}
				}
			}
			
// 			$this->ricalcolaPrezziRighe((int)$ordine["id_o"], $sconto);
			
			$this->values = array();
			$this->aggiungiTotali($ordine["stato"]);
			
			$this->pUpdate($idOrdine);
			
			RigheModel::g()->mDel("id_o = ".(int)$ordine["id_o"]);
			
			$this->riempiRighe($ordine["id_o"]);
			
			$c->del(null, "cart_uid = '".User::$cart_uid."'");
			
			VariabiliModel::$valori["attiva_giacenza"] = $bckAttivaGiacenza;
			
			User::$id = $bckUserId;
			PromozioniModel::$staticIdO = null;
			Params::$lang = $bckLang;
			
			Params::$setValuesConditionsFromDbTableStruct = true;
			Params::$automaticConversionToDbFormat = true;
			
			unset($_SESSION["aggiorna_totali_ordine"]);
		}
	}
	
// 	public function ricalcolaPrezziRighe($idOrdine, $sconto)
// 	{
// 		$rModel = new RigheModel();
// 		$pModel = new PagesModel();
// 		$iModel = new IvaModel();
// 		
// 		$righe = $rModel->clear()->where(array(
// 			"id_o"	=>	(int)$idOrdine,
// 		))->send(false);
// 		
// 		$idIvaGenerica = $iModel->clear()->orderBy("id_order")->limit(1)->field("id_iva");
// 		
// 		if (v("usa_transactions"))
// 			$this->db->beginTransaction();
// 		
// 		foreach ($righe as $r)
// 		{
// 			$pagina = $pModel->selectId($r["id_page"]);
// 			
// 			$idIvaPagina = (!empty($pagina)) ? $pagina["id_iva"] : $idIvaGenerica;
// 			
// 			$prezzoFinale = number_format($r["price"] - ($r["price"] * ($sconto / 100)),v("cifre_decimali"),".","");
// 			
// 			$idIva = isset(IvaModel::$idIvaEstera) ? IvaModel::$idIvaEstera : $idIvaPagina;
// 			$iva = isset(IvaModel::$aliquotaEstera) ? IvaModel::$aliquotaEstera : IvaModel::g()->getValore((int)$idIvaPagina);
// 			
// 			$rModel->sValues(array(
// 				"id_iva"		=>	$idIva,
// 				"iva"			=>	$iva,
// 				"prezzo_finale"	=>	$prezzoFinale,
// 				"prezzo_finale_ivato"	=>	number_format($prezzoFinale * (1 + ($iva / 100)),2,".",""),
// 				"percentuale_promozione"=>	$sconto,
// 			));
// 			
// 			$rModel->pUpdate($r["id_r"]);
// 		}
// 		
// 		if (v("usa_transactions"))
// 			$this->db->commit();
// 	}
	
	public function aggiungiTotali($forzaAlloStato = null)
	{
		$this->values["subtotal"] = getSubTotalN();
		$this->values["spedizione"] = getSpedizioneN();
		$this->values["costo_pagamento"] = getPagamentoN();
		
		$this->values["subtotal_ivato"] = setPrice(getSubTotal(true));
		$this->values["spedizione_ivato"] = setPrice(getSpedizione(1));
		$this->values["costo_pagamento_ivato"] = setPrice(getPagamento(1));
		
		$this->values["iva"] = setPrice(getIva());
		$this->values["total"] = setPrice(getTotal());
		$this->values["cart_uid"] = User::$cart_uid;
		$this->values["admin_token"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
		$this->values["banca_token"] = md5(randString(18).microtime().uniqid(mt_rand(),true));
		
		$this->values["total_pieno"] = $this->values["subtotal_ivato"] + $this->values["spedizione_ivato"] + $this->values["costo_pagamento_ivato"];
		
		$this->values["creation_time"] = time();
		
		if (!isset($forzaAlloStato))
		{
			$statoOrdine = "pending";
			
			if (number_format(getTotalN(),2,".","") <= 0.00)
				$statoOrdine = "completed";
		}
		else
			$statoOrdine = $forzaAlloStato;
		
		$this->values["stato"] = $statoOrdine;
		
		$this->values["prezzo_scontato"] = getPrezzoScontatoN();
		$this->values["prezzo_scontato_ivato"] = setPrice(getPrezzoScontato(1));
		
		$this->values["codice_promozione"] = User::$coupon;
		$this->values["nome_promozione"] = htmlentitydecode(getNomePromozione());
		$this->values["usata_promozione"] = hasActiveCoupon() ? "Y" : "N";
		
		$coupon = PromozioniModel::getCouponAttivo();
		
		if (!empty($coupon))
		{
			$this->values["tipo_promozione"] = $coupon["tipo_sconto"];
			$this->values["euro_promozione"] = $this->values["total_pieno"] - $this->values["total"];
			$this->values["id_p"] = $coupon["id_p"];
		}
		else
		{
			$this->values["tipo_promozione"] = "PERCENTUALE";
			$this->values["euro_promozione"] = 0.00;
			$this->values["id_p"] = 0;
		}
		
		$this->values["id_iva"] = CartModel::getIdIvaSpedizione();
		$this->values["iva_spedizione"] = CartModel::getAliquotaIvaSpedizione();
		
		if (isset(IvaModel::$aliquotaEstera))
		{
			$this->values["id_iva_estera"] = IvaModel::$idIvaEstera;
			$this->values["aliquota_iva_estera"] = IvaModel::$aliquotaEstera;
			$this->values["stringa_iva_estera"] = IvaModel::$titoloAliquotaEstera;
			$this->values["nascondi_iva_estera"] = IvaModel::$nascondiAliquotaEstera;
		}
		else
		{
			$this->values["id_iva_estera"] = 0;
			$this->values["aliquota_iva_estera"] = 0.00;
			$this->values["stringa_iva_estera"] = "";
			$this->values["nascondi_iva_estera"] = 0;
		}
		
		$this->values["da_spedire"] = v("attiva_spedizione");
	}
	
	public static function totaleNazione($nazione, $annoPrecedente = false)
	{
		$anno = date("Y");
		
		if ($annoPrecedente)
			$anno = date("Y", strtotime("-1 year", time()));
		
		$o = new OrdiniModel();
		
		$res = $o->clear()->select("SUM(prezzo_scontato) as TOTALE")->where(array(
			"nazione_spedizione"	=>	sanitizeAll($nazione),
		))->sWhere("DATE_FORMAT(data_creazione, '%Y') = '".$anno."'")->send();
		
		if (isset($res[0]["aggregate"]["TOTALE"]) && $res[0]["aggregate"]["TOTALE"])
			return $res[0]["aggregate"]["TOTALE"];
		
		return 0;
	}
	
	public static function totaleFuoriItaliaEu()
	{
		$anno = date("Y");
		
		$o = new OrdiniModel();
		
		$res = $o->clear()->select("SUM(prezzo_scontato) as TOTALE")->where(array(
			"ne"	=>	array(
				"nazione_spedizione"	=>	"IT",
			),
		))->sWhere("nazione_spedizione in (select iso_country_code from nazioni where tipo = 'UE') AND DATE_FORMAT(data_creazione, '%Y') = '".$anno."'")->send();
		
		if (isset($res[0]["aggregate"]["TOTALE"]) && $res[0]["aggregate"]["TOTALE"])
			return $res[0]["aggregate"]["TOTALE"];
		
		return 0;
	}
	
	public function statoordinelabel($records)
	{
		if (isset(OrdiniModel::$stati[$records["orders"]["stato"]]))
			return "<span class='text-bold text text-".OrdiniModel::$labelStati[$records["orders"]["stato"]]."'>".OrdiniModel::$stati[$records["orders"]["stato"]]."<span>";
		
		return $records["orders"]["stato"];
	}
	
	// Importa i dati della spedizione $id_s nell'ordine $id_o
	public function importaSpedizione($id_o, $id_s)
	{
		$s = new SpedizioniModel();
		$o = new OrdiniModel();
		
		$spedizione = $s->selectId((int)$id_s);
		
		if (!empty($spedizione))
		{
			$o->setValues(array(
				"id_spedizione"			=>	(int)$id_s,
// 				"indirizzo_spedizione"	=>	$spedizione["indirizzo_spedizione"],
// 				"cap_spedizione"		=>	$spedizione["cap_spedizione"],
// 				"provincia_spedizione"	=>	$spedizione["provincia_spedizione"],
// 				"nazione_spedizione"	=>	$spedizione["nazione_spedizione"],
// 				"citta_spedizione"		=>	$spedizione["citta_spedizione"],
// 				"telefono_spedizione"	=>	$spedizione["telefono_spedizione"],
// 				"dprovincia_spedizione"	=>	$spedizione["dprovincia_spedizione"],
			), "sanitizeDb");
			
			$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
			
			foreach ($campiSpedizione as $cs)
			{
				$o->setValue($cs, $spedizione[$cs], "sanitizeDb");
			}
			
			return $o->update((int)$id_o);
		}
		
		return false;
	}
	
	public static function getByCartUid($cartUid)
	{
		$clean["cartUid"] = sanitizeAll($cartUid);
		
		$o = new OrdiniModel();
		
		return $o->clear()->where(array(
			"cart_uid"	=>	$clean["cartUid"],
		))->record();
	}
	
	public static function conPagamentoOnline($ordine)
	{
		if ($ordine["pagamento"] == "paypal" || $ordine["pagamento"] == "carta_di_credito")
			return true;
		
		return false;
	}
	
	public function settaMailDaInviare($idO)
	{
		$this->setValues(array(
			"mail_da_inviare"	=>	1,
		));
		
		$this->update((int)$idO);
	}
	
	// Manda le mail dopo il pagamento
	public function mandaMailDopoPagamento($idO)
	{
		$ordine = $this->selectId($idO);
		
		if (!empty($ordine) && $ordine["mail_da_inviare"])
		{
			$this->mandaMail((int)$idO);
			
			$this->setValues(array(
				"mail_da_inviare"	=>	0,
			));
			
			$this->update((int)$idO);
			
			if ($ordine["id_user"])
			{
				$idUser = (int)$ordine["id_user"];
				
				$r = new RegusersModel();
				
				$cliente = $r->selectId($idUser);
				
				if (!empty($cliente) && !$cliente["credenziali_inviate"])
				{
					if (RegusersModel::resettaCredenziali($idUser))
					{
						$r->setValues(array(
							"credenziali_inviate"	=>	1,
						));
						
						$r->pUpdate($idUser);
					}
				}
			}
		}
	}
	
	public function quantitaTotale($idO)
	{
		$r = new RigheModel();
		
		$res = $r->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_o"	=>	$idO,
		))->findAll();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 1;
	}
	
	public function linkcrud($record)
	{
		return '<a href="'.Url::getRoot().$this->applicationUrl.$this->controller.'/vedi/'.$record["orders"]["id_o"].$this->cViewStatus.'">#'.$record["orders"]["id_o"].'</a>';
	}
	
	public static function sistemaDaSpedire()
	{
		if (!v("attiva_spedizione"))
		{
			$o = new OrdiniModel();
			
			$o->db->query("update orders set da_spedire = 0 where 1");
		}
	}
	
	public function listaregalo($record)
	{
		if ($record["orders"]["id_lista_regalo"])
		{
			$lista = ListeregaloModel::g()->selectId((int)$record["orders"]["id_lista_regalo"]);
			
			if (!empty($lista))
			{
				return gtext("Titolo").": ". $lista["titolo"]."<br />".gtext("Codice").": <b>".$lista["codice"]."</b>";
			}
		}
		
		return "";
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			if ($record["tipo_ordine"] == "W")
				return false;
			
			if ($record["stato"] != "pending")
				return false;
		}
		
		return true;
	}
	
	public function isDeletable($id)
	{
		if (!isset(self::$isDeletable))
			self::$isDeletable = $this->deletable($id);
		
		return self::$isDeletable;
	}
	
	public static function tipoOrdine($id)
	{
		return self::g()->select("tipo_ordine")->whereId((int)$id)->field("tipo_ordine");
	}
	
	public function tipoOrdineCrud($record)
	{
		return self::getLabelTipoOrdine($record["orders"]["tipo_ordine"]);
	}
	
	public function dedicaCrud($record)
	{
		$dedica = $this->getElemendoDedica($record["orders"]["id_o"]);
		
		if ($dedica)
		{
			return "<i class='fa fa-check text text-success'></i> <small>".gtext("Inviata il")." ".date("d/m/Y H:i", strtotime($dedica["data_creazione"]))."</small>";
		}
	}
	
	public function getElemendoDedica($idOrdine)
	{
		$idListaRegaloEmail = (int)ListeregaloemailModel::g()->where(array(
			"id_o"	=>	(int)$idOrdine
		))->field("id_lista_regalo_email");
		
		if ($idListaRegaloEmail)
		{
			return EventiretargetingelementiModel::getElemento((int)$idListaRegaloEmail, "liste_regalo_email");
		}
		
		return array();
	}
}
