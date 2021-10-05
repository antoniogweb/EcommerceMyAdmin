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

class BaseRegusersController extends BaseController
{

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - Login';
		
		$data["arrayLingue"] = array();
		
		$this->append($data);
	}
	
	private function setUserHead()
	{
		Output::setHeaderValue("Status","logged");
		Output::setHeaderValue("UserId",$this->s['registered']->getUid());
		
		$res = $this->m['RegusersModel']->clear()->where(array("id_user"=>(int)$this->s['registered']->status['id_user']))->send();
		User::$dettagli = $res[0]['regusers'];
		
		$nomeCliente = (strcmp(User::$dettagli["tipo_cliente"],"privato") === 0 || strcmp(User::$dettagli["tipo_cliente"],"libero_professionista") === 0) ?  User::$dettagli["nome"] : User::$dettagli["ragione_sociale"];
		
		Output::setHeaderValue("Nome",$nomeCliente);
	}
	
	public function login()
	{
		$data['title'] = Parametri::$nomeNegozio . ' - Login';
		
		$data['headerClass'] = "woocommerce-account";
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/regusers/login";
		}
		
		$redirect = $this->request->get('redirect','','sanitizeAll');
		$redirect = ltrim($redirect,"/");
		
		//valori permessi per il redirect
		$allowedRedirect = explode(",",v("redirect_permessi"));
		
		if (is_numeric($redirect))
		{
			$page = $this->m["PagesModel"]->selectId((int)$redirect);
			
			if (!empty($page))
				$redirect = (int)$redirect;
			else
				$redirect = '';
		}
		else
		{
			if (!in_array($redirect,$allowedRedirect))
				$redirect = '';
		}
		
		$data['action'] = Url::getRoot("regusers/login?redirect=$redirect");
		
		$data['notice'] = null;
		
		$this->s['registered']->checkStatus();
		
		if ($this->s['registered']->status['status']=='logged') { //check if already logged
			if (Output::$html)
			{
				$this->redirect('area-riservata',0);
				die();
			}
		}
		
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			$username = checkMail($_POST['username']) ? sanitizeAll($_POST['username']) : '';
			$choice = $this->s['registered']->login(sanitizeAll($_POST['username']),$_POST['password']);

			switch($choice) {
				case 'logged':
					if (Output::$html)
						$this->redirect('area-riservata',0);
					break;
				case 'accepted':
					if (Output::$html)
					{
						if (strcmp($redirect,'') !== 0)
						{
							if (is_numeric($redirect))
								$urlRedirect = Url::getRoot().getUrlAlias((int)$redirect);
							else
								$urlRedirect = Url::getRoot().$redirect;
							
							header('Location: '.$urlRedirect);
						}
						else
						{
							$this->redirect("area-riservata");
						}
					}
					else
					{
						$this->setUserHead();
					}
					break;
				case 'login-error':
					if (Output::$html)
						$data['notice'] = '<div class="'.v("alert_error_class").'">'.gtext('E-Mail o Password sbagliati').'</div>';
					else
						Output::setHeaderValue("Status","login-error");
					break;
				case 'wait':
					if (Output::$html)
						$data['notice'] = '<div class="'.v("alert_error_class").'">'.gtext('Devi aspettare 5 secondi prima di poter tentare nuovamente il login').'</div>';
					else
						Output::setHeaderValue("Status","wait");
					break;
			}
		}
		
		if (Output::$html)
		{
			$this->append($data);
			$this->load('login');
		}
		else
			$this->load("api_output");
	}

	public function logout()
	{
		$res = $this->s['registered']->logout();
		
		if ($res === 'not-logged')
		{
			if (Output::$html)
				$this->redirect('',0);
		}
		else if ($res === 'was-logged')
		{
			if (Output::$html)
				$this->redirect('',0);
		}
		else if ($res === 'error')
		{
			$data['notice'] = null;
		}

		if (Output::$html)
		{
			$this->append($data);
			$this->load('logout');
		}
		else
			$this->load("api_output");
	}

	public function forgot()
	{
		require_once(LIBRARY.'/External/PHPMailer-master/src/Exception.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/PHPMailer.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/SMTP.php');
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("richiedi una nuova password");
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/password-dimenticata";
		}
		
		Output::setBodyValue("InvioMail", "KO");
		
		session_start();
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect("area-riservata");
		}
		else
		{
			$data['notice'] = null;
			$this->m["RegusersModel"]->errors = array();
			
			if (isset($_POST['invia']))
			{
				$tessera = $this->request->post('tessera','');
				if (strcmp($tessera,'') === 0)
				{
					if (isset($_POST['username']))
					{
						if (checkMail($_POST['username']))
						{
							$clean['username'] = sanitizeAll($_POST['username']);
							
							$res = $this->m["RegusersModel"]->db->select('regusers','*','username="'.$clean['username'].'" and has_confirmed = 0');

							if (count($res) > 0)
							{
								$e_mail = $res[0]['regusers']['username'];
								$id_user = (int)$res[0]['regusers']['id_user'];
								$forgot_token = $this->m["RegusersModel"]->getUniqueToken(md5(randString(20).microtime().uniqid(mt_rand(),true)));
								$forgot_time = time();
								$updateArray = array($forgot_token, $forgot_time);
								$this->m["RegusersModel"]->db->update('regusers','forgot_token,forgot_time',$updateArray,'username="'.$clean['username'].'"');
								
								ob_start();
								include tp()."/Regusers/mail_richiesta_cambio_password.php";

								$output = ob_get_clean();
								
								$res = MailordiniModel::inviaMail(array(
									"emails"	=>	array($e_mail),
									"oggetto"	=>	"Richiesta di modifica password",
									"testo"		=>	$output,
									"tipologia"	=>	"FORGOT",
									"id_user"	=>	(int)User::$id,
									"id_page"	=>	0,
								));
								
								if($res)
								{
									$_SESSION['result'] = 'send_mail_to_change_password';
								} else {
									$_SESSION['result'] = 'error';
								}
								
								if (Output::$html)
									$this->redirect("avvisi");
								else
									Output::setBodyValue("InvioMail", "OK");
							}
							else
							{
								$error = gtext("Siamo spiacenti, non esiste alcun utente attivo corrispondente all'email da lei inserita");
								$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
								$res = $this->m["RegusersModel"]->addError("username", $error);
							}
						}
						else
						{
							$error = gtext("Si prega di ricontrollare l'indirizzo e-mail");
							$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
							$res = $this->m["RegusersModel"]->addError("username", $error);
						}
					}
				}
			}
			
			if (Output::$html)
			{
				$this->append($data);
				$this->load('password_dimenticata');
			}
			else
			{
				Output::setBodyValue("Errori", $this->m['RegusersModel']->errors);
				
				$testi = array(
					"InfoRecupera"	=>	"Inserisci l'indirizzo e-mail con il quale ti sei registrato, ti invieremo una mail attraverso la quale potrai ottenere una nuova password.",
					"TestoInviata"	=>	"Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password.",
				);
				
				Output::setBodyValue("Testi", $testi);
				
				$this->load("api_output");
			}
		}

	}
	
	public function conferma($conf_token = "")
	{
		$this->clean();
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Conferma registrazione");
		
		$validToken = false;
		
		$urlAdd = isset($_GET["eFromApp"]) ? "?eFromApp&ecommerce" : "";
		
		session_start();
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect("area-riservata");
		}
		else
		{
			if (strcmp((string)$conf_token,"") !== 0)
			{
				$clean['conf_token'] = $data['conf_token'] = sanitizeAll($conf_token);

				$res = $this->m['RegusersModel']->clear()->where(array("confirmation_token"=>$clean['conf_token'],"has_confirmed"=>1))->send();

				if (count($res) > 0)
				{
					$confirmSeconds = (int)v("ore_durata_link_conferma")*3600;
					
					$now = time();
					$checkTime = $res[0]['regusers']['confirmation_time'] + $confirmSeconds;
					
					if ($checkTime > $now)
					{
						$validToken = true;
						
						$clean['id_user'] = (int)$res[0]['regusers']['id_user'];
						
						$this->m['RegusersModel']->setPasswordCondition();
					
						$this->m['RegusersModel']->setValues(array(
							"has_confirmed"	=>	0,
						));
						
						$this->m['RegusersModel']->pUpdate($clean['id_user']);

						$_SESSION['result'] = 'account_confermato';
						
						$this->redirect("avvisi".$urlAdd);
					}
				}
			}
		}
		
		if (!$validToken)
		{
			$_SESSION['result'] = 'invalid_token';
			$this->redirect("avvisi".$urlAdd);
		}
	}
	
	public function change($forgot_token = '')
	{
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Imposta nuova password");

		$validToken = false;
		
		$urlAdd = isset($_GET["eFromApp"]) ? "?eFromApp&ecommerce" : "";
		
		session_start();

		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect("area-riservata");
		}
		else
		{
			$clean['forgot_token'] = $data['forgot_token'] = sanitizeAll($forgot_token);

			$res = $this->m['RegusersModel']->clear()->where(array("forgot_token"=>$clean['forgot_token'],"has_confirmed"=>0))->send();

			if (count($res) > 0)
			{
				$now = time();
				$checkTime = $res[0]['regusers']['forgot_time'] + Parametri::$confirmTime;
				if ($checkTime > $now)
				{
					$validToken = true;
					
					$clean['id_user'] = (int)$res[0]['regusers']['id_user'];
					
					$this->m['RegusersModel']->setPasswordCondition();
				
					$this->m['RegusersModel']->setFields("password",PASSWORD_HASH);

					$data['notice'] = null;
		
					if (isset($_POST['invia']))
					{
						$tessera = $this->request->post('tessera','');
						if (strcmp($tessera,'') === 0)
						{
							if ($this->m['RegusersModel']->checkConditions('insert'))
							{
								$this->m['RegusersModel']->values['forgot_time'] = 0;
								
								$this->m['RegusersModel']->sanitize("sanitizeDb");
								
								if ($this->m['RegusersModel']->pUpdate($clean['id_user']))
								{
									$_SESSION['result'] = 'password_cambiata';
									
									$this->redirect("avvisi".$urlAdd);
								}
							}
							else
							{
								$data['notice'] = $this->m['RegusersModel']->notice;
							}
						}
					}
					
					$this->m['RegusersModel']->fields = "password,confirmation";
		
					$data['values'] = $this->m['RegusersModel']->getFormValues('insert','sanitizeHtml');
				
					$this->append($data);
					$this->load('reimposta_password');
				}
			}
		}
		
		if (!$validToken)
		{
			$_SESSION['result'] = 'invalid_token';
			$this->redirect("avvisi".$urlAdd);
		}
	}
	
	public function password()
	{
		$this->s['registered']->check(null,0);
		
		Output::setBodyValue("PasswordModificata", "KO");
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Modifica password");
		$data['notice'] = null;
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/modifica-password";
		}
		
		$this->m['RegusersModel']->setFields('password:'.PASSWORD_HASH,'none');

		$this->m['RegusersModel']->setPasswordCondition();
		
		$id = (int)$this->s['registered']->status['id_user'];
		if (isset($_POST['updateAction'])) {
			$pass = $this->s['registered']->getPassword();
			if (passwordverify($_POST['old'], $pass))
			{
				$this->m['RegusersModel']->updateTable('update',$id);
				if ($this->m['RegusersModel']->result)
				{
					$data['notice'] = $this->m['RegusersModel']->notice;
					Output::setBodyValue("PasswordModificata", "OK");
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['RegusersModel']->notice;
				}
			}
			else
			{
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Vecchia password sbagliata")."</div><span class='evidenzia'>class_old</span>\n";
				
				$this->m['RegusersModel']->addError("old",gtext("Vecchia password sbagliata"));
				
				$this->m['RegusersModel']->result = false;
			}
		}
		
		$this->m['RegusersModel']->fields = "old,password,confirmation";
		
		$data['values'] = $this->m['RegusersModel']->getFormValues('insert','sanitizeHtml');
		
		if (Output::$html)
		{
			$this->append($data);
			$this->load('cambia_password');
		}
		else
		{
			Output::setBodyValue("Errori", $this->m['RegusersModel']->errors);
			
			$testi = array(
				"TestoModificata"	=>	"La password è stata correttamente modificata.",
				"TestoInfo"			=>	"Imposta una nuova password riempiendo i campi sottostanti.",
			);
			
			Output::setBodyValue("Testi", $testi);
			
			$this->load("api_output");
		}
	}
	
	public function indirizzo($idSpedizione = 0)
	{
		$this->clean();
		
		if ($this->islogged)
		{
			$spedizione = $this->m["SpedizioniModel"]->clear()->where(array(
				"id_spedizione"	=>	(int)$idSpedizione,
				"id_user"	=>	User::$id,
			))->record();
			
			if (!empty($spedizione))
			{
				echo json_encode($spedizione);
			}
		}
		
		echo "";
	}
	
	public function infoaccount($loadViewFile = true)
	{
		$this->clean();
		
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
			
			Output::setBodyValue("Dettagli", htmlentitydecodeDeep($temp));
		}
		
		if (Output::$json)
		{
			$whereArray = array();
			$isSpedizione = false;
			
			if (isset($_GET["idSpedizione"]))
			{
				$whereArray = array(
					"visibile_spedizione"	=>	1
				);
				
				$isSpedizione = true;
			}
			
			$res = $this->m["NazioniModel"]->clear()->select("iso_country_code,titolo")->where($whereArray)->orderBy("titolo")->send(false);
			
			$selectArray = array(
				array(
					"iso_country_code"	=>	"",
					"titolo"	=>	"Seleziona",
				)
			);
			
			foreach ($res as $r)
			{
				$selectArray[] = array(
					"iso_country_code"	=>	$r["iso_country_code"],
					"titolo"	=>	htmlentitydecode($r["titolo"]),
				);
			}
			
			Output::setBodyValue("Nazioni", $selectArray);
			Output::setBodyValue("Province", $this->m['ProvinceModel']->selectArray($isSpedizione));
			
			$testi = array(
				"FatturaElettronica"	=>	"Per aziende o liberi professionisti la PEC o il CODICE DESTINATARIO sono obbligatori nella fatturazione elettronica. Nel caso non si possegga un CODICE DESTINATARIO, compilare solo il campo PEC. Se non si dispone del CODICE DESTINATARIO o in caso di esonero dalla fatturazione elettronica, indicare nel campo CODICE DESTINATARIO 7 zeri (0000000).Per i privati (non possessori di partita iva) tali dati non sono necessari.</br>Per soggetti con sede all'estero inserire all'interno del codice destinatario 7 X (XXXXXXX).",
				"TestoPrivacy"	=>	"Confermando l'invio dei tuoi dati dichiari di aver prevo visione e di accettare le condizioni di privacy.",
				"AlertTitleModificato"	=>	"Dati modificati",
				"AlertTitleCreato"	=>	"Account creato",
				"AlertSubtitleModificato"	=>	"I suoi dati di fatturazione sono stati correttamente modificati",
				"AlertSubtitleCreato"	=>	"Il suo account è stato creato. Le è stata inviata una mail con le credenziali di accesso.",
			);
			
			Output::setBodyValue("Testi", $testi);
			
			if (isset($_GET["idSpedizione"]))
			{
				$spedizione = $this->m["SpedizioniModel"]->clear()->where(array(
					"id_user"	=>	User::$id,
					"id_spedizione"	=>	(int)$_GET["idSpedizione"]
				))->record();
				
				unset($spedizione["id_order"]);
				unset($spedizione["id_user"]);
				
				if (!empty($spedizione))
					Output::setBodyValue("Spedizione", htmlentitydecodeDeep($spedizione));
			}
			
			$spedizioni = $this->m["SpedizioniModel"]->clear()->where(array(
				"id_user"	=>	User::$id,
			))->orderBy("id_spedizione desc")->send(false);
			
			$arraySpedizioni = array();
			
			foreach ($spedizioni as $sp)
			{
				$sp["nazione_spedizione"] = nomeNazione($sp["nazione_spedizione"]);
// 				$sp["provincia_spedizione"] = nomeProvincia($sp["provincia_spedizione"]);
				
				$arraySpedizioni[] = htmlentitydecodeDeep($sp);
			}
			
			Output::setBodyValue("Spedizioni", $arraySpedizioni);
		}
		
		if ($loadViewFile)
			$this->load("api_output");
	}
	
	public function modify()
	{
		$this->s['registered']->check(null,0);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/modifica-account";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Modifica account");
		$data['notice'] = null;
		$data['action'] = "/modifica-account";
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
		$fields = 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2';
		
		if (v("attiva_ruoli"))
			$fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$fields .= ",id_tipo_azienda";
		
		$this->m['RegusersModel']->setFields($fields,'sanitizeAll');
		
		$this->m['RegusersModel']->setConditions($tipo_cliente, "update", $pec, $codiceDestinatario);
		
