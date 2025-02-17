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

class CartelementiModel extends GenericModel
{
	public static $erroriElementi = null;
	
	public function __construct() {
		$this->_tables = 'cart_elementi';
		$this->_idFields = 'id_cart_elemento';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'cart' => array("BELONGS_TO", 'CartModel', 'id_cart',null,"CASCADE"),
        );
    }
    
	public static function getElementiCarrello($idCart)
	{
		$ce = new CartelementiModel();
		
		return $ce->clear()->where(array(
			"id_cart"	=>	(int)$idCart,
		))->orderBy("id_cart_elemento")->send(false);
	}
	
	public static function isOkField($idEl, $field)
	{
		if (!v("attiva_gift_card"))
			return true;
		
		$arrayErrori = self::getErroriElementi();
		
		if (isset($arrayErrori[$idEl][$field]) && !$arrayErrori[$idEl][$field])
			return false;
		
		return true;
	}
	
	public static function haErrori($idEl = 0)
	{
		if (!v("attiva_gift_card"))
			return false;
		
		$arrayErrori = self::getErroriElementi();
		
		foreach ($arrayErrori as $id => $struct)
		{
			if ($idEl && (int)$id !== (int)$idEl)
				continue;
			
			foreach ($struct as $k => $v)
			{
				if (!$v)
					return true;
			}
		}
		
		return false;
	}
	
	public static function evidenzia($pageView)
	{
		return (isset($_GET["evidenzia"]) || $pageView == "partial") ? true : false;
	}
	
	public static function asArray($errori)
	{
		$arrayReturn = array();
		
		foreach ($errori as $id => $struct)
		{
			$temp = $struct;
			
			$temp["id"] = $id;
			
			$arrayReturn[] = $temp;
		}
		
		return $arrayReturn;
	}
	
	public static function getErroriElementi()
	{
		if (!isset(self::$erroriElementi))
		{
			$arrayErrori = array();
			
			if (!v("attiva_gift_card"))
				return $arrayErrori;
			
			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
			
			$ce = new CartelementiModel();
			
			$elementiCarrello = $ce->clear()->inner(array("cart"))->where(array(
				"cart.cart_uid"		=>	$clean["cart_uid"],
				"cart.gift_card"	=>	1,
			))->orderBy("cart.id_order ASC,id_cart ASC,id_cart_elemento")->send(false);
			
			foreach ($elementiCarrello as $el)
			{
				$temp = array();
				
				if (!isset($_GET["incrementa"]) && !checkMail($el["email"]))
					$temp["email"] = 0;
				else
					$temp["email"] = 1;
				
				if (!isset($_GET["incrementa"]) && !trim($el["testo"]))
					$temp["testo"] = 0;
				else
					$temp["testo"] = 1;
				
				$arrayErrori[$el["id_cart_elemento"]] = $temp;
			}
			
			self::$erroriElementi = $arrayErrori;
		}
		
		return self::$erroriElementi;
	}
}
