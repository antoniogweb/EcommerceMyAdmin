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

class GroupsModel extends GenericModel {
	
	public $campoTitolo = "name";
	
	public function __construct() {
		$this->_tables='admingroups';
		$this->_idFields='id_group';
		
		$this->addStrongCondition("both",'checkNotEmpty',"name");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'utenti' => array("HAS_MANY", 'UsersgroupsModel', 'id_group', null, "RESTRICT", "L'elemento non può essere eliminato perché ha degli utenti collegati"),
			'controller' => array("HAS_MANY", 'GroupscontrollersModel', 'id_group', null, "CASCADE"),
		);
    }
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'name'	=>	array(
					"labelString"	=>	"Titolo gruppo",
				),
			),
		);
	}
	
	public function bulkaggiungiauser($record)
    {
		return "<i data-azione='aggiungiauser' title='".gtext("Aggiungi al gruppo")."' class='bulk_trigger help_trigger_aggiungi_a_user fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungiauser($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_user"]))
		{
			$ug = new UsersgroupsModel();
			
			$ug->sValues(array(
				"id_user"	=>	(int)$_GET["id_user"],
				"id_group"	=>	(int)$id,
			), "sanitizeDb");
			
			$ug->insert();
		}
    }
}
