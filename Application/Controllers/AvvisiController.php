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

class AvvisiController extends PagesController {

	public $voceMenu = "avvisi";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);
		
		$this->orderBy = "pages.id_order desc";
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Titolo,Attiva';
		$this->filters = array(null,'title',array("attivo",null,SlideModel::$YN));
		
		$this->metaQueryFields = "keywords,meta_description,template,add_in_sitemap";
		$this->queryFields = "title,attivo,description";
		
		$this->clean();
		
		$this->load('header_sito');
		$this->load('footer','last');
		
		$data["sezionePannello"] = "sito";
		
		$data["tabella"] = "avvisi";
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->append($data);
	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		parent::form($queryType, $id);
		
		$data["use_editor"] = "Y";
		
		$this->append($data);
	}

}
