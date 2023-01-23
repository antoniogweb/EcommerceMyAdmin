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

class TipidocumentoModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'tipi_documento';
		$this->_idFields = 'id_tipo_doc';
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/tipidocumento",
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
	
	public function relations() {
        return array(
			'documenti' => array("HAS_MANY", 'DocumentiModel', 'id_tipo_doc', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'estensioni' => array("HAS_MANY", 'TipidocumentoestensioniModel', 'id_tipo_doc', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
    public function aggiungiagruppo($id)
    {
		$this->aggiungiAGruppoTipo($id, "DO");
    }
    
    public static function findIdByTitle($titolo)
    {
		$clean["titolo"] = sanitizeAll(F::togliSpazi($titolo));
		
		$td = new TipidocumentoModel();
		
		return $td->clear()->select("id_tipo_doc")->sWhere(array('REPLACE(titolo, " ", "") = ?',array($clean["titolo"])))->field("id_tipo_doc");
    }
    
    public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
		
		return false;
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
		
		return false;
	}
    
}
