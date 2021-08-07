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

class LingueModel extends GenericModel
{
	public static $valori = null;
	public static $valoriAttivi = null;
	public static $codici = null;
	public static $lingueBackend = array();
	
	public function __construct() {
		$this->_tables = 'lingue';
		$this->_idFields = 'id_lingua';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function getValoriAttivi()
	{
		$l = new LingueModel();
		
		if (!isset(self::$valoriAttivi))
		{
			$l->clear()->orderBy("id_order")->toList("codice","descrizione");
			
			$l->where(array(
				"attiva"	=>	1,
			));
			
			self::$valoriAttivi = $l->send();
		}
		
		return self::$valoriAttivi;
	}
	
	public static function getValori($soloAttive = false)
	{
		$l = new LingueModel();
		
		if (!isset(self::$valori))
		{
			$l->clear()->orderBy("id_order")->toList("codice","descrizione");
			
			self::$valori = $l->send();
		}
		
		return self::$valori;
	}
	
	public static function getCodici()
	{
		$l = new LingueModel();
		
		if (!isset(self::$codici))
			self::$codici = $l->clear()->orderBy("id_order")->toList("codice_clean","codice")->send();
		
		return self::$codici;
	}
	
	public static function getPrincipale()
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"principale"	=>	1
		))->field("codice");
	}
	
	public static function getLingueBackend()
	{
		self::$lingueBackend = self::getLingue(true);
	}
	
	public static function linguaPermessaBackend($lang)
	{
		if (isset(self::$lingueBackend[$lang]))
			return true;
		
		return false;
	}
	
	public static function getLingue($soloBackend = false)
	{
		$l = new LingueModel();
		
		$l->clear()->where(array(
			"attiva"	=>	1,
		))->orderBy("id_order")->toList("codice", "descrizione");
		
		if ($soloBackend)
			$l->aWhere(array(
				"backend"	=>	1,
			));
		
		return $l->send();
	}
	
	public static function titoloLinguaCorrente()
	{
		if (isset(self::$lingueBackend[Params::$lang]))
			return self::$lingueBackend[Params::$lang];
		
		return "";
	}
	
	public static function permettiCambioLinguaBackend()
	{
		self::getLingueBackend();
		
		if (!empty(self::$lingueBackend) && v("permetti_cambio_lingua"))
			return true;
		
		return false;
	}
	
	public static function getIdDaCodice($codice)
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"codice"	=>	sanitizeAll($codice),
		))->field("id_lingua");
	}
}
