<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class ProdottiController extends PagesController {

	public $voceMenu = "prodotti";
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$campoPrice = "price";
		
		if (v("prezzi_ivati_in_prodotti"))
			$campoPrice = "price_ivato";
			
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a> <br /><span class='get_title'>(alias: ;pages.alias;)</span><br />codice: <b>;pages.codice;</b><br />prezzo: <b>;setPriceReverse|pages.$campoPrice;€</b>",
			'PagesModel.categoriesS|pages.id_page',
// 			'PagesModel.getInputOrdinamento|pages.id_page',
		);
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Thumb,Codice / Titolo,Categorie';
		
		$filtroTag = array("tutti" => "Tutti") + $this->m["TagModel"]->selectPerFiltro();
		
		$this->filters = array(null,null,'title',null,null,array("id_tag",null,$filtroTag));
		
		if (v("usa_marchi"))
		{
			$this->tableFields[] = 'marchio';
			$this->head .= ',Marchio';
		}
		
		if (v("usa_tag"))
		{
			$this->tableFields[] = 'tag';
			$this->head .= ',Tag';
		}
		
		$this->tableFields[] = 'PagesModel.inPromozioneText|pages.id_page';
		$this->tableFields[] = 'PagesModel.getPubblicatoCheckbox|pages.id_page';
		$this->tableFields[] = 'PagesModel.getInEvidenzaCheckbox|pages.id_page';
		
		$this->head .= ',In promoz?,Pubbl?,In evid?';
		
		$this->queryFields = "title,alias,id_c,attivo,in_evidenza,immagine,sottotitolo";
		
		if (v("ecommerce_attivo"))
			$this->queryFields .= ",$campoPrice,id_iva,codice,peso,in_promozione,prezzo_promozione,dal,al,giacenza";
		
		if (v("abilita_blocco_acquisto_diretto"))
			$this->queryFields .= ",acquisto_diretto";
		
		if (v("usa_marchi"))
			$this->queryFields .= ",id_marchio";
		
		if (v("accessori_in_prodotti"))
			$this->queryFields .= ",acquistabile,aggiungi_sempre_come_accessorio";
		
		if (v("mostra_descrizione_in_prodotti"))
			$this->queryFields .= ",description,use_editor";
		
		$data["tabella"] = "prodotti";
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}

}
