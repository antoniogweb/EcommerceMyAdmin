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

class FornitoriimportModel extends GenericModel
{
	public $campoTitolo = "id_fornitore_import";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public function __construct() {
		$this->_tables = 'fornitori_import';
		$this->_idFields = 'id_fornitore_import';
		
		$this->_idOrder='id_order';
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"admin/media/Import",
				"allowedExtensions"	=>	'xls,xlsx',
				"maxFileSize"	=>	1000000,
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"attachment",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'fornitore' => array("BELONGS_TO", 'FornitoriModel', 'id_fornitore',null,"RESTRICT", "Si prega di selezionare il fornitore"),
		);
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
		
		return false;
	}
	
	public function filenameCrud($record)
    {
		return "<a class='iframe action_iframe' href='".Url::getRoot()."fornitoriimport/form/update/".$record["fornitori_import"]["id_fornitore_import"]."?partial=Y&nobuttons=Y'>".$record["fornitori_import"]["clean_filename"]."</a>";
    }
}
