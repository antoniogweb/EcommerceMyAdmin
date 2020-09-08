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

class DocumentiModel extends GenericModel {

	public $parentRootFolder;
	
	public function __construct() {
		$this->_tables='documenti';
		$this->_idFields='id_doc';

		$this->orderBy = 'id_order desc';
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/anteprimedocumenti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	true,
				"maxFileSize"	=>	3000000,
				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	400,
					'imgHeight'		=>	400,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"images/documenti",
				"allowedExtensions"	=>	'pdf,png,jpg,jpeg',
				"maxFileSize"	=>	3000000,
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'tipo' => array("BELONGS_TO", 'TipidocumentoModel', 'id_tipo_doc',null,"CASCADE"),
        );
    }
    
	public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectLingua(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_tipo_doc'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo documento",
					"options"	=>	$this->selectTipo(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function selectTipo()
	{
		$t = new TipidocumentoModel();
		
		return array(0	=>	"--") + $t->clear()->orderBy("titolo")->toList("id_tipo_doc","titolo")->send();
	}
    
    public function titoloDocumento($record)
    {
		return "<a class='iframe action_iframe' href='".Url::getRoot()."documenti/form/update/".$record["documenti"]["id_doc"]."?partial=Y'>".$record["documenti"]["titolo"]."</a>";
    }
    
    public function filename($record)
    {
		return "<a target='_blank' href='".Url::getRoot()."documenti/documento/filename/".$record["documenti"]["id_doc"]."'>".$record["documenti"]["clean_filename"]."</a>";
    }
    
    public function immagine($record)
    {
		if ($record["documenti"]["immagine"])
			return "<img width='100px;' src='".Url::getRoot()."documenti/thumb/immagine/".$record["documenti"]["id_doc"]."'/>";
    }
    
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
	}
	
	//duplica i contenuti
// 	public function duplica($from_id, $to_id)
// 	{
// 		$clean["from_id"] = (int)$from_id;
// 		$clean["to_id"] = (int)$to_id;
// 		
// 		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy("id_order")->send(false);
// 		
// 		foreach ($res as $r)
// 		{
// 			$this->setValues($r, "sanitizeDb");
// 			$this->setValue("id_page", $to_id);
// 			
// 			unset($this->values["id_doc"]);
// 			unset($this->values["data_creazione"]);
// 			unset($this->values["id_order"]);
// // 			print_r($this->values);
// 			parent::insert();
// 		}
// 		
// // 		print_r($this->db->queries);
// 	}
	
}
