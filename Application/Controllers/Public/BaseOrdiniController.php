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

class BaseOrdiniController extends BaseController
{

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - Gestione ordine';

		$this->append($data);
	}

	public function scaricafattura($id_o)
	{
		$this->s['registered']->check(null,0);
		
		$this->clean();
		
		$clean["id_o"] = (int)$id_o;
		
		$this->model("FattureModel");
		
		$res = $this->m["FattureModel"]->inner("orders")->using("id_o")->where(array("id_o"=>$clean["id_o"],"orders.id_user"=>User::$id))->send();
		
		if (count($res) > 0)
		{
			header('Content-disposition: attachment; filename='.$res[0]['fatture']['filename']);
			header('Content-Type: application/pdf');
			readfile(Domain::$parentRoot . rtrim("/".Parametri::$cartellaFatture) . "/".$res[0]['fatture']['filename']);
		}
	}
	
// 	public function simulapaypal()
// 	{
// 		$this->clean();
		/*
		echo '<form action="<?php echo $this->baseUrl."/notifica-pagamento";?>" method="POST">';
		echo 'payment status <input type="text" name="payment_status" value=""/>';
		echo 'iten number <input type="text" name="item_number" value=""/>';
		echo 'txn_id<input type="text" name="txn_id" value=""/>';
		echo '<input type="submit" name="invia" value="invia" />';
		echo '</form>';
		*/
