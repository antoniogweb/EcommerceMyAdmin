<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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
		'id_tipo_car:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->session('admin');
		
		$this->model("PagesModel");
		$this->model("CaratteristicheModel");
		
		$this->s['admin']->check();
	}
	
	public function main()
	{
		$this->shift();
		
		$filtroTipologia = array("tutti" => "Tipologia") + $this->m["CaratteristicheModel"]->selectTipologia();
		
		$section = "";
		
		if ($this->viewArgs["id_page"] != "tutti")
			$section = $this->m["PagesModel"]->section((int)$this->viewArgs["id_page"], true);
		
		$filtroCaratteristica = array("tutti" => "Caratteristica") + $this->m[$this->modelName]->selectCaratteristica();
		
		if (($this->viewArgs["id_page"] != "tutti" && $this->viewArgs["id_tipo_car"] != "tutti") || !v("attiva_tipologie_caratteristiche"))
		{
			$this->filters = array(array("id_car_f", null, $filtroCaratteristica), "titolo");
			$this->mainFields = array("caratteristiche.titolo", "caratteristiche_valori.titolo");
			$this->mainHead = "Caratteristica,Valore";
		}
		else
		{
			$this->filters = array(array("id_tipologia_caratteristica", null, $filtroTipologia), array("id_car_f", null, $filtroCaratteristica), "titolo");
			$this->mainFields = array("tipologie_caratteristiche.titolo", "caratteristiche.titolo", "caratteristiche_valori.titolo");
			$this->mainHead = "Tipologia,Caratteristica,Valore";
		}
		
		if (v("immagine_in_caratteristiche"))
		{
			$this->mainFields[] = "thumb";
			$this->mainHead .= ",Immagine";
		}
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiaprodotto";
			$this->mainHead .= ",Aggiungi";
		}
		
		$this->m[$this->modelName]->clear()->select("*")
				->left(array("caratteristica"))
				->left("tipologie_caratteristiche")->on("tipologie_caratteristiche.id_tipologia_caratteristica = caratteristiche.id_tipologia_caratteristica")
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
					"caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs['id_tipologia_caratteristica'],
					"caratteristiche.id_car"	=>	$this->viewArgs['id_car_f'],
					"caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs['id_tipo_car'],
				))
				->orderBy("tipologie_caratteristiche.titolo,caratteristiche.titolo, caratteristiche_valori.titolo");
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiaprodotto";
			
			$this->bulkActions = array(
				"checkbox_caratteristiche_valori_id_cv"	=>	array("aggiungiaprodotto","Aggiungi al prodotto"),
			);
			
			if ($section)
				$this->m[$this->modelName]->aWhere(array(
					"caratteristiche.section"	=>	sanitizeAll($section),
				));
			
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
		
		$fields = "titolo,alias";
		
		if (v("attiva_titolo_2_valori_caratteristiche"))
			$fields .= ",titolo_2";
		
		if (v("immagine_in_caratteristiche"))
			$fields .= ",immagine";
		
		if ($this->viewArgs["id_page"] != "tutti")
			$fields = "id_car,".$fields;
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_car"] != "tutti")
			$this->m[$this->modelName]->setValue("id_car", $this->viewArgs["id_car"]);
		
		parent::form($queryType, $id);
	}
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
	
	public function elenco($section = "")
	{
		header('Content-type: application/json; charset=utf-8');
		
		$this->clean();
		$campoTitolo = $this->m[$this->modelName]->campoTitolo;
		
		$elementi = $this->m[$this->modelName]->clear()->inner(array("caratteristica"))->where(array(
			"caratteristiche.section"	=>	sanitizeAll($section),
		))->select("distinct caratteristiche_valori.$campoTitolo")->orderBy("caratteristiche_valori.".$campoTitolo)->toList("caratteristiche_valori.".$campoTitolo)->send();
		
		$elementi = htmlentitydecodeDeep($elementi);
		
		echo json_encode($elementi);
	}
}
