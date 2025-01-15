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

class CookiearchivioModel extends GenericModel
{
	public static $testoCookieSessione = "Viene eliminato alla chiusura del browser";
	
	public function __construct() {
		$this->_tables='cookie_archivio';
		$this->_idFields='id_cookie_archivio';
		
		parent::__construct();
	}
	
	public function attiva($id)
	{
		$this->setValues(array(
			"attivo"	=>	1
		));
		
		$this->update((int)$id);
	}
	
	public function disattiva($id)
	{
		$this->setValues(array(
			"attivo"	=>	0,
			"durata"	=>	"",
		));
		
		$this->update((int)$id);
	}
	
	public function attivoCrud($record)
	{
		if ($record["cookie_archivio"]["attivo"])
			return "<i class='fa fa-check text-success'></i>";
		
		return "<i class='fa fa-ban'></i>";
	}
	
	public static function elencoCookie()
	{
		$cookies = self::g()->clear()->where(array(
			"attivo"	=>	1,
		))->orderBy("dominio,titolo")->send(false);
		
		if (count($cookies) > 0)
		{
			ob_start();
			include tpf("Elementi/Cookie/elenco_cookie.php");
			return ob_get_clean();
		}
		
		return "";
	}
	
	public static function getProprietario($text)
	{
		return $text;
		// return strpos($text, "oogle") !== false ? "Google" : $text;
	}
	
	public static function durata($time)
	{
		$ore = 0;
		$giorni = ($time + 60 - time()) / (3600 * 24);
		$giorniEsatti = number_format($giorni, 0);
		
		if ($giorni > 0 && $giorni < 1)
			$ore = ceil(($time + 60 - time()) / 3600);
		
		if ($ore)
			return "< $ore ".singPlu($ore, "ora", "ore");
		else
			return $giorni > 0 ? $giorniEsatti . " " . singPlu($giorniEsatti, "giorno", "giorni") : self::$testoCookieSessione;
	}
}
