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

class VariabiliModel extends GenericModel {
	
	public static $valori = array();
	
	public static $variabili = array(
		"usa_marchi"	=>	"1",
		"db_version"	=>	0,
		"contenuti_in_prodotti"		=>	1,
		"contenuti_in_categorie"	=>	0,
		"fasce_in_prodotti"		=>	0,
		"fasce_in_categorie"	=>	0,
		"scaglioni_in_prodotti"		=>	1,
		"correlati_in_prodotti"		=>	1,
		"caratteristiche_in_prodotti"=>	1,
		"combinazioni_in_prodotti"	=>	1,
		"attiva_personalizzazioni"	=>	0,
		"documenti_in_prodotti"		=>	1,
		"ecommerce_attivo"			=>	1,
		"mostra_link_in_blog"		=>	0,
		"has_child_class"			=>	"menu-item-has-children",
		"attiva_ruoli"				=>	0,
		"in_evidenza_blog"			=>	0,
		"contenuti_in_blog"			=>	0,
		"team_attivo"				=>	1,
		"immagini_in_referenze"		=>	0,
		"nome_cognome_anche_azienda"=>	0,
		"attiva_gruppi_utenti"		=>	1,
		"accessori_in_prodotti"		=>	1,
		"contenuti_in_pagine"		=>	1,
		"fasce_in_pagine"			=>	1,
		"mostra_tipi_documento"		=>	1,
		"download_attivi"			=>	1,
		"attiva_giacenza"			=>	0,
		"usa_tag"					=>	0,
		"shop_in_alias_marchio"		=>	1,
		"reg_expr_file"				=>	"/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|gif|png)$/i",
		"nazione_default"			=>	"IT", // Codice ISO nazione di default
		"referenze_attive"			=>	1,
		"blog_attivo"				=>	1,
		"divisone_breadcrum"		=>	" » ",
		"shop_in_alias_tag"			=>	0,
		"menu_class_prefix"			=>	"",
		"primo_attributo_selezionato"	=>	0,
		"prodotti_per_pagina"		=>	999999,
		"template_attributo"		=>	"",
		"template_personalizzazione"=>	"",
		"usa_https"					=>	0,
		"codice_cron"				=>	"",
		"mostra_fasce_prezzo"		=>	0,
		"estrai_materiali"			=>	0,
		"immagine_2_in_slide"		=>	0,
		"mostra_seconda_immagine_categoria_prodotti"	=>	0,
		"mostra_seconda_immagine_tag"	=>	0,
		"mostra_colore_testo"		=>	0,
		"attiva_gruppi"				=>	0,
		"attiva_gruppi_contenuti"	=>	0,
		"attiva_gruppi_documenti"	=>	0,
		"mostra_descrizione_in_prodotti"	=>	1,
		"mostra_pulsanti_ordinamenti"	=>	0,
		"fatture_attive"			=>	1,
		"favicon_url"				=>	"",
		"cifre_decimali"			=>	2,
		"link_cms"					=>	"blog/main",
	);
	
	public function __construct() {
		$this->_tables='variabili';
		$this->_idFields='id_v';
		
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public static function migrazioni()
	{
		$var = new VariabiliModel();
		
		foreach (self::$variabili as $k => $v)
		{
			if (!isset(self::$valori[$k]))
			{
				$var->setValues(array(
					"chiave"	=>	$k,
					"valore"	=>	$v,
				));
				
				$var->insert();
			}
		}
	}
	
	public static function valore($chiave)
	{
		if (!isset(self::$valori[$chiave]))
		{
			self::migrazioni();
			self::ottieniVariabili();
		}
		
		if (!isset(self::$valori[$chiave]))
			die("Attenzione, chiave $chiave inesistente!");
		
		return self::$valori[$chiave];
	}
	
	public static function ottieniVariabili()
	{
		$var = new VariabiliModel();
		
		$values = $var->clear()->toList("chiave", "valore")->send();
		
		self::$valori = $values;
	}
}
