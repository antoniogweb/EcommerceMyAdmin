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

class DocumentiController extends BaseController
{
	use TraitController;
	
	public $tabella = "documenti";
	
	public $argKeys = array('id_page:sanitizeAll'=>'tutti');
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->model("ReggroupsdocumentiModel");
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
// 		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo,lingua,immagine_1,immagine_2,descrizione,link_contenuto,link_libero,target");
		
		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo_doc,filename,data_documento,lingua,immagine,descrizione");
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
		parent::form($queryType, $id);
	}
	
	public function caricamolti($id = 0)
	{
		$this->shift(1);
		
		$this->_posizioni['caricamolti'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['menu'] = "";
		
		$this->append($data);
		
		$this->load("carica_molti");
	}
	
	public function upload()
	{
		header('Content-type: application/json');
		
		$this->shift();
		
		$this->clean();
		
		$this->m["DocumentiModel"]->setValues(array(
			"filename"	=>	"",
		));
		
		$result = "KO";
		$errore = "";
		
		$erroreGenerico = gtext("Errore caricamento file: ");
		
		if (isset($_FILES["filename"]["name"]))
			$erroreGenerico .= "<b>".sanitizeHtml($_FILES["filename"]["name"])."</b> ";
		
		if ($this->m["DocumentiModel"]->upload("insert"))
		{
			$ext = $this->m["DocumentiModel"]->files->ext;
			
			$this->m["DocumentiModel"]->setValue("estensione", $ext);
			
			if ($_FILES["filename"]["type"])
				$this->m["DocumentiModel"]->setValue("content_type", $_FILES["filename"]["type"]);
			
			$idTipoDoc = TipidocumentoestensioniModel::cercaTipoDocumentoDaEstensione($ext);
			
			$this->m["DocumentiModel"]->setValue("id_tipo_doc", $idTipoDoc);
			
			if ($this->viewArgs["id_page"] != "tutti")
				$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
			
			$this->m["DocumentiModel"]->setValue("titolo", $this->m["DocumentiModel"]->files->getNameWithoutFileExtension($_FILES["filename"]["name"]));
			$this->m["DocumentiModel"]->setValue("data_documento", date("Y-m-d"));
			
			if ($this->m["DocumentiModel"]->pInsert())
				$result = "OK";
			else
				$errore = $erroreGenerico.strip_tags($this->m["DocumentiModel"]->notice);
		}
		else
			$errore = $erroreGenerico.strip_tags($this->m["DocumentiModel"]->notice);
		
		echo json_encode(array(
			"result"	=>	$result,
			"errore"	=>	$errore,
		));
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
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
		$this->id_name = "id_doc";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ReggroupsdocumentiModel";
		
		$this->m[$this->modelName]->setFields('id_group','sanitizeAll');
		$this->m[$this->modelName]->values['id_doc'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("reggroups.name");
		$this->mainHead = "Gruppo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"gruppi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("reggroups_documenti.*,reggroups.*")->inner("reggroups")->using("id_group")->orderBy("reggroups.name")->where(array("id_doc"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["listaGruppi"] = $this->m[$this->modelName]->clear()->from("reggroups")->select("reggroups.name,reggroups.id_group")->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$data['tabella'] = "documenti";
		
		$data["titoloRecord"] = $this->m["DocumentiModel"]->where(array("id_doc"=>$clean['id']))->field("titolo");
		
		$this->append($data);
	}
}
