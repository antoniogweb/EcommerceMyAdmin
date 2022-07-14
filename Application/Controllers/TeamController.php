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

class TeamController extends PagesController {

	public $voceMenu = "team";
	
	public $argKeys = array(
		'id_ruolo:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->model("RuoliModel");
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
		);
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Titolo';
		
		$this->filters = array(null,null,'title');
		
		if (v("attiva_ruoli"))
		{
			$this->tableFields[] = 'ruoloCrud';
			$this->head .= ",Ruolo";
			$this->filters[] = array("id_ruolo",null,$this->m["RuoliModel"]->selectTipi(false,"tutti","Ruolo"));
		}
		
		$this->tableFields[] = 'PagesModel.getPubblicatoCheckbox|pages.id_page';
		$this->head .= ",Attiva";
		
		$this->orderBy = "pages.id_order";
		
		$this->metaQueryFields = "keywords,meta_description,template,add_in_sitemap";
		$this->queryFields = "title,attivo,immagine,sottotitolo,link_pagina_facebook,link_pagina_twitter,link_pagina_youtube,email_contatto_evento,telefono_contatto_evento,indirizzo_localita_evento,description,alias,link_pagina_instagram,link_pagina_linkedin";
		
		if (v("immagine_2_in_team"))
			$this->queryFields .= ",immagine_2";
		
		if (v("attiva_ruoli"))
			$this->queryFields .= ",id_ruolo";
		
		$this->clean();
		
		$this->load('header_sito');
		$this->load('footer','last');
		
		$data["sezionePannello"] = "sito";
		
		$data["tabella"] = "team";
		
		$this->append($data);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->m[$this->modelName]->aWhere(array(
			"id_ruolo"	=>	$this->viewArgs["id_ruolo"],
		))->save();
		
		return parent::main();
	}
	
}
