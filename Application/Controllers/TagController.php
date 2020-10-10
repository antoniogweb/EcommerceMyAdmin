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

class TagController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->tabella = gtext("Tag / Linee",true);
		
		$this->model("ContenutitradottiModel");
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tag.titolo", "tag.attivo");
		$this->mainHead = "Titolo,Attivo";
		
// 		$this->filters = array(array("attivo",null,$this->filtroAttivo),"cerca");
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("id_order")->convert()->save();
		
		parent::main();
	}
	
	public function ordina()
	{
		$this->modelName = "TagModel";
		
		parent::ordina();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$fields = 'titolo,alias,attivo';
		
		if (v("mostra_seconda_immagine_tag"))
			$fields .= ",immagine_2";
		
		if (v("mostra_colore_testo"))
			$fields .= ",colore_testo_in_slide";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
