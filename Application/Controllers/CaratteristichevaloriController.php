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

class CaratteristichevaloriController extends BaseController {
	
	public $tabella = "valori variante";
	
	public $argKeys = array(
		'id_car:sanitizeAll'=>'tutti',
		'titolo:sanitizeAll'=>'tutti',
		'id_page:sanitizeAll'=>'tutti',
		'id_tipologia_caratteristica:sanitizeAll'=>'tutti',
		'id_car_f:sanitizeAll'=>'tutti',
	);
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->session('admin');

		$this->model("CaratteristicheModel");
		
		$this->s['admin']->check();
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tipologie_caratteristiche.titolo", "caratteristiche.titolo", "caratteristiche_valori.titolo");
		$this->mainHead = "Tipologia,Caratteristica,Valore";
		
		$filtroTipologia = array("tutti" => "Tipologia") + $this->m["CaratteristicheModel"]->selectTipologia();
		$filtroCaratteristica = array("tutti" => "Caratteristica") + $this->m[$this->modelName]->selectCaratteristica();
		
		$this->filters = array(array("id_tipologia_caratteristica", null, $filtroTipologia), array("id_car_f", null, $filtroCaratteristica), "titolo");
		
		$this->m[$this->modelName]->clear()->select("*")
				->left(array("caratteristica"))
				->left("tipologie_caratteristiche")->on("tipologie_caratteristiche.id_tipologia_caratteristica = caratteristiche.id_tipologia_caratteristica")
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
					"caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs['id_tipologia_caratteristica'],
					"caratteristiche.id_car"	=>	$this->viewArgs['id_car_f'],
				))
				->orderBy("tipologie_caratteristiche.titolo,caratteristiche.titolo, caratteristiche_valori.titolo");
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->bulkQueryActions = "aggiungiaprodotto";
			
			$this->bulkActions = array(
				"checkbox_caratteristiche_valori_id_cv"	=>	array("aggiungiaprodotto","Aggiungi al prodotto"),
			);
			
			$this->m[$this->modelName]->sWhere("caratteristiche_valori.id_cv not in (select id_cv from pages_caratteristiche_valori where id_cv is not null and id_page = ".(int)$this->viewArgs["id_page"].")");
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->model("ContenutitradottiModel");
		
		$menuLinks = "save";
		
		if ($this->viewArgs["id_page"] != "tutti")
			$menuLinks = "back,save";
		
		$this->menuLinks = $this->menuLinksInsert = $menuLinks;
		
		$fields = "titolo";
		
		if ($this->viewArgs["id_page"] != "tutti")
			$fields = "id_car,titolo";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_car"] != "tutti")
			$this->m[$this->modelName]->setValue("id_car", $this->viewArgs["id_car"]);
		
		parent::form($queryType, $id);
	}
}
