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

class TipidocumentoController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'id_group:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "utenti";
	
	public $tabella = "tipi documenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->model("TipidocumentoestensioniModel");
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tipi_documento.titolo");
		$this->mainHead = "Titolo";
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("titolo");
		
		if ($this->viewArgs["id_group"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiagruppo";
			
			$this->bulkActions = array(
				"checkbox_tipi_documento_id_tipo_doc"	=>	array("aggiungiagruppo","Aggiungi al gruppo"),
			);
			
			$this->m[$this->modelName]->sWhere(array("tipi_documento.id_tipo_doc not in (select id_tipo from reggroups_tipi where tipo='DO' and id_group = ?)",array((int)$this->viewArgs["id_group"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = "titolo,immagine";
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function estensioni($id = 0)
	{
		$this->_posizioni['estensioni'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_doc";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "TipidocumentoestensioniModel";
		
		$this->mainFields = array("tipi_documento_estensioni.estensione");
		$this->mainHead = "Estensione";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"estensioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("tipi_documento_estensioni.estensione")->where(array("id_tipo_doc"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["TipidocumentoModel"]->where(array("id_tipo_doc"=>$clean['id']))->field("titolo");
		
		$this->append($data);
	}
}
