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

class AttributiController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->session('admin');
		$this->model();

		$this->setArgKeys(array('page:forceNat'=>1,'titolo:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token'));

		$this->model("AttributiModel");
		$this->model("AttributivaloriModel");
		
		$this->s['admin']->check();
		
		$this->_topMenuClasses['prodotti'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->tabella = "varianti";
		
		$this->append($data);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("attributi.titolo", "attributi.nota_interna", "attributi.tipo");
		$this->mainHead = "Titolo,Nota,Tipo";
		$this->filters = array("titolo");
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
				))
				->orderBy("id_order")->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('titolo,nota_interna,tipo');
		
		parent::form($queryType, $id);
	}
	
	public function valori($id = 0)
	{
		$this->_posizioni['valori'] = 'class="active"';
		
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_a";
		
		$this->mainButtons = "ldel";
		
		$this->ordinaAction = "ordinavalori";
		
		$this->modelName = "AttributivaloriModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("edit");
		$this->mainHead = "Titolo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"valori/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("attributi_valori.*")->orderBy("attributi_valori.id_order")->where(array("id_a"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["AttributiModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function ordinavalori()
	{
		$this->orderBy = "id_order";
		
		$this->modelName = "AttributivaloriModel";
		
		parent::ordina();
	}
}
