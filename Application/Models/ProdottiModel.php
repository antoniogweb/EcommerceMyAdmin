<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class ProdottiModel extends PagesModel {
	
	public $hModelName = "CategorieModel";
	
	public function __construct() {
		
		parent::__construct();

	}
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
			'id_c'	=>	'id_c',
			'in_evidenza'	=>	'in_evidenza',
			'in_promozione'	=>	'in_promozione',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
			'id_c'	=>	'CATEGORIA',
			'in_evidenza'	=>	'IN EVIDENZA?',
			'in_promozione'	=>	'IN PROMOZIONE?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
			'id_c'	=>	'getCatNameForFilters',
			'in_evidenza'	=>	'getYesNo',
			'in_promozione'	=>	'getYesNo',
		);
		
		$this->_popupOrderBy = array(
			'id_c'	=>	'lft asc',
		);
		
		$this->_popupWhere[] = array();
		
		if (isset($this->hModel->section))
			$this->_popupWhere["id_c"] = $this->hModel->getChildrenFilterWhere();
		
		if (v("usa_marchi"))
		{
			$this->_popupItemNames["-id_marchio"] = "titolo";
			$this->_popupLabels["-id_marchio"] = gtext("marchio",true,"strtoupper");
// 			$this->_popupWhere["-id_marchio"] = "id_marchio != 0";
// 			$this->_popupFunctions["-id_marchio"] = "getTitoloMarchio";
			$this->_popupOrderBy["-id_marchio"] = "marchi.titolo";
		}
	}
	
	public function insert()
	{
// 		if (strcmp($this->values["prezzo_promozione"],"") === 0)
// 		{
// 			$this->values["prezzo_promozione"] = $this->values["price"];
// 		}
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
// 		if (strcmp($this->values["in_promozione"],"N") === 0)
// 		{
// 			$this->values["prezzo_promozione"] = $this->values["price"];
// 		}
		
		return parent::update($id, $where);
	}
	
}
