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

class ReggroupsController extends BaseController
{
// 	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->tabella = gtext("gruppi",true);
		
		$this->_topMenuClasses['clienti'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$this->append($data);
		
		$this->model("ContenutitradottiModel");
		$this->model("ReggroupstipiModel");
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("reggroups.name");
		$this->mainHead = "Titolo";
// 		$this->filters = array(array("attivo",null,$this->filtroAttivo),"cerca");
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("name")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = "name";
		
		if (v("permetti_di_collegare_gruppi_utenti_a_newsletter"))
			$fields .= ",sincronizza_newsletter";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function tipi($id = 0)
	{
		$this->_posizioni['tipi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_group";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ReggroupstipiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("tipologiacontenuto","categoriacontenuto");
		$this->mainHead = "Tipo,Tipologia contenuto / documento";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"tipi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("reggroups_tipi.tipo,reggroups_tipi.id_order")->where(array(
			"id_group"	=>	$clean['id'],
		))->convert()->save();
		
		$this->tabella = "gruppi";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ReggroupsModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
