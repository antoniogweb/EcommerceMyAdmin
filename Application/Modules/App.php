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

class App
{
	public static $isFrontend = false;
	
	public static $isUsingCrud = false;
	
	public static $elencoCookieTecnici = array();
	
	public static $pannelli = array();
	
	public static $currentBreadcrumb = null;
	
	public static $vociMenuApp = [];
	
	public static function setPannelli()
	{
		self::$pannelli = array(
			"sito"	=>	array(
				"titolo"	=>	"CMS",
				"link"		=>	v("link_cms"),
				"icona"		=>	"fa-cloud",
				"classe"	=>	"help_cms",
				"ordine"	=>	10,
			),
			"ecommerce"	=>	array(
				"titolo"	=>	"E-commerce",
				"link"		=>	v("url_elenco_prodotti").'/main',
				"icona"		=>	"fa-shopping-cart",
				"condizioni"	=>	array(
					"attiva_menu_ecommerce"	=>	1,
				),
				"classe"	=>	"help_ecommerce",
				"ordine"	=>	20,
			),
			"marketing"	=>	array(
				"titolo"	=>	"Marketing",
				"link"		=>	'panel/main/marketing',
				"icona"		=>	"fa-line-chart",
				"condizioni"	=>	array(
					"attiva_marketing"	=>	1,
				),
				"classe"	=>	"help_marketing",
				"ordine"	=>	30,
			),
			"utenti"	=>	array(
				"titolo"	=>	"Preferenze",
				"link"		=>	'users/main',
				"icona"		=>	"fa-cog",
				"classe"	=>	"help_configurazione",
				"ordine"	=>	40,
			),
		);
		
		// Imposto le app
		if (defined("APPS"))
		{
			Params::$installed = APPS;
			
			foreach (APPS as $app)
			{
				$path = ROOT."/Application/Apps/".ucfirst($app)."/pannelli.php";
				
				if (file_exists($path))
				{
					if (isset($APP_PANNELLI))
						unset($APP_PANNELLI);
					
					include($path);
					
					if (isset($APP_PANNELLI))
						self::$pannelli = array_merge(self::$pannelli, $APP_PANNELLI);
				}
			}
		}
		
		uasort(self::$pannelli, function($a, $b){
			return strcmp($a["ordine"], $b["ordine"]);
		});
	}
	
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
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento degli articoli nel carrello"),
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
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento della sessione"),
					gtext("Durata")		=>	0,
					"usato"				=>	1,
				),
				"uidr"	=>	array(
					gtext("Fornitore")	=>	Parametri::$nomeNegozio,
					gtext("Tipologia")	=>	gtext("Tecnici"),
					gtext("Necessario")	=>	gtext("Sì"),
					gtext("Descrizione")=>	gtext("Utilizzato per il mantenimento della sessione utente"),
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
	
	public static function getCSFR($key)
	{
		if( !session_id() )
			session_start();
		
		$_SESSION[$key] = randomToken(20);
		
		return $_SESSION[$key];
	}
	
	public static function checkCSRF($key)
	{
		if( !session_id() )
			session_start();
		
		if (isset($_SESSION[$key]) && isset($_GET[$key]) && (string)$_SESSION[$key] === (string)$_GET[$key])
			return true;
		
		return false;
	}
	
	public static function primoLinkPannello($pannello = "ecommerce", $link = "")
	{
		if (!v("attiva_gruppi_admin"))
			return $link;
		
		$controllersTotali = ControllersModel::getControllerAbilitati();
// 		print_r($controllersTotali);die();
		if ((int)count($controllersTotali) === 0)
			return $link;
		
		$controllerPrincipali = ControllersModel::getControlliPrincipaliAbilitati(false, $pannello);
		
		if (count($controllerPrincipali) > 0)
			return $controllerPrincipali[0]["controllers"]["codice"]."/".$controllerPrincipali[0]["controllers"]["action_per_link"];
		
		return null;
	}
	
	public static function caricaMenu($pannello = "ecommerce")
	{
		$controllerPrincipali = ControllersModel::getControllerAbilitati(true);
		
		$path = ROOT . "/Application/Views/Vocimenu/".ucfirst($pannello);
		
		$files = array_diff(scandir($path), array('.', '..'));
		natsort($files);
		
		$arrayFilesConPath = [];
		
		// Aggiungo i file del CMS
		foreach ($files as $file)
		{
			$arrayFilesConPath[$file] = $path."/".$file;
		}
		
		// Carico i file di menù delle APP
		self::caricaVociMenuApp();
		
		// Cerco if file del pannello dalle APP
		$filesPannello = self::getVociMenuApp($pannello);
		
		// Aggiungo o sovrascrivo dai file delle APP
		foreach ($filesPannello as $app => $filesApp)
		{
			$path = ROOT . "/Application/Apps/".ucfirst($app)."/Vocimenu/".ucfirst($pannello);
			
			foreach ($filesApp as $file)
			{
				$arrayFilesConPath[$file] = $path."/".$file;
			}
		}
		
		$files = array_keys($arrayFilesConPath);
		natsort($files);
		
		$filesDaImportare = array();
		
		foreach ($files as $file)
		{
			if (preg_match('/^[0-9]{1,}\-([a-zA-Z\_]{1,})\.(php)$/',$file, $matches))
			{
				if (ControllersModel::checkAccessoAlController(array($matches[1])))
					$filesDaImportare[] = $arrayFilesConPath[$file];
			}
		}
		
		return $filesDaImportare;
	}
	
	public static function getVociMenuApp($pannello)
	{
		if (isset(self::$vociMenuApp[$pannello]))
			return self::$vociMenuApp[$pannello];
		
		return array();
	}
	
	// Va a cercare le voci di menù dell'app e le carica in self::$vociMenuApp
	public static function caricaVociMenuApp()
	{
		foreach (APPS as $app)
		{
			$pannelli = array_keys(self::$pannelli);
			
			foreach ($pannelli as $pannello)
			{
				$path = ROOT . "/Application/Apps/".ucfirst($app)."/Vocimenu/".ucfirst($pannello);
				
				if (@is_dir($path))
				{
					$files = array_diff(scandir($path), array('.', '..'));
					natsort($files);
					
					self::$vociMenuApp[$pannello][$app] = $files;
				}
			}
		}
	}
	
	// Crea la cartella Logs se non è presente
	public static function createLogFolder()
	{
		if(!is_dir(ROOT.'/Logs'))
		{
			if (@mkdir(ROOT.'/Logs'))
			{
				$fp = fopen(ROOT.'/Logs/index.html', 'w');
				fclose($fp);
				
				$fp = fopen(ROOT.'/Logs/.htaccess', 'w');
				fwrite($fp, 'deny from all');
				fclose($fp);
			}
		}
	}
}
