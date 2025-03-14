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

class HomeController extends PagesController {

	public $voceMenu = "home";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
// 			'PagesModel.categoriesS|pages.id_page',
// 			'PagesModel.inPromozioneText|pages.id_page',
// 			'pages.settore',
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
// 			'PagesModel.getInEvidenzaCheckbox|pages.id_page',
// 			'PagesModel.getInSlideCheckbox|pages.id_page',
// 			'PagesModel.getInputOrdinamento|pages.id_page',
		);
		
		$this->orderBy = "pages.id_order desc";
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Titolo,Pubblicato?';
		$this->filters = array(null,null,'title');
		
		$this->metaQueryFields = "keywords,meta_description,template,add_in_sitemap";
		$this->queryFields = "title,alias,attivo,description,immagine,use_editor";
		
		$this->clean();
		
		$this->load('header_sito');
		$this->load('footer','last');
		
		$data["sezionePannello"] = "sito";
		
		$data["tabella"] = "news";
		
		$this->append($data);
	}

}