// 	}
	
	//ritorno da paypal
	public function ipn()
	{
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/ipn.txt', 'a+');
		fwrite($fp, date("Y-m-d H:i:s"));
		fwrite($fp, print_r($_GET,true));
		fwrite($fp, print_r($_POST,true));
		fclose($fp);
		
		$this->clean();
		
		require (LIBRARY.'/External/paypal/paypal_class.php');
			
		if (Parametri::$useSandbox)
		{
			$p = new paypal_class(true); //usa sandbox
			$p->paypal_mail = Parametri::$paypalSandBoxSeller;
		}
		else
		{
			$p = new paypal_class(); //usa il vero paypal
			$p->paypal_mail = Parametri::$paypalSeller;
		}
		
		$p->txn_id = $this->m["OrdiniModel"]->clear()->toList("txn_id")->send();
		
		if ($p->validate_ipn())
		{
			$clean['payment_status'] = $this->request->post('payment_status','','sanitizeAll');
			$clean['cart_uid'] = $this->request->post('item_number','','sanitizeAll');
			$clean['codiceTransazione'] = $this->request->post('txn_id','','sanitizeAll');
			$clean['amount'] = $this->request->post('mc_gross','0','none');
			
			$res = $this->m["OrdiniModel"]->clear()->where(array("cart_uid" => $clean['cart_uid']))->send();

			if (count($res) > 0)
			{
				if (strcmp($clean['amount'],$res[0]["orders"]["total"]) === 0 )
				{
					$this->model("FattureModel");
					
					$ordine = $res[0]["orders"];
					$this->m["OrdiniModel"]->values = array();
					$this->m["OrdiniModel"]->values["txn_id"] = $clean['codiceTransazione'];
					if (strcmp($clean['payment_status'],"Completed") === 0)
						$this->m["OrdiniModel"]->values["stato"] = "completed";
					$this->m["OrdiniModel"]->update((int)$res[0]["orders"]["id_o"]);
					
					if (strcmp($clean['payment_status'],"Completed") === 0)
					{
						if (ImpostazioniModel::$valori["manda_mail_fattura_in_automatico"] == "Y")
						{
							//genera la fattura
							$this->m["FattureModel"]->crea($ordine["id_o"]);
						}
					}
					
// 					require_once(ROOT."/External/phpmailer/class.phpmailer.php");

					$mail = new PHPMailer(true); //New instance, with exceptions enabled

					if (Parametri::$useSMTP)
					{
						$mail->IsSMTP();                         // tell the class to use SMTP
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Port       = Parametri::$SMTPPort;                    // set the SMTP server port
						$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
						$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
						$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
					}
					
					$mail->From       = Parametri::$mailFrom;
					$mail->FromName   = Parametri::$mailFromName;
					if (Parametri::$mailReplyTo && Parametri::$mailFromName)
						$mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
					
					$mail->CharSet = 'UTF-8';
					
					$mail->IsHTML(true);
					
					$mail->WordWrap = 70;
					
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					
					if (ImpostazioniModel::$valori["bcc"])
						$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
					
					switch ($clean['payment_status'])
					{
						case "Completed":
							
// 							if ($ordine["registrato"] == "Y")
// 								$this->m["OrdiniModel"]->mandaMail($ordine["id_o"]);
							
							$mail->Subject  = Parametri::$nomeNegozio." - ".gtext("Conferma Pagamento Nº Ordine: ").$ordine["id_o"];
							$mail->AddAddress($ordine["email"]);
							$output = gtext("Grazie per il suo acquisto!<br />Il pagamento dell'ordine #").$ordine["id_o"]." ".gtext("è andato a buon fine.")."<br />";
							
							$fattura = $this->m["FattureModel"]->where(array(
								"id_o"	=>	$ordine["id_o"]
							))->record();
							
							if (!empty($fattura) && file_exists(ROOT."/admin/media/Fatture/".$fattura["filename"]))
							{
								$output .= "<br />In allegato la fattura relativa al suo ordine.";
								$mail->AddAttachment(ROOT."/admin/media/Fatture/".$fattura["filename"]);
							}
							
							$output = MailordiniModel::loadTemplate($mail->Subject, $output);
							$mail->MsgHTML($output);
							
							try
							{
								// Segna inviata mail ordine pagato
								$this->m['OrdiniModel']->aggiungiStoricoMail($ordine["id_o"], "P");
								
								$mail->Send();
							} catch (Exception $e) {
								
							}
							$mail->ClearAddresses();
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							$output = "Il pagamento dell'ordine #".$ordine["id_o"]." è andato a buon fine. <br />";
							$output = MailordiniModel::loadTemplate($mail->Subject, $output);
							break;
						case "Pending":
							$mail->Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: ".$ordine["id_o"];
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						case "Denied":
							$mail->Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: ".$ordine["id_o"];
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						case "Failed":
							$mail->Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: ".$ordine["id_o"];
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						default:
							$mail->Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: ".$ordine["id_o"];
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
					}
					
					$mail->MsgHTML($output);
					
					try
					{
						$mail->Send();
					} catch (Exception $e) {
						
					}
				}
			}
		}
		else
		{
			MailordiniModel::inviaMailLog("ERRORE IPN", "<pre>".$p->log_ipn_results(false, false)."</pre>", "IPN");
		}
	}
	
	public function modifica($id_o = 0, $cart_uid = 0)
	{
		$data['notice'] = null;
		
		$data['title'] = Parametri::$nomeNegozio . " - Modifica resoconto ordine";
		
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["id_o"] = (int)$id_o;
		
		if (!$this->m["OrdiniModel"]->recordExists($clean["id_o"], $clean["cart_uid"]))
		{
			if (Output::$html)
				$this->redirect("carrello");
			else
				$esisteOrdine = false;
		}
		
		$data["tendinaIndirizzi"] = $this->m["RegusersModel"]->getTendinaIndirizzi(User::$id);
		
		$this->m['OrdiniModel']->addStrongCondition("update",'checkIsStrings|'.v("pagamenti_permessi"),"pagamento|".gtext("<b>Si prega di selezionare il pagamento</b>")."<div class='evidenzia'>class_pagamento</div>");
		
		$this->m['OrdiniModel']->addStrongCondition("update",'checkIsStrings|'.implode(",",array_keys($data["tendinaIndirizzi"])),"id_spedizione|".gtext("<b>Si prega di selezionare l'indirizzo</b>")."<div class='evidenzia'>class_id_spedizione</div>");
		
		$this->m['OrdiniModel']->setFields("pagamento,id_spedizione",'sanitizeAll');
		$this->m['OrdiniModel']->updateTable('update',$clean["id_o"]);
		$data['notice'] = $this->m['OrdiniModel']->notice;
		
		if ($this->m['OrdiniModel']->queryResult)
		{
			$result = false;
			
			if (isset($_POST["id_spedizione"]) && in_array($_POST["id_spedizione"], array_keys($data["tendinaIndirizzi"])))
				$result = $this->m['OrdiniModel']->importaSpedizione($clean["id_o"], $_POST["id_spedizione"]);
			
			if ($result)
				$this->redirect("resoconto-acquisto/".$clean["id_o"]."/".$clean["cart_uid"]."?n=y");
			else
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, non è stato possibile cambiare i dati di spedizione. Contattare il negozio")."</div>";
		}
		
		$res = $this->m["OrdiniModel"]->clear()
							->where(array("id_o" => $clean["id_o"], "cart_uid" => $clean["cart_uid"] ))
							->send();
		
		$data["ordine"] = $res[0]["orders"];
		
		if ($data["ordine"]["stato"] != "pending")
			$this->redirect("");
		
		$this->append($data);
		$this->load("modifica_ordine");
	}
	
	public function summary($id_o = 0, $cart_uid = 0, $admin_token = "token")
	{
		$data['notice'] = null;
		
		$data['title'] = Parametri::$nomeNegozio . " - Resoconto ordine";
		
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["admin_token"] = $data["admin_token"] = sanitizeAll($admin_token);
		$clean["id_o"] = (int)$id_o;
		
		$esisteOrdine = true;
		
		if (!$this->m["OrdiniModel"]->recordExists($clean["id_o"], $clean["cart_uid"]))
		{
			if (Output::$html)
				$this->redirect("carrello");
			else
				$esisteOrdine = false;
		}
		
		$rightAdminToken = $this->m["OrdiniModel"]->getAdminToken($clean["id_o"], $clean["cart_uid"]);
		
		if (isset($_POST["modifica_stato_ordine"]) and strcmp($clean["admin_token"],$rightAdminToken) === 0)
		{
			$clean['stato'] = $this->request->post("stato","pending","sanitizeAll");
			$statiPermessi = array("pending","deleted","completed");
			
			if (in_array($clean['stato'],$statiPermessi))
			{
				$this->m["OrdiniModel"]->values = array("stato" => $clean['stato']);
				$this->m["OrdiniModel"]->update(null, "id_o=".$clean["id_o"]." and cart_uid='".$clean["cart_uid"]."'");
				$data['notice'] = $this->m["OrdiniModel"]->notice;
			}
		}
		
		$res = $this->m["OrdiniModel"]->clear()
							->where(array("id_o" => $clean["id_o"], "cart_uid" => $clean["cart_uid"] ))
							->send();
		
		$data["righeOrdine"] = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean["id_o"],"cart_uid" => $clean["cart_uid"]))->send();
		
		$data["ordine"] = $res[0]["orders"];
		
		// ID ordine per GTM e FBK (solo se bonifico)
		if ($data["ordine"]["pagamento"] == "bonifico" || ($data["ordine"]["pagamento"] == "paypal" && $data["ordine"]["stato"] != "pending" && $data["ordine"]["stato"] != "deleted"))
			$data['idOrdineGtm'] = (int)$id_o;
		
		$data["tipoOutput"] = "web";
		
		if (Output::$html)
		{
			if (strcmp($data["ordine"]["pagamento"],"paypal") === 0 and strcmp($data["ordine"]["stato"],"pending") === 0)
			{
				if (isset($_GET["to_paypal"]))
				{
					$this->clean();
				}
				
				require (LIBRARY.'/External/paypal/paypal_class.php');
				
				if (Parametri::$useSandbox)
				{
					$p = new paypal_class(true); //usa sandbox
					$p->paypal_mail = Parametri::$paypalSandBoxSeller;
				}
				else
				{
					$p = new paypal_class(); //usa il vero paypal
					$p->paypal_mail = Parametri::$paypalSeller;
				}
				
				$p->add_field('return', $this->baseUrl."/grazie-per-l-acquisto");
				$p->add_field('cancel_return', $this->baseUrl);
				$p->add_field('notify_url', $this->baseUrl."/notifica-pagamento");
				$p->add_field('item_name', "Ordine #".$data["ordine"]["id_o"]);
				$p->add_field('item_number', $data["ordine"]["cart_uid"]);
				$p->add_field('amount', $data["ordine"]["total"]);
				$p->add_field('currency_code', 'EUR');
				$p->add_field('lc', 'IT');
				
				$p->add_field('email', $data["ordine"]["email"]);

				if (strcmp($data["ordine"]["tipo_cliente"], "privato") === 0) {
					$p->add_field('first_name', $data["ordine"]["nome"]);
					$p->add_field('last_name', $data["ordine"]["cognome"]);
				}
				
				$p->add_field('address1', $data["ordine"]["indirizzo"]);
				$p->add_field('city', $data["ordine"]["citta"]);
				$p->add_field('zip', $data["ordine"]["cap"]);
				$p->add_field('country', $data["ordine"]["nazione"]);
				
				if ($data["ordine"]["nazione"] == "IT")
					$p->add_field('state', $data["ordine"]["provincia"]);
				else
					$p->add_field('state', $data["ordine"]["dprovincia"]);
				
				$p->add_field('cmd', '_xclick');
				$p->add_field('rm', '2');   // Return method = POST
				
				$data["pulsantePaypal"] = $p->paypal_button();
			}
			else if (strcmp($data["ordine"]["pagamento"],"carta_di_credito") === 0 and strcmp($data["ordine"]["stato"],"pending") === 0)
			{
				$data["pulsantePaga"] = "<a href='#'>Paga adesso</a></div>";
			}

			$this->append($data);
			
			if (!isset($_GET["to_paypal"]))
			{
				$this->load("top_resoconto");
				$this->load("resoconto-acquisto");
			}
			else
				$this->load("to_paypal");
		}
		else
		{
			if ($esisteOrdine)
				$this->infoordine($clean["id_o"]);
			
			$this->load("api_output");
		}
	}
	
	private function infoordine($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$ordine = $this->m["OrdiniModel"]->selectId($clean["id_o"]);
		
		if (!empty($ordine))
		{
			$temp = $ordine;
			
			unset($temp["descrizione_acquisto"]);
			unset($temp["creation_time"]);
			unset($temp["id_order"]);
			unset($temp["admin_token"]);
			unset($temp["txn_id"]);
			unset($temp["registrato"]);
			unset($temp["banca_token"]);
			unset($temp["descrizione_acquisto"]);
			unset($temp["descrizione_acquisto"]);
			
			$temp = htmlentitydecodeDeep($temp);
			
			$temp["nazione"] = nomeNazione($temp["nazione"]);
			$temp["nazione_spedizione"] = nomeNazione($temp["nazione_spedizione"]);

			$temp["stato_desc"] = statoOrdine($temp["stato"]);
			$temp["data_ordine"] = date("d/m/Y", strtotime($temp["data_creazione"]));
			
			$temp["total"] = number_format($temp["total"],2,",","");
			$temp["subtotal"] = number_format($temp["subtotal"],2,",","");
			$temp["spedizione"] = number_format($temp["spedizione"],2,",","");
			$temp["iva"] = number_format($temp["iva"],2,",","");
			$temp["prezzo_scontato"] = number_format($temp["prezzo_scontato"],2,",","");
			$temp["peso"] = number_format($temp["peso"],2,",","");
			if ($temp["promo"] && is_numeric($temp["promo"]))
				$temp["promo"] = number_format($temp["promo"],2,",","");
			
			Output::setBodyValue("Ordine", $temp);
			
			$totali = array(
				"pieno"			=>	$temp["subtotal"],
				"imponibile"	=>	$temp["prezzo_scontato"],
				"spedizione"	=>	$temp["spedizione"],
				"iva"			=>	$temp["iva"],
				"totale"		=>	$temp["total"],
			);
			
			Output::setBodyValue("Totali", $totali);
			
			$pages = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send(false);
			
			$pagineConDecode = array();
			
			foreach ($pages as $page)
			{
				$temp = $page;
// 				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["price"] = number_format($temp["prezzo_intero"],2,",","");
// 				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
				$page["prezzo_scontato"] = number_format($temp["price"],2,",","");
				$page["iva"] = number_format($page["iva"],2,",","");
				$page["subtotale"] = number_format($temp["price"]*$temp["quantity"],2,",","");
				
				unset($page["cart_uid"]);
				
				$page = htmlentitydecodeDeep($page);
				
				$pagineConDecode[] = $page;
			}
			
			Output::setBodyValue("Righe", $pagineConDecode);
			
			$testi = array(
				"TestoPagamento"	=>	"Il pagamento potrà essere effettuato alla consegna, tramite Carta di Credito, Bancomat, Assegno Bancario, per Contanti, o con Satispay",
				"OrdineCreatoHeader"	=>	"Ordine creato con successo!",
				"OrdineCreatoContent"	=>	"Ti è stata inviata una email con il resoconto dell'ordine.",
			);
			
			Output::setBodyValue("Testi", $testi);
		}
	}
	
	private function createLogFolder()
	{
		if(!is_dir(ROOT.'/Logs'))
		{
			if (@mkdir(ROOT.'/Logs'))
			{
				$fp = fopen(ROOT.'/Logs/index.html', 'w');
				fclose($fp);
				
				$fp = fopen(ROOT.'/Logs/.htaccess', 'w');
				fwrite($fp, 'deny from all');
				fclose($fp);
			}
		}
	}
	
	public function ritornodapaypal()
	{
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/back_paypal.txt', 'a+');
		fwrite($fp, date("Y-m-d H:i:s"));
		fwrite($fp, print_r($_GET,true));
		fwrite($fp, print_r($_POST,true));
		fclose($fp);
		
		$data['title'] = Parametri::$nomeNegozio . " - Grazie per l'acquisto";
		
		if (isset($_GET["item_number"]) || isset($_GET["tx"]))
		{
			$clean['cart_uid'] = $this->request->get('item_number','','sanitizeAll');
			$clean['txn_id'] = $this->request->get('tx','','sanitizeAll');
			$clean['st'] = $this->request->get('st','','sanitizeAll');
		}
		else
		{
			$clean['cart_uid'] = $this->request->post('item_number','','sanitizeAll');
			$clean['txn_id'] = $this->request->post('tx','','sanitizeAll');
			$clean['st'] = $this->request->post('st','','sanitizeAll');
		}
		
		$res = $this->m["OrdiniModel"]->clear()->where(array("cart_uid" => $clean['cart_uid']))->send();
		$data["conclusa"] = false;
		
		if ((int)count($res) === 0 && trim($clean['txn_id']))
			$res = $this->m["OrdiniModel"]->clear()->where(array("txn_id" => $clean['txn_id']))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($clean['st'],"Completed") === 0)
				$data["conclusa"] = true;
			
			if (strcmp($res[0]["orders"]["stato"],"completed") === 0)
				$data["conclusa"] = false;
			
			$data["ordine"] = $res[0]["orders"];
			
			$data['idOrdineGtm'] = (int)$data["ordine"]["id_o"];
			
			$this->append($data);
			$this->load("ritorno-da-paypal");
		}
		else if (trim($clean['txn_id']))
		{
			$this->append($data);
			
			$this->load("ritorno-da-paypal");
		}
	}
	
	public function index()
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		session_start();
		
		$data['title'] = Parametri::$nomeNegozio . ' - Checkout';
		
		$data['customHeaderClass'] = "page-template-default page page-id-9 logged-in custom-background wp-custom-logo theme-auros woocommerce-checkout woocommerce-page woocommerce-no-js opal-style chrome platform-linux woocommerce-active product-style-1 opal-layout-wide opal-pagination-6 opal-page-title-top-bottom-center opal-footer-skin-light opal-comment-4 opal-comment-form-2 elementor-default";
