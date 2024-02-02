<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

// require_once(LIBRARY.'/External/PHPMailer-master/src/Exception.php');
// require_once(LIBRARY.'/External/PHPMailer-master/src/PHPMailer.php');
// require_once(LIBRARY.'/External/PHPMailer-master/src/SMTP.php');

class BaseOrdiniController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->load('header');
		$this->load('footer','last');
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext('Gestione ordine'));

		$this->append($data);
	}

	public function scaricafattura($id_o)
	{
		$this->s['registered']->check(null,0);
		
		$this->clean();
		
		$clean["id_o"] = (int)$id_o;
		
		$this->model("FattureModel");
		
		$res = $this->m("FattureModel")->inner("orders")->using("id_o")->where(array("id_o"=>$clean["id_o"],"orders.id_user"=>User::$id))->send();
		
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
	
	protected function mandaMailDopoPagamento($ordine)
	{
		// Controlla e manda mail dopo pagamento
		$this->m("OrdiniModel")->mandaMailDopoPagamento($ordine["id_o"]);
		
		ElementitemaModel::getPercorsi();
		ElementitemaModel::$percorsi["RESOCONTO_PRODOTTI"]["nome_file"] = "default";
		// Controlla e manda mail dopo pagamento al negozio
		$this->m("OrdiniModel")->mandaMailDopoPagamentoNegozio($ordine["id_o"]);
		
		// Mail ad agente
		$this->m("OrdiniModel")->mandaMailAdAgente($ordine["id_o"]);
	}
	
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
		
		$p->txn_id = $this->m("OrdiniModel")->clear()->select("txn_id")->toList("txn_id")->send();
		
		if ($p->validate_ipn())
		{
			$clean['payment_status'] = $this->request->post('payment_status','','sanitizeAll');
			$clean['cart_uid'] = $this->request->post('item_number','','sanitizeAll');
			$clean['codiceTransazione'] = $this->request->post('txn_id','','sanitizeAll');
			$clean['amount'] = $this->request->post('mc_gross','0','none');
			
			$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => $clean['cart_uid'],"stato"=>"pending"))->send();

			if (count($res) > 0)
			{
				if (strcmp($clean['amount'],$res[0]["orders"]["total"]) === 0 )
				{
					$this->model("FattureModel");
					
					$ordine = $res[0]["orders"];
					
					$statoPagato = $this->getStatoOrdinePagato($ordine);
					
					$this->m("OrdiniModel")->values = array();
					$this->m("OrdiniModel")->values["txn_id"] = $clean['codiceTransazione'];
					if (strcmp($clean['payment_status'],"Completed") === 0)
						$this->m("OrdiniModel")->values["stato"] = $statoPagato;
					$this->m("OrdiniModel")->update((int)$res[0]["orders"]["id_o"]);
					
					if (strcmp($clean['payment_status'],"Completed") === 0)
					{
						if (ImpostazioniModel::$valori["manda_mail_fattura_in_automatico"] == "Y")
						{
							//genera la fattura
							$this->m("FattureModel")->crea($ordine["id_o"]);
						}
					}
					
					switch ($clean['payment_status'])
					{
						case "Completed":
							
							$this->mandaMailDopoPagamento($ordine);
							
							$mandaFattura = false;
							
							if (ImpostazioniModel::$valori["manda_mail_fattura_in_automatico"] == "Y")
							{
								$fattura = $this->m("FattureModel")->where(array(
									"id_o"	=>	$ordine["id_o"]
								))->record();
								
								if (!empty($fattura) && file_exists(ROOT."/admin/media/Fatture/".$fattura["filename"]))
									$mandaFattura = true;
							}
							
							if (v("manda_mail_avvenuto_pagamento_al_cliente"))
								$this->m("OrdiniModel")->mandaMailGeneric($ordine["id_o"], v("oggetto_ordine_pagato"), "mail-$statoPagato", "P", $mandaFattura);
							
							$Subject  = v("oggetto_ordine_pagato");
							$output = "Il pagamento dell'ordine #".$ordine["id_o"]." è andato a buon fine. <br />";
							break;
						case "Pending":
							$Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine [ID_ORDINE]";
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						case "Denied":
							$Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: [ID_ORDINE]";
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						case "Failed":
							$Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: [ID_ORDINE]";
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
							break;
						default:
							$Subject  = "[".Parametri::$nomeNegozio."] Errore nella transazione del pagamento tramite PayPal Nº Ordine: [ID_ORDINE]";
							$output = "Si è verificato un errore nella transazione del pagamento dell'ordine #".$ordine["id_o"]."<br />";
							$output .= "Di seguito i dettagli della transazione:<br /><br />";
							$output .= $p->ipn_status;
					}
					
					$res = MailordiniModel::inviaMail(array(
						"emails"	=>	array(Parametri::$mailInvioOrdine),
						"oggetto"	=>	$Subject,
						"testo"		=>	$output,
						"tipologia"	=>	"ORDINE NEGOZIO",
						"id_o"		=>	$ordine["id_o"],
						"tipo"		=>	"P",
						"id_user"	=>	$ordine["id_user"],
						"array_variabili"	=>	$ordine,
						"lingua"	=>	v("lingua_default_frontend"),
					));
				}
			}
		}
		else
		{
			MailordiniModel::inviaMailLog("ERRORE IPN", "<pre>".$p->log_ipn_results(false, false)."</pre>", "IPN");
		}
		
		// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
		header("HTTP/1.1 200 OK");
	}
	
	protected function getStatoOrdinePagato($ordine)
	{
		$stato = "completed";
		
		if (v("lega_lo_stato_ordine_a_corriere") && $ordine["id_corriere"])
		{
			$cModel = new CorrieriModel();
			
			$statoCorriere = $cModel->clear()->whereId((int)$ordine["id_corriere"])->field("stato_ordine");
			
			if ($statoCorriere)
				$stato = $statoCorriere;
		}
		
		return $stato;
	}
	
	//IPN carta
	public function ipncarta()
	{
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/.ipncarta.txt', 'a+');
		fwrite($fp, date("Y-m-d H:i:s"));
		fwrite($fp, print_r($_GET,true));
		fwrite($fp, print_r($_POST,true));
		fclose($fp);
		
		$this->clean();
		
		if (PagamentiModel::gateway()->validate())
		{
			$clean['cart_uid'] = $this->request->get('cart_uid','','sanitizeAll');
			
			$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => $clean['cart_uid'],"stato"=>"pending"))->send();

			if (count($res) > 0)
			{
				if (PagamentiModel::gateway($res[0]["orders"], true)->checkOrdine())
				{
					$this->model("FattureModel");
					
					$ordine = $res[0]["orders"];
					$this->m("OrdiniModel")->values = array();
					$this->m("OrdiniModel")->values["data_pagamento"] = date("d-m-Y");
					
					$statoPagato = $this->getStatoOrdinePagato($ordine);
					
					if (PagamentiModel::gateway()->success())
						$this->m("OrdiniModel")->values["stato"] = $statoPagato;
					
					$this->m("OrdiniModel")->update((int)$res[0]["orders"]["id_o"]);
					
					if (PagamentiModel::gateway()->success())
					{
						$this->mandaMailDopoPagamento($ordine);
						
						$mandaFattura = false;
						
						if (ImpostazioniModel::$valori["manda_mail_fattura_in_automatico"] == "Y")
						{
							$mandaFattura = true;
							//genera la fattura
							$this->m("FattureModel")->crea($ordine["id_o"]);
						}
						
						if (v("manda_mail_avvenuto_pagamento_al_cliente"))
							$this->m("OrdiniModel")->mandaMailGeneric($ordine["id_o"], v("oggetto_ordine_pagato"), "mail-$statoPagato", "P", $mandaFattura);
						
						$output = "Il pagamento dell'ordine #".$ordine["id_o"]." è andato a buon fine. <br />";
						
						$res = MailordiniModel::inviaMail(array(
							"emails"	=>	array(Parametri::$mailInvioOrdine),
							"oggetto"	=>	v("oggetto_ordine_pagato"),
							"testo"		=>	$output,
							"tipologia"	=>	"ORDINE NEGOZIO",
							"id_o"		=>	$ordine["id_o"],
							"tipo"		=>	"P",
							"id_user"	=>	$ordine["id_user"],
							"array_variabili"	=>	$ordine,
							"lingua"	=>	v("lingua_default_frontend"),
						));
					}
				}
				else
				{
					MailordiniModel::inviaMailLog("ERRORE PAGAMENTO DIVERSO DA ORDINE", "Discrepanza nel dovuto:<br />Dovuto: ".number_format($res[0]["orders"]["total"],2,",","")." Euro<br />Pagato: ".number_format(PagamentiModel::gateway()->amountPagato(), 2, ",", "")." Euro", "Ordine N.".$res[0]["orders"]["id_o"]);
				}
			}
		}
		else
		{
			MailordiniModel::inviaMailLog("ERRORE IPN", "<pre>".PagamentiModel::gateway()->scriviLog(false, false)."</pre>", "IPN CARTA");
		}
	}
	
	public function modifica($id_o = 0, $cart_uid = 0)
	{
		$this->s['registered']->check(null,0);
		
		$data['notice'] = null;
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Modifica resoconto ordine"));
		
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["id_o"] = (int)$id_o;
		
		if (!$this->m("OrdiniModel")->recordExists($clean["id_o"], $clean["cart_uid"]))
			$this->redirect("carrello");
		
		$data["tendinaIndirizzi"] = $this->m("RegusersModel")->getTendinaIndirizzi(User::$id);
		
		$this->m('OrdiniModel')->addStrongCondition("update",'checkIsStrings|'.v("pagamenti_permessi"),"pagamento|".gtext("<b>Si prega di selezionare il pagamento</b>")."<div class='evidenzia'>class_pagamento</div>");
		
		$this->m('OrdiniModel')->addStrongCondition("update",'checkIsStrings|'.implode(",",array_keys($data["tendinaIndirizzi"])),"id_spedizione|".gtext("<b>Si prega di selezionare l'indirizzo</b>")."<div class='evidenzia'>class_id_spedizione</div>");
		
		$this->m('OrdiniModel')->setFields("pagamento,id_spedizione",'sanitizeAll');
		$this->m('OrdiniModel')->updateTable('update',$clean["id_o"]);
		$data['notice'] = $this->m('OrdiniModel')->notice;
		
		if ($this->m('OrdiniModel')->queryResult)
		{
			$result = false;
			
			if (isset($_POST["id_spedizione"]) && in_array($_POST["id_spedizione"], array_keys($data["tendinaIndirizzi"])))
				$result = $this->m('OrdiniModel')->importaSpedizione($clean["id_o"], $_POST["id_spedizione"]);
			
			if ($result)
				$this->redirect("resoconto-acquisto/".$clean["id_o"]."/".$clean["cart_uid"]."?n=y");
			else
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, non è stato possibile cambiare i dati di spedizione. Contattare il negozio")."</div>";
		}
		
		$res = $this->m("OrdiniModel")->clear()
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
		$data["isAreaRiservata"] = true;
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Resoconto ordine"));
		
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["admin_token"] = $data["admin_token"] = sanitizeAll($admin_token);
		$clean["id_o"] = (int)$id_o;
		
		if (!$this->m("OrdiniModel")->recordExists($clean["id_o"], $clean["cart_uid"]))
			$this->redirect("");
		
		$rightAdminToken = $this->m("OrdiniModel")->getAdminToken($clean["id_o"], $clean["cart_uid"]);
		
		if (isset($_POST["modifica_stato_ordine"]) and strcmp($clean["admin_token"],$rightAdminToken) === 0)
		{
			$clean['stato'] = $this->request->post("stato","pending","sanitizeAll");
			$statiPermessi = array("pending","deleted","completed");
			
			if (in_array($clean['stato'],$statiPermessi))
			{
				$this->m("OrdiniModel")->values = array("stato" => $clean['stato']);
				$this->m("OrdiniModel")->update(null, "id_o=".$clean["id_o"]." and cart_uid='".$clean["cart_uid"]."'");
				$data['notice'] = $this->m("OrdiniModel")->notice;
			}
		}
		
		$res = $this->m("OrdiniModel")->clear()
							->where(array("id_o" => $clean["id_o"], "cart_uid" => $clean["cart_uid"] ))
							->send();
		
		$data["righeOrdine"] = $this->m("RigheModel")->clear()->where(array("id_o"=>$clean["id_o"],"cart_uid" => $clean["cart_uid"]))->send();
		
		$data["ordine"] = $res[0]["orders"];
		
		// ID ordine per GTM e FBK
		if ($data["ordine"]["tipo_ordine"] == "W" && (!OrdiniModel::conPagamentoOnline($data["ordine"]) || OrdiniModel::isPagato($clean["id_o"])))
			$data['idOrdineGtm'] = (int)$id_o;
		
		$data["tipoOutput"] = "web";
		
		if (isset($_GET["to_paypal"]) && PagamentiModel::gateway($data["ordine"], true, "paypal")->isPaypalCheckout())
			unset($_GET["to_paypal"]);
		
		if (strcmp($data["ordine"]["pagamento"],"paypal") === 0 and strcmp($data["ordine"]["stato"],"pending") === 0)
		{
			if (PagamentiModel::gateway($data["ordine"], true, "paypal")->isPaypalCheckout())
			{
				$data["pulsantePaypal"] = PagamentiModel::gateway($data["ordine"], false, "paypal")->getPulsantePaga();
			}
			else
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
				
				$p->add_field('return', $this->baseUrl."/grazie-per-l-acquisto?cart_uid=".$clean["cart_uid"]);
				$p->add_field('cancel_return', $this->baseUrl."/ordini/annullapagamento/paypal/".$clean["cart_uid"]);
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
				$p->add_field('night_phone_b', $data["ordine"]["telefono"]);
				
				if ($data["ordine"]["nazione"] == "IT")
					$p->add_field('state', $data["ordine"]["provincia"]);
				else
					$p->add_field('state', $data["ordine"]["dprovincia"]);
				
				$p->add_field('cmd', '_xclick');
				$p->add_field('rm', '2');   // Return method = POST
				
				$data["pulsantePaypal"] = $p->paypal_button();
			}
		}
		else if (strcmp($data["ordine"]["pagamento"],"carta_di_credito") === 0 and strcmp($data["ordine"]["stato"],"pending") === 0)
		{
			$urlPagamento = PagamentiModel::gateway($data["ordine"])->getUrlPagamento();
			
			if (isset($urlPagamento))
			{
				$data["pulsantePaga"] = PagamentiModel::gateway()->getPulsantePaga();
				$data["urlPagamento"] = $urlPagamento;
				
				if (isset($_GET["to_paypal"]) && strcmp($data["ordine"]["stato"],"pending") === 0 && strcmp($data["tipoOutput"],"web") === 0 && PagamentiModel::gateway()->redirect())
				{
					header('Location: '.$urlPagamento);
					die();
				}
			}
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
	
	private function infoordine($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$ordine = $this->m("OrdiniModel")->selectId($clean["id_o"]);
		
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
			
			$pages = $this->m("RigheModel")->clear()->where(array("id_o"=>$clean["id_o"]))->send(false);
			
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
		App::createLogFolder();
		
// 		if(!is_dir(ROOT.'/Logs'))
// 		{
// 			if (@mkdir(ROOT.'/Logs'))
// 			{
// 				$fp = fopen(ROOT.'/Logs/index.html', 'w');
// 				fclose($fp);
// 				
// 				$fp = fopen(ROOT.'/Logs/.htaccess', 'w');
// 				fwrite($fp, 'deny from all');
// 				fclose($fp);
// 			}
// 		}
	}
	
	public function annullapagamento($tipo = "", $cartuUid = "")
	{
		$this->clean();
		
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/error_pagamento.txt', 'a+');
		
		if ($fp)
		{
			fwrite($fp, date("Y-m-d H:i:s"));
			fwrite($fp, "\nTIPO:".sanitizeHtml($tipo)."\n");
			fwrite($fp, print_r($_GET,true));
			fwrite($fp, print_r($_POST,true));
			fclose($fp);
		}
		
		$clean['cart_uid'] = sanitizeAll($cartuUid);
		
		$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => $clean['cart_uid']))->send();
		
		if (count($res) > 0 && $res[0]["orders"]["stato"] == "pending")
		{
			$data["ordine"] = $res[0]["orders"];
			
			$codiceTransazione = $this->m("OrdiniModel")->getUniqueCodTrans(generateString(30));
			
			$this->m("OrdiniModel")->setValues(array(
				"codice_transazione"	=>	$codiceTransazione,
			));
			
// 			$this->m("OrdiniModel")->pUpdate($data["ordine"]["id_o"]);
			
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array(Parametri::$mailInvioOrdine),
				"oggetto"	=>	"Pagamento ordine ".$data["ordine"]["id_o"]." annullato",
				"testo"		=>	"Il pagamento dell'ordine ".$data["ordine"]["id_o"]." è stato annullato",
				"tipologia"	=>	"PAGAMENTO ANNULLATO",
				"id_user"	=>	(int)$data["ordine"]['id_user'],
				"id_o"		=>	$data["ordine"]["id_o"],
			));
		}
		
		$this->redirect("");
	}
	
	public function ritornodapaypal()
	{
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/back_paypal.txt', 'a+');
		fwrite($fp, date("Y-m-d H:i:s"));
		fwrite($fp, print_r($_GET,true));
		fwrite($fp, print_r($_POST,true));
		fclose($fp);
		
		VariabiliModel::noCookieAlert();
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Grazie per l'acquisto"));
		
		if (isset($_GET["item_number"]) || isset($_GET["tx"]))
		{
			$clean['cart_uid'] = $this->request->get('item_number','','sanitizeAll');
			$clean['txn_id'] = $this->request->get('tx','','sanitizeAll');
			$clean['st'] = $this->request->get('st','','sanitizeAll');
			
			if (isset($_GET["txn_id"]))
				$clean['txn_id'] = $this->request->get('txn_id','','sanitizeAll');
			
			if (isset($_GET["payment_status"]))
				$clean['st'] = $this->request->get('payment_status','','sanitizeAll');
		}
		else
		{
			$clean['cart_uid'] = $this->request->post('item_number','','sanitizeAll');
			$clean['txn_id'] = $this->request->post('tx','','sanitizeAll');
			$clean['st'] = $this->request->post('st','','sanitizeAll');
			
			if (isset($_POST["txn_id"]))
				$clean['txn_id'] = $this->request->post('txn_id','','sanitizeAll');
			
			if (isset($_POST["payment_status"]))
				$clean['st'] = $this->request->post('payment_status','','sanitizeAll');
		}
		
		if (isset($_GET['cart_uid']))
			$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => sanitizeAll($_GET['cart_uid'])))->send();
		else
			$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => $clean['cart_uid']))->send();
		
		$data["conclusa"] = false;
		
		if (strcmp($clean['st'],"Completed") === 0)
			$data["conclusa"] = true;
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["orders"]["stato"],"deleted") === 0)
				$this->redirect("");
			
			$data["ordine"] = $res[0]["orders"];
			
			if ($data["ordine"]["cookie_terzi"])
			{
				F::settaCookiesGdpr(true);
				VariabiliModel::ottieniVariabili();
			}
			
			$data['idOrdineGtm'] = (int)$data["ordine"]["id_o"];
		}
		
		$this->append($data);
		$this->load("ritorno-da-paypal");
	}
	
	public function ritornodacarta()
	{
		$this->createLogFolder();
		
		$fp = fopen(ROOT.'/Logs/back_carta.txt', 'a+');
		fwrite($fp, date("Y-m-d H:i:s"));
		fwrite($fp, print_r($_GET,true));
		fwrite($fp, print_r($_POST,true));
		fclose($fp);
		
		VariabiliModel::noCookieAlert();
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Grazie per l'acquisto"));
		
		PagamentiModel::gateway()->validate(false);
		
		if (isset($_GET["cart_uid"]))
		{
			$clean['cart_uid'] = $this->request->get('cart_uid','','sanitizeAll');
			
			$res = $this->m("OrdiniModel")->clear()->where(array("cart_uid" => $clean['cart_uid']))->send();
			
			$data["conclusa"] = false;
		
			if (count($res) > 0)
			{
				if (strcmp($res[0]["orders"]["stato"],"deleted") === 0)
					$this->redirect("");
				
				if (PagamentiModel::gateway($res[0]["orders"], true)->validate(false))
					$data["conclusa"] = true;
				
				$data["ordine"] = $res[0]["orders"];
				
				if ($data["ordine"]["cookie_terzi"])
				{
					F::settaCookiesGdpr(true);
					VariabiliModel::ottieniVariabili();
				}
				
				$data['idOrdineGtm'] = (int)$data["ordine"]["id_o"];
			}
			else
				$this->redirect("");
		}
		
		$this->append($data);
		$this->load("ritorno-da-paypal");
	}
	
	public function checklogin()
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		if (User::$logged)
			$this->redirect("checkout");
		
		if( !session_id() )
			session_start();
		
		$data['title'] = Parametri::$nomeNegozio . ' - Checkout';
		
		$data["pages"] = $this->m("CartModel")->getProdotti();
		
		if (count($data["pages"]) === 0)
		{
			if (Output::$html)
				$this->redirect("carrello/vedi");
		}
		
		$this->checkCheckout();
		
		$this->getAppLogin();
		$this->load("autenticazione");
	}
	
	private function checkCheckout()
	{
		if (!$this->m("CartModel")->checkQtaFull() || (CartModel::numeroGifCartInCarrello() > v("numero_massimo_gift_card")) || CartelementiModel::haErrori())
		{
			if (Output::$html)
				$this->redirect("carrello/vedi?evidenzia");
		}
	}
	
	public function index()
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		if (v("checkout_solo_loggato") && !User::$logged)
			$this->redirect("regusers/login?redirect=/checkout");
		
		$this->m('OrdiniModel')->checkNumeroOrdini();
		
		$logSubmit = new LogModel();
		
		if( !session_id() )
			session_start();
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext('Checkout'));
		
		$data['customHeaderClass'] = "";
