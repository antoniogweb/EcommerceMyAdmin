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

class SlidesottoModel extends PagesModel {
	
	public $hModelName = "SlidesottocatModel";
	
	public function __construct() {
		
		parent::__construct();

	}
	
	public function insert()
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"slidesotto"))->field("id_c");
		
		$this->values["alias"] = "";
		
		$this->values["id_c"] = $clean["id_c"];
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$c = new CategoriesModel();
		
		$clean["id_c"] = (int)$c->clear()->where(array("section"=>"slidesotto"))->field("id_c");
		
		$this->values["alias"] = "";
		
		$this->values["id_c"] = $clean["id_c"];
		
		return parent::update($id, $where);
	}
	
// 	public function setFormStruct()
// 	{
// 		parent::setFormStruct();
// 		
// 		$plus = array(
// 		
// 			'description'		=>	array(
// 				'type'		 =>	'Textarea',
// 				'labelString'=>	'Descrizione',
// // 				'className'		=>	'dettagli',
// 			),
// 			'description_en'		=>	array(
// 				'type'		 =>	'Textarea',
// 				'labelString'=>	'Descrizione (EN)',
// // 				'className'		=>	'dettagli',
// 			),
// 		);
// 		
// 		$this->formStruct["entries"] = array_merge($this->formStruct["entries"], $plus);
// 	}
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
// 			'id_c'	=>	'id_c',
// 			'in_evidenza'	=>	'in_evidenza',
// 			'in_promozione'	=>	'in_promozione',
// 			'mostra_in_slide'	=>	'mostra_in_slide',
// 			'settore'	=>	'settore',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
// 			'id_c'	=>	'CATEGORIA',
// 			'in_evidenza'	=>	'IN EVIDENZA?',
// 			'in_promozione'	=>	'IN PROMOZIONE?',
// 			'mostra_in_slide'	=>	'IN SLIDE?',
// 			'settore'	=>	'SETTORE',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
// 			'id_c'	=>	'getCatNameForFilters',
// 			'in_evidenza'	=>	'getYesNo',
// 			'in_promozione'	=>	'getYesNo',
// 			'mostra_in_slide'	=>	'getYesNo',
		);
		
// 		$this->_popupOrderBy = array(
// 			'id_c'	=>	'lft asc',
// 		);
// 		
// 		if (isset($this->hModel->section))
// 		{
// 			$this->_popupWhere = array(
// 				'id_c'	=>	$this->hModel->getChildrenFilterWhere(),
// 			);
// 		}
	}
}