// 		$data["inlineCssFile"] = "auros-css-inline-category.css";
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$data["pages"] = $this->m["CartModel"]->getProdotti();
		
		if (count($data["pages"]) === 0)
		{
			if (Output::$html)
				$this->redirect("carrello/vedi");
		}
		
		if (!$this->m["CartModel"]->checkQtaFull())
		{
			if (Output::$html)
				$this->redirect("carrello/vedi");
		}
		
		// Prendo valori da account, per app
		if ($this->s['registered']->status['status'] === 'logged')
		{
			if (isset($_POST["datiFromAccount"]))
			{
				$tempDettagli = htmlentitydecodeDeep(User::$dettagli);
				
				$_POST["tipo_cliente"] = $tempDettagli["tipo_cliente"];
				$_POST["nome"] = $tempDettagli["nome"];
				$_POST["cognome"] = $tempDettagli["cognome"];
				$_POST["ragione_sociale"] = $tempDettagli["ragione_sociale"];
				$_POST["codice_fiscale"] = $tempDettagli["codice_fiscale"];
				$_POST["p_iva"] = $tempDettagli["p_iva"];
				$_POST["indirizzo"] = $tempDettagli["indirizzo"];
				$_POST["cap"] = $tempDettagli["cap"];
				$_POST["nazione"] = $tempDettagli["nazione"];
				$_POST["provincia"] = $tempDettagli["provincia"];
				$_POST["dprovincia"] = $tempDettagli["dprovincia"];
				$_POST["citta"] = $tempDettagli["citta"];
				$_POST["telefono"] = $tempDettagli["telefono"];
				$_POST["email"] = $tempDettagli["username"];
				$_POST["conferma_email"] = $tempDettagli["username"];
				$_POST["pec"] = $tempDettagli["pec"];
				$_POST["codice_destinatario"] = $tempDettagli["codice_destinatario"];
				
				if (isset($_POST["id_spedizione"]))
				{
					$clean["usaIdSpedizione"] = (int)$_POST["id_spedizione"];
					
					$spedizioneDaUsare = $this->m["SpedizioniModel"]->where(array(
						"id_user"	=>	(int)User::$id,
						"id_spedizione"	=>	$clean["usaIdSpedizione"],
					))->record();
					
					if (!empty($spedizioneDaUsare))
					{
						$spedizioneDaUsare = htmlentitydecodeDeep($spedizioneDaUsare);
						
						$_POST["indirizzo_spedizione"] = $spedizioneDaUsare["indirizzo_spedizione"];
						$_POST["cap_spedizione"] = $spedizioneDaUsare["cap_spedizione"];
						$_POST["provincia_spedizione"] = $spedizioneDaUsare["provincia_spedizione"];
						$_POST["dprovincia_spedizione"] = $spedizioneDaUsare["dprovincia_spedizione"];
						$_POST["citta_spedizione"] = $spedizioneDaUsare["citta_spedizione"];
						$_POST["telefono_spedizione"] = $spedizioneDaUsare["telefono_spedizione"];
						$_POST["nazione_spedizione"] = $spedizioneDaUsare["nazione_spedizione"];
					}
				}
			}
		}
		
		$descrizioneAcquisto = serialize($data["pages"]);
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
		$codiciSpedizioneAttivi = $this->m["NazioniModel"]->selectCodiciAttiviSpedizione();
		
		$elencoCorrieri = $this->m["CorrieriModel"]->elencoCorrieri();
		
		//imposto spedizione uguale a fatturazione
		if (isset($_POST["spedisci_dati_fatturazione"]) && strcmp($_POST["spedisci_dati_fatturazione"],"Y") === 0)
		{
			$_POST["indirizzo_spedizione"] = $this->request->post("indirizzo","","none");
			$_POST["cap_spedizione"] = $this->request->post("cap","","none");
			$_POST["provincia_spedizione"] = $this->request->post("provincia","","none");
			$_POST["dprovincia_spedizione"] = $this->request->post("dprovincia","","none");
			$_POST["citta_spedizione"] = $this->request->post("citta","","none");
			$_POST["telefono_spedizione"] = $this->request->post("telefono","","none");
			$_POST["nazione_spedizione"] = $this->request->post("nazione","","none");
			
			if (!in_array($_POST["nazione_spedizione"], $codiciSpedizioneAttivi))
				$_POST["spedisci_dati_fatturazione"] = "N";
			
			$_POST["id_spedizione"] = 0;
		}
		
		IvaModel::getAliquotaEstera();
		
		$campoObbligatoriProvincia = "dprovincia";
		
		if (isset($_POST["nazione"]))
		{
			if ($_POST["nazione"] == "IT")
				$campoObbligatoriProvincia = "provincia";
		}
		
		$campoObbligatoriProvinciaSpedizione = "dprovincia_spedizione";
		
		if (isset($_POST["nazione_spedizione"]))
		{
			if ($_POST["nazione_spedizione"] == "IT")
				$campoObbligatoriProvinciaSpedizione = "provincia_spedizione";
		}
		
		$campoConfermaEmail = "";
		
		if (v("account_attiva_conferma_username"))
			$campoConfermaEmail = "conferma_email,";
		
		$campiObbligatoriComuni = "indirizzo,$campoObbligatoriProvincia,citta,telefono,email,".$campoConfermaEmail."pagamento,accetto,tipo_cliente,indirizzo_spedizione,$campoObbligatoriProvinciaSpedizione,citta_spedizione,telefono_spedizione,nazione,nazione_spedizione,cap,cap_spedizione";
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT" && v("abilita_codice_fiscale"))
			$campiObbligatoriComuni .= ",codice_fiscale";
		
