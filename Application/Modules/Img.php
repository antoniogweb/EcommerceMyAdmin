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

class Img
{
	public static $staticDataSrcAttribute = "data-src";
	public static $thumbController = "thumb";
	public static $lazyLoadClass = "lazyload";
	public static $layoutCaricato = false;
	public static $layoutParams = array();
	public static $contatore = 0;
	private static $soglia = 0;
	
	public static function p($attributes = array())
	{
		return "<img ".arrayToAttributeString(Img::manage($attributes))."/>";
	}
	
	// imposta $soglia, sotto la quale non viene usato il lazyloading
	// serve per i cicli
	public static function setSogliaLazyLoading($soglia)
	{
		self::$soglia = $soglia;
	}
	
	// restituisce gli attributi lazy loading
	private static function manage($attributes)
	{
		if (!isset($attributes["lazy"]) || !isset($attributes["action"]) || !$attributes["lazy"])
		{
			if (isset($attributes["src"]))
				$attributes["src"] = Url::getFileRoot().$attributes["src"];
			
			return $attributes;
		}
		
		if (self::$contatore < self::$soglia)
		{
			unset($attributes["lazy"]);
			unset($attributes["action"]);
			
			self::$contatore++;
			
			if (isset($attributes["src"]))
				$attributes["src"] = Url::getFileRoot().$attributes["src"];
			
			return $attributes;
		}
		
		if ($attributes["class"])
			$attributes["class"] .= " ".Img::$lazyLoadClass;
		
		if (isset($attributes["src"]))
		{
			$attributes["src"] = ltrim($attributes["src"], "/");
			$path_parts = pathinfo($attributes["src"]);
			
			$attributes["data-src"] = Url::getFileRoot().$attributes["src"];
			$attributes["src"] = Url::getFileRoot().Img::$thumbController."/".$attributes["action"]."/".$path_parts["basename"];
		}
		
		unset($attributes["lazy"]);
		unset($attributes["action"]);
		
		self::$contatore++;
		
		return $attributes;
	}
}