// 		$data["inlineCssFile"] = "auros-css-inline-category.css";
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$this->m("CartModel")->salvaDisponibilitaCarrello();
		
		$data["pages"] = $this->m("CartModel")->getProdotti();
		
		$numeroProdottiInCarrello = $this->m("CartModel")->numberOfItems();
		
		$logSubmit->setNumeroProdotti($numeroProdottiInCarrello);
		
		if (count($data["pages"]) === 0)
		{
			if (Output::$html)
				$this->redirect("carrello/vedi");
		}
		
		$this->checkCheckout();
		
		$this->getAppLogin();
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
		$codiciSpedizioneAttivi = $this->m("NazioniModel")->selectCodiciAttiviSpedizione();
		
		$elencoCorrieri = $this->m("CorrieriModel")->elencoCorrieri();
		
		//imposto spedizione uguale a fatturazione
		if ((isset($_POST["spedisci_dati_fatturazione"]) && strcmp($_POST["spedisci_dati_fatturazione"],"Y") === 0) || !v("attiva_spedizione"))
		{
			$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
			
			foreach ($campiSpedizione as $cs)
			{
				$_POST[$cs] = $this->request->post(str_replace("_spedizione","",$cs),"","none");
			}
			
			if (!in_array($_POST["nazione_spedizione"], $codiciSpedizioneAttivi))
				$_POST["spedisci_dati_fatturazione"] = "N";
			
			$_POST["id_spedizione"] = 0;
		}
		
		if (ListeregaloModel::hasIdLista())
			$_POST["regalo"] = 1;
		
		// Setta password
		$this->m("RegusersModel")->settaPassword();
		
		IvaModel::getAliquotaEstera();
		
		if (isset(IvaModel::$aliquotaEstera))
			$data["pages"] = $this->m("CartModel")->getProdotti();
		
		$descrizioneAcquisto = serialize($data["pages"]);
		
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
		
		$campoTelefono = $campoTelefonoSpedizione = "";
		
		if (v("insert_ordine_telefono_obbligatorio"))
		{
			$campoTelefono .= "telefono,";
			$campoTelefonoSpedizione .= "telefono_spedizione,";
		}
		
