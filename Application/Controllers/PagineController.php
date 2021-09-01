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

class PagineController extends PagesController {

	public $voceMenu = "pagine";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->clean();
		
		$data["sezionePannello"] = "sito";
		$data["tabella"] = "pagine";
		$this->append($data);
		
		$this->queryFields = "use_editor,title,alias,id_c,attivo,description,use_editor,immagine,tipo_pagina,sottotitolo";
		
		if (v("immagine_2_in_pagine"))
			$this->queryFields .= ",immagine_2";
		
		$this->orderBy = "pages.id_order";
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a> <br /><span class='get_title'>(alias: ;pages.alias;)</span>",
			'tipopagina',
// 			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Titolo,Tipo';
		$this->filters = array(null,'title');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			)
		);
		
		$this->load('header_sito');
		$this->load('footer','last');
	}
	
}
