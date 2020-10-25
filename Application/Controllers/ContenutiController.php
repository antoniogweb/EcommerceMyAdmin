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

class ContenutiController extends BaseController
{
	use TraitController;
	
	public $tabella = "elemento";
	
	public $argKeys = array('id_page:sanitizeAll'=>'tutti', 'id_c:sanitizeAll'=>'tutti', 'tipo:sanitizeAll'=>'tutti', 'id_tipo:sanitizeAll'=>'tutti');
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->model("ReggroupscontenutiModel");
		$this->model("TipicontenutoModel");
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
		$recordTipo = array();
		
		if ($this->viewArgs["id_tipo"] != "tutti")
			$recordTipo = $this->m["TipicontenutoModel"]->selectId($this->viewArgs["id_tipo"]);
		else
		{
			$recordContenuto = $this->m[$this->modelName]->selectId((int)$id);
			
			if ($recordContenuto)
				$recordTipo = $this->m["TipicontenutoModel"]->selectId($recordContenuto["id_tipo"]);
		}
		
// 		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo,lingua,immagine_1,immagine_2,descrizione,link_contenuto,link_libero,target");
		
		$fields = "lingua,attivo";
		
		if (!empty($recordTipo) && trim($recordTipo["campi"]))
			$fields .= ",".$recordTipo["campi"];
		else
		{
			if ($this->viewArgs["tipo"] == "GENERICO")
				$fields .= ",descrizione,immagine_1";
			else if ($this->viewArgs["tipo"] == "MARKER")
				$fields .= ",descrizione,coordinate";
		}
		
		$fields .= ",titolo";
		
		if ($this->viewArgs["id_tipo"] == "tutti" && $queryType == "insert")
			$fields .= ",id_tipo";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
		if ($this->viewArgs["id_c"] != "tutti")
			$this->m[$this->modelName]->setValue("id_c", $this->viewArgs["id_c"]);
		
		if ($this->viewArgs["tipo"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("tipo", $this->viewArgs["tipo"]);
			
// 			$this->tabella = strtolower($this->viewArgs["tipo"]);
		}
		
		if ($this->viewArgs["id_tipo"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("id_tipo", $this->viewArgs["id_tipo"]);
		}
		
		if ($recordTipo)
			$this->tabella = $recordTipo["titolo"];
		
		parent::form($queryType, $id);
	}
	
	
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
	
	public function gruppi($id = 0)
	{
		$this->_posizioni['gruppi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_cont";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ReggroupscontenutiModel";
		
		$this->m[$this->modelName]->setFields('id_group','sanitizeAll');
		$this->m[$this->modelName]->values['id_cont'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("reggroups.name");
		$this->mainHead = "Gruppo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"gruppi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("reggroups_contenuti.*,reggroups.*")->inner("reggroups")->using("id_group")->orderBy("reggroups.name")->where(array("id_cont"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["listaGruppi"] = $this->m[$this->modelName]->clear()->from("reggroups")->select("reggroups.name,reggroups.id_group")->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$data['tabella'] = "fascia";
		
		$data["titoloRecord"] = $this->m["ContenutiModel"]->where(array("id_cont"=>$clean['id']))->field("titolo");
		
		$this->append($data);
	}
}