// 		$campiObbligatoriComuni = "indirizzo,$campoObbligatoriProvincia,citta,".$campoTelefono."email,".$campoConfermaEmail."pagamento,accetto,tipo_cliente,indirizzo_spedizione,$campoObbligatoriProvinciaSpedizione,citta_spedizione,".$campoTelefonoSpedizione."nazione,nazione_spedizione,cap,cap_spedizione";
		
		$campiObbligatoriComuni = $campoTelefono."email,".$campoConfermaEmail.$campoTelefonoSpedizione."pagamento,accetto,tipo_cliente";
		
		if (!CartModel::soloProdottiSenzaSpedizione(null, true, false))
			$campiObbligatoriComuni .= ",nazione,indirizzo,$campoObbligatoriProvincia,cap,citta,nazione_spedizione,indirizzo_spedizione,$campoObbligatoriProvinciaSpedizione,cap_spedizione,citta_spedizione";
		
		if ($this->campoObbligatorio("codice_fiscale"))
			$campiObbligatoriComuni .= ",codice_fiscale";
		
		if (ListeregaloModel::hasIdLista())
			$campiObbligatoriComuni .= ",dedica,firma";
		
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
		
		$campiObbligatoriComuni .= $campiObbligatoriAggiuntivi;
		
		$campoPIva = "";
		
		if (isset($_POST["nazione"]) && in_array($_POST["nazione"], NazioniModel::elencoNazioniConVat()))
			$campoPIva = "p_iva,";
		
		if (strcmp($tipo_cliente,"privato") === 0)
		{
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkNotEmpty',"nome,cognome,".$campiObbligatoriComuni);
		}
		else if (strcmp($tipo_cliente,"libero_professionista") === 0)
		{
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkNotEmpty',"nome,cognome,$campoPIva".$campiObbligatoriComuni);
		}
		else
		{
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkNotEmpty',"ragione_sociale,$campoPIva".$campiObbligatoriComuni);
		}
		
		$this->m('OrdiniModel')->addSoftCondition("both",'checkMail',"pec|".gtext("Si prega di ricontrollare <b>l'indirizzo Pec</b>")."<div class='evidenzia'>class_pec</div>");
		
		$this->m('OrdiniModel')->addSoftCondition("both",'checkLength|7',"codice_destinatario|".gtext("Si prega di ricontrollare <b>il Codice Destinatario</b>")."<div class='evidenzia'>class_codice_destinatario</div>");
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>")."<div class='evidenzia'>class_email</div>");
		
		if (v("account_attiva_conferma_username"))
		{
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkMail',"conferma_email|".gtext("Si prega di ricontrollare il campo <b>conferma dell'indirizzo Email</b>")."<div class='evidenzia'>class_conferma_email</div>");
			
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkEqual',"email,conferma_email|".gtext("<b>I due indirizzi email non corrispondono</b>")."<div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>");
		}
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|accetto',"accetto|".gtext("<b>Si prega di accettare le condizioni di vendita</b>")."<div class='evidenzia'>class_accetto</div>");
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|'.Parametri::$metodiPagamento,"pagamento|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>"));
		
