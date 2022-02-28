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

class App
{
	public static $elencoCookieTecnici = array();
	
	public static function getCookieTecnici()
	{
		if (empty(self::$elencoCookieTecnici))
		{
			self::$elencoCookieTecnici = array(
				"wishlist_uid"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento della lista dei desideri"),
					gtext("Durata")		=>	v("durata_carrello_wishlist_coupon"),
					"usato"				=>	v("ecommerce_attivo"),
				),
				"cart_uid"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento della lista del carrello"),
					gtext("Durata")		=>	v("durata_carrello_wishlist_coupon"),
					"usato"				=>	v("ecommerce_attivo"),
				),
				"uid_contatto"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per mantenere la sessioni dei contatti verificati"),
					gtext("Durata")		=>	v("tempo_durata_uid_contatto"),
					"usato"				=>	v("ecommerce_attivo"),
				),
				"coupon"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per le promozioni"),
					gtext("Durata")		=>	v("durata_carrello_wishlist_coupon"),
					"usato"				=>	v("ecommerce_attivo"),
				),
				"ok_cookie"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per le preferenze sui cookie"),
					gtext("Durata")		=>	v("durata_impostazioni_cookie"),
					"usato"				=>	1,
				),
				"ok_cookie_terzi"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per le preferenze sui cookie"),
					gtext("Durata")		=>	v("durata_impostazioni_cookie"),
					"usato"				=>	1,
				),
				"PHPSESSID"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	"Utilizzato per il mantenimento della sessione",
					gtext("Durata")		=>	0,
					"usato"				=>	1,
				),
				"uidr"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	"Utilizzato per il mantenimento della sessione utente",
					gtext("Durata")		=>	REG_SESSION_EXPIRE,
					"usato"				=>	1,
				),
				"uid"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento della sessione utente"),
					gtext("Durata")		=>	ADMIN_SESSION_EXPIRE,
					"usato"				=>	1,
				),
				"uid_stats"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per le statistiche di visualizzazione delle pagine, non vengono salvati dati sensibili come IP o altre informazioni."),
					gtext("Durata")		=>	v("durata_statistiche_cookie"),
					"usato"				=>	v("salva_satistiche_visualizzazione_pagina"),
				),
			);
			
			if (v("attiva_modali"))
			{
				$p = new PagesModel();
				
				$modale = $p->clear()->addJoinTraduzionePagina()->where(array(
					"categories.section"	=>	"modali",
					"attivo"=>"Y",
				))->orderBy("pages.id_order desc")->limit(1)->record();
				
				if (!empty($modale) && $modale["giorni_durata_modale"] >= 0)
				{
					self::$elencoCookieTecnici["modale_".$modale["id_page"]] = array(
						gtext("Fornitore")	=>	Parametri::$nomeNegozio,
						gtext("Tipologia")		=>	gtext("Tecnici"),
						gtext("Necessario")	=>	gtext("Sì"),
						gtext("Descrizione")	=>	gtext("Utilizzato per le preferenze nella visualizzazione dei popup"),
						gtext("Durata")		=>	$modale["giorni_durata_modale"] * 3600 * 24,
					);
				}
			}
		}
		
		return self::$elencoCookieTecnici;
	}
	
	public static function cancellaCookiesGdpr()
	{
		F::cancellaCookiesGdpr();
	}
	
	public static function settaCookiesGdpr($allCookies = false)
	{
		F::settaCookiesGdpr($allCookies);
	}
}