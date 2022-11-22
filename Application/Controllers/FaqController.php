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

class FaqController extends PagesController {

	public $voceMenu = "faq";
	
	public $colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
			'PagesModel.getInEvidenzaCheckbox|pages.id_page',
		);
		
		$this->orderBy = "pages.id_order";
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Titolo,Attiva,In evidenza';
		$this->filters = array(null,'title',array("attivo",null,SlideModel::$YN));
		
		$this->queryFields = "title,attivo,description,in_evidenza";
		
		$this->clean();
		
		$this->load('header_sito');
		$this->load('footer','last');
		
		$data["sezionePannello"] = "sito";
		
		$data["tabella"] = "faq";
		$data["use_editor"] = "Y";
		
		$this->append($data);
	}
}
