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

Helper_List::$filtersFormLayout["filters"]["username"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Username ..",
	),
);

class UsersController extends BaseController {

	protected $_posizioni = array(
		"main"		=>	null,
		"gruppi"	=>	null,
	);
	
	public $sezionePannello = "utenti";
	
	public $tabella = "amministratori";
	
	public $argKeys = array('page:forceInt'=>1,'username:sanitizeAll'=>'tutti','has_confirmed:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token','page_fgl:forceInt'=>1);
	
	public $loginFormAction = "users/login";
	
	public $redirectUrlDopoLogin = "panel/main";
	
	public $redirectUrlDopoLogout = "";
	
	public $redirectUrlErroreTwoFactor = ""; // url di atterraggio quando c'è un errore nell'autenticazione a due fattori
	
	function __construct($model, $controller, $queryString, $application, $action)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if ($action != "login" && $action != "logout" && $action != "twofactor" && $action != "twofactorinviamail" && $action != "twofactorcheck" && $action != "notice")
		{
			$this->s['admin']->check();
			
			if (!ControllersModel::checkAccessoAlController(array($controller)))
				$this->responseCode(403);
		}
		
		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->creaSessioneAdmin();
		$this->model();
		$this->model("UsersgroupsModel");
		
		$this->redirectUrlErroreTwoFactor = $this->applicationUrl.$this->controller."/login";
		
		$data["sezionePannello"] = "utenti";
		
