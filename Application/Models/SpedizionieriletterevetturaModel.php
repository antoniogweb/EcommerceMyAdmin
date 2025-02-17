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

class SpedizionieriletterevetturaModel extends GenericModel
{
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "Template";
	public $classeModuloPadre = "Template";
	
	public function __construct() {
		$this->_tables='spedizionieri_lettere_vettura';
		$this->_idFields='id_spedizioniere_lettera_vettura';
		$this->_idOrder = 'id_order';
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"images/letterevettura",
				"allowedExtensions"	=>	'doc,docx,xls,xlsx',
				"maxFileSize"	=>	3000000,
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"inline",
				"disallow"		=>	true,
// 				"allowedMimeTypes"	=>	"application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			),
		);
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'spedizioni' => array("HAS_MANY", 'SpedizioninegozioModel', 'id_spedizioniere_lettera_vettura', null, "RESTRICT", "L'elemento ha delle spedizioni collegate e non può essere eliminato"),
			'spedizioniere' => array("BELONGS_TO", 'SpedizionieriModel', 'id_spedizioniere',null,"CASCADE"),
		);
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					'type'		=>	'Select',
					'options'	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
				),
				'modulo'	=>	array(
					'type'		=>	'Select',
					'labelString'	=>	'Tipologia',
					'options'	=>	array(
						"Word"	=>	"Word",
						"Excel"	=>	"Excel",
					),
					"reverse"	=>	"yes",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function titoloCrud($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."/spedizionieriletterevettura/form/update/".$record["spedizionieri_lettere_vettura"]["id_spedizioniere_lettera_vettura"]."?partial=Y&nobuttons=Y'>".$record["spedizionieri_lettere_vettura"]["titolo"]."</a>";
		
		return "--";
	}
	
	public function filename($record)
    {
		return "<a target='_blank' href='".Url::getRoot()."spedizionieriletterevettura/documento/filename/".$record["spedizionieri_lettere_vettura"]["id_spedizioniere_lettera_vettura"]."'>".$record["spedizionieri_lettere_vettura"]["clean_filename"]."</a>";
    }
	
	public function attivoCrud($record)
	{
		return $record[$this->_tables]["attivo"] ? "Sì" : "No";
	}
	
	public function insert()
	{
		$this->sistemaCodice();
		
		if ($this->upload("insert"))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$this->sistemaCodice();
		
		if ($this->upload("update"))
			return parent::update($id, $where);
		
		return false;
	}
}