// 		$this->m['RegusersModel']->fields = "nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,tipo_cliente";
		
		$this->m['RegusersModel']->updateTable('update',$this->iduser);
		if ($this->m['RegusersModel']->result)
		{
			if (Output::$html)
				$data['notice'] = $this->m['RegusersModel']->notice;
		}
		else
		{
			if (Output::$html)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['RegusersModel']->notice;
		}
		
		if (Output::$html)
		{
			$data['values'] = $this->m['RegusersModel']->getFormValues('update','sanitizeHtml',$this->iduser);
			
			$data['province'] = $this->m['ProvinceModel']->selectTendina();
			
			$this->append($data);
			$this->load('form');
		}
		else
		{
			Output::setBodyValue("Errori", $this->m['RegusersModel']->errors);
			$this->load("api_output");
		}
	}
	
	public function impostaspedizioneperapp($id = 0)
	{
		$this->s['registered']->check(null,0);
		
		$clean["id"] = (int)$id;
		
		if ($clean["id"] > 0)
		{
			$this->m['SpedizioniModel']->setDaUsarePerApp($clean["id"]);
		}
		
		if (Output::$json)
			$this->load("api_output");
	}
	
	public function spedizione($id = 0)
	{
		$this->s['registered']->check(null,0);
		
		if ((int)$id === 0 && (isset($_GET["impostaFatt"]) || isset($_POST["impostaFatt"])))
		{
			$_POST["indirizzo_spedizione"] = User::$dettagli["indirizzo"];
			$_POST["cap_spedizione"] = User::$dettagli["cap"];
			$_POST["provincia_spedizione"] = User::$dettagli["provincia"];
			$_POST["dprovincia_spedizione"] = User::$dettagli["dprovincia"];
			$_POST["telefono_spedizione"] = User::$dettagli["telefono"];
			$_POST["nazione_spedizione"] = User::$dettagli["nazione"];
			$_POST["citta_spedizione"] = User::$dettagli["citta"];
		}
		
		$clean["id"] = $data["id"] = (int)$id;
		
		if ($clean["id"] > 0)
		{
			$numero = $this->m['SpedizioniModel']->clear()->where(array(
				"id_spedizione"	=>	$clean["id"],
				"id_user"		=>	User::$id,
			))->rowNumber();
			
			if ($numero === 0)
				$this->redirect("");
		}
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/gestisci-spedizione/".$clean["id"];
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Gestisci spedizione", false);
		$data['notice'] = null;
		$data['action'] = "/gestisci-spedizione/".$clean["id"];
		
		$ordine = array();
		
		if (isset($_GET["cart_uid"]))
			$ordine = OrdiniModel::getByCartUid($_GET["cart_uid"]);
		
		if (!empty($ordine))
			$data['action'] .= "?cart_uid=".sanitizeHtml($_GET["cart_uid"]);
		
		$campoObbligatoriProvincia = "dprovincia_spedizione";
		
		if (isset($_POST["nazione_spedizione"]))
		{
			if ($_POST["nazione_spedizione"] == "IT")
				$campoObbligatoriProvincia = "provincia_spedizione";
		}
		
		$campiObbligatori = "indirizzo_spedizione,$campoObbligatoriProvincia,citta_spedizione,telefono_spedizione,nazione_spedizione,cap_spedizione";
		
// 		if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] == "IT")
// 			$campiObbligatori .= ",cap_spedizione";
		
		$fields = 'indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,citta_spedizione,telefono_spedizione,nazione_spedizione';
		
		$this->m['SpedizioniModel']->setFields($fields,'sanitizeAll');
		
		if (isset(User::$id))
			$this->m['SpedizioniModel']->values["id_user"] = User::$id;
		
		$this->m['SpedizioniModel']->clearConditions("strong");
		$this->m['SpedizioniModel']->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		$codiciSpedizioneAttivi = $this->m["NazioniModel"]->selectCodiciAttiviSpedizione();
		$codiciNazioniAttiveSpedizione = implode(",",$codiciSpedizioneAttivi);
		
		$this->m['SpedizioniModel']->addStrongCondition("both",'checkIsStrings|'.$codiciNazioniAttiveSpedizione,"nazione_spedizione|".gtext("<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>"));
		
		$this->m['SpedizioniModel']->updateTable('insert,update',$clean["id"]);
		
		if ($this->m['SpedizioniModel']->queryResult)
		{
			if (Output::$html)
			{
				if (!empty($ordine))
					$this->redirect("ordini/modifica/".$ordine["id_o"]."/".$ordine["cart_uid"]);
				else
					$this->redirect("riservata/indirizzi");
			}
		}
		else
		{
			if (!$this->m['SpedizioniModel']->result)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['SpedizioniModel']->notice;
		}
		
		$submitAction = $id > 0 ? "update" : "insert";
		
		$data['values'] = $this->m['SpedizioniModel']->getFormValues($submitAction,'sanitizeHtml',$clean["id"],array("nazione_spedizione"=>"IT"));
		
		$data['province'] = $this->m['ProvinceModel']->selectTendina();
		
		if (Output::$html)
		{
			$this->append($data);
			$this->load('modifica_spedizione');
		}
		else
		{
			$this->infoaccount(false);
			
			Output::setBodyValue("Errori", $this->m['SpedizioniModel']->errors);
			$this->load("api_output");
		}
	}
	
	public function add()
	{
		session_start();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/crea-account";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("registrati");
		$data['action'] = "/crea-account";
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect("area-riservata");
		}
		else
		{
			$this->formRegistrazione();
			
			if (Output::$html)
			{
				$this->load('form');
			}
			else
			{
				Output::setBodyValue("Errori", $this->m['RegusersModel']->errors);
				$this->load("api_output");
			}
		}
		
		$this->append($data);
	}
	
	public function notice()
	{
		session_start();

		$data['title'] = Parametri::$nomeNegozio . ' - Avvisi';
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/avvisi";
		}
		
		$this->append($data);
		$this->load('notice');
	}
}
