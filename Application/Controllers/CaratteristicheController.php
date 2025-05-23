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

class CaratteristicheController extends BaseController {
	
	public $orderBy = "id_order";
	
	public $sezionePannello = "ecommerce";
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti',
		'id_tipologia_caratteristica:sanitizeAll'=>'tutti',
		'id_tip_car:sanitizeAll'=>'tutti',
		'id_c:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		$this->orderBy = v("attiva_filtri_caratteristiche") ? "id_order" : "titolo";
		
		parent::__construct($model, $controller, $queryString, $application, $action);

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
		
		$this->mainFields = array();
		$this->mainHead = "";
		
		if (v("attiva_tipologie_caratteristiche"))
		{
			$this->mainFields[] = "tipologie_caratteristiche.titolo";
			$this->mainFields[] = "caratteristiche.titolo";
			$this->mainHead = "Tipologia,Titolo";
		}
		else
		{
			$this->mainFields[] = "caratteristiche.titolo";
			$this->mainHead .= "Titolo";
		}
		
		$this->mainFields[] = "caratteristiche.nota_interna";
		$this->mainHead .= ",Nota interna";
		
		$this->filters = array();
		
		if (v("attiva_filtri_caratteristiche"))
		{
			$this->mainFields[] = "caratteristiche.filtro";
			$this->mainHead .= ",Usata come filtro";
			
			if (v("attiva_tipologie_caratteristiche"))
			{
				$filtroTipologia = array("tutti" => "Tipologia") + $this->m[$this->modelName]->selectTipologia();
				$this->filters[] = array("id_tipologia_caratteristica", null, $filtroTipologia);
			}
		}
		
		$this->filters[] = "titolo";
		
		if ($this->viewArgs["id_c"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiacategoria";
			$this->mainHead .= ",Aggiungi";
		}
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("tipologia"))
				->where(array(
					"lk" => array('caratteristiche.titolo'			=>	$this->viewArgs['titolo']),
					"caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs['id_tipologia_caratteristica'],
				))
				->orderBy("caratteristiche.id_order")->convert();
		
		if ($this->viewArgs["id_c"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiacategoria";
			
			$this->bulkActions = array(
				"checkbox_caratteristiche_id_car"	=>	array("aggiungiacategoria","Aggiungi alla categoria"),
			);
			
			$this->m[$this->modelName]->sWhere(array("caratteristiche.filtro = 'Y' and caratteristiche.id_car not in (select id_car from categories_caratteristiche where id_car is not null and id_c = ?)",array((int)$this->viewArgs["id_c"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'titolo,alias,nota_interna';
		
		if (v("attiva_filtri_caratteristiche"))
			$fields .= ",filtro";
		
		if ($this->viewArgs["id_tip_car"] == "tutti" && v("attiva_tipologie_caratteristiche"))
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
		
		$ordinamento = v("attiva_filtri_caratteristiche") ? "id_order" : "titolo";
		
		$data["orderBy"] = $this->orderBy = $ordinamento;
		
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
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>500,'mainMenu'=>'back','mainAction'=>"valori/".$clean['id'],'pageVariable'=>'page_fgl');
		
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
	
	public function elenco($section = "")
	{
		header('Content-type: application/json; charset=utf-8');
		
		$this->clean();
		$campoTitolo = $this->m[$this->modelName]->campoTitolo;
		
		$elementi = $this->m[$this->modelName]->clear()->where(array(
			"section"	=>	sanitizeAll($section),
		))->select("distinct $campoTitolo")->orderBy($campoTitolo)->toList($campoTitolo)->send();
		
		$elementi = htmlentitydecodeDeep($elementi);
		
		echo json_encode($elementi);
	}
}
