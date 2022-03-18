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

class NotificheModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='notifiche';
		$this->_idFields='id_notifica';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function getNotifiche()
	{
		self::segnaEseguite();
		
		$notifiche = array();
		
		$files = scandir(ROOT."/DB/Migrazioni", SCANDIR_SORT_DESCENDING);
		$ultimaMigrazione = $files[0];
		$migrationNum = (int)basename($ultimaMigrazione, '.sql');
		
		if ($migrationNum > (int)v("db_version"))
			$notifiche[] = array(
				"testo"	=>	gtext("Attenzione, aggiorna il database!"),
				"link"	=>	Url::getRoot()."cron/migrazioni/".v("codice_cron"),
				"icona"	=>	"fa-database",
				"class"	=>	"text-yellow",
			);
		
		if (v("piattaforma_in_sviluppo"))
			$notifiche[] = array(
				"testo"	=>	gtext("Indicizzazione non attiva."),
				"link"	=>	Url::getRoot()."impostazioni/variabili/1",
				"icona"	=>	"fa-warning",
				"class"	=>	"text-yellow",
			);
		
		if (v("permetti_gestione_sitemap") && !SitemapModel::g(false)->rowNumber())
			$notifiche[] = array(
				"testo"	=>	gtext("Sitemap vuota!"),
				"link"	=>	Url::getRoot()."sitemap/main",
				"icona"	=>	"fa-map-o",
				"class"	=>	"text-yellow",
			);
		
		$n = new NotificheModel();
		$res = $n->clear()->where(array(
			"risolta"	=>	0,
		))->orderBy("id_order desc")->send(false);
		
		foreach ($res as $r)
		{
			$queryStringChar = strstr($r["url"], '?') ? "&" : "?";
			$queryString = $queryStringChar . "id_notifica=".$r["id_notifica"];
			
			parse_str($r["condizioni"], $condizioni);
			
			if (VariabiliModel::verificaCondizioni($condizioni))
				$notifiche[] = array(
					"testo"	=>	gtext(htmlentitydecode($r["titolo"])),
					"link"	=>	Url::getRoot().$r["url"].$queryString,
					"icona"	=>	$r["icona"],
					"class"	=>	$r["classe"],
				);
		}
		
		return $notifiche;
	}
	
	public static function segnaEseguite()
	{
		if (isset($_GET["id_notifica"]))
		{
			$n = new NotificheModel();
			
			$n->setValues(array(
				"risolta"	=>	1,
			));
			
			$n->update((int)$_GET["id_notifica"]);
		}
	}
}
