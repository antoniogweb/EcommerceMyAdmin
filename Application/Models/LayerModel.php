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

class LayerModel extends GenericModel {
	
	public $upload = false;
	
	public function __construct() {
		$this->_tables='slide_layer';
		$this->_idFields='id_layer';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/layer",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'animazione'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array(
						'alto'=>"Dall'alto",
						'basso'=>"Dal basso",
						'sinistra'=>"Da sinistra",
						'destra'=>"Da destra",
						"centro"	=>	"Del cerchio",
						'testo'=>"Del testo",
					),
					"reverse"	=>	"yes",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function edit($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."/layer/form/update/".$record["slide_layer"]["id_layer"]."?partial=Y&nobuttons=Y'>".$record["slide_layer"]["titolo"]."</a>";
	}
	
	public function thumb($record)
	{
		$html = "";
		
		if ($record["slide_layer"]["immagine"] && file_exists(Domain::$parentRoot."/images/layer/".$record["slide_layer"]["immagine"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/layer/".$record["slide_layer"]["immagine"]."'><img src='".Url::getRoot()."layer/thumb/immagine/".$record["slide_layer"]["id_layer"]."' /></a>";
		
		return $html;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if ($this->upload || $this->upload("insert"))
		{
			return parent::insert();
		}
	}
	
// 	//duplica i layer
// 	public function duplica($from_id, $to_id, $field = "id_page")
// 	{
// 		$clean["from_id"] = (int)$from_id;
// 		$clean["to_id"] = (int)$to_id;
// 		
// 		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy("id_layer")->send(false);
// 		
// 		foreach ($res as $r)
// 		{
// 			$this->setValues($r, "sanitizeDb");
// 			$this->setValue("id_page", $to_id);
// 			
// 			unset($this->values["id_layer"]);
// 			
// 			$this->upload = true;
// 			$this->insert();
// 		}
// 	}
}