// 		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|privato,azienda,libero_professionista',"tipo_cliente|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>"));
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|'.TipiclientiModel::getListaTipi(),"tipo_cliente|".gtext("<b>Si prega di scegliere la modalità di pagamento</b>"));
		
		$this->m('OrdiniModel')->addSoftCondition("insert",'checkLength|255',"note|<b>".gtext("Le note non possono superare i 255 caratteri")."</b><div class='evidenzia'>class_note</div>");
		
		$this->m('OrdiniModel')->addSoftCondition("insert",'checkLength|255',"indirizzo_spedizione|".gtext("<b>L'indirizzo di spedizione non può superare i 255 caratteri</b>")."<div class='evidenzia'>class_indirizzo_spedizione</div>");
		
		$this->m('OrdiniModel')->addSoftCondition("insert","checkMatch|/^[0-9\s\+]+$/","telefono|".gtext("Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_telefono</div>");
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
// 			$this->m('OrdiniModel')->addStrongCondition("insert","checkMatch|/^[0-9]+$/","cap|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_cap</div>");
			
			if (v("abilita_codice_fiscale"))
				$this->m('OrdiniModel')->addSoftCondition("insert","checkMatch|/^[0-9a-zA-Z]+$/","codice_fiscale|".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>")."<div class='evidenzia'>class_codice_fiscale</div>");
			
			$this->m('OrdiniModel')->addSoftCondition("insert","checkMatch|/^[0-9a-zA-Z]+$/","p_iva|".gtext("Si prega di controllare il campo <b>Partita Iva</b>")."<div class='evidenzia'>class_p_iva</div>");
		}
		
		if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] == "IT")
		{
// 			$this->m('OrdiniModel')->addStrongCondition("insert","checkMatch|/^[0-9]+$/","cap_spedizione|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche")."<div class='evidenzia'>class_cap_spedizione</div>");
		}
		
		$codiciNazioniAttive = implode(",",$this->m("NazioniModel")->selectCodiciAttivi());
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|'.$codiciNazioniAttive,"nazione|".gtext("<b>Si prega di selezionare una nazione tra quelle permesse</b>"));
		
		$codiciNazioniAttiveSpedizione = implode(",",$codiciSpedizioneAttivi);
		
		$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|'.$codiciNazioniAttiveSpedizione,"nazione_spedizione|".gtext("<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>"));
		
		if (v("attiva_spedizione") && isset($_POST["nazione_spedizione"]) && count($elencoCorrieri) > 0)
		{
			$listaCorrieriNazione = implode(",",$this->m("CorrieriModel")->getIdsCorrieriNazione($_POST["nazione_spedizione"]));
			
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|'.$listaCorrieriNazione,"id_corriere|".gtext("<b>Non è possibile spedire nella nazione selezionata</b>"));
		}
		
		$fields = OpzioniModel::stringaValori("CAMPI_SALVATAGGIO_ORDINE");
		//'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,dprovincia,citta,telefono,email,pagamento,accetto,tipo_cliente,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,citta_spedizione,telefono_spedizione,aggiungi_nuovo_indirizzo,id_spedizione,id_corriere,nazione,nazione_spedizione,pec,codice_destinatario,note';
		
		if (!$this->islogged)
		{
			if (!v("permetti_acquisto_anonimo"))
				$_POST["registrato"] = "Y";
			
			$_POST["aggiungi_nuovo_indirizzo"] = "N";
			$_POST["id_spedizione"] = 0;
			
			$this->m('OrdiniModel')->addStrongCondition("insert",'checkIsStrings|Y,N',"registrato|<b>".gtext("Si prega di indicare se volete continuare come utente ospite oppure creare un account", false)."</b><div class='evidenzia'>class_registrato</div>");
			
			$fields .= ",registrato";
			
			$registrato = $this->request->post("registrato","","sanitizeAll");
			
			if (strcmp($registrato, "Y") === 0)
			{
				$this->m('RegusersModel')->setFields("username",'sanitizeAll');
				
				$clean["username"] = $this->request->post("email","","sanitizeAll");
				$this->m('RegusersModel')->values["username"] = $clean["username"];
				
				$alertAnonimo = v("permetti_acquisto_anonimo") ? gtext("oppure decidere di completare l'acquisto come utente ospite.", false) : "";
				
				ob_start();
				include(tpf(ElementitemaModel::p("ERRORE_UTENTE_PRESENTE","", array(
					"titolo"	=>	"Messaggio di errore quando l'utente è già presente",
					"percorso"	=>	"Elementi/FormRegistrazioneCheckout/UtenteGiaPresente",
				))));
				$erroreUtenteGiaPresente = ob_get_clean();
				
				if (isset($_POST["email"]) && RegusersModel::utenteDaConfermare($_POST["email"]))
					$this->m('RegusersModel')->databaseConditions['insert'] = array(
						"checkUnique"		=>	"username|".gtext("Il suo account è già presente nel nostro sistema ma non è attivo perché non è mai stata completata la verifica dell'indirizzo e-mail.",false)."<br />".gtext("Può procedere con la conferma del proprio account al seguente",false)." <a href='".Url::getRoot()."account-verification'>".gtext("indirizzo web", false)."</a> ".$alertAnonimo."<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>",
					);
				else
					$this->m('RegusersModel')->databaseConditions['insert'] = array(
						"checkUnique"		=>	"username|".$erroreUtenteGiaPresente,
					);
				
				if (v("account_attiva_conferma_password"))
					$this->m('RegusersModel')->addStrongCondition("insert",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b><span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>");
				else
					$this->m('RegusersModel')->addStrongCondition("insert",'checkNotEmpty',"password");
				
				$this->m('RegusersModel')->addStrongCondition("insert",'checkMatch|/^[a-zA-Z0-9\_\-\!\,\.]+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>Tutte le lettere, maiuscole o minuscole (a, A, b, B, ...)</li><li>Tutti i numeri (0,1,2,...)</li><li>I seguenti caratteri: <b>_ - ! , .</b></li></ul><span class='evidenzia'>class_password</span>");
			}
		}
		
		$this->m('OrdiniModel')->setFields($fields,'strip_tags');

		$data['notice'] = null;

		Output::setBodyValue("Risultato", "KO");
		
		$utenteRegistrato = false;
		
		if ($this->islogged)
			$utenteRegistrato = true;
		
		$erroriInvioOrdine = array();
		
		if (isset($_POST['invia']))
		{
			RegusersModel::checkEdEliminaAccount();
			
			if (v("disattiva_antispam_checkout") || CaptchaModel::getModulo()->checkRegistrazione())
			{
				if ($this->m('OrdiniModel')->checkConditions('insert'))
				{
					if ($this->m('RegusersModel')->checkConditions('insert'))
					{
						$_SESSION = $_POST;
						$_SESSION["email_carrello"] = sanitizeAll($this->m('OrdiniModel')->values["email"]);
						
						unset($_SESSION['accetto']);
						
						$this->m('OrdiniModel')->aggiungiTotali();
						
						$statoOrdine = "pending";
						
						if (number_format(getTotalN(),2,".","") <= 0.00)
							$statoOrdine = "completed";
						
						$statoOrdine = $this->getStatoOrdineFrontend($statoOrdine);
						
						$this->m('OrdiniModel')->values["stato"] = $statoOrdine;
						
						if (isset($_COOKIE["ok_cookie_terzi"]))
							$this->m('OrdiniModel')->values["cookie_terzi"] = 1;
						
						if (ListeregaloModel::hasIdLista())
							$this->m('OrdiniModel')->values["id_lista_regalo"] = (int)User::$idLista;
						
						$this->m('OrdiniModel')->sanitize("sanitizeHtml");
						$this->m('OrdiniModel')->values["descrizione_acquisto"] = $descrizioneAcquisto;
						$this->m('OrdiniModel')->sanitize();
						
						if ($this->m('OrdiniModel')->insert())
						{
							Output::setBodyValue("Risultato", "OK");
							
							// Salvo la dprovincia
							$dprovincia = $this->m('OrdiniModel')->values["dprovincia"];
							
							// Salvo la dprovincia spedizione
							$dprovincia_spedizione = $this->m('OrdiniModel')->values["dprovincia_spedizione"];
							
							$clean['lastId'] = (int)$this->m('OrdiniModel')->lId;
							
							Output::setBodyValue("IdOrdine", $clean['lastId']);
							
							//riempie la tabella delle righe
							$this->m('OrdiniModel')->riempiRighe($clean['lastId']);
							
							$this->m("CartModel")->del(null, array(
								"cart_uid"	=>	$clean["cart_uid"],
							));
							setcookie("cart_uid", "", time()-3600,"/");
							
							//distruggi il cookie del coupon
							if (hasActiveCoupon($clean['lastId']))
							{
								setcookie("coupon", "", time()-3600,"/");
							}
							
							// elimina il cookie con l'ID della lista regalo
							ListeregaloModel::unsetCookieIdLista();
							
							$clean["cart_uid"] = sanitizeAll($this->m('OrdiniModel')->cart_uid);
							
							Output::setBodyValue("CartUid", $clean['cart_uid']);
							
							$res = $this->m("OrdiniModel")->clear()
								->where(array("id_o" => $clean['lastId'], "cart_uid" => $clean["cart_uid"] ))
								->send();
							
							$ordine = $res[0]["orders"];
							
							//se la password deve essere mandata via mail
							$sendPassword = false;
							
							if (!$this->islogged)
							{
								//registra utente
								if (strcmp($registrato, "Y") === 0)
								{
									$sendPassword = true;
									
									$password = $this->request->post("password","","none");
									$clean["password"] = sanitizeAll(call_user_func(PASSWORD_HASH,$password));
									
									$this->m('RegusersModel')->values["password"] = $clean["password"];
									
									$campiDaCopiare = OpzioniModel::arrayValori("CAMPI_DA_COPIARE_DA_ORDINE_A_CLIENTE");
									
									foreach ($campiDaCopiare as $cdc)
									{
										$this->m('RegusersModel')->values[$cdc] = $this->m('OrdiniModel')->values[$cdc];
									}
									
									if (v("mail_credenziali_dopo_pagamento") && OrdiniModel::conPagamentoOnline($ordine) && (number_format($ordine["total"],2,".","") > 0.00))
										$this->m('RegusersModel')->values["credenziali_inviate"] = 0;
									
									if ($this->m('RegusersModel')->insert())
									{
										$clean['userId'] = (int)$this->m('RegusersModel')->lastId();
										
										// Controllo che sia attiva la spedizione
										if (v("attiva_spedizione"))
										{
											//aggiungo l'indirizzo di spedizione
											$this->m("SpedizioniModel")->setValues(array(
												"id_user"	=>	$clean['userId'],
												"ultimo_usato"	=>	"Y",
											));
											
											$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
											
											foreach ($campiSpedizione as $cs)
											{
												$this->m('SpedizioniModel')->setValue($cs, $_POST[$cs]);
											}
											
											$this->m("SpedizioniModel")->insert();
											
											$idSpedizione = $this->m("SpedizioniModel")->lId;
										}
										else
											$idSpedizione = 0;
										
										$this->m('OrdiniModel')->values = array(
											"id_user" => $clean['userId'],
											"registrato" => "Y",
											"id_spedizione" => $idSpedizione,
											"aggiungi_nuovo_indirizzo"	=>	"Y",
										);
										
										$this->m('OrdiniModel')->update($clean['lastId']);
										
										// MAIL AL CLIENTE
										if (!v("mail_credenziali_dopo_pagamento") || !OrdiniModel::conPagamentoOnline($ordine) || (number_format($ordine["total"],2,".","") <= 0.00))
											$res = MailordiniModel::inviaCredenziali($clean['userId'], array(
												"username"	=>	$clean["username"],
												"password"	=>	$password,
											));
										
										//loggo l'utente
										$this->s['registered']->login($clean["username"],$password);
										
										$utenteRegistrato = true;
									}
								}
							}
							else
							{
								$nuovoIndirizzo = $this->request->post("aggiungi_nuovo_indirizzo","Y","sanitizeAll");
								
								// Controllo che sia attiva la spedizione
								if (v("attiva_spedizione"))
								{
									$idSpedizione = $this->request->post("id_spedizione","0","forceInt");
									
									if (strcmp($nuovoIndirizzo,"Y") === 0)
									{
										$this->m("SpedizioniModel")->query(array("update spedizioni set ultimo_usato = 'N' where id_user = ?",array((int)User::$id)));
										
										$this->m("SpedizioniModel")->setValues(array(
											"id_user"	=>	$this->iduser,
											"ultimo_usato"	=>	"Y",
										));
										
										$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
										
										foreach ($campiSpedizione as $cs)
										{
											$this->m('SpedizioniModel')->setValue($cs, $_POST[$cs]);
										}
										
										if ($this->m("SpedizioniModel")->insert())
											$idSpedizione = $this->m("SpedizioniModel")->lId;
									}
									else if ($idSpedizione > 0)
									{
										$this->m("SpedizioniModel")->query(array("update spedizioni set ultimo_usato = 'N' where id_user = ?",array((int)User::$id)));
										$this->m("SpedizioniModel")->query(array("update spedizioni set ultimo_usato = 'Y' where id_spedizione = ? and id_user = ?",array((int)$idSpedizione, (int)User::$id)));
									}
								}
								else
									$idSpedizione = 0;
								
								//segna da che utente è stato eseguito l'ordine
								$this->m('OrdiniModel')->values = array(
									"id_user" => $this->iduser,
									"registrato" => "Y",
									"id_spedizione" => $idSpedizione,
								);
								$this->m('OrdiniModel')->update($clean['lastId']);
								
								if (v("aggiorna_sempre_i_dati_del_cliente_al_checkout") || !$this->m('RegusersModel')->isCompleto(User::$id))
									$this->m('RegusersModel')->sincronizzaDaOrdine(User::$id, $clean['lastId']);
							}

							// Se è nazione estera, salvo la dprovincia in provincia
                            if (isset($_POST["nazione"]) && $_POST["nazione"] != "IT")
                            {
								$this->m('OrdiniModel')->values = array(
                                    "provincia" => $dprovincia,
                                );
                                $this->m('OrdiniModel')->update($clean['lastId']);
                            }
                            
                            // Se è nazione estera, salvo la dprovincia_spedizione in provincia_spedizione
                            if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] != "IT")
                            {
								$this->m('OrdiniModel')->values = array(
                                    "provincia_spedizione" => $dprovincia_spedizione,
                                );
                                $this->m('OrdiniModel')->update($clean['lastId']);
                            }
                            
                            // Estraggo nuovamente l'ordine
                            $res = $this->m("OrdiniModel")->clear()
                                ->where(array("id_o" => $clean['lastId']))
                                ->send();

                            $ordine = $res[0]["orders"];
                            
// 							$mail->WordWrap = 70;
							
							$righeOrdine = $this->m("RigheModel")->clear()->where(array("id_o"=>$clean['lastId'],"cart_uid" => $clean["cart_uid"]))->send();
							
							// hook chiamato quando l'ordine è stato confermato
							$this->m("OrdiniModel")->triggersOrdine($clean['lastId']);
							
							// hook ordine confermato
							if (v("hook_ordine_confermato") && function_exists(v("hook_ordine_confermato")))
								call_user_func(v("hook_ordine_confermato"), $clean['lastId']);
							
							// mail al cliente
							if (!v("mail_ordine_dopo_pagamento") || !$utenteRegistrato || !OrdiniModel::conPagamentoOnline($ordine) || (number_format($ordine["total"],2,".","") <= 0.00))
							{
								ob_start();
								$tipoOutput = "mail_al_cliente";
								if (file_exists(tpf("/Elementi/Mail/mail_ordine_ricevuto.php")))
									include tpf("/Elementi/Mail/mail_ordine_ricevuto.php");
								else
									include tpf("/Ordini/resoconto-acquisto.php");
								$output = ob_get_clean();
								
								$res = MailordiniModel::inviaMail(array(
									"emails"	=>	array($ordine["email"]),
									"oggetto"	=>	v("oggetto_ordine_ricevuto"),
									"testo"		=>	$output,
									"tipologia"	=>	"ORDINE",
									"id_user"	=>	(int)$ordine['id_user'],
									"tipo"		=>	"R",
									"id_o"		=>	$clean['lastId'],
									"array_variabili"	=>	$ordine,
								));
							}
							else
								$this->m('OrdiniModel')->settaMailDaInviare($clean['lastId']);
							
							// mail al negozio
							if (!v("mail_ordine_dopo_pagamento_negozio") || !OrdiniModel::conPagamentoOnline($ordine) || (number_format($ordine["total"],2,".","") <= 0.00))
							{
								ob_start();
								$tipoOutput = "mail_al_negozio";
								ElementitemaModel::getPercorsi();
								ElementitemaModel::$percorsi["RESOCONTO_PRODOTTI"]["nome_file"] = "default";
								include tpf("/Ordini/resoconto-acquisto.php");
								$output = ob_get_clean();
								
								$res = MailordiniModel::inviaMail(array(
									"emails"	=>	array(Parametri::$mailInvioOrdine),
									"oggetto"	=>	v("oggetto_ordine_ricevuto"),
									"testo"		=>	$output,
									"tipologia"	=>	"ORDINE NEGOZIO",
									"id_user"	=>	(int)$ordine['id_user'],
									"tipo"		=>	"R",
									"id_o"		=>	$clean['lastId'],
									"array_variabili"	=>	$ordine,
								));
							}
							else
								$this->m('OrdiniModel')->settaMailDaInviare($clean['lastId'], "mail_da_inviare_negozio");
							
							// mail ad agente
							if (v("attiva_agenti") && $ordine["id_agente"] && v("manda_mail_ordine_ad_agenti"))
							{
								if (!v("mail_ordine_dopo_pagamento_agente") || !OrdiniModel::conPagamentoOnline($ordine) || (number_format($ordine["total"],2,".","") <= 0.00))
									$this->m('OrdiniModel')->mandaMailAdAgente($clean['lastId'], true);
								else
									$this->m('OrdiniModel')->settaMailDaInviare($clean['lastId'], "mail_da_inviare_agente");
							}
							
							// Iscrizione alla newsletter
							if (isset($_POST["newsletter"]) && IntegrazioninewsletterModel::integrazioneAttiva())
							{
								IntegrazioninewsletterModel::getModulo()->iscrivi(IntegrazioninewsletterModel::elaboraDati(htmlentitydecodeDeep($ordine)));
								
								// Inserisco il contatto
								$this->m('ContattiModel')->insertDaArray($ordine, "NEWSLETTER_DA_ORDINE");
							}
							
							// Redirect immediato a gateway
							$toPaypal = (ImpostazioniModel::$valori["redirect_immediato_a_paypal"] == "Y" && OrdiniModel::conPagamentoOnline($ordine)) ? "?to_paypal" : "";
							
							if ($statoOrdine != "pending")
								$toPaypal = "";

							$logSubmit->write(LogModel::LOG_CHECKOUT, LogModel::ORDINE_ESEGUITO);
							
							F::checkPreparedStatement();
							
							if (Output::$html)
								$this->redirect("resoconto-acquisto/".$clean['lastId']."/".$clean["cart_uid"]."/token".$toPaypal);
						}
						else
						{
							$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('OrdiniModel')->notice;
							$this->m('RegusersModel')->result = false;
							
							$erroriInvioOrdine = $this->m('OrdiniModel')->errors;
							
							if (Output::$json)
								Output::setBodyValue("Errori", $this->m('OrdiniModel')->errors);
						}
					}
					else
					{
						$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('RegusersModel')->notice;
						$this->m('OrdiniModel')->result = false;
						
						$erroriInvioOrdine = $this->m('RegusersModel')->errors;
						
						if (Output::$json)
							Output::setBodyValue("Errori", $this->m('RegusersModel')->errors);
					}
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('OrdiniModel')->notice;
					$this->m('RegusersModel')->result = false;
					
					$erroriInvioOrdine = $this->m('OrdiniModel')->errors;
					
					if (Output::$json)
						Output::setBodyValue("Errori", $this->m('OrdiniModel')->errors);
				}
			}
			else
			{
				ob_start();
				include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
				$data['notice'] = ob_get_clean();
				$data['notice'] .= $this->m('OrdiniModel')->notice;
				
				$this->m('RegusersModel')->result = false;
				$this->m('OrdiniModel')->result = false;
				
				$logSubmit->setSpam();
			}
		}
		
		$data["erroriInvioOrdine"] = $erroriInvioOrdine;
		list($data["mostraCampiFatturazione"], $data["mostraCampiSpedizione"], $data["mostraCampiIndirizzoFatturazione"]) = OrdiniModel::analizzaErroriCheckout($erroriInvioOrdine);
		
		$logSubmit->setErroriSubmit($data['notice']);
		$logSubmit->write(LogModel::LOG_CHECKOUT, $data['notice'] ? LogModel::ERRORI_VALIDAZIONE : "");
		
		$this->m('OrdiniModel')->fields = OpzioniModel::stringaValori("CAMPI_FORM_CHECKOUT");
		
		// Elenco corrieri
		if (!v("scegli_il_corriere_dalla_categoria_dei_prodotti"))
			$data['corrieri'] = $elencoCorrieri;
		else
		{
			// cerca il corriere dal carrello
			$idCorriereDaCarrello = $this->m("CorrieriModel")->getIdCorriereDaCarrello();
			
			$data['corrieri'] = $idCorriereDaCarrello ? $this->m("CorrieriModel")->whereId((int)$idCorriereDaCarrello)->send(false) : $this->m("CorrieriModel")->elencoCorrieri(true);
		}
		
