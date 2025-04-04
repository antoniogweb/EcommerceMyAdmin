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

class SlideModel extends PagesModel {
	
	public $hModelName = "SlidecatModel";
	
	public function __construct() {
		
		parent::__construct();

	}
	
	public function insert()
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"slide"))->field("id_c");
		
		$this->values["alias"] = "";
		
		$this->values["id_c"] = $clean["id_c"];
		
		if (!v("attiva_in_evidenza_slide"))
			$this->values["in_evidenza"] = "Y";
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"slide"))->field("id_c");
		
		$this->values["alias"] = "";
		
		$this->values["id_c"] = $clean["id_c"];
		
		return parent::update($id, $where);
	}
	
	public function setFormStruct($id = 0)
	{
		parent::setFormStruct($id);
		
		$plus = array(
			'id_opzione'	=>	array(
				'type'		=>	'Select',
				'entryClass'	=>	'form_input_text help_iva',
				'labelString'=>	'Tipologia',
				'options'	=>	OpzioniModel::codice("TIPO_SLIDE", "id_opzione"),
				'reverse' => 'yes',
				
			),
			'url'		=>	array(
				'labelString'=>	'Link libero',
			),
		);
		
		$this->formStruct["entries"] = array_merge($this->formStruct["entries"], $plus);
	}
	
	public function setFilters()
	{
		
	}
}
