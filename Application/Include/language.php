<?php
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
		
		$t->insert();
		
		return call_user_func($function,$string);
		
// 		return $string;
	}
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
