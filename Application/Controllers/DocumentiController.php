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

class DocumentiController extends BaseController
{
	use TraitController;
	
	public $tabella = "documenti";
	
	public $argKeys = array(
		'id_page:sanitizeAll'	=>	'tutti',
		'compresso:forceInt'	=>	0,
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->model("ReggroupsdocumentiModel");
		$this->model("DocumentilingueModel");
		
		$this->m[$this->modelName]->uploadFields["filename"]["allowedExtensions"] = v("estensioni_accettate_documenti");
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
// 		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo,lingua,immagine_1,immagine_2,descrizione,link_contenuto,link_libero,target");
		
		$fields = "titolo,id_tipo_doc,filename,data_documento,lingua";
		
		if (v("attiva_immagine_in_documenti"))
			$fields .= ",immagine";
		
		$fields .= ",descrizione";
		
		if ($queryType == "update" && !$this->m[$this->modelName]->hasPage((int)$id))
			$fields .= ",id_page";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
		parent::form($queryType, $id);
	}
	
	public function lingue($id = 0)
	{
		$this->model("LingueModel");
		
		$this->_posizioni['lingue'] = 'class="active"';
		
		$this->shift(1);
		
		$data['id'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_doc";
		
		$this->modelName = "DocumentilingueModel";
		
		$this->m[$this->modelName]->setFields('id_lingua','sanitizeAll');
		$this->m[$this->modelName]->values['id_doc'] = $clean['id'];
		
		if (isset($_POST["includi"]))
			$this->m[$this->modelName]->values['includi'] = 1;
		else if (isset($_POST["escludi"]))
			$this->m[$this->modelName]->values['includi'] = 0;
		
		if (isset($_POST["includi"]) || isset($_POST["escludi"]))
			$_POST["insertAction"] = $_REQUEST["insertAction"] = 1;
		
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("lingua","tipoVisibilitaLingua");
		$this->mainHead = "Lingua,Tipo";
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainButtons = "ldel";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"lingue/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("lingua"))->where(array(
			"documenti_lingue.id_doc"	=>	$clean['id'],
		))->orderBy("lingue.descrizione")->convert()->save();
		
		parent::main();
		
		$data["listaLingue"] = DocumentilingueModel::lingueCheMancano($clean['id']);
		
		$data["titoloRecord"] = $this->m["DocumentiModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function caricamolti($id = 0)
	{
		$this->shift(1);
		
		$this->_posizioni['caricamolti'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['menu'] = "";
		
		$data['uploadUrl'] = $this->baseUrl."/documenti/upload?id_page=".$this->viewArgs["id_page"];
		
		$this->append($data);
		
		$this->load("carica_molti");
	}
	
	public function caricazip($id = 0)
	{
		$this->shift(1);
		
		$this->_posizioni['caricazip'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['menu'] = "";
		$data['caricaZip'] = true;
		
		$data['uploadUrl'] = $this->baseUrl."/documenti/upload?id_page=".$this->viewArgs["id_page"]."&compresso=1";
		
		$this->append($data);
		
		$this->load("carica_molti");
	}
	
	public function upload()
	{
		header('Content-type: application/json');
		
		$this->shift();
		
		$compresso = false;
		
		if ((int)$this->viewArgs["compresso"] === 1 && v("riconoscimento_tipo_documento_automatico") && extension_loaded("zip") && v("permetti_upload_archivio"))
			$compresso = true;
		
		$this->clean();
		
		$this->m["DocumentiModel"]->setValues(array(
			"filename"	=>	"",
		));
		
		$result = "KO";
		$errore = "";
		
		$erroreGenerico = gtext("Errore caricamento file: ");
		$testoSuccesso = "";
		
		if (isset($_FILES["filename"]["name"]))
			$erroreGenerico .= "<b>".sanitizeHtml($_FILES["filename"]["name"])."</b> ";
			$testoSuccesso = gtext("File")." ".sanitizeHtml($_FILES["filename"]["name"])." ".gtext("correttamente caricato");
		
		if ($compresso)
			$this->m["DocumentiModel"]->uploadFields["filename"]["allowedExtensions"] = "zip";
		
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
			
			// Lingua
			$this->m["DocumentiModel"]->setValue("lingua", DocumentiModel::cercaLinguaDaNomeFile($_FILES["filename"]["name"]));
			
			if ($compresso)
			{
				$this->m["DocumentiModel"]->setValue("visibile",0);
				$this->m["DocumentiModel"]->setValue("archivio",1);
			}
			
			DocumentiModel::$uploadFile = false;
			
			if ($this->m["DocumentiModel"]->insert())
			{
				$errore = $testoSuccesso;
				
				$lId = $this->m["DocumentiModel"]->lId;
				$result = "OK";
				
				if ($compresso)
					$this->m["DocumentiModel"]->elaboraArchivio($lId, (int)$this->viewArgs["id_page"]);
			}
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
