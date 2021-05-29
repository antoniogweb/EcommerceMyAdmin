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
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	self::$tipi,
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
		
		$sezioni = array(""=>"--") + $c->clear()->select("section, section as `Sezione|strtoupper`")->where(array(
			"ne"	=>	array(
				"section"	=>	"",
			)
		))->process()->toList("section", "Sezione")->send();
		
		return $sezioni;
	}
	
	public static function getRecord($id)
	{
		$t = new TipicontenutoModel();
		
		return $t->clear()->selectId($id);
	}
}
