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

class GroupsController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'id_user:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->tabella = gtext("gruppi amministratori",true);
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("admingroups.name");
		$this->mainHead = "Titolo";
		
		if ($this->viewArgs["id_user"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiauser";
			$this->mainHead .= ",Aggiungi";
		}
		
		$this->m[$this->modelName]->clear()->orderBy("name")->convert();
		
		if ($this->viewArgs["id_user"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiauser";
			
			$this->bulkActions = array(
				"checkbox_admingroups_id_group"	=>	array("aggiungiauser","Aggiungi al gruppo"),
			);
			
			$this->m[$this->modelName]->sWhere(array("admingroups.id_group not in (select id_group from adminusers_groups where id_group is not null and id_user = ? )",array((int)$this->viewArgs["id_user"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('name');
		
		parent::form($queryType, $id);
	}
	
	public function controllers($id = 0)
	{
		$this->_posizioni['controllers'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_group";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "GroupscontrollersModel";
		
		$this->m($this->modelName)->updateTable('del');
		
		$this->mainFields = array("controllers.titolo","controllers.codice");
		$this->mainHead = "Titolo,Codice";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"controllers/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->select("*")->inner(array("controller"))->orderBy("controllers.id_order")->where(array(
			"id_group"	=>	$clean['id'],
		))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["GroupsModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
