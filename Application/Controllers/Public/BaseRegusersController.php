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

class BaseRegusersController extends BaseController
{
	private $permalinkPaginaRegistrazione = "crea-account";
	private $creaAccountViewFile = "form";
	
	public $redirectUrlErroreTwoFactor = ""; // url di atterraggio quando c'è un errore nell'autenticazione a due fattori
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		VariabiliModel::$valori["usa_versione_random"] = 1;

		if (!empty($_POST))
			IpcheckModel::check("POST");
		
		if (!v("attiva_area_riservata") && $action != "notice")
		{
			$this->redirect("");
			die("Area riservata non attiva");
		}
		
		if( !session_id() )
			session_start();
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
// 		IpcheckModel::check($controller.$action);
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext('Login'));
		
		$data["arrayLingue"] = array();
		
		$this->redirectUrlErroreTwoFactor = $this->applicationUrl.$this->controller."/login";
		
		$this->append($data);
	}
	
	private function setUserHead()
	{
		Output::setHeaderValue("Status","logged");
		Output::setHeaderValue("UserId",$this->s['registered']->getUid());
		
		$res = $this->m('RegusersModel')->clear()->where(array("id_user"=>(int)$this->s['registered']->status['id_user']))->send();
		User::$dettagli = $res[0]['regusers'];
		
		$nomeCliente = (strcmp(User::$dettagli["tipo_cliente"],"privato") === 0 || strcmp(User::$dettagli["tipo_cliente"],"libero_professionista") === 0) ?  User::$dettagli["nome"] : User::$dettagli["ragione_sociale"];
		
		Output::setHeaderValue("Nome",$nomeCliente);
	}
	
	protected function logAccountCheck($azione, $email)
	{
		$result = LogaccountModel::getInstance($azione)->check($email);
		
		if (!$result)
		{
			$_SESSION['result'] = 'pausa_'.$azione;
			$this->redirect("avvisi");
		}
	}
	
	public function login()
	{
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext('Login'));
		
		$data['headerClass'] = "";
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/regusers/login";
		}
		
		$redirect = RegusersModel::getRedirect();
		
		$data['action'] = Url::getRoot("regusers/login".RegusersModel::$redirectQueryString);
		$data['redirectQueryString'] = RegusersModel::$redirectQueryString;
		
		$data['notice'] = null;
		
		$this->checkNonLoggato();
		
		$this->getAppLogin();
		
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			$username = checkMail($_POST['username']) ? sanitizeAll($_POST['username']) : '';
			$choice = $this->s['registered']->login(sanitizeAll($_POST['username']),$_POST['password']);
			
			if ($username)
				$this->logAccountCheck("LOGIN", $_POST['username']);
			
			switch($choice) {
				case 'logged':
					if (Output::$html)
						$this->redirect(Url::routeToUrl("area-riservata"),0);
					break;
				case 'accepted':
					LogaccountModel::getInstance()->set(1);
					
					$this->hookAfterLogin();
					
					if (Output::$html)
					{
						$this->redirectUser();
					}
					else
					{
						$this->setUserHead();
					}
					break;
				case 'two-factor':
					$this->redirectTwoFactorSendMail();
					break;
				case 'login-error':
					if (Output::$html)
					{
						if (v("conferma_registrazione"))
						{
							$res = RegusersModel::utenteDaConfermare($username, false);
							
							if (count($res) > 0)
							{
								$this->redirect("account-verification");
								// $_POST['invia'] = 1;
								// $this->richieditokenconferma();
							}
						}
						
						$data['notice'] = '<div class="'.v("alert_error_class").'">'.gtext('E-Mail o Password sbagliati').'</div>';
					}
					else
						Output::setHeaderValue("Status","login-error");
					break;
				case 'wait':
					LogaccountModel::getInstance()->remove();
					
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
	
	protected function checkTwoFactor()
	{
		if (!v("attiva_autenticazione_due_fattori_front"))
			$this->responseCode(403);
		
		if (!empty($_POST))
			IpcheckModel::check("POST");
		
		$this->s['registered']->checkStatus();
		
		if ($this->s['registered']->status['status'] != 'two-factor') { //check if already logged
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		}
		
		$uidt = $this->s['registered']->getTwoFactorUidt();
		
		$user = $this->m('RegusersModel')->selectId((int)$this->s['registered']->status["id_user"]);
		
		if (empty($user) || !$uidt)
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$sessioneTwo = $this->s['registered']->getTwoFactorModel()->clear()->where(array(
			"uid_two"	=>	sanitizeAll($uidt),
			"attivo"	=>	0,
			"id_user"	=>	(int)$this->s['registered']->status["id_user"],
			// "user_agent_md5"	=>	getUserAgent(),
		))->record();
		
		if (empty($sessioneTwo) || $sessioneTwo["tentativi_verifica"] >= (int)v("autenticazione_due_fattori_numero_massimo_tentativi_front"))
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		return array($sessioneTwo, $user);
	}
	
	public function twofactor()
	{
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		$redirect = RegusersModel::getRedirect();
		
		$data['action'] = $this->baseUrl."/".$this->applicationUrl.$this->controller."/twofactorcheck/".RegusersModel::$redirectQueryString;
		$data['notice'] = null;
		
		$data["user"] = $user;
		$data["sessioneTwo"] = $sessioneTwo;
		
		$this->append($data);
		$this->load('two_factor');
	}
	
	public function twofactorsendmail()
	{
		$this->clean();
		
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		$redirect = RegusersModel::getRedirect();
		
		if ($sessioneTwo["numero_invii_codice"] >= (int)v("autenticazione_due_fattori_numero_massimo_invii_codice_front"))
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$res = $this->s['registered']->getTwoFactorModel()->inviaCodice($sessioneTwo, $user, "username");
		
		if ($res)
		{
			flash("notice","<div class='".v("alert_success_class")."'>".gtext("Il codice di autenticazione a due fattori è stato inviato all'indirizzo email indicato.")."</div>");
			
			$this->redirect($this->applicationUrl.$this->controller."/twofactor/".RegusersModel::$redirectQueryString);
		}
		else
		{
			flash("notice","<div class='".v("alert_error_class")."'>".gtext("Attenzione, non è stato possibile inviare il codice di autenticazione a due fattori. Si prega di riprovare.")."</div>");
			
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		}
	}
	
	public function twofactorcheck()
	{
		$this->clean();
		
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		$redirect = RegusersModel::getRedirect();
		
		$clean["codice"] = trim($this->request->post("codice","", "sanitizeAll"));
		
		if ($clean["codice"])
		{
			if ($this->s['registered']->getTwoFactorModel()->checkCodice($sessioneTwo, $clean["codice"]))
			{
				$this->hookAfterLogin();
				
				$this->redirectUser();
			}
			else
				flash("notice","<div class='".v("alert_error_class")."'>".gtext("Attenzione, il codice non è corretto, si prega di riprovare")."</div>");
		}
		else
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$this->redirect($this->applicationUrl.$this->controller."/twofactor/".RegusersModel::$redirectQueryString);
	}
	
	protected function redirectUser()
	{
		$urlRedirect = RegusersModel::getUrlRedirect();
		
		if ($urlRedirect)
		{
			HeaderObj::location($urlRedirect);
			// header('Location: '.$urlRedirect);
			// die();
		}
		else
			$this->m('RegusersModel')->redirectVersoAreaRiservata();
	}
	
	protected function checkNonLoggato()
	{
		$this->s['registered']->checkStatus();
		
		if ($this->s['registered']->status['status']=='logged') { //check if already logged
			if (Output::$html)
			{
				$this->m('RegusersModel')->redirectVersoAreaRiservata();
				die();
			}
		} else if ($this->s['registered']->status['status']=='two-factor') {
			$this->s['registered']->logout();
		}
	}
	
	// metodo chiamato da APP esterna per chiedere l'eliminazione dell'account
	public function deleteaccountdaapp($codice = "")
	{
		$this->clean();
		
		$clean["codice"] = sanitizeAll($codice);
		
		if (!trim($codice) || !v("abilita_login_tramite_app") || !IntegrazioniloginModel::getApp($clean["codice"])->isAttiva() || VariabiliModel::confermaUtenteRichiesta())
			$this->redirect("");
		
		if (!VariabiliModel::checkToken("token_eliminazione_account_da_app"))
			die();
		
		IntegrazioniloginModel::getApp($clean["codice"])->deleteAccountCallback($this->m('RegusersModel'), RegusersModel::getUrlAccountEliminato());
	}
	
	// Elimina l'approvazione della APP (access token, eventuali file, etc)
	public function eliminaapprovazione($codice = "")
	{
		$this->clean();
		
		$clean["codice"] = sanitizeAll($codice);
		
		if (!trim($codice) || !v("abilita_login_tramite_app") || !IntegrazioniloginModel::getApp($clean["codice"])->isAttiva())
			$this->redirect("");
		
		if (!VariabiliModel::checkToken("token_eliminazione_account_da_app"))
			die();
		
		IntegrazioniloginModel::getApp($clean["codice"])->eliminaApprovazione(new IntegrazioniloginModel(), RegusersModel::getUrlApprovazioneEliminata());
	}
	
	public function loginapp($codice = "")
	{
		if (!v("permetti_registrazione"))
			$this->redirect("");
		
		$this->clean();
		
		if (!isset($_SESSION["ok_csrf"]))
		{
			if (App::checkCSRF("csrf_code"))
				$_SESSION["ok_csrf"] = 1;
			else
				$this->redirect("");
		}
		
		$clean["codice"] = sanitizeAll($codice);
		
		$redirect = RegusersModel::getRedirect("?", true);
		
		if (!trim($codice) || !v("abilita_login_tramite_app") || !IntegrazioniloginModel::getApp($clean["codice"])->isAttiva() || VariabiliModel::confermaUtenteRichiesta())
			$this->redirect("");
		
		$this->checkNonLoggato();
		
		$this->model("RegusersintegrazioniloginModel");
		
		$recordLoginApp = IntegrazioniloginModel::g()->where(array(
			"codice"	=>	$clean["codice"],
		))->record();
		
		IntegrazioniloginModel::getApp($clean["codice"])->getInfoOrGoToLogin(RegusersModel::$redirectQueryString);
		
		$infoUtente = IntegrazioniloginModel::getApp($clean["codice"])->getInfoUtente();
		
		if (!$infoUtente["result"])
		{
			$this->redirect("regusers/login");
		}
		else if ($infoUtente["redirect"] && $infoUtente["login_redirect"])
		{
			header('Location: '.$infoUtente["login_redirect"]);
			die();
		}
		else if ($infoUtente["utente_loggato"] && checkMail($infoUtente["dati_utente"]["external_email"]) && trim($infoUtente["dati_utente"]["external_full_name"]))
		{
			$clean["username"] = sanitizeAll($infoUtente["dati_utente"]["external_email"]);
			
			$utente = $this->m('RegusersModel')->clear()->where(array(
				"username"	=>	$clean["username"],
			))->record();
			
			if (!empty($utente) && (int)$utente[Users_CheckAdmin::$statusFieldName] !== (int)Users_CheckAdmin::$statusFieldActiveValue)
				$this->redirect("regusers/login");
			
			if (empty($utente))
			{
				VariabiliModel::$valori["insert_account_cf_obbligatorio"] = 0;
				VariabiliModel::$valori["insert_account_p_iva_obbligatorio"] = 0;
				
				$fullNameArray = explode(" ", $infoUtente["dati_utente"]["external_full_name"]);
				
				if (count($fullNameArray) > 1)
				{
					$nome = array_shift($fullNameArray);
					$cognome = implode(" ", $fullNameArray);
				}
				else
				{
					$nome = $infoUtente["dati_utente"]["external_full_name"];
					$cognome = "";
				}
				
				$this->m('RegusersModel')->sValues(array(
					"username"	=>	$infoUtente["dati_utente"]["external_email"],
					Users_CheckAdmin::$statusFieldName	=>	(int)Users_CheckAdmin::$statusFieldActiveValue,
					"nome"		=>	$nome,
					"cognome"	=>	$cognome,
					"tipo_cliente"	=>	"privato",
					"codice_app"	=>	$codice,
				));
				
				$this->m('RegusersModel')->setValue("password", randomToken(), PASSWORD_HASH);
				$this->m('RegusersModel')->setValue("completo", 0);
				
				// Prendi la nazione dall'URL o imposta quella di default
				$nazione = isset(Params::$country) ? strtoupper(Params::$country) : v("nazione_default");
				$this->m('RegusersModel')->setValue("nazione", $nazione);
				
				VariabiliModel::$valori["conferma_registrazione"] = 0;
				
				if ($this->m('RegusersModel')->insert())
				{
					$idCliente = (int)$this->m('RegusersModel')->lId;
					$datiCliente = $this->m('RegusersModel')->selectId($idCliente);
					
					ob_start();
					include tpf("Elementi/Mail/mail_al_negozio_registr_nuovo_cliente_tramite_app.php");
					$output = ob_get_clean();
					
					$res = MailordiniModel::inviaMail(array(
						"emails"	=>	array(Parametri::$mailInvioOrdine),
						"oggetto"	=>	"invio dati nuovo utente - registrazione tramite ".$recordLoginApp["titolo"],
						"testo"		=>	$output,
						"tipologia"	=>	"ISCRIZIONE ".$recordLoginApp["codice"],
						"id_user"	=>	$idCliente,
						"id_page"	=>	0,
					));
				}
				else
					$this->redirect("regusers/login");
			}
			else
				$idCliente = (int)$utente["id_user"];
			
			// Aggiungo la voce del login nella tabella delle integrazioni
			$this->m("RegusersintegrazioniloginModel")->sValues(array(
				"id_user"	=>	$idCliente,
				"id_integrazione_login"	=>	$recordLoginApp["id_integrazione_login"],
				"codice"	=>	$codice,
				"user_id_app"	=>	$infoUtente["dati_utente"]["external_id"],
			));
			
			$this->m("RegusersintegrazioniloginModel")->insert();
			
			// Forza il login
			$choice = $this->s['registered']->login($clean["username"],null,true);

			switch($choice) {
				case 'logged':
					$this->redirect(Url::routeToUrl("area-riservata"),0);
					break;
				case 'accepted':
					
					$this->hookAfterLogin();
					
					ob_start();
					include tpf("Elementi/Mail/mail_login_da_social.php");
					$output = ob_get_clean();
					
					$res = MailordiniModel::inviaMail(array(
						"emails"	=>	array($clean["username"]),
						"oggetto"	=>	"Login al sito tramite ".$recordLoginApp["titolo"],
						"testo"		=>	$output,
						"tipologia"	=>	"LOGIN ".$recordLoginApp["codice"],
						"id_user"	=>	$idCliente,
						"id_page"	=>	0,
					));
					
					$this->redirectUser();
					break;
				case 'login-error':
					$this->redirect("regusers/login");
					break;
				case 'wait':
					$this->redirect("regusers/login");
					break;
			}
		}
		else
		{
			$this->redirect("regusers/login");
		}
	}
	
	protected function unsetAccessToken()
	{
		if (isset($_SESSION["access_token"]))
			unset($_SESSION["access_token"]);
	}
	
	public function logout()
	{
		$res = $this->s['registered']->logout();
		
		if ($res === 'not-logged')
		{
			if (Output::$html)
			{
				$this->unsetAccessToken();
				
				$this->redirect('',0);
			}
		}
		else if ($res === 'was-logged')
		{
			if (Output::$html)
			{
				$this->unsetAccessToken();
				
				$this->redirect('',0);
			}
		}
		else if ($res === 'error')
		{
			$data['notice'] = null;
		}
		
		$this->unsetAccessToken();
		
		if (Output::$html)
		{
			$this->append($data);
			$this->load('logout');
		}
		else
			$this->load("api_output");
	}
	
	public function richieditokenconferma()
	{
		if (!v("conferma_registrazione"))
			$this->redirect("");
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("richiedi l'invio del link di conferma dell'account");
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/account-verification";
		}
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			$data['notice'] = null;
			$this->m("RegusersModel")->errors = array();
			
			if (isset($_POST['invia']))
			{
				if (CaptchaModel::getModulo()->checkRegistrazione())
				{
					if (isset($_POST['username']))
					{
						if (checkMail($_POST['username']))
						{
							$clean['username'] = sanitizeAll($_POST['username']);
							
							$res = RegusersModel::utenteDaConfermare($clean['username'], false);

							if (count($res) > 0)
							{
								$e_mail = $res[0]['regusers']['username'];
								$id_user = (int)$res[0]['regusers']['id_user'];
								
								$tokenConferma = md5(randString(30).microtime().uniqid(mt_rand(),true));
								$tokenReinvio = md5(randString(30).microtime().uniqid(mt_rand(),true));
								
								$codiceConfermaRegistrazione = sanitizeAll(Aes::encrypt(generateString(v("conferma_registrazione_numero_cifre_codice_verifica"), "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ")));
								
								$this->m('RegusersModel')->setValues(array(
									"confirmation_token"	=>	$tokenConferma,
									"token_reinvio"			=>	$tokenReinvio,
									"time_token_reinvio"	=>	time(),
									"token_reinvio_usato_volte"	=>	0,
									"codice_verifica"		=>	$codiceConfermaRegistrazione,
									"tentativi_verifica"	=>	0,
								));
								
								$_SESSION['result'] = 'error';
								
								if ($this->m('RegusersModel')->pUpdate($id_user))
								{
									$_SESSION['result'] = 'utente_creato';
									$_SESSION['token_reinvio'] = $tokenReinvio;
									$_SESSION['conferma_utente'] = 1;
								}
								
								$this->redirect("send-confirmation");
							}
							else
							{
								$error = gtext("Siamo spiacenti, non esiste alcun utente da confermare corrispondente all'email da lei inserita");
								$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
								$res = $this->m("RegusersModel")->addError("username", $error);
							}
						}
						else
						{
							$error = gtext("Si prega di ricontrollare l'indirizzo e-mail");
							$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
							$res = $this->m("RegusersModel")->addError("username", $error);
						}
					}
				}
				else
				{
					ob_start();
					include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
					$data['notice'] = ob_get_clean();
					$data['notice'] .= $this->m('RegusersModel')->notice;
					$this->m('RegusersModel')->result = false;
				}
			}
			
			$this->append($data);
			$this->load('richiedi_mail_verifica');
		}
	}
	
	public function forgot()
	{
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("richiedi una nuova password"));
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/password-dimenticata";
		}
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			$data['notice'] = null;
			$this->m("RegusersModel")->errors = array();
			
			if (isset($_POST['invia']))
			{
				if (CaptchaModel::getModulo()->checkRegistrazione())
				{
					if (isset($_POST['username']))
					{
						if (checkMail($_POST['username']))
						{
							$clean['username'] = sanitizeAll($_POST['username']);
							
							$res = $this->m("RegusersModel")->clear()->where(array(
								"username"		=>	$clean['username'],
								"has_confirmed"	=>	0,
							))->send();
							
							if (count($res) > 0)
							{
								$this->logAccountCheck("RECUPERO_PASSWORD", $_POST['username']);
								
								$e_mail = $res[0]['regusers']['username'];
								$id_user = (int)$res[0]['regusers']['id_user'];
								$forgot_token = $this->m("RegusersModel")->getUniqueToken(md5(randString(20).microtime().uniqid(mt_rand(),true)));
								$forgot_time = time();
								$updateArray = array($forgot_token, $forgot_time);

								$this->m("RegusersModel")->sValues(array(
									"forgot_token"	=>	$forgot_token,
									"forgot_time"	=>	$forgot_time,
								));
								
								$this->m("RegusersModel")->pUpdate(null, array(
									"username"	=>	$clean['username'],
								));
								
								ob_start();
								include tpf("/Regusers/mail_richiesta_cambio_password.php");

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
								
								$this->redirect("avvisi");
							}
							else
							{
								sleep(3);
								$_SESSION['result'] = 'send_mail_to_change_password';
								$this->redirect("avvisi");
								
// 								$error = gtext("Siamo spiacenti, non esiste alcun utente attivo corrispondente all'email da lei inserita");
// 								$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
// 								$res = $this->m("RegusersModel")->addError("username", $error);
							}
						}
						else
						{
							$error = gtext("Si prega di ricontrollare l'indirizzo e-mail");
							$data['notice'] = "<div class='".v("alert_error_class")."'>".$error."</div><span class='evidenzia'>class_username</span>";
							$res = $this->m("RegusersModel")->addError("username", $error);
						}
					}
				}
				else
				{
					ob_start();
					include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
					$erroreInvio = ob_get_clean();
				
// 					$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("Errore nel tentativo di recupero della password, per favore riprova più tardi")."</div>";
					
					$data['notice'] = $erroreInvio;
					$res = $this->m("RegusersModel")->addError("username", $erroreInvio);
				}
			}
			
			$this->append($data);
			$this->load('password_dimenticata');
		}
	}
	
	public function rinnovo($token = "")
	{
		if (!v("attiva_scadenza_account"))
			$this->responseCode(403);
		
		IpcheckModel::check("RINNOVO");
		
		$this->clean();
		
		$clean["token_reinvio"] = sanitizeAll(trim((string)$token));
		
		$_SESSION['result'] = 'invalid_token';
		
		if ($clean["token_reinvio"])
		{
			$record = $this->m('RegusersModel')->clear()->where(array(
				"token_rinnovo_scadenza"	=>	$clean['token_reinvio'],
				"has_confirmed"			=>	0,
				"ha_confermato"			=>	1,
				"bloccato"				=>	0,
				"ne"	=>	array(
					"token_rinnovo_scadenza"	=>	"",
				),
			))->record();
			
			if (!empty($record) && checkIsoDate($record["data_scadenza"]))
			{
				$confirmSeconds = (int)v("ore_durata_link_rinnovo")*3600;
				
				$now = time();
				$checkTime = $record['time_token_rinnovo_scadenza'] + $confirmSeconds;
				
				if ($checkTime > $now)
				{
					$nuovaScadenza = DateTime::createFromFormat("Y-m-d", $record["data_scadenza"]);
					$nuovaScadenza->modify('+'.v("giorni_scadenza_account").' day');
					
					$this->m('RegusersModel')->sValues(array(
						"data_scadenza"	=>	sanitizeDb($nuovaScadenza->format("Y-m-d")),
						"token_rinnovo_scadenza"		=>	randomToken(),
						"time_token_rinnovo_scadenza"	=>	time(),
						"numero_avvisi_scadenza"		=>	0
					));
					
					if ($this->m('RegusersModel')->pUpdate((int)$record["id_user"]))
						$_SESSION['result'] = "account_rinnovato";
				}
			}
		}
		
		$this->redirect("avvisi");
	}
	
	public function reinviamailconferma()
	{
		if (!v("conferma_registrazione") || !isset($_SESSION["token_reinvio"]) || !trim($_SESSION["token_reinvio"]))
			$this->redirect("");
		
		IpcheckModel::check("REINVIA");
		
		$this->clean();
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			$clean["token_reinvio"] = sanitizeAll($_SESSION["token_reinvio"]);
			
			$res = $this->m('RegusersModel')->clear()->where(array(
				"token_reinvio"	=>	$clean['token_reinvio'],
				"has_confirmed"			=>	1,
				"ha_confermato"			=>	0,
				"bloccato"				=>	0,
				"ne"	=>	array(
					"token_reinvio"	=>	"",
				),
			))->send();
			
			$_SESSION['result'] = 'error';
			
			if (strcmp((string)$_SESSION["token_reinvio"],"") !== 0 && count($res) > 0)
			{
				$clean['id_user'] = (int)$res[0]['regusers']['id_user'];
				$usatoVolte = (int)$res[0]["regusers"]["token_reinvio_usato_volte"];
				
				if ($usatoVolte < 3)
				{
					$confirmSeconds = (int)v("minuti_durata_link_conferma")*60;
					
					$now = time();
					$checkTime = $res[0]['regusers']['time_token_reinvio'] + $confirmSeconds;
					
					if ($checkTime > $now)
					{
						// $tokenConferma = md5(randString(20).microtime().uniqid(mt_rand(),true));
						$tokenConferma = $res[0]["regusers"]["confirmation_token"];
						
						$resInvio = MailordiniModel::inviaMail(array(
							"emails"	=>	array($res[0]["regusers"]["username"]),
							"oggetto"	=>	"codice di conferma per il tuo account",
							"tipologia"	=>	"LINK_CONFERMA",
							"id_user"	=>	$clean['id_user'],
							"id_page"	=>	0,
							"testo_path"	=>	"Elementi/Mail/mail_link_conferma.php",
							"array_variabili_tema"	=>	array(
								"LINK_CONFERMA"		=>	Url::getRoot()."conferma-account/$tokenConferma",
								"CODICE_VERIFICA"	=>	Aes::decrypt($res[0]["regusers"]["codice_verifica"]),
								"NOME_CLIENTE"		=>	RegusersModel::getNominativo($res[0]["regusers"]),
							),
						));
						
						if ($resInvio)
						{
							$usatoVolte++;
							
							$this->m('RegusersModel')->setValues(array(
								// "confirmation_token"	=>	$tokenConferma,
								"confirmation_time"		=>	time(),
								"time_token_reinvio"	=>	time(),
								"token_reinvio_usato_volte"	=>	$usatoVolte,
							));
							
							if ($this->m('RegusersModel')->pUpdate($clean['id_user']))
							{
								flash("notice", "<div class='".v("alert_success_class")."'>".gtext("Il codice di verifica è stato inviato all'indirizzo email indicato.")."</div>");
								
								$this->redirect("conferma-account/$tokenConferma");
							}
						}
					}
				}
			}
			
			$this->redirect("avvisi");
		}
	}
	
	public function conferma($conf_token = "")
	{
		// $this->clean();
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Conferma registrazione");
		
		$validToken = false;
		
		if (!v("conferma_registrazione"))
			$this->redirect("");
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			if (strcmp((string)$conf_token,"") !== 0)
			{
				$clean['conf_token'] = $data['conf_token'] = sanitizeAll($conf_token);

				$res = $this->m('RegusersModel')->clear()->where(array(
					"confirmation_token"	=>	$clean['conf_token'],
					"has_confirmed"			=>	1,
					"ha_confermato"			=>	0,
					"bloccato"				=>	0,
					"ne"	=>	array(
						"confirmation_token"	=>	"",
					),
				))->send();

				if (count($res) > 0)
				{
					$confirmSeconds = (int)v("minuti_durata_link_conferma")*60;
					
					$now = time();
					$checkTime = $res[0]['regusers']['confirmation_time'] + $confirmSeconds;
					
					if ($checkTime > $now)
					{
						$clean['id_user'] = (int)$res[0]['regusers']["id_user"];
						
						$clean["codice"] = trim($this->request->post("codice","", "sanitizeAll"));
						
						if ($this->m('RegusersModel')->checkNumeroTentativiVerifica($clean['id_user']))
						{
							if ($clean["codice"])
							{
								if ($this->m('RegusersModel')->checkCodice($clean['id_user'], $clean['conf_token'], $clean["codice"]))
								{
									$this->s['registered']->twoFactorResetSession($clean['id_user']);
									$this->maindaMailNegozioNuovaRegistrazione($clean['id_user']);
									
									$_SESSION['result'] = 'account_confermato';
									$this->redirect("avvisi");
								}
								else
									flash("notice","<div class='".v("alert_error_class")."'>".gtext("Attenzione, il codice non è corretto, si prega di riprovare")."</div>");
							}
							
							if ($this->m('RegusersModel')->checkNumeroTentativiVerifica($clean['id_user']))
							{
								$validToken = true;
							
								$clean['id_user'] = (int)$res[0]['regusers']['id_user'];
								
								$data["user"] = $res[0]['regusers'];
								
								if ((int)$res[0]["regusers"]["token_reinvio_usato_volte"] < 3)
									$_SESSION['token_reinvio'] = $res[0]["regusers"]["token_reinvio"];
								else if (isset($_SESSION['token_reinvio']))
									unset($_SESSION['token_reinvio']);
									
								$data['action'] = $this->baseUrl."/conferma-account/".$clean['conf_token'];
								$data['notice'] = null;
								
								$this->append($data);
								$this->load("conferma_account");
							}
						}
					}
				}
			}
		}
		
		if (!$validToken)
		{
			$_SESSION['result'] = 'invalid_token';
			$this->redirect("avvisi");
		}
	}
	
	public function change($forgot_token = '')
	{
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Imposta nuova password"));

		$validToken = false;
		
		$urlAdd = "";
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			$clean['forgot_token'] = $data['forgot_token'] = sanitizeAll($forgot_token);

			$res = $this->m('RegusersModel')->clear()->where(array("forgot_token"=>$clean['forgot_token'],"has_confirmed"=>0))->send();

			if (count($res) > 0)
			{
				$now = time();
				$checkTime = $res[0]['regusers']['forgot_time'] + Parametri::$confirmTime;
				if ($checkTime > $now)
				{
					$validToken = true;
					
					$clean['id_user'] = (int)$res[0]['regusers']['id_user'];
					
					$this->m('RegusersModel')->setPasswordCondition();
				
					$this->m('RegusersModel')->setFields("password",PASSWORD_HASH);

					$data['notice'] = null;
		
					if (isset($_POST['invia']))
					{
						$tessera = $this->request->post('tessera','');
						if (strcmp($tessera,'') === 0)
						{
							if ($this->m('RegusersModel')->checkConditions('insert'))
							{
								$this->m('RegusersModel')->values['forgot_time'] = 0;
								
								$this->m('RegusersModel')->sanitize("sanitizeDb");
								
								if ($this->m('RegusersModel')->pUpdate($clean['id_user']))
								{
									$_SESSION['result'] = 'password_cambiata';
									
									$this->redirect("avvisi".$urlAdd);
								}
							}
							else
							{
								$data['notice'] = $this->m('RegusersModel')->notice;
							}
						}
					}
					
					$this->m('RegusersModel')->fields = "password,confirmation";
		
					$data['values'] = $this->m('RegusersModel')->getFormValues('insert','sanitizeHtml');
				
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
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Modifica password"));
		$data['notice'] = null;
		$data["isAreaRiservata"] = true;
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/modifica-password";
		}
		
		$this->m('RegusersModel')->setFields('password:'.PASSWORD_HASH,'none');

		$this->m('RegusersModel')->setPasswordCondition();
		
		$id = (int)$this->s['registered']->status['id_user'];
		if (isset($_POST['updateAction'])) {
			$pass = $this->s['registered']->getPassword();
			if (passwordverify($_POST['old'], $pass))
			{
				$this->m('RegusersModel')->updateTable('update',$id);
				if ($this->m('RegusersModel')->result)
				{
					$data['notice'] = $this->m('RegusersModel')->notice;
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('RegusersModel')->notice;
				}
			}
			else
			{
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Vecchia password sbagliata")."</div><span class='evidenzia'>class_old</span>\n";
				
				$this->m('RegusersModel')->addError("old",gtext("Vecchia password sbagliata"));
				
				$this->m('RegusersModel')->result = false;
			}
		}
		
		$this->m('RegusersModel')->fields = "old,password,confirmation";
		
		$data['values'] = $this->m('RegusersModel')->getFormValues('insert','sanitizeHtml');
		
		$this->append($data);
		$this->load('cambia_password');
	}
	
	public function indirizzo($idSpedizione = 0)
	{
		$this->clean();
		
		if ($this->islogged)
		{
			$spedizione = $this->m("SpedizioniModel")->clear()->where(array(
				"id_spedizione"	=>	(int)$idSpedizione,
				"id_user"	=>	User::$id,
			))->record();
			
			$spedizione = htmlentitydecodeDeep($spedizione);
			$spedizione = strip_tagsDeep($spedizione);
			$spedizione = array_map(v("funzione_sanitize_spedizione_in_ordine"), $spedizione);
			
			if (!empty($spedizione))
			{
				echo json_encode($spedizione);
			}
		}
		
		echo "";
	}
	
	public function infoaccount($loadViewFile = true)
	{
		return;
	}
	
	// permette di modificare l'immagine di profilo
	public function immagine()
	{
		if (!v("attiva_gestione_immagine_utente"))
			$this->responseCode(403);
		
		$this->s['registered']->check(null,0);
		
		$redirect = RegusersModel::getRedirect();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/immagine-profilo";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Modifica immagine profilo"));
		$data['notice'] = null;
		$data['action'] = "/".Url::routeToUrl("modifica-account").RegusersModel::$redirectQueryString;
		$data["isAreaRiservata"] = true;
		
		if (isset($_GET['deleteFoto']))
		{
			$this->m['RegusersModel']->values = array('immagine'=>'');
			$this->m['RegusersModel']->pUpdate(User::$id);
		}
		
		if (isset($_POST["updateAction"]))
		{
			$this->m["RegusersModel"]->addValuesCondition("both",'checkNotEmpty',"immagine");
			
			$this->m["RegusersModel"]->sValues(array(
				"immagine"	=>	"",
			));
			
			if ($this->m["RegusersModel"]->upload())
			{
				if (isset($this->m["RegusersModel"]->values["immagine"]))
				{
					if ($this->m["RegusersModel"]->pUpdate(User::$id))
						$this->redirect("immagine-profilo");
				}
				else
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di selezionare un file")."</div>".'<div class="evidenzia">class_immagine</div>';
			}
			else
				$data['notice'] = $this->m('RegusersModel')->notice;
		}
		
		$data["utenteProfilo"] = $this->m["RegusersModel"]->selectId(User::$id);
		
		$this->append($data);
		$this->load('immagine_profilo');
	}
	
	public function modify()
	{
		$this->s['registered']->check(null,0);
		
		// Sistema maiuscole
		$this->correggiValoriPostFormRegistrazioneEOrdine();
		
		$redirect = RegusersModel::getRedirect();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".Url::routeToUrl("modifica-account");
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Modifica account"));
		$data['notice'] = null;
		$data['action'] = "/".Url::routeToUrl("modifica-account").RegusersModel::$redirectQueryString;
		$data["isAreaRiservata"] = true;
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
// 		$fields = 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2';
		
		$fields = OpzioniModel::stringaValori("CAMPI_SALVATAGGIO_UTENTE");
		
		if (v("attiva_ruoli"))
			$fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$fields .= ",id_tipo_azienda";
		
		$this->m('RegusersModel')->setFields($fields,'sanitizeAll');
		$this->m('RegusersModel')->setValue("completo", 1);
		
		$this->m('RegusersModel')->setConditions($tipo_cliente, "update", $pec, $codiceDestinatario);
		
		$this->setCondizioniDatiUtente((int)$this->iduser);
		
// 		$this->m('RegusersModel')->fields = "nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,tipo_cliente";
		
		if (v("permetti_modifica_account"))
			$this->m('RegusersModel')->updateTable('update',$this->iduser);
		else
			Html_Form::$forceStaticAttribute = "disabled";
		
		if ($this->m('RegusersModel')->queryResult)
		{
			$statoAccessoUtente = "logged";
			
			if ($this->m('RegusersModel')->modificataEmail)
				$statoAccessoUtente = $this->s['registered']->twoFactorResetSession();
			
			if (Output::$html)
			{
				$data['notice'] = $this->m('RegusersModel')->notice;
				
				F::checkPreparedStatement();
				
				$urlRedirect = RegusersModel::getUrlRedirect();
				
				if ($statoAccessoUtente == 'two-factor')
					$this->redirectTwoFactorSendMail();
				else if ($urlRedirect)
					HeaderObj::location($urlRedirect);
			}
		}
		else
		{
			if (!$this->m('RegusersModel')->result)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('RegusersModel')->notice;
		}
		
		if (Output::$html)
		{
			$data['values'] = $this->m('RegusersModel')->getFormValues('update','sanitizeHtml',$this->iduser);
			
			$this->estraiProvince();
			
			// $data['province'] = $this->m('ProvinceModel')->selectTendina("nazione");
			// $data['provinceSpedizione'] = $this->m('ProvinceModel')->selectTendina("nazione_spedizione");
			
			$data["tipoAzione"] = "update";
			
			$this->append($data);
			$this->load('form');
		}
		else
		{
			Output::setBodyValue("Errori", $this->m('RegusersModel')->errors);
			$this->load("api_output");
		}
	}
	
	public function impostaspedizioneperapp($id = 0)
	{
		return;
		
// 		$this->s['registered']->check(null,0);
// 		
// 		$clean["id"] = (int)$id;
// 		
// 		if ($clean["id"] > 0)
// 		{
// 			$this->m('SpedizioniModel')->setDaUsarePerApp($clean["id"]);
// 		}
// 		
// 		if (Output::$json)
// 			$this->load("api_output");
	}
	
	public function spedizione($id = 0)
	{
		VariabiliModel::$valori["attiva_spedizione"] = 1;
		
		$this->s['registered']->check(null,0);
		
		// Sistema maiuscole
		$this->correggiValoriPostFormRegistrazioneEOrdine();
		
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
		$data["isAreaRiservata"] = true;
		$clean["id"] = $data["id"] = (int)$id;
		
		if ($clean["id"] > 0)
		{
			$numero = $this->m('SpedizioniModel')->clear()->where(array(
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
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Gestisci spedizione", false));
		$data['notice'] = null;
		$data['action'] = "/gestisci-spedizione/".$clean["id"];
		
		$ordine = array();
		
		if (isset($_GET["cart_uid"]))
			$ordine = OrdiniModel::getByCartUid($_GET["cart_uid"]);
		
		if (!empty($ordine))
		{
			if ((int)$ordine["id_user"] !== (int)User::$id)
				$this->redirect("");
			
			$data['action'] .= "?cart_uid=".sanitizeHtml($_GET["cart_uid"]);
		}
		
		$campoObbligatoriProvincia = "dprovincia_spedizione";
		
		if (isset($_POST["nazione_spedizione"]))
		{
			// if ($_POST["nazione_spedizione"] == "IT")
			if (in_array((string)$_POST["nazione_spedizione"], NazioniModel::nazioniConProvince()))
				$campoObbligatoriProvincia = "provincia_spedizione";
		}
		
		$campiObbligatori = "indirizzo_spedizione,$campoObbligatoriProvincia,citta_spedizione,telefono_spedizione,nazione_spedizione,cap_spedizione";
		
// 		if (isset($_POST["nazione_spedizione"]) && $_POST["nazione_spedizione"] == "IT")
// 			$campiObbligatori .= ",cap_spedizione";
		
		$fields = OpzioniModel::stringaValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
		
// 		$fields = 'indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,citta_spedizione,telefono_spedizione,nazione_spedizione';
		
		$this->m('SpedizioniModel')->setFields($fields,'sanitizeAll');
		
		if (isset(User::$id))
			$this->m('SpedizioniModel')->values["id_user"] = User::$id;
		
		$this->m('SpedizioniModel')->clearConditions("strong");
		$this->m('SpedizioniModel')->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		$codiciSpedizioneAttivi = $this->m("NazioniModel")->selectCodiciAttiviSpedizione();
		$codiciNazioniAttiveSpedizione = implode(",",$codiciSpedizioneAttivi);
		
		$this->m('SpedizioniModel')->addStrongCondition("both",'checkIsStrings|'.$codiciNazioniAttiveSpedizione,"nazione_spedizione|".gtext("<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>"));
		
		if (v("permetti_modifica_account"))
			$this->m('SpedizioniModel')->updateTable('insert,update',$clean["id"]);
		else
			Html_Form::$forceStaticAttribute = "disabled";
		
		if ($this->m('SpedizioniModel')->queryResult)
		{
			if (!empty($ordine))
				$this->redirect("ordini/modifica/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]);
			else
				$this->redirect("riservata/indirizzi");
		}
		else
		{
			if (!$this->m('SpedizioniModel')->result)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('SpedizioniModel')->notice;
		}
		
		$submitAction = $id > 0 ? "update" : "insert";
		
		$data['values'] = $this->m('SpedizioniModel')->getFormValues($submitAction,'sanitizeHtml',$clean["id"],array("nazione_spedizione"=>"IT"));
		
		$data['province'] = $this->m('ProvinceModel')->selectTendina("nazione");
		$data['provinceSpedizione'] = $this->m('ProvinceModel')->selectTendina("nazione_spedizione");
		
		$this->append($data);
		$this->load('modifica_spedizione');
	}
	
	public function add()
	{
		if (!v("permetti_registrazione"))
			$this->redirect("");
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".$this->permalinkPaginaRegistrazione;
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("registrati"));
		
		$redirect = RegusersModel::getRedirect();
		
		$data['action'] = "/".$this->permalinkPaginaRegistrazione.RegusersModel::$redirectQueryString;
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$this->redirect(Url::routeToUrl("area-riservata"));
		}
		else
		{
			$this->formRegistrazione();
			
			$this->load($this->creaAccountViewFile);
		}
		
		$data["tipoAzione"] = "insert";
		
		$this->append($data);
	}
	
	public function addagente()
	{
		if (!v("attiva_agenti"))
			$this->responseCode(403);
		
		$this->permalinkPaginaRegistrazione = "crea-account-agente";
		$this->registrazioneAgente = true;
		$this->creaAccountViewFile = "form_agente";
		
		$this->add();
	}
	
	public function notice()
	{
		VariabiliModel::noCookieAlert();
		
		$data['title'] = $this->aggiungiNomeNegozioATitle('Avvisi');
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/avvisi";
		}
		
		$this->append($data);
		$this->load('notice');
	}
	
	// Forza il login come se fossi l'utente $idUser
	public function logincomeutente($idUser)
	{
		$this->clean();
		
		if (!v("permetti_di_loggarti_come_utente") || !User::$adminLogged || !VariabiliModel::checkToken("token_login_come_utente"))
			$this->responseCode(403);
		
		$utente = $this->m("RegusersModel")->clear()->where(array(
			"id_user"	=>	(int)$idUser,
			Users_CheckAdmin::$statusFieldName	=>	(int)Users_CheckAdmin::$statusFieldActiveValue,
		))->record();
		
		if (empty($utente))
			$this->responseCode(403);
		
		$res = $this->s['registered']->logout();
		$this->unsetAccessToken();
		
		// Forza il login
		$this->s['registered']->login(sanitizeAll($utente["username"]),null,true);
		$this->redirectUser();
	}
}
