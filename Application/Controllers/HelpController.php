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

class HelpController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "help";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->model("HelpitemModel");
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("help.titolo", "help.controlleraction", "help.tag");
		$this->mainHead = "Titolo,Controller/Action,Tag";
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("titolo")->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = "titolo,controlleraction,tag";
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function elementi($id = 0)
	{
		$this->_posizioni['elementi'] = 'class="active"';
		
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$data["ordinaAction"] = "ordinaelementi";
		
		$this->shift(1);
		
		$data['id'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_help";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "HelpitemModel";
		
		$this->mainFields = array("edit", "help_item.selettore", "help_item.mostra", "help_item.letto");
		$this->mainHead = "Titolo,Selettore,Mostra,Letto";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"elementi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->clear()->select("*")->orderBy("help_item.id_order")->where(array("id_help"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["HelpModel"]->where(array("id_help"=>$clean['id']))->field("titolo");
		
		$this->append($data);
	}
	
	public function ordinaelementi()
	{
		$this->orderBy = "id_order";
		$this->modelName = "HelpitemModel";
		
		parent::ordina();
	}
}
