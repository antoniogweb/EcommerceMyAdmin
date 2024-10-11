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

if (!defined('EG')) die('Direct access not allowed!');

class PasswordController extends BaseController {

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->helper('Menu','users','panel');
		$this->helper('Array');

		$this->session('admin');
		$this->model('UsersModel');

		$this->m['UsersModel']->setFields('password','none');

		$this->m['UsersModel']->strongConditions['update'] = array('checkEqual'=>"password,confirmation|".gtext("Le due password non coincidono").UsersModel::evidenziaPassword());

		$this->m['UsersModel']->identifierName = 'id_user';
		
		$this->setArgKeys(array('token:sanitizeAll'=>'token'));
		$this->shift();
	}

	public function form($queryType = null, $id = null)
	{
		$this->shift(0);

		$this->s['admin']->check();
		
		$data['notice'] = null;
		
		$this->m['UsersModel']->setPasswordStrengthCondition("strong");
		
		$id = (int)$this->s['admin']->status['id_user'];
		if (isset($_POST['updateAction'])) {
			$pass = $this->s['admin']->getPassword();
			if (passwordverify($_POST['old'], $pass))
			{
				$this->m['UsersModel']->updateTable('update',$id);
				$data['notice'] = $this->m['UsersModel']->notice;
				
				if ($this->m['UsersModel']->queryResult)
				{
// 					$this->s['admin']->logout();
// 					$this->redirect('users/login',3,'logout');
				}
			}
			else
			{
				$data['notice'] = "<div class='alert alert-danger'>".gtext("Vecchia password sbagliata")."</div>\n";
			}
		}
		$data['menù'] = $this->h['Menu']->render('panel');

		$values = $this->m['UsersModel']->selectId($id);
		$values['old'] = '';
		$values['confirmation'] = '';
		
		$action = array('updateAction'=>'save');
		$form = new Form_Form($this->applicationUrl.$this->controller."/".$this->action."/".$this->viewArgs['token'],$action);
		$form->setEntry('old','Password');
		$form->entry['old']->labelString = 'Vecchia password:';
		$form->setEntry('password','Password');
		$form->setEntry('confirmation','Password');
		$form->entry['password']->labelString = 'Nuova password:';
		$form->entry['confirmation']->labelString = 'Conferma la password:';
		
		$form->entry['old']->className = 'form-control';
		$form->entry['password']->className = 'form-control';
		$form->entry['confirmation']->className = 'form-control';
		
		$data['form'] = $form->render($values,'old,password,confirmation');

		$this->append($data);
		$this->load('form');
	}

}
