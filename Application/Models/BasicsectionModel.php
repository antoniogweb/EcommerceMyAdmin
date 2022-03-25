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

class BasicsectionModel extends PagesModel {
	
	public function insert()
	{
		$this->setAliasAndCategory();
		
// 		$c = new CategoriesModel();
// 		
// 		$clean["id_c"] = (int)$c->clear()->where(array("section"=>$this->hModel->section))->field("id_c");
// 		
// 		if (!isset($this->values["alias"]) || !$this->values["alias"])
// 			$this->values["alias"] = "";
// 		
// 		$this->values["id_c"] = $clean["id_c"];
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->setAliasAndCategory();
		
// 		$c = new CategoriesModel();
// 		
// 		$clean["id_c"] = (int)$c->clear()->where(array("section"=>$this->hModel->section))->field("id_c");
// 		
// 		if (!isset($this->values["alias"]) || !$this->values["alias"])
// 			$this->values["alias"] = "";
// 		
// 		$this->values["id_c"] = $clean["id_c"];
		
		return parent::update($id, $where);
	}
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
		);
	}
}