// 		if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] == "IT")
// 			$campiObbligatoriComuni .= ",cap_spedizione";
		
		$campiObbligatoriAggiuntivi = "";
		
		if (strcmp($tipo_cliente,"privato") !== 0 && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			if (trim($codiceDestinatario) == "")
				$campiObbligatoriAggiuntivi .= ",codice_destinatario";
			
			if (trim($pec) == "")
				$campiObbligatoriAggiuntivi .= ",pec";
			
			if (trim($pec) != "" || trim($codiceDestinatario) != "")
				$campiObbligatoriAggiuntivi = "";
		}
// 		echo $codiceDestinatario;die();
		$campiObbligatoriComuni .= $campiObbligatoriAggiuntivi;
		
		$campoPIva = "";
		
		if (isset($_POST["nazione"]) && in_array($_POST["nazione"], NazioniModel::elencoNazioniConVat()))
			$campoPIva = "p_iva,";
		
		if (strcmp($tipo_cliente,"privato") === 0)
		{
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkNotEmpty',"nome,cognome,".$campiObbligatoriComuni);
		}
		else if (strcmp($tipo_cliente,"libero_professionista") === 0)
		{
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkNotEmpty',"nome,cognome,$campoPIva".$campiObbligatoriComuni);
		}
		else
		{
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkNotEmpty',"ragione_sociale,$campoPIva".$campiObbligatoriComuni);
		}
		
		$this->m['OrdiniModel']->addSoftCondition("both",'checkMail',"pec|".gtext("Si prega di ricontrollare <b>l'indirizzo Pec</b>")."<div class='evidenzia'>class_pec</div>");
		
		$this->m['OrdiniModel']->addSoftCondition("both",'checkLength|7',"codice_destinatario|".gtext("Si prega di ricontrollare <b>il Codice Destinatario</b>")."<div class='evidenzia'>class_codice_destinatario</div>");
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>")."<div class='evidenzia'>class_email</div>");
		
		if (v("account_attiva_conferma_username"))
		{
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkMail',"conferma_email|".gtext("Si prega di ricontrollare il campo <b>conferma dell'indirizzo Email</b>")."<div class='evidenzia'>class_conferma_email</div>");
			
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkEqual',"email,conferma_email|".gtext("<b>I due indirizzi email non corrispondono</b>")."<div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>");
		}
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|accetto',"accetto|".gtext("<b>Si prega di accettare le condizioni di privacy</b>")."<div class='evidenzia'>class_accetto</div>");
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|'.Parametri::$metodiPagamento,"pagamento|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>"));
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|privato,azienda,libero_professionista',"tipo_cliente|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>"));
		
		$this->m['OrdiniModel']->addSoftCondition("insert",'checkLength|255',"note|<b>".gtext("Le note non possono superare i 255 caratteri")."</b><div class='evidenzia'>class_note</div>");
		
		$this->m['OrdiniModel']->addSoftCondition("insert",'checkLength|255',"indirizzo_spedizione|".gtext("<b>L'indirizzo di spedizione non può superare i 255 caratteri</b>")."<div class='evidenzia'>class_indirizzo_spedizione</div>");
		
		$this->m['OrdiniModel']->addStrongCondition("insert","checkMatch|/^[0-9\s]+$/","telefono|".gtext("Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_telefono</div>");
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			$this->m['OrdiniModel']->addStrongCondition("insert","checkMatch|/^[0-9]+$/","cap|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_cap</div>");
			
			if (v("abilita_codice_fiscale"))
				$this->m['OrdiniModel']->addStrongCondition("insert","checkMatch|/^[0-9a-zA-Z]+$/","codice_fiscale|".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>")."<div class='evidenzia'>class_codice_fiscale</div>");
			
			$this->m['OrdiniModel']->addSoftCondition("insert","checkMatch|/^[0-9a-zA-Z]+$/","p_iva|".gtext("Si prega di controllare il campo <b>Partita Iva</b>")."<div class='evidenzia'>class_p_iva</div>");
		}
		
		if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] == "IT")
		{
			$this->m['OrdiniModel']->addStrongCondition("insert","checkMatch|/^[0-9]+$/","cap_spedizione|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_cap_spedizione</div>");
		}
		
		$codiciNazioniAttive = implode(",",$this->m["NazioniModel"]->selectCodiciAttivi());
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|'.$codiciNazioniAttive,"nazione|".gtext("<b>Si prega di selezionare una nazione tra quelle permesse</b>"));
		
		$codiciNazioniAttiveSpedizione = implode(",",$codiciSpedizioneAttivi);
		
		$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|'.$codiciNazioniAttiveSpedizione,"nazione_spedizione|".gtext("<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>"));
		
		if (isset($_POST["nazione_spedizione"]) && count($elencoCorrieri) > 0)
		{
			$listaCorrieriNazione = implode(",",$this->m["CorrieriModel"]->getIdsCorrieriNazione($_POST["nazione_spedizione"]));
			
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|'.$listaCorrieriNazione,"id_corriere|".gtext("<b>Non è possibile spedire nella nazione selezionata</b>"));
		}
		
		$fields = 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,dprovincia,citta,telefono,email,pagamento,accetto,tipo_cliente,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,citta_spedizione,telefono_spedizione,aggiungi_nuovo_indirizzo,id_spedizione,id_corriere,nazione,nazione_spedizione,pec,codice_destinatario,note';
		
		if (!$this->islogged)
		{
			$_POST["aggiungi_nuovo_indirizzo"] = "N";
			$_POST["id_spedizione"] = 0;
			
			$this->m['OrdiniModel']->addStrongCondition("insert",'checkIsStrings|Y,N',"registrato|<b>".gtext("Si prega di indicare se volete continuare come utente anonimo oppure creare un account", false)."</b><div class='evidenzia'>class_registrato</div>");
			
			$fields .= ",registrato";
			
			$registrato = $this->request->post("registrato","","sanitizeAll");
			
			if (strcmp($registrato, "Y") === 0)
			{
				$this->m['RegusersModel']->setFields("username",'sanitizeAll');
				
				$clean["username"] = $this->request->post("email","","sanitizeAll");
				$this->m['RegusersModel']->values["username"] = $clean["username"];
				
				$this->m['RegusersModel']->databaseConditions['insert'] = array(
					"checkUnique"		=>	"username|".gtext("La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.",false)."<br />".gtext("Può eseguire il login (se non ricorda la password può impostarne una nuova al seguente",false)." <a href='http://".DOMAIN_NAME."/password-dimenticata'>".gtext("indirizzo web", false)."</a>) ".gtext("oppure decidere di completare l'acquisto come utente anonimo", false)."<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>",
				);
				
				if (v("account_attiva_conferma_password"))
					$this->m['RegusersModel']->addStrongCondition("insert",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b><span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>");
				else
					$this->m['RegusersModel']->addStrongCondition("insert",'checkNotEmpty',"password");
				
				$this->m['RegusersModel']->addStrongCondition("insert",'checkMatch|/^[a-zA-Z0-9\_\-\!\,\.]+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>Tutte le lettere, maiuscole o minuscole (a, A, b, B, ...)</li><li>Tutti i numeri (0,1,2,...)</li><li>I seguenti caratteri: <b>_ - ! , .</b></li></ul><span class='evidenzia'>class_password</span>");
			}
		}
		
		$this->m['OrdiniModel']->setFields($fields,'strip_tags');

		$data['notice'] = null;

		Output::setBodyValue("Risultato", "KO");
		
		$utenteRegistrato = false;
		
		if ($this->islogged)
			$utenteRegistrato = true;
		
		if (isset($_POST['invia']))
		{
			$tessera = $this->request->post('tessera','');
			if (strcmp($tessera,'') === 0)
			{
				if ($this->m['OrdiniModel']->checkConditions('insert'))
				{
					if ($this->m['RegusersModel']->checkConditions('insert'))
					{
						$_SESSION = $_POST;
						unset($_SESSION['accetto']);
						
						$this->m['OrdiniModel']->values["subtotal"] = getSubTotalN();
						$this->m['OrdiniModel']->values["spedizione"] = getSpedizioneN();
						
						$this->m['OrdiniModel']->values["subtotal_ivato"] = setPrice(getSubTotal(true));
						$this->m['OrdiniModel']->values["spedizione_ivato"] = setPrice(getSpedizione(1));
						
						$this->m['OrdiniModel']->values["iva"] = setPrice(getIva());
						$this->m['OrdiniModel']->values["total"] = setPrice(getTotal());
						$this->m['OrdiniModel']->values["cart_uid"] = User::$cart_uid;
						$this->m['OrdiniModel']->values["admin_token"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
						$this->m['OrdiniModel']->values["banca_token"] = md5(randString(18).microtime().uniqid(mt_rand(),true));
						
						$this->m['OrdiniModel']->values["creation_time"] = time();
						$this->m['OrdiniModel']->values["stato"] = "pending";
						
						$this->m['OrdiniModel']->values["prezzo_scontato"] = getPrezzoScontatoN();
						$this->m['OrdiniModel']->values["prezzo_scontato_ivato"] = setPrice(getPrezzoScontato(1));
						
						$this->m['OrdiniModel']->values["codice_promozione"] = User::$coupon;
						$this->m['OrdiniModel']->values["nome_promozione"] = htmlentitydecode(getNomePromozione());
						$this->m['OrdiniModel']->values["usata_promozione"] = hasActiveCoupon() ? "Y" : "N";
						
						$this->m['OrdiniModel']->values["id_iva"] = CartModel::getIdIvaSpedizione();
						$this->m['OrdiniModel']->values["iva_spedizione"] = CartModel::getAliquotaIvaSpedizione();
						
						if (isset(IvaModel::$aliquotaEstera))
						{
							$this->m['OrdiniModel']->values["id_iva_estera"] = IvaModel::$idIvaEstera;
							$this->m['OrdiniModel']->values["aliquota_iva_estera"] = IvaModel::$aliquotaEstera;
							$this->m['OrdiniModel']->values["stringa_iva_estera"] = IvaModel::$titoloAliquotaEstera;
						}
						
						$this->m['OrdiniModel']->sanitize("sanitizeHtml");
						$this->m['OrdiniModel']->values["descrizione_acquisto"] = $descrizioneAcquisto;
						$this->m['OrdiniModel']->sanitize();
						
						if ($this->m['OrdiniModel']->insert())
						{
							Output::setBodyValue("Risultato", "OK");
							
							// Salvo la dprovincia
							$dprovincia = $this->m['OrdiniModel']->values["dprovincia"];
							
							// Salvo la dprovincia spedizione
							$dprovincia_spedizione = $this->m['OrdiniModel']->values["dprovincia_spedizione"];
							
							$clean['lastId'] = (int)$this->m['OrdiniModel']->lId;
							
							Output::setBodyValue("IdOrdine", $clean['lastId']);
							
							//riempie la tabella delle righe
							$this->m['OrdiniModel']->riempiRighe($clean['lastId']);
							
							$this->m["CartModel"]->del(null, "cart_uid = '".$clean["cart_uid"]."'");
							setcookie("cart_uid", "", time()-3600,"/");
							
							//distruggi il cookie del coupon
							if (hasActiveCoupon($clean['lastId']))
							{
								setcookie("coupon", "", time()-3600,"/");
							}
							
							$clean["cart_uid"] = sanitizeAll($this->m['OrdiniModel']->cart_uid);
							
							Output::setBodyValue("CartUid", $clean['cart_uid']);
							
							$res = $this->m["OrdiniModel"]->clear()
								->where(array("id_o" => $clean['lastId'], "cart_uid" => $clean["cart_uid"] ))
								->send();
							
							$ordine = $res[0]["orders"];
							
							//se la password deve essere mandata via mail
							$sendPassword = false;
		
// 							require_once(ROOT."/External/phpmailer/class.phpmailer.php");

							$mail = new PHPMailer(true); //New instance, with exceptions enabled

							if (Parametri::$useSMTP)
							{
								$mail->IsSMTP();                         // tell the class to use SMTP
								$mail->SMTPAuth   = true;                  // enable SMTP authentication
								$mail->Port       = Parametri::$SMTPPort;                    // set the SMTP server port
								$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
								$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
								$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
							}
							
							$mail->From       = Parametri::$mailFrom;
							$mail->FromName   = Parametri::$mailFromName;
							$mail->CharSet = 'UTF-8';
							
							$mail->SMTPOptions = array(
								'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true
								)
							);
							
							if (ImpostazioniModel::$valori["bcc"])
								$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
							
							if (defined("BCC") && is_array(BCC))
							{
								foreach (BCC as $emailBcc)
								{
									$mail->addBCC($emailBcc);
								}
							}
							
							if (!$this->islogged)
							{
								//registra utente
								if (strcmp($registrato, "Y") === 0)
								{
									$sendPassword = true;
									
									$password = $this->request->post("password","","none");
									$clean["password"] = sha1($password);
									
									$this->m['RegusersModel']->values["password"] = $clean["password"];
									
									$this->m['RegusersModel']->values["nome"] = $this->m['OrdiniModel']->values["nome"];
									$this->m['RegusersModel']->values["cognome"] = $this->m['OrdiniModel']->values["cognome"];
									$this->m['RegusersModel']->values["ragione_sociale"] = $this->m['OrdiniModel']->values["ragione_sociale"];
									$this->m['RegusersModel']->values["p_iva"] = $this->m['OrdiniModel']->values["p_iva"];
									$this->m['RegusersModel']->values["codice_fiscale"] = $this->m['OrdiniModel']->values["codice_fiscale"];
									$this->m['RegusersModel']->values["nazione"] = $this->m['OrdiniModel']->values["nazione"];
									$this->m['RegusersModel']->values["indirizzo"] = $this->m['OrdiniModel']->values["indirizzo"];
									$this->m['RegusersModel']->values["cap"] = $this->m['OrdiniModel']->values["cap"];
									$this->m['RegusersModel']->values["provincia"] = $this->m['OrdiniModel']->values["provincia"];
									$this->m['RegusersModel']->values["dprovincia"] = $this->m['OrdiniModel']->values["dprovincia"];
									
									$this->m['RegusersModel']->values["citta"] = $this->m['OrdiniModel']->values["citta"];
									$this->m['RegusersModel']->values["citta"] = $this->m['OrdiniModel']->values["citta"];
									$this->m['RegusersModel']->values["telefono"] = $this->m['OrdiniModel']->values["telefono"];
									$this->m['RegusersModel']->values["tipo_cliente"] = $this->m['OrdiniModel']->values["tipo_cliente"];
									$this->m['RegusersModel']->values["accetto"] = $this->m['OrdiniModel']->values["accetto"];
									
									$this->m['RegusersModel']->values["pec"] = $this->m['OrdiniModel']->values["pec"];
									$this->m['RegusersModel']->values["codice_destinatario"] = $this->m['OrdiniModel']->values["codice_destinatario"];
									
// 									$this->m['RegusersModel']->values["indirizzo_spedizione"] = $this->m['OrdiniModel']->values["indirizzo_spedizione"];
									
									if ($this->m['RegusersModel']->insert())
									{
										$clean['userId'] = (int)$this->m['RegusersModel']->lastId();
										
										//aggiungo l'indirizzo di spedizione
										$this->m["SpedizioniModel"]->setValues(array(
											"indirizzo_spedizione"	=>	$_POST["indirizzo_spedizione"],
											"cap_spedizione"	=>	$_POST["cap_spedizione"],
											"provincia_spedizione"	=>	$_POST["provincia_spedizione"],
											"dprovincia_spedizione"	=>	$_POST["dprovincia_spedizione"],
											"citta_spedizione"	=>	$_POST["citta_spedizione"],
											"telefono_spedizione"	=>	$_POST["telefono_spedizione"],
											"nazione_spedizione"	=>	$_POST["nazione_spedizione"],
											"id_user"	=>	$clean['userId'],
											"ultimo_usato"	=>	"Y",
										));
										
										$this->m["SpedizioniModel"]->insert();
										
										$idSpedizione = $this->m["SpedizioniModel"]->lId;
										
										$this->m['OrdiniModel']->values = array(
											"id_user" => $clean['userId'],
											"registrato" => "Y",
											"id_spedizione" => $idSpedizione,
											"aggiungi_nuovo_indirizzo"	=>	"Y",
										);
										
										$this->m['OrdiniModel']->update($clean['lastId']);
										
										try
										{
											//manda mail con credenziali al cliente
											$mail->ClearAddresses();
											$mail->AddAddress($ordine["email"]);
											if (Parametri::$mailReplyTo && Parametri::$mailFromName)
												$mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
											$mail->Subject  = Parametri::$nomeNegozio." - ".gtext("Invio credenziali nuovo utente");
											$mail->IsHTML(true);
											
											//mail con credenziali
											ob_start();
											include tp()."/Regusers/mail_credenziali.php";

											$output = ob_get_clean();
											$output = MailordiniModel::loadTemplate($mail->Subject, $output);
											
											$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
											$mail->MsgHTML($output);
											$mail->Send();
										} catch (Exception $e) {
											
										}
										
										//loggo l'utente
										$this->s['registered']->login($clean["username"],$password);
										
										$utenteRegistrato = true;
									}
								}
							}
							else
							{
								$nuovoIndirizzo = $this->request->post("aggiungi_nuovo_indirizzo","Y","sanitizeAll");
								
								$idSpedizione = $this->request->post("id_spedizione","0","forceInt");
								
								if (strcmp($nuovoIndirizzo,"Y") === 0)
								{
									$this->m["SpedizioniModel"]->query("update spedizioni set ultimo_usato = 'N' where id_user = ".(int)User::$id);
									
									$this->m["SpedizioniModel"]->setValues(array(
										"indirizzo_spedizione"	=>	$_POST["indirizzo_spedizione"],
										"cap_spedizione"	=>	$_POST["cap_spedizione"],
										"provincia_spedizione"	=>	$_POST["provincia_spedizione"],
										"dprovincia_spedizione"	=>	$_POST["dprovincia_spedizione"],
										"citta_spedizione"	=>	$_POST["citta_spedizione"],
										"telefono_spedizione"	=>	$_POST["telefono_spedizione"],
										"nazione_spedizione"	=>	$_POST["nazione_spedizione"],
										"id_user"	=>	$this->iduser,
										"ultimo_usato"	=>	"Y",
									));
									
									if ($this->m["SpedizioniModel"]->insert())
										$idSpedizione = $this->m["SpedizioniModel"]->lId;
								}
								else if ($idSpedizione > 0)
								{
									$this->m["SpedizioniModel"]->query("update spedizioni set ultimo_usato = 'N' where id_user = ".(int)User::$id);
									$this->m["SpedizioniModel"]->query("update spedizioni set ultimo_usato = 'Y' where id_spedizione = ".(int)$idSpedizione." and id_user = ".(int)User::$id);
								}
								
								//segna da che utente è stato eseguito l'ordine
								$this->m['OrdiniModel']->values = array(
									"id_user" => $this->iduser,
									"registrato" => "Y",
									"id_spedizione" => $idSpedizione,
								);
								$this->m['OrdiniModel']->update($clean['lastId']);
							}

							// Se è nazione estera, salvo la dprovincia in provincia
                            if (isset($_POST["nazione"]) && $_POST["nazione"] != "IT")
                            {
								$this->m['OrdiniModel']->values = array(
                                    "provincia" => $dprovincia,
                                );
                                $this->m['OrdiniModel']->update($clean['lastId']);
                            }
                            
                            // Se è nazione estera, salvo la dprovincia_spedizione in provincia_spedizione
                            if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] != "IT")
                            {
								$this->m['OrdiniModel']->values = array(
                                    "provincia_spedizione" => $dprovincia_spedizione,
                                );
                                $this->m['OrdiniModel']->update($clean['lastId']);
                            }
                            
                            // Estraggo nuovamente l'ordine
                            $res = $this->m["OrdiniModel"]->clear()
                                ->where(array("id_o" => $clean['lastId']))
                                ->send();

                            $ordine = $res[0]["orders"];
                            
// 							$mail->WordWrap = 70;
							
							$righeOrdine = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean['lastId'],"cart_uid" => $clean["cart_uid"]))->send();
							
							try
							{
								$mail->ClearAddresses();
								$mail->AddAddress($ordine["email"]);
								if (Parametri::$mailReplyTo && Parametri::$mailFromName)
									$mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
								$mail->Subject  = Parametri::$nomeNegozio." - ".gtext("Ordine")." N°" . $clean['lastId'];
								$mail->IsHTML(true);
								
								//mail al cliente
								ob_start();
								$tipoOutput = "mail_al_cliente";
								include tp()."/Ordini/resoconto-acquisto.php";

								$output = ob_get_clean();
								$output = MailordiniModel::loadTemplate($mail->Subject, $output);
								
								$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
								$mail->MsgHTML($output);
								
// 								if (!$utenteRegistrato || $ordine["pagamento"] != "paypal")
									$mail->Send();

								//mail al negozio
								$mail->ClearAllRecipients();
								
								if (defined("BCC") && is_array(BCC))
								{
									foreach (BCC as $emailBcc)
									{
										$mail->addBCC($emailBcc);
									}
								}
								
								$mail->AddAddress(Parametri::$mailInvioOrdine);
								
								ob_start();
								$tipoOutput = "mail_al_negozio";
								include tp()."/Ordini/resoconto-acquisto.php";

								$output = ob_get_clean();
								$output = MailordiniModel::loadTemplate($mail->Subject, $output);
								
								$mail->MsgHTML($output);
								$mail->Send();
								
								// Segna inviata mail ordine ricevuto
// 								if (!$utenteRegistrato || $ordine["pagamento"] != "paypal")
									$this->m['OrdiniModel']->aggiungiStoricoMail($clean['lastId'], "R");
								
							} catch (Exception $e) {
								
							}
							
							// Iscrizione alla newsletter
							if (isset($_POST["newsletter"]) && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
							{
								$this->m['OrdiniModel']->iscriviANewsletter($clean['lastId']);
							}
							
							// Redirect immediato a paypal oppure no
							$toPaypal = (ImpostazioniModel::$valori["redirect_immediato_a_paypal"] == "Y" && strcmp($ordine["pagamento"],"paypal") === 0) ? "?to_paypal" : "";
							
// 							$this->clean();

							if (Output::$html)
								$this->redirect("resoconto-acquisto/".$clean['lastId']."/".$clean["cart_uid"]."/token".$toPaypal);
						}
						else
						{
							$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m['OrdiniModel']->notice;
							$this->m['RegusersModel']->result = false;
							
							if (Output::$json)
								Output::setBodyValue("Errori", $this->m['OrdiniModel']->errors);
						}
					}
					else
					{
						$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m['RegusersModel']->notice;
						$this->m['OrdiniModel']->result = false;
						
						if (Output::$json)
							Output::setBodyValue("Errori", $this->m['RegusersModel']->errors);
					}
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m['OrdiniModel']->notice;
					$this->m['RegusersModel']->result = false;
					
					if (Output::$json)
						Output::setBodyValue("Errori", $this->m['OrdiniModel']->errors);
				}
			}
		}
		
		$this->m['OrdiniModel']->fields = "nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,conferma_email,pagamento,accetto,tipo_cliente,registrato,newsletter,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,citta_spedizione,telefono_spedizione,aggiungi_nuovo_indirizzo,id_spedizione,spedisci_dati_fatturazione,id_corriere,nazione,nazione_spedizione,pec,codice_destinatario,dprovincia,note";
		
		// Elenco corrieri
		$data['corrieri'] = $elencoCorrieri;
		
		$defaultValues = $_SESSION;
		
		if ($this->islogged)
		{
			$defaultValues = htmlentitydecodeDeep($this->dettagliUtente);
			$defaultValues["email"] = $defaultValues["username"];
			$defaultValues["conferma_email"] = $defaultValues["username"];
			
			$data["tendinaIndirizzi"] = $this->m["RegusersModel"]->getTendinaIndirizzi(User::$id);
			
			$defaultValues["aggiungi_nuovo_indirizzo"] = "Y";
			
			if (count($data["tendinaIndirizzi"]) > 0)
			{
				$defaultValues["aggiungi_nuovo_indirizzo"] = "N";
				$defaultValues["id_spedizione"] = $this->m["RegusersModel"]->getIdSpedizioneUsatoNellUltimoOrdine(User::$id);
			}
			else
				$defaultValues["spedisci_dati_fatturazione"] = "Y";
		}
		else
		{
			$defaultValues["spedisci_dati_fatturazione"] = "Y";
			
			$nazioneDefault = v("nazione_default");
			
			if (v("attiva_ip_location"))
				$nazioneDefault = User::$nazioneNavigazione;
			
			if (!isset($defaultValues["nazione"]))
				$defaultValues["nazione"] = $nazioneDefault;
			
			if (!isset($defaultValues["nazione_spedizione"]))
				$defaultValues["nazione_spedizione"] = $nazioneDefault;
		}
		
		if (isset($defaultValues["accetto"]))
			unset($defaultValues["accetto"]);
		
		if (count($data['corrieri']) > 0)
			$defaultValues["id_corriere"] = $data['corrieri'][0]["id_corriere"];
		
		$defaultValues["newsletter"] = "N";
		
		$data['values'] = $this->m['OrdiniModel']->getFormValues('insert','sanitizeHtml',null,$defaultValues);
		
		$data['province'] = $this->m['ProvinceModel']->selectTendina();
		
		$this->m['RegusersModel']->fields = "password";
		
		if (v("account_attiva_conferma_password"))
			$this->m['RegusersModel']->fields .= ",confirmation";
		
		$data['regusers_values'] = $this->m['RegusersModel']->getFormValues('insert','sanitizeHtml');
		
		if (strcmp($data['values']["tipo_cliente"],"") === 0)
		{
			$data['values']["tipo_cliente"] = "privato";
		}
		
		if (strcmp($data['values']["registrato"],"") === 0)
		{
			$data['values']["registrato"] = "Y";
		}
		
		if (Output::$json)
		{
			$pagineConDecode = array();
			
			foreach ($data["pages"] as $page)
			{
				$temp = $page;
				$page["quantity"] = 1;
				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["cart"]["price"] = number_format($temp["cart"]["prezzo_intero"],2,",","");
// 				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
				$page["cart"]["prezzo_scontato"] = number_format($temp["cart"]["price"],2,",","");
				$page["cart"]["iva"] = number_format($page["cart"]["iva"],2,",","");
				
				$page["cart"] = htmlentitydecodeDeep($page["cart"]);
				
				$pagineConDecode[] = $page;
			}
			
			$totali = array(
				"pieno"			=>	getSubTotal(),
				"imponibile"	=>	getPrezzoScontato(),
				"spedizione"	=>	getSpedizione(),
				"iva"			=>	getIva(),
				"totale"		=>	getTotal(),
			);
			
			Output::setBodyValue("Totali", $totali);
			
			Output::setBodyValue("Type", "Cart");
			Output::setBodyValue("Pages", $pagineConDecode);
			Output::setHeaderValue("CartProductsNumber",$this->m["CartModel"]->numberOfItems());
			
			$testi = array(
				"TestoCondizioniVendita"	=>	'Confermando il tuo acquisto accetti i nostri termini e condizioni di vendita.',
			);
			
			Output::setBodyValue("Testi", $testi);
			
			if ($this->s['registered']->status['status'] === 'logged')
			{
				$temp = User::$dettagli;
				unset($temp["password"]);
				unset($temp["forgot_token"]);
				unset($temp["forgot_time"]);
				unset($temp["last_failure"]);
				unset($temp["has_confirmed"]);
				unset($temp["ha_confermato"]);
				unset($temp["confirmation_token"]);
				unset($temp["creation_date"]);
				unset($temp["creation_time"]);
				unset($temp["temp_field"]);
				unset($temp["deleted"]);
				
				$temp["nazione"] = nomeNazione($temp["nazione"]);
// 				$temp["provincia"] = nomeProvincia($temp["provincia"]);
				
				Output::setBodyValue("Dettagli", $temp);
				
				$idSpedizione = $this->m['RegusersModel']->getIndirizzoSpedizionePerAdd(User::$id);
				
				Output::setBodyValue("IdSpedizione", $idSpedizione);
				
				if ($idSpedizione > 0)
				{
					$spedizione = $this->m['SpedizioniModel']->selectId($idSpedizione);
					
					$spedizione["nazione_spedizione"] = nomeNazione($spedizione["nazione_spedizione"]);
// 					$spedizione["provincia_spedizione"] = nomeProvincia($spedizione["provincia_spedizione"]);
					
					if (!empty($spedizione))
						Output::setBodyValue("Spedizione", $spedizione);
				}
			}
			
			$this->load("api_output");
		}
		else
		{
			$this->append($data);
		
			$this->load('checkout');
		}
	}
	
	public function totale()
	{
		IvaModel::getAliquotaEstera();
		
		$this->clean();
		
		$data["pages"] = $this->m["CartModel"]->getProdotti();
		$this->append($data);
		
		$this->load("totale_merce");
	}
	
	public function corrieri($nazione = 0)
	{
		IvaModel::getAliquotaEstera();
		
		$this->clean();
		
		if (!$nazione)
			$nazione = v("nazione_default");
		
		$clean["nazione"] = sanitizeAll($nazione);
		
		$corr = new CorrieriModel();
		
		$corrieri = $corr->getIdsCorrieriNazione($clean["nazione"]);
		
		echo json_encode($corrieri);
	}
}
