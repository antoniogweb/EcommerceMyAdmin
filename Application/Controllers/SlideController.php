<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class SlideController extends PagesController {

	public $voceMenu = "slide";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Titolo,Attiva';
		$this->filters = array(null,null,'title',array("attivo",null,SlideModel::$YN));
		
		if (v("attiva_in_evidenza_slide"))
		{
			$this->tableFields[] = 'PagesModel.getInEvidenzaCheckbox|pages.id_page';
			$this->head .= ',In evidenza';
		}
		
		$this->metaQueryFields = "keywords,meta_description,template,add_in_sitemap";
		$this->queryFields = "title,attivo,immagine,sottotitolo,url,link_id_page,link_id_c,testo_link,target";
		
		if (v("immagine_2_in_slide"))
			$this->queryFields .= ",immagine_2";
		
		if (v("immagine_3_in_slide"))
			$this->queryFields .= ",immagine_3";
		
		if (v("usa_marchi"))
			$this->queryFields .= ",link_id_marchio";
		
		if (v("usa_tag"))
			$this->queryFields .= ",link_id_tag";
		
		if (v("usa_descrizione_in_slide"))
			$this->queryFields .= ",description";
		
		if (v("attiva_in_evidenza_slide"))
			$this->queryFields .= ",in_evidenza";
		
		if (v("attiva_link_documenti"))
			$this->queryFields .= ",link_id_documento";
		
		if (v("attiva_tipo_slide"))
			$this->queryFields .= ",id_opzione";
		
		$this->clean();
		
		$this->load('header_sito');
		$this->load('footer','last');
		
		$data["sezionePannello"] = "sito";
		
		$data["tabella"] = "slide";
		
		$this->append($data);
	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		parent::form($queryType, $id);
		
		$data["use_editor"] = "Y";
		
		$this->append($data);
	}

}
