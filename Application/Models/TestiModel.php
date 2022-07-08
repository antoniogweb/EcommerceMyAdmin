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

class TestiModel extends GenericModel {
	
	public static $mostraIconaEdit = true;
	
	public function __construct() {
		$this->_tables='testi';
		$this->_idFields='id_t';
		
		$this->_lang = 'It';

		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'valore'		=>	array(
					'className'		=>	'dettagli dettagli_testi',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/widgets",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif,svg',
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
			"immagine_2x"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/widgets",
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
				'id_contenuto'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link al contenuto",
					"options"	=>	$this->selectLinkContenuto(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_categoria'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link alla categoria",
					"options"	=>	$this->buildAllCatSelect(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'target_link'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Apri in ",
					"options"	=>	array(
						"INTERNO"	=>	"Stessa scheda",
						"ESTERNO"	=>	"Nuova scheda",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'editor_visuale'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Usa editor visuale",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'tag_elemento'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo elemento",
					"options"	=>	OpzioniModel::codice("TAG_CONTENITORE"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record) && $record["tipo"] != "TESTO")
				$this->values["valore"] = "";
			
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			return parent::insert();
		}
	}
	
	public function lingua($record)
	{
		return strtoupper($record["testi"]["lingua"]);
	}
	
	public function thumb($record)
	{
		if ($record["testi"]["tipo"] == "IMMAGINE" && $record["testi"]["immagine"] && file_exists(Domain::$parentRoot."/images/widgets/".$record["testi"]["immagine"]))
			return "<img width='100px' src='".Domain::$publicUrl."/images/widgets/".$record["testi"]["immagine"]."' />";
		
		return "";
	}
	
	public static function numero($idCont)
	{
		$t = new TestiModel();
		
		return $t->clear()->where(array(
			"id_cont"	=>	(int)$idCont,
		))->rowNumber();
	}
}
