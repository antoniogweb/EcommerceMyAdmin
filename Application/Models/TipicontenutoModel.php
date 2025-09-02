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

class TipicontenutoModel extends GenericModel
{
	public static $tipi = array(
		"FASCIA"	=>	"FASCIA",
		"GENERICO"	=>	"GENERICO",
		"MARKER"	=>	"MARKER",
	);
	
	public function __construct() {
		$this->_tables = 'tipi_contenuto';
		$this->_idFields = 'id_tipo';
		
		$this->_idOrder = 'id_order';
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/anteprimefasce",
				"allowedExtensions"	=>	'png,jpg,jpeg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	true,
				"maxFileSize"	=>	v("dimensioni_upload_contenuti"),
				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	400,
					'imgHeight'		=>	400,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pages' => array("HAS_MANY", 'ContenutiModel', 'id_tipo', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
    }
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	gtextDeep(self::$tipi),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'section'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->getSezioni(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'labelString'=>	'Sezione',
				),
				'campi'	=>	array(
					'labelString'=>	'Campi (divisi da virgola)',
				),
			),
		);
	}
	
	public function getSezioni()
	{
		$c = new CategoriesModel();
		
		$sezioni = array("" => "-- Nessuna --", "-- root --"=>"-- Radice --") + $c->clear()->select("section, section as `Sezione|strtoupper`")->where(array(
			"ne"	=>	array(
				"section"	=>	"",
			),
			"installata"	=>	1,
		))->process()->toList("section", "Sezione")->send();
		
		return $sezioni;
	}
	
	public static function getRecord($id)
	{
		$t = new TipicontenutoModel();
		
		return $t->clear()->selectId($id);
	}
	
	public function aggiungiagruppo($id)
    {
		$this->aggiungiAGruppoTipo($id, "CO");
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
