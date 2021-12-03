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

class EventiController extends GenericsectionController {

	public $voceMenu = "eventi";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->queryFields = "title,alias,attivo,description,immagine,id_c,sottotitolo,data_inizio_evento,data_fine_evento,ora_inizio_evento,ora_fine_evento,email_contatto_evento,telefono_contatto_evento,indirizzo_localita_evento,data_news";
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			'PagesModel.categoriesS|pages.id_page',
			'smartDate|pages.data_inizio_evento',
			'smartDate|pages.data_fine_evento',
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);
		
		$this->orderBy = "pages.data_inizio_evento desc, pages.ora_inizio_evento, pages.data_fine_evento desc, pages.ora_fine_evento";
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Titolo,Categoria,Data inizio,Data fine,Pubblicato?';
		
		$this->formDefaultValues = array(
			"data_inizio_evento"	=>	date("d-m-Y"),
			"data_fine_evento"		=>	date("d-m-Y"),
		);
	}

}