		$this->append($data);
	}
	
	protected function logAccountCheck($azione, $email)
	{
		$result = LogaccountModel::getInstance($azione)->check($email);
		
		if (!$result)
		{
			$_SESSION['result'] = 'pausa_'.$azione;
			$this->redirect("users/notice");
		}
	}
	
	public function notice()
	{
		$this->checkLogged();
		
		ob_start();
		include(tpf("/Elementi/Registrazione/Resoconto/account_pausa_LOGIN.php"));
		$data["output"] = ob_get_clean();
		
		$this->append($data);
		$this->load("notice");
	}
	
	protected function checkLogged()
	{
		$this->s['admin']->checkStatus();
		if ($this->s['admin']->status['status']=='logged') { //check if already logged
			$this->s['admin']->redirect('logged');
		} else if ($this->s['admin']->status['status']=='two-factor') {
			$this->s['admin']->logout();
		}
	}
	
	public function login()
	{
		if (!empty($_POST))
			IpcheckModel::check("POST_ADMIN");
		
		$data['action'] = $this->baseUrl."/".$this->loginFormAction;
		$data['notice'] = null;
		
		$this->checkLogged();
		// $this->s['admin']->checkStatus();
		// if ($this->s['admin']->status['status']=='logged') { //check if already logged
		// 	$this->s['admin']->redirect('logged');
		// } else if ($this->s['admin']->status['status']=='two-factor') {
		// 	$this->s['admin']->logout();
		// }
		
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			if (trim($_POST['username']))
				$this->logAccountCheck("LOGIN", $_POST['username']);
			
			$choice = $this->s['admin']->login(sanitizeAll($_POST['username']),$_POST['password']);
			
			switch($choice) {
				case 'logged':
					$this->redirect($this->redirectUrlDopoLogin,3,'Sei già loggato...');
					break;
				case 'accepted':
					LogaccountModel::getInstance()->set(1);
					$this->redirect($this->redirectUrlDopoLogin,0);
					break;
				case 'two-factor':
					$this->redirect($this->applicationUrl.$this->controller."/twofactorinviamail",0);
					break;
				case 'login-error':
					$data['notice'] = '<div class="alert alert-danger">Username o password sbagliati</div>';
					break;
				case 'wait':
					LogaccountModel::getInstance()->remove();
					$data['notice'] = '<div class="alert alert-danger">Devi aspettare 5 secondi prima di poter eseguire nuovamente il login</div>';
					break;
			}
		}
		
		$this->append($data);
		$this->load('login');
	}
	
	public function logout()
	{
		$this->clean();
		
		$res = $this->s['admin']->logout();
		
		if ($res == 'not-logged') {
			$this->redirect($this->redirectUrlDopoLogout,0);
		} else if ($res == 'was-logged') {
			$this->redirect($this->redirectUrlDopoLogout,0);
		} else if ($res == 'error') {

		}
	}
	
	protected function checkTwoFactor()
	{
		if (!v("attiva_autenticazione_due_fattori_admin"))
			$this->responseCode(403);
		
		if (!empty($_POST))
			IpcheckModel::check("POST_ADMIN");
		
		$this->s['admin']->checkStatus();
		
		if ($this->s['admin']->status['status'] != 'two-factor') { //check if already logged
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		}
		
		$uidt = $this->s['admin']->getTwoFactorUidt();
		
		$uModel = new UsersModel();
		
		$user = $uModel->selectId((int)$this->s['admin']->status["id_user"]);
		
		if (empty($user) || !$uidt)
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$sessioneTwo = $this->s['admin']->getTwoFactorSession();
		// $sessioneTwo = $this->s['admin']->getTwoFactorModel()->clear()->where(array(
		// 	"uid_two"	=>	sanitizeAll($uidt),
		// 	"attivo"	=>	0,
		// 	"id_user"	=>	(int)$this->s['admin']->status["id_user"],
		// 	// "user_agent_md5"	=>	getUserAgent(),
		// ))->record();
		
		if (empty($sessioneTwo) || $sessioneTwo["tentativi_verifica"] >= (int)v("autenticazione_due_fattori_numero_massimo_tentativi_admin"))
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		return array($sessioneTwo, $user);
	}
	
	public function twofactor()
	{
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		$data['action'] = $this->baseUrl."/".$this->applicationUrl.$this->controller."/twofactorcheck";
		$data['notice'] = null;
		
		$data["user"] = $user;
		$data["sessioneTwo"] = $sessioneTwo;
		
		$this->append($data);
		$this->load('two_factor');
	}
	
	public function twofactorinviamail()
	{
		$this->clean();
		
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		if ($sessioneTwo["numero_invii_codice"] >= (int)v("autenticazione_due_fattori_numero_massimo_invii_codice_admin"))
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$res = $this->s['admin']->getTwoFactorModel()->inviaCodice($sessioneTwo, $user);
		
		if ($res)
			$this->redirect($this->applicationUrl.$this->controller."/twofactor");
		else
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
	}
	
	public function twofactorcheck()
	{
		$this->clean();
		
		list($sessioneTwo, $user) = $this->checkTwoFactor();
		
		$clean["codice"] = trim($this->request->post("codice","", "sanitizeAll"));
		
		if ($clean["codice"])
		{
			if (!LogaccountModel::getInstance("LOGIN")->hasId())
				$clean["codice"] = randomToken();
			
			if ($this->s['admin']->getTwoFactorModel()->checkCodice($sessioneTwo, $clean["codice"], (int)$user["id_user"]))
			{
				// Segno il login come avvenuto con successo
				LogaccountModel::getInstance("LOGIN")->restoreFromSession()->set(1);
				
				$this->redirect($this->redirectUrlDopoLogin);
			}
			else
				flash("notice","<div class='alert alert-danger'>".gtext("Attenzione, il codice non è corretto, si prega di riprovare")."</div>");
		}
		else
			$this->redirect($this->redirectUrlErroreTwoFactor,0);
		
		$this->redirect($this->applicationUrl.$this->controller."/twofactor");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array('[[ledit]];adminusers.username;','getYesNoUtenti|adminusers:has_confirmed');
		$this->mainHead = 'Nome utente,Attivo?';
		
		if (v("attiva_autenticazione_due_fattori_admin") || v("attiva_campo_email_admin"))
		{
			$this->mainFields[] = "adminusers.email";
			$this->mainHead .= ",Email";
		}
		
		$this->filters = array("username");
		
		$this->m[$this->modelName]->clear()->where(array(
			"lk" => array('adminusers.username' => $this->viewArgs['username']),
		))->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$clean['id'] = (int)$id;
		
		if (v("attiva_autenticazione_due_fattori_admin") || v("attiva_campo_email_admin"))
		{
			$this->m[$this->modelName]->setValuesFromPost('username:sanitizeAll,has_confirmed:sanitizeAll,email:sanitizeAll,password','none');
			$this->formFields = 'username,has_confirmed,email,password,confirmation';
			
			$this->m[$this->modelName]->addStrongCondition("both",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo E-mail</b>").'<div style="display:none;" rel="hidden_alert_notice">email</div>');
		}
		else
		{
			$this->m[$this->modelName]->setValuesFromPost('username:sanitizeAll,has_confirmed:sanitizeAll,password','none');
			$this->formFields = 'username,has_confirmed,password,confirmation';
		}
		
		$this->m[$this->modelName]->setPasswordStrengthCondition();
		
		parent::form($queryType, $id);
	}
	
	public function gruppi($id = 0)
	{
		$this->_posizioni['gruppi'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_group";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "UsersgroupsModel";
		
		$this->m($this->modelName)->updateTable('del');
		
		$this->mainFields = array("admingroups.name");
		$this->mainHead = "Nome gruppo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"gruppi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->select("*")->inner(array("gruppo"))->orderBy("admingroups.name")->where(array(
			"id_user"	=>	$clean['id'],
		))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["UsersModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
