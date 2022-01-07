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

class OpzioniModel extends GenericModel {
	
	const CATEGORIE_GOOGLE = 'CATEGORIE_GOOGLE';
	
	public static $erroriImportazione = array();
	
	public function __construct() {
		$this->_tables='opzioni';
		$this->_idFields='id_opzione';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function codice($codice)
	{
		$op = new OpzioniModel();
		
		return $op->clear()->where(array(
			"codice"	=>	$codice,
			"attivo"	=>	1,
		))->toList("valore", "titolo")->findAll();
	}
	
	public static function arrayValori($codice)
	{
		return array_keys(self::codice($codice)); 
	}
	
	public static function stringaValori($codice)
	{
		return implode(",", array_keys(self::codice($codice))); 
	}
	
	// se l'opzione è attiva
	public static function isAttiva($codice, $valore)
	{
		$op = new OpzioniModel();
		
		return (int)$op->clear()->where(array(
			"codice"	=>	sanitizeDb($codice),
			"valore"	=>	sanitizeDb($valore),
		))->field("attivo");
	}
	
	public static function importaCategorieGoogle()
	{
		if (v("usa_transactions"))
		{
			Params::$setValuesConditionsFromDbTableStruct = false;
			
			$doc = file_get_contents(v("url_codici_categorie_google"));
			
			$lines = explode("\n",$doc);
			
			if (count($lines) > 0)
			{
				$o = new OpzioniModel();
				
				$o->query("delete from opzioni where codice = '".self::CATEGORIE_GOOGLE."'");
				
				$o->db->beginTransaction();
				
				foreach ($lines as $l)
				{
					if (preg_match('/^([0-9]{1,})([\-\s]{2,})(.*?)$/',$l, $matches))
					{
						$o->setValues(array(
							"valore"	=>	$matches["1"],
							"titolo"	=>	$matches["3"],
							"codice"	=>	self::CATEGORIE_GOOGLE,
							"traduzione"=>	0,
						),"sanitizeDb");
						
						if (!$o->insert())
							self::$erroriImportazione = $matches["1"] . " - " . $matches["3"];
					}
				}
				
				$o->db->commit();
			}
		}
	}
    
}
