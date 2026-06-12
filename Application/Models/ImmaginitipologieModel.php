<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class ImmaginitipologieModel extends GenericModel
{
	public static $contesti = array(
		"P"	=>	"PagesModel", // Pagina
		"C"	=>	"CategoriesModel", // Categoria
		"M"	=>	"MarchiModel", // Marchio
		"T"	=>	"TagModel" // Tag
	);
	
	public function __construct() {
		$this->_tables = 'immagini_tipologie';
		$this->_idFields = 'id_immagine_tipologia';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		parent::__construct();
	}
	
	public static function checkContesto($contesto)
	{
		if (!in_array($contesto, array_keys(self::$contesti)))
			return false;
		
		switch ($contesto)
		{
			case "P":
				return true;
				break;
			case "C":
				if (v("immagini_in_categorie_prodotti"))
					return true;
				break;
			case "M":
				if (v("immagini_in_marchi"))
					return true;
				break;
			case "T":
				if (v("immagini_in_tag"))
					return true;
				break;
		}
		
		return false;
	}
	
	public static function getModel($contesto)
	{
		if (!self::checkContesto($contesto))
			return null;
		
		$modelString = self::$contesti[$contesto];
		
		return new $modelString();
	}
	
	public static function numero()
	{
		return self::g(false)->rowNumber();
	}
}
