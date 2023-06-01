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

class ListeregalotipiModel extends GenericModel
{
	public static $tipi = array();
	public static $tipiDelCampo = array();
	public static $arrayIdCampi = null;
	
	public function __construct() {
		$this->_tables = 'liste_regalo_tipi';
		$this->_idFields = 'id_lista_tipo';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		$this->addStrongCondition("both",'checkIsNotStrings|0',"giorni_scadenza");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'liste' => array("HAS_MANY", 'ListeregaloModel', 'id_lista_tipo', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
    }
    
    public static function getSelectTipi($idTipo = 0)
    {
		$tipiAttivi = self::g()->where(array(
			"OR"	=>	array(
				"attivo"	=>	"Y",
				"id_lista_tipo"	=>	(int)$idTipo,
			)
		))->orderBy("id_order")->toList("id_lista_tipo", "titolo")->send();
		
		return array_map('gtext', $tipiAttivi);
    }
    
    public static function obbligatorio($idTipo, $campo)
    {
		if (!isset(self::$tipi[$idTipo]))
			self::$tipi[$idTipo] = self::g()->selectId($idTipo);
		
		if (isset(self::$tipi[$idTipo]["campi_obbligatori"]) && self::$tipi[$idTipo]["campi_obbligatori"])
		{
			$campiArray = explode(",", self::$tipi[$idTipo]["campi_obbligatori"]);
			
			if (in_array($campo, $campiArray))
				return true;
		}
		
		return false;
    }
    
    public static function campoPresenteInTipi($campo, $stringa = "tipo_lista_")
    {
		if (!isset(self::$tipiDelCampo[$campo]))
		{
			self::$tipiDelCampo[$campo] = array();
			
			$tipi = isset(self::$arrayIdCampi) ? self::$arrayIdCampi : self::g()->select("id_lista_tipo,campi")->toList("id_lista_tipo", "campi")->send();
			
			foreach ($tipi as $id => $campi)
			{
				$campiArray = explode(",", $campi);
				
				if (in_array($campo, $campiArray))
					self::$tipiDelCampo[$campo][] = $stringa.$id;
			}
		}
		
		return self::$tipiDelCampo[$campo];
    }
}
