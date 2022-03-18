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

class ReferenzeModel extends PagesModel {
	
	public $hModelName = "ReferenzecatModel";
	
	public function __construct() {
		
		parent::__construct();
	}
	
	public function insert()
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"referenze"))->field("id_c");
		
		$this->values["id_c"] = $clean["id_c"];
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"referenze"))->field("id_c");
		
		$this->values["id_c"] = $clean["id_c"];
		
		return parent::update($id, $where);
	}
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
			'in_evidenza'	=>	'IN EVIDENZA?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
		);
	}
}