// 		$defaultValues = $_SESSION;
		$defaultValues = array();
		
		if ($this->islogged)
		{
			$defaultValues = htmlentitydecodeDeep($this->dettagliUtente);
			$defaultValues["email"] = $defaultValues["username"];
			$defaultValues["conferma_email"] = $defaultValues["username"];
			
			$data["tendinaIndirizzi"] = $this->m("RegusersModel")->getTendinaIndirizzi(User::$id);
			$data["elencoIndirizzi"] = $this->m("RegusersModel")->getIndirizziSpedizione(User::$id);
			
			$defaultValues["aggiungi_nuovo_indirizzo"] = "Y";
			
			if (count($data["tendinaIndirizzi"]) > 0)
			{
				$defaultValues["aggiungi_nuovo_indirizzo"] = "N";
				$defaultValues["id_spedizione"] = $this->m("RegusersModel")->getIdSpedizioneUsatoNellUltimoOrdine(User::$id);
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
			
			if (isset($_GET["default_email"]) && $_GET["default_email"] && checkMail($_GET["default_email"]))
				$defaultValues["email"] = sanitizeAll((string)$_GET["default_email"]);
		}
		
		if (isset($defaultValues["accetto"]))
			unset($defaultValues["accetto"]);
		
		if (count($data['corrieri']) > 0)
			$defaultValues["id_corriere"] = $data['corrieri'][0]["id_corriere"];
		
		$defaultValues["newsletter"] = "N";
		$defaultValues["regalo"] = ListeregaloModel::hasIdLista() ? 1 : 0;
		
		$data['values'] = $this->m('OrdiniModel')->getFormValues('insert','sanitizeHtml',null,$defaultValues);
		
		$data['province'] = $this->m('ProvinceModel')->selectTendina();
		
		$this->m('RegusersModel')->fields = "password";
		
		if (v("account_attiva_conferma_password"))
			$this->m('RegusersModel')->fields .= ",confirmation";
		
		$data['regusers_values'] = $this->m('RegusersModel')->getFormValues('insert','sanitizeHtml');
		
		if (strcmp($data['values']["tipo_cliente"],"") === 0)
		{
			$data['values']["tipo_cliente"] = "privato";
		}
		
		if (strcmp($data['values']["registrato"],"") === 0)
		{
			$data['values']["registrato"] = "Y";
		}
		
		$data["tipoAzione"] = "insert";
		
		if (Output::$json)
		{
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
		
		$data["pages"] = $this->m("CartModel")->getProdotti();
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
	
	public function couponattivo()
	{
		$this->clean();
		
		echo hasActiveCoupon() ? "OK" : "KO";
	}
	
	protected function getStatoOrdineFrontend($statoOrdine)
	{
		return $statoOrdine;
	}
}
