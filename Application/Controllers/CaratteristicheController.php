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

class CaratteristicheController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti',
		'id_tipologia_caratteristica:sanitizeAll'=>'tutti',
		'id_tip_car:sanitizeAll'=>'tutti',
	);
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->session('admin');
		$this->model();

		$this->model("CaratteristicheModel");
		$this->model("CaratteristichevaloriModel");
		
		$this->s['admin']->check();
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->tabella = "caratterisiche";
		
		$this->append($data);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tipologie_caratteristiche.titolo", "caratteristiche.titolo");
		$this->mainHead = "Tipologia,Titolo";
		
		if (v("attiva_filtri_caratteristiche"))
		{
			$this->mainFields[] = "caratteristiche.filtro";
			$this->mainHead .= ",Usata come filtro";
		}
		
		$filtroTipologia = array("tutti" => "Tipologia") + $this->m[$this->modelName]->selectTipologia();
		
		$this->filters = array(array("id_tipologia_caratteristica", null, $filtroTipologia), "titolo");
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("tipologia"))
				->where(array(
					"lk" => array('caratteristiche.titolo'			=>	$this->viewArgs['titolo']),
					"caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs['id_tipologia_caratteristica'],
				))
				->orderBy("caratteristiche.id_order")->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'titolo,alias';
		
		if (v("attiva_filtri_caratteristiche"))
			$fields .= ",filtro";
		
		if ($this->viewArgs["id_tip_car"] == "tutti")
			$fields .= ",id_tipologia_caratteristica";
		
		if (v("mostra_tipo_caratteristica"))
			$fields .= ",tipo";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_tip_car"] != "tutti")
			$this->m[$this->modelName]->setValue("id_tipologia_caratteristica", $this->viewArgs["id_tip_car"]);
		
		parent::form($queryType, $id);
	}
	
	public function valori($id = 0)
	{
		$this->_posizioni['valori'] = 'class="active"';
		
		$data["orderBy"] = $this->orderBy = "titolo";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_car";
		
		$this->mainButtons = "ldel";
		
		$this->ordinaAction = "ordinavalori";
		
		$this->modelName = "CaratteristichevaloriModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$mainFields = array();
		$mainHeadArray = array();
		
		if (v("immagine_in_caratteristiche"))
		{
			$mainFields[] = "thumb";
			$mainHeadArray[]= "Immagine";
		}
		
		$mainFields[] = "edit";
		$mainHeadArray[]= "Titolo";
		
		$this->mainFields = $mainFields;
		$this->mainHead = implode(",", $mainHeadArray);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"valori/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("caratteristiche_valori.*")->orderBy("caratteristiche_valori.id_order")->where(array("id_car"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["CaratteristicheModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function ordinavalori()
	{
		$this->orderBy = "id_order";
		
		$this->modelName = "CaratteristichevaloriModel";
		
		parent::ordina();
	}
	
	//ottieni lista valori caratteristica
	public function lista($id_car)
	{
		header ("Content-Type:text/xml");
		
		$this->clean();
		
		$clean['id_car'] = (int)$id_car;

		$data['lista'] = $this->m['CaratteristichevaloriModel']->where(array('id_car'=>$clean['id_car']))->toList("id_cv","titolo")->send();

		$this->append($data);
		$this->load("select_xml");
	}
}
