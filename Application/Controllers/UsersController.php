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

if (!defined('EG')) die('Direct access not allowed!');

class UsersController extends BaseController {

	protected $_posizioni = array(
		"main"		=>	null,
		"gruppi"	=>	null,
	);
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();
		$this->model("UsersgroupsModel");

		$data["sezionePannello"] = "utenti";
		
		$data['posizioni'] = $this->_posizioni;
		
		$this->setArgKeys(array('page:forceInt'=>1,'username:sanitizeAll'=>'tutti','has_confirmed:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token','page_fgl:forceInt'=>1));

		$this->_topMenuClasses['utenti'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
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

	public function main() { //view all the users

		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>20, 'mainMenu'=>'add'));
		
		$this->scaffold->loadMain('[[checkbox]];adminusers.id_user;,[[ledit]];adminusers.username;,getYesNoUtenti|adminusers:has_confirmed','adminusers:id_user','ldel,ledit');

		$this->scaffold->update('del');
		
		$this->m[$this->modelName]->bulkAction("del");
		
		$this->scaffold->setHead('[[bulkselect:checkbox_adminusers_id_user]],NOME UTENTE,ATTIVO?');
		$this->scaffold->itemList->setFilters(array(null,'username'));

		$this->scaffold->itemList->setBulkActions(array(
			"checkbox_adminusers_id_user"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$whereClauseArray = array(
			'has_confirmed'	=>	$this->viewArgs['has_confirmed'],
		);
		$this->scaffold->model->clear()->where($whereClauseArray);
		
		if (strcmp($this->viewArgs['username'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('n!adminusers.username' => $this->viewArgs['username']),
// 				'n!adminusers.username' =>	"like '%".$this->viewArgs['username']."%'",
			);

			$this->scaffold->model->aWhere($where);
		}
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci un nuovo utente';
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$data['scaffold'] = $this->scaffold->render();
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
		
		$data["tabella"] = "amministratori";
		
		$this->append($data);
		$this->load('main');
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$clean['id'] = (int)$id;
		
		$this->m[$this->modelName]->setValuesFromPost('username:sanitizeAll,has_confirmed:sanitizeAll,password:sha1','none');
		
		$this->formFields = 'username,has_confirmed,password,confirmation';
		
		parent::form($queryType, $id);
		
		$this->_posizioni['main'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;

		if (strcmp($queryType,'update') === 0)
		{
			$data['id_user'] = $clean['id'];
			$data["titoloPagina"] = $this->m[$this->modelName]->where(array("id_user"=>$clean['id']))->field("username");
			$data['numeroGruppi'] = $this->m["UsersgroupsModel"]->where(array("id_user"=>$clean['id']))->rowNumber();
		}
	
		$this->append($data);
	}
	
// 	public function form($queryType = 'insert',$id = 0)
// 	{
// 		$this->shift(2);
// 		
// 		$this->_posizioni['main'] = 'class="active"';
// 		$data['posizioni'] = $this->_posizioni;
// 		
// 		$qAllowed = array("insert","update");
// 		
// 		if (in_array($queryType,$qAllowed))
// 		{
// 			$clean['id'] = (int)$id;
// 			
// 			$data['type'] = $queryType;
// 			
// 			$this->s['admin']->check();
// // 			if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
// 			
// 			$this->m['UsersModel']->setFields('username:sanitizeAll,has_confirmed:sanitizeAll,password:sha1','none');
// 			
// 			$this->m['UsersModel']->updateTable('insert,update',$clean['id']);
// 
// 			if (strcmp($queryType,'insert') === 0 and $this->m[$this->modelName]->queryResult)
// 			{
// 				$lId = $this->m[$this->modelName]->lId;
// 				$this->redirect($this->controller.'/form/update/'.$lId.$this->viewStatus);
// 			}
// 			
// 			$params = array(
// 				'formMenu'=>"back,save",
// 			);
// 			
// 			$this->loadScaffold('form', $params);
// 			$this->scaffold->loadForm($queryType,"users/form/$queryType/".$clean['id']);
// 			
// 			$this->scaffold->model->fields = 'username,has_confirmed,password,confirmation';
// 			
// 			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);
// 
// 			$data['scaffold'] = $this->scaffold->render();
// 			
// 			$data['menu'] = $this->scaffold->html['menu'];
// 			$data['main'] = $this->scaffold->html['main'];
// 			$data['notice'] = $this->scaffold->model->notice;
// 		
// 			if (strcmp($queryType,'update') === 0)
// 			{
// 				$data['id_user'] = $clean['id'];
// 				$data["titoloPagina"] = $this->m[$this->modelName]->where(array("id_user"=>$clean['id']))->field("username");
// 				$data['numeroGruppi'] = $this->m["UsersgroupsModel"]->where(array("id_user"=>$clean['id']))->rowNumber();
// 			}
// 		
// 			$this->append($data);
// 			$this->load('form');
// 		}
// 	}
	
	public function gruppi($id = 0)
	{
		$this->s['admin']->check();
		
		$this->_posizioni['gruppi'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$this->m["UsersgroupsModel"]->bulkAction("del");
		
		$data['type'] = "gruppi";
		
		$this->shift(1);
		
		$clean['id'] = (int)$id;
		$data['id_user'] = $clean['id'];
		
		$data["titoloPagina"] = $this->m[$this->modelName]->where(array("id_user"=>$clean['id']))->field("username");
		
		$this->modelName = "UsersgroupsModel";
		
		Params::$nullQueryValue = 'tutti';
		
		$this->m['UsersgroupsModel']->setFields('id_group','sanitizeAll');
		$this->m['UsersgroupsModel']->values['id_user'] = $clean['id'];
		$this->m['UsersgroupsModel']->updateTable('insert,del');
		
		$mainAction = "gruppi/".$clean['id'];
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>$mainAction,'pageVariable'=>'page_fgl'));

		$this->scaffold->fields = "adminusers_groups.*,admingroups.*";
		$this->scaffold->loadMain('[[checkbox]];adminusers_groups.id_ug;,admingroups.name','adminusers_groups:id_ug','del');
		$this->scaffold->setHead('[[bulkselect:checkbox_adminusers_groups_id_ug]],GRUPPO');
		
		$this->scaffold->itemList->setBulkActions(array(
			"checkbox_adminusers_groups_id_ug"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$this->scaffold->model->clear()->inner("admingroups")->using("id_group")->orderBy("admingroups.name")->where(array("id_user"=>$clean['id']))->convert();
		
// 		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_user" id="'.$clean['id'].'"';
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['numeroGruppi'] = $this->scaffold->model->rowNumber();
		
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$data["listaGruppi"] = $this->scaffold->model->clear()->from("admingroups")->select("admingroups.name,admingroups.id_group")->orderBy("admingroups.name")->toList("admingroups.id_group","admingroups.name")->send();
		
		$this->append($data);
		$this->load('gruppi');
	}

}
