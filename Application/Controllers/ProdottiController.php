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

class ProdottiController extends PagesController {

	public $voceMenu = "prodotti";
	public $sezionePannello = "ecommerce";
	public static $sCampoPrice = "price";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$campoPrice = self::$sCampoPrice;
		
		if (v("prezzi_ivati_in_prodotti"))
			$campoPrice = self::$sCampoPrice = "price_ivato";
			
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a class='help_titolo' href='".$this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a> <br /><span class='get_title'>(alias: ;pages.alias;)</span><br />codice: <b>;pages.codice;</b><br />prezzo: <b>;PagesModel.prezzoMinimoDisplay|pages.id_page;€</b>",
			'PagesModel.categoriesS|pages.id_page',
// 			'PagesModel.getInputOrdinamento|pages.id_page',
		);
		
		$this->head = '[[bulkselect:checkbox_pages_id_page]],Immagine,Codice / Titolo,Categorie';
		
		if (v("usa_marchi"))
		{
			$this->tableFields[] = 'marchio';
			$this->head .= ',Marchio';
			$filtroMarchio = array("tutti" => "Tutti") + $this->m["MarchiModel"]->filtro();
			$this->filters = array(null,null,'title',null,array("-id_marchio",null,$filtroMarchio));
		}
		
		$fTag = null;
		
		if (v("usa_tag"))
		{
			$this->tableFields[] = 'tag';
			$this->head .= ',Tag';
			$filtroTag = array("tutti" => "Tutti") + $this->m["TagModel"]->filtro();
			$fTag = array("id_tag",null,$filtroTag);
		}
		
		$this->filters[] = $fTag;
		
		$fNaz = $fReg = null;
		
		if (v("attiva_localizzazione_prodotto"))
		{
			$this->tableFields[] = 'nazioneCrud';
			$this->head .= ',Nazione';
			$fNaz = array("id_naz",null,array("tutti" => "Tutti") + $this->m["NazioniModel"]->filtro());
			
			$this->tableFields[] = 'regioneCrud';
			$this->head .= ',Regione';
			$fReg = array("id_reg",null,array("tutti" => "Tutti") + $this->m["RegioniModel"]->filtro());
		}
		
		$this->filters[] = $fNaz;
		$this->filters[] = $fReg;
		
		$this->tableFields[] = 'PagesModel.inPromozioneText|pages.id_page';
		$this->tableFields[] = 'PagesModel.getPubblicatoCheckbox|pages.id_page';
		$this->tableFields[] = 'PagesModel.getInEvidenzaCheckbox|pages.id_page';
		
		$this->head .= ',In promoz?,Pubbl?,In evid?';
		
		$data["tabella"] = "prodotti";
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		$campoPrice = self::$sCampoPrice;
		
		$data = array("avviso_combinazioni" => "");
		
		$haCombinazioni = $this->m[$this->modelName]->hasCombinations((int)$id, false);
		
		if ($haCombinazioni)
			$data["avviso_combinazioni"] = "<div class='callout callout-info'>".gtext("Il prodotto ha delle varianti.")."<br />".gtext("I campi prezzo, codice, peso e giacenza devono essere modificati nella scheda 'Varianti', tramite il pulsante 'Gestisci combinazioni'")."</div>";
		
		$this->queryFields = "title,alias,id_c,attivo,in_evidenza,immagine,sottotitolo";
		
		if (v("ecommerce_attivo"))
		{
			if ($haCombinazioni)
				$this->queryFields .= ",id_iva,in_promozione,prezzo_promozione,dal,al";
			else
				$this->queryFields .= ",$campoPrice,id_iva,codice,peso,in_promozione,prezzo_promozione,dal,al,giacenza";
		}
		
		if (v("abilita_blocco_acquisto_diretto"))
			$this->queryFields .= ",acquisto_diretto";
		
		if (v("usa_marchi"))
			$this->queryFields .= ",id_marchio";
		
		if (v("accessori_in_prodotti"))
			$this->queryFields .= ",acquistabile,aggiungi_sempre_come_accessorio";
		
		if (v("mostra_descrizione_in_prodotti"))
			$this->queryFields .= ",description,use_editor";
		
		if (v("mostra_tendina_prodotto_principale"))
			$this->queryFields .= ",id_p";
		
		if (v("attiva_campo_nuovo_in_pagine"))
			$this->queryFields .= ",nuovo";
		
		if (v("attiva_campo_test_in_pagine"))
			$this->queryFields .= ",test";
		
		if (v("attiva_descrizione_2_in_prodotti"))
			$this->queryFields .= ",descrizione_2";
		
		if (v("attiva_descrizione_3_in_prodotti"))
			$this->queryFields .= ",descrizione_3";
		
		if (v("attiva_descrizione_4_in_prodotti"))
			$this->queryFields .= ",descrizione_4";
		
		if (v("attiva_strumenti_merchant_google"))
		{
			$this->queryFields .= ",codice_categoria_prodotto_google,gtin,mpn,identifier_exists";
			
			$this->formDefaultValues = array(
				"identifier_exists"	=>	v("identificatore_feed_default"),
			);
		}
		
		if (v("attiva_margine_in_prodotti"))
			$this->queryFields .= ",margine";
		
		if (v("attiva_gift_card"))
			$this->queryFields .= ",gift_card";
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}

}
