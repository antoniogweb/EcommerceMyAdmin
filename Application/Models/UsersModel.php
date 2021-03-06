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

if (!defined('EG')) die('Direct access not allowed!');

class UsersModel extends GenericModel {
	
	public $applySoftConditionsOnPost = true;
	
	public function __construct() {
	
		$this->campoTitolo = "username";
		
		$this->_tables='adminusers';
		$this->_idFields='id_user';
		
		$this->orderBy = 'adminusers.id_user desc';
		$this->_lang = 'It';

		$this->_popupItemNames = array(
			'has_confirmed'	=>	'has_confirmed',
		);

		$this->_popupLabels = array(
			'has_confirmed'	=>	'ATTIVO?',
		);

		$this->_popupFunctions = array(
			'has_confirmed'	=>	'getYesNoUtenti',
		);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'username'		=>	array(
					'labelString'=>	'Username',
				),
				'has_confirmed'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Utente attivo',
					'options'	=>	array('sì'=>'0','no'=>'1'),
				),
				'password'			=>	array(
					'type'	=>	'Password',
				),
				'confirmation'		=>	array(
					'labelString'	=>	'Conferma la password',
					'type'			=>	'Password',
				),
				'id_user'	=>	array(
					'type'		=>	'Hidden'
				),
			),
		);
		
		parent::__construct();

		$this->addStrongCondition("both",'checkAlphaNum',"username|L'username deve essere una stringa alfanumerica");
		$this->addStrongCondition("insert",'checkNotEmpty',"password,confirmation");
		$this->addSoftCondition("both",'checkEqual',"password,confirmation|Le due password non coincidono");
	}
	
	public function update($id = null, $where = null)
	{
		$clean['id'] = (int)$id;
		if (strcmp($this->values['password'],sha1('')) === 0)
		{
			$this->delFields('password');
		}
		parent::update($clean['id']);
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = (int)$id;
			
		//cancello tutti i gruppi a cui è associato
		$ug = new UsersgroupsModel();
		$lug = $ug->clear()->where(array("id_user"=>$clean['id']))->toList("id_ug")->send();
		
		foreach ($lug as $id_ug)
		{
			$ug->del($id_ug);
		}
		
		return parent::del($clean['id']);
	}

	public function listaGruppi($id)
	{
		$clean["id"] = (int)$id;
		
		$ug = new UsersgroupsModel();
		
		$gruppi = $ug->clear()->select("admingroups.name")->inner("admingroups")->using("id_group")->where(array("id_user"=>$clean["id"]))->toList("admingroups.name")->send();
		
		if (count($gruppi) > 0)
		{
			return implode("<br />",$gruppi);
		}
		
		return "- -";
	}
}
