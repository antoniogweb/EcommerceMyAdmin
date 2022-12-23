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
	
	function __construct($model, $controller, $queryString, $application, $action)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if ($action != "login" && $action != "logout")
		{
			$this->s['admin']->check();
			
			if (!$this->m("UsersModel")->checkAccessoAlController($controller))
				$this->responseCode(403);
		}
		
		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();
		$this->model("UsersgroupsModel");

		$data["sezionePannello"] = "utenti";
		
		$data['posizioni'] = $this->_posizioni;
		
		$this->setArgKeys(array('page:forceInt'=>1,'username:sanitizeAll'=>'tutti','has_confirmed:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token','page_fgl:forceInt'=>1));
		
		$this->append($data);
	}

	public function login()
	{
		$data['action'] = $this->baseUrl."/users/login";
		$data['notice'] = null;
		
		$this->s['admin']->checkStatus();
		if ($this->s['admin']->status['status']=='logged') { //check if already logged
			$this->s['admin']->redirect('logged');
		}
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			$choice = $this->s['admin']->login(sanitizeAll($_POST['username']),$_POST['password']);

			switch($choice) {
				case 'logged':
					$this->redirect('panel/main',3,'Sei già loggato...');
					break;
				case 'accepted':
					$this->redirect('panel/main',0);
					break;
				case 'login-error':
					$data['notice'] = '<div class="alert">Username o password sbagliati</div>';
					break;
				case 'wait':
					$data['notice'] = '<div class="alert">Devi aspettare 5 secondi prima di poter eseguire nuovamente il login</div>';
					break;
			}
		}
		$this->append($data);
		$this->load('login');
	}
	
	public function logout() {
		$this->clean();
		$res = $this->s['admin']->logout();
		if ($res == 'not-logged') {
			header('Refresh: 0;url='.$this->baseUrl);

		} else if ($res == 'was-logged') {
			header('Refresh: 0;url='.$this->baseUrl);

		} else if ($res == 'error') {

		}

	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array('[[ledit]];adminusers.username;','getYesNoUtenti|adminusers:has_confirmed');
		$this->mainHead = 'Nome utente,Attivo?';
		
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
		
		$this->m[$this->modelName]->setValuesFromPost('username:sanitizeAll,has_confirmed:sanitizeAll,password','none');
		
		$this->formFields = 'username,has_confirmed,password,confirmation';
		
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
