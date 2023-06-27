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

class Lang
{
	public static $allowed = array('it','en');
	public static $current = 'en';

	public static function sanitize($lang = 'it')
	{
		return (in_array($lang,self::$allowed)) ? sanitizeAll($lang) : 'en';
	}

	public static $edit = true;
	
	public static $langDb = "";
	
	public static $i18n = array();
}

function getLinguaIso()
{
	return Params::$lang ? Params::$lang : Params::$defaultFrontEndLanguage;
}

//get the text in the right language
function gtext($string, $edit = true, $function = "none", $contesto = null, $gestibile = 1, $applicativo = "")
{
	$t = new TraduzioniModel();
	
	$string = rtrim($string);
	$string = str_replace("__"," ",$string);
	
	$tempLang = getLinguaIso();
	
	if (isset(Lang::$i18n[$tempLang][$string]))
	{
		if ((User::$adminLogged || TraduzioniModel::$edit) and $edit and Lang::$edit)
		{
			return $t->getTraduzione($string, $function, $contesto);
		}
		else
		{
			return call_user_func($function,htmlentitydecode(Lang::$i18n[$tempLang][$string]));
// 			return Lang::$i18n[$tempLang][$string];
		}
	}
	else
	{
		if (!isset($contesto))
			$contesto = TraduzioniModel::$contestoStatic;
		
		//inserisco la traduzione
		$t->values = array(
			"chiave"	=>	sanitizeDb($string),
			"valore"	=>	sanitizeDb($string),
			"lingua"	=>	sanitizeDb($tempLang),
			"contesto"	=>	sanitizeDb($contesto),
			"gestibile"	=>	(int)$gestibile,
			"applicativo"	=>	$applicativo,
		);
		
// 		try
// 		{
			$t->insert();
// 		}
// 		catch (Exception $e)
// 		{
// 			
// 		}
		
		return call_user_func($function,$string);
		
// 		return $string;
	}
}

function gtextDeep($value) {
	return array_map('gtext', $value);
}


function gtexta($string, $applicativo = "")
{
	return gtext($string, true, "none", null, 1, $applicativo);
}

// //get the text in the right language
// function gtext($string)
// {
// 	if (isset(Lang::$i18n[Lang::$current][$string]))
// 	{
// 		return Lang::$i18n[Lang::$current][$string];
// 	}
// 	return $string;
// }
