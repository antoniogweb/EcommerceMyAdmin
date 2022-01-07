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

class F
{
	public static function mediana($arr) {
		sort($arr);
		$count = count($arr);
		$middleval = floor(($count-1)/2);
		if ($count % 2) {
			$median = $arr[$middleval];
		} else {
			$low = $arr[$middleval];
			$high = $arr[$middleval+1];
			$median = (($low+$high)/2);
		}
		return $median;
	}
	
	public static function getLimitiMinMax($valore, $scaglione)
	{
		$rapporto = floor(abs($valore) / $scaglione);
		
		$min = $rapporto * $scaglione;
		$max = ($rapporto + 1) * $scaglione;
		
		if ($valore < 0)
			return array($max * (-1), $min * (-1));
		else
			return array($min, $max);
	}
	
	public static function meta($string, $num = 999999)
	{
		$string = strip_tags(htmlentitydecode($string));
		
		if (eg_strlen($string) > $num)
		{
			$string = mb_substr($string,0,$num)."...";
		}
		return htmlspecialchars($string, ENT_COMPAT, "UTF-8");
	}
}
