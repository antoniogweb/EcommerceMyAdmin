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

class AttributiController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $argKeys = array(
		'page:forceNat'		=>	1,
		'titolo:sanitizeAll'=>	'tutti',
		'token:sanitizeAll'	=>	'token',
		'id_page:sanitizeAll'	=>	'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->session('admin');
		$this->model();
		$this->model("AttributiModel");
		$this->model("AttributivaloriModel");
		
		$this->s['admin']->check();
		
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
				->orderBy("id_order")->convert();
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->bulkQueryActions = "aggiungiapagina";
			
			$this->bulkActions = array(
				"checkbox_caratteristiche_id_car"	=>	array("aggiungiapagina","Aggiungi alla pagina"),
			);
			
			$this->m[$this->modelName]->aWhere(array(
				"id_page"	=>	(int)$this->viewArgs["id_page"]
			));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('titolo,nota_interna,tipo');
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
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
		
		$tipo = AttributiModel::getTipo($clean['id']);
		
		$data["aggiuntaLibera"] = true;
		
		if ($tipo == "IMMAGINE")
		{
			$this->mainFields = array("thumb", "edit");
			$this->mainHead = "Immagine,Titolo";
			
			$this->colProperties = array(
				array(
					'width'	=>	'60px',
				),
				array(
					'width'	=>	'120px',
				),
			);
			
			$data["aggiuntaLibera"] = false;
		}
		else
		{
			$this->mainFields = array("edit");
			$this->mainHead = "Titolo";
		}
		
		if ($tipo == "COLORE")
			$data["aggiuntaLibera"] = false;
		
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
