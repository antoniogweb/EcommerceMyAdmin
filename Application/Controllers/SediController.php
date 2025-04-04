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

class SediController extends GenericsectionController {
	
	public $voceMenu = "sedi";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a>",
			"pages.email_contatto_evento",
			"pages.telefono_contatto_evento",
			"pages.indirizzo_localita_evento",
			"regioneCrud",
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
		);

		$this->head = '[[bulkselect:checkbox_pages_id_page]],Titolo,Email,Telefono,Indirizzo,Regione,Pubblicato?';

		$this->filters = array(null,'title');

		if (v("attiva_categorie_sedi"))
		{
			$this->tableFields[] = 'PagesModel.categoriesS|pages.id_page';
			$this->head .= ',Categoria';
			$filtroCategoria = array("tutti" => SedicatModel::g(false)->getTitoloCategoriaPadreSezione()) + SedicatModel::g(false)->buildSelect(null,false);

			$this->filters = array(null,'title',null,null,null,null,null,array("id_c",null,$filtroCategoria));
		}
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);

		$this->orderBy = "pages.id_order";
		


		$this->queryFields = "title,attivo,email_contatto_evento,telefono_contatto_evento,indirizzo_localita_evento,description,localita_evento,id_regione,coordinate,link_pagina_facebook,link_pagina_twitter,link_pagina_youtube,link_pagina_instagram,link_pagina_linkedin";
		
		if (v("attiva_categorie_sedi"))
			$this->queryFields .= ",id_c";
	}
}
