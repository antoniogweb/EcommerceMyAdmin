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

class ServiziController extends GenericsectionController {
	
	public $voceMenu = "servizi";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);
		
		$this->orderBy = "pages.id_order";
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Titolo,Pubblicato?';
		
		$this->filters = array(null,null,'title',array("attivo",null,SlideModel::$YN));
		
		$this->queryFields = "title,alias,sottotitolo,attivo,description,immagine,immagine_2";
		
		if (v("attiva_gestione_servizi_in_evidenza"))
		{
			$this->queryFields .= ",in_evidenza";
			
			$this->tableFields[] = 'PagesModel.getInEvidenzaCheckbox|pages.id_page';
			$this->head .= ",In evidenza";
		}
		
		if (v("immagine_3_in_servizi"))
			$this->queryFields .= ",immagine_3";
	}
}
