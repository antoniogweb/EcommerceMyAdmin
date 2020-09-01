<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');


/*
	SANITIZE FUNCTIONS
*/

function none($string) {
	return $string;
}

function forceInt($string) {
	return (int)$string;
}

function forceNat($string)
{
	$string = (int)$string;
	if ($string <= 0) return 1;
	return $string;
}

function sanitizeQuery($value)
{
	if ((!defined('NEW_WHERE_CLAUSE_STYLE') or !NEW_WHERE_CLAUSE_STYLE) && !Params::$newWhereClauseStyle)
	{
		$regExpr = '/^('.implode("|",Params::$whereClauseTransformSymbols).')\:(.*)$/';
		
		foreach (params::$whereClauseSymbolArray as $symbol)
		{
			if (strpos($value, $symbol) === 0)
			{
				$value = Params::$cleanSymbol.$value;
// 				$value = mb_substr($value,strlen($symbol));
// 				
// 				return sanitizeQuery($value);
			}
		}
		
		if (preg_match($regExpr,$value,$matches))
		{
			$value = Params::$cleanSymbol.$value;
// 			$value = mb_substr($value,strlen($matches[1])+1);
// 			
// 			return sanitizeQuery($value);
		}
	}
	
	return $value;
}

function sanitizeDb($stringa) {

	if (DATABASE_TYPE === 'Mysql')
	{
		$stringa = sanitizeQuery($stringa);
		$stringa = mysql_real_escape_string($stringa);
		return $stringa;
	}

	if (DATABASE_TYPE === 'Mysqli')
	{
		$stringa = sanitizeQuery($stringa);
		$mysqli = Db_Mysqli::getInstance();
		$db = $mysqli->getDb();
		$stringa = $db->real_escape_string($stringa);
		return $stringa;
	}

	return $stringa;
}

function sanitizeAll($stringa) {

	$stringa=sanitizeHtml($stringa);
	$stringa=sanitizeDb($stringa);
	return $stringa;

}

function sanitizeHtml($stringa) {

	$charset = Params::$htmlentititiesCharset;
	$stringa=htmlentities($stringa,ENT_QUOTES,$charset);
	return $stringa;

}

//check if only alphabetic + optional characters are present in the string $string. Set $string to $altString if other characters are found
//$optChar: allowed characters divided by '|'  Ex: '+|-|;'
function sanitizeCustom($string,$optChar,$altString = 'EasyGiant')
{
	
	$optChar = html_entity_decode($optChar,ENT_QUOTES);
	$optCharArray = explode('|',$optChar);
	$temp = $string;
	foreach($optCharArray as $char)
	{
		$temp = str_replace($char,null,$temp);
	}
	if (ctype_alnum($temp))
	{
		return $string;
	}
	else
	{
		return $altString;
	}
}




/*
SANITIZE DEEP
*/

function stripslashesDeep($value) {
	if(get_magic_quotes_gpc()) {#if stripslashes
		return array_map_recursive('stripslashes', $value);
	}
	return $value;
}

//from http://www.php.net/array_map#112857
function array_map_recursive($callback, $array) {
	foreach ($array as $key => $value) {
		if (is_array($array[$key])) {
			$array[$key] = array_map_recursive($callback, $array[$key]);
		}
		else {
			$array[$key] = call_user_func($callback, $array[$key]);
		}
	}
	return $array;
}
    
function sanitizeHtmlDeep($value) {
	return array_map('sanitizeHtml', $value);
}


function sanitizeDbDeep($value) {
	return array_map('sanitizeDb', $value);
}


function sanitizeCustomDeep($stringArray,$optChar,$altString = 'EasyGiant')
{
	$result = array();
	foreach ($stringArray as $key => $value)
	{
		$result[$key] = sanitizeCustom($value,$optChar,$altString);
	}
	return $result;
}


function sanitizeAllDeep($value) {
	return array_map('sanitizeAll', $value);
}


function forceIntDeep($value) {
	return array_map('forceInt', $value);
}

function forceNatDeep($value) {
	return array_map('forceNat', $value);
}

function noneDeep($value) {
	return array_map('none', $value);
}


function md5Deep($value) 
{
	return array_map('md5', $value);
}

function sha1Deep($value)
{
	return array_map('sha1', $value);
}

function strip_tagsDeep($value) {
	return array_map('strip_tags', $value);
}





function sanitizeAlnum($string)
{
	return ctype_alnum($string) ? sanitizeAll($string) : '';
}


function sanitizeIp($ip)
{
	return preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',$ip) ? sanitizeAll($ip) : '';
}


/*
	CHECK FUNCTIONS
*/

//check if a string has the mail format (abc.efg@hij.klm.on)
//modification of the rule found at http://www.sastgroup.com/tutorials/8-espressioni-regolari-per-validare-un-po-di-tutto
//original rule: /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/
function checkMail($string)
{
// 	if (preg_match('/^[a-zA-Z0-9_\-]+([.][a-zA-Z0-9_\-]+){0,3}[@][a-zA-Z0-9_\-]+([.][a-zA-Z0-9_\-]+){0,2}[.][a-zA-Z]{2,4}$/',$string))
	if (filter_var($string, FILTER_VALIDATE_EMAIL))
	{
		return true;
	}
	else
	{
		return false;
	}
}



function wrap($string,$tag_class) {#wrap the string with the tag and its class
	#$tag_class has to be an associative array (tag1=>class1,$tag2=>class2,.. )!!
	$str_front=null;
	$str_rear=null;
	if (is_array($tag_class)) {
		foreach ($tag_class as $tag => $class) {
				$tag = str_replace('+','',$tag);
				if (!is_array($class))
				{
					$str_class=isset($class) ? " class=\"".$class."\"" : null;
				}
				else
				{
					$str_class = null;
					foreach ($class as $attr => $val)
					{
						$str_class .= " ".$attr."='".$val."' ";
					}
				}
				$str_front.="<".$tag.$str_class.">\n";
				$str_rear.="</".$tag.">\n";
		}
	}
	return $str_front.$string.$str_rear;
}

//check that $date is a ISO date (YYYY-MM-DD)
function checkIsoDate($date)
{
	if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/',$date))
	{
		$dateArray = explode('-',$date);
		if ((int)$dateArray[1] <= 12 and (int)$dateArray[1] >= 1 )
		{
			if ((int)$dateArray[2] >= 1 and (int)$dateArray[2] <= 31)
			{
				return checkdate((int)$dateArray[1],(int)$dateArray[2],(int)$dateArray[0]);
			}
		}
	}
	return false;
}

//check if $string is an integer string
function checkInteger($string)
{
	if (preg_match('/^\-?[0-9]{1,}$/',$string))
	{
		return true;
	}
	return false;
}

//check if $string is decimal with the format indicated in $format
//$format: M,D M is the maximum number of digits, D is the number of digits to the right of the decimal point
function checkDecimal($string, $format)
{
	$t = explode(",",$format);
	$M = (int)$t[0];
	$D = (int)$t[1];
	$I = $M - $D;
	
	if (preg_match("/^[0-9]{1,$I}(\.[0-9]{0,$D})?$/",$string))
	{
		return true;
	}
	return false;
}

//get label name from field name
function getFieldLabel($fieldName)
{
	if (class_exists("Lang_".Params::$language."_Formats_Fields"))
	{
		return call_user_func(array("Lang_".Params::$language."_Formats_Fields", "getLabel"), $fieldName);
	}
	
	return call_user_func(array("Lang_En_Formats_Fields", "getLabel"), $fieldName);
	
// 	if (strstr($fieldName,","))
// 	{
// 		$temp = explode(",",$fieldName);
// 		for ($i=0; $i< count($temp); $i++)
// 		{
// 			$temp[$i] = getFieldLabel($temp[$i]);
// 		}
// 		return implode (" and ",$temp);
// 	}
// 	else
// 	{
// 		$fieldName = str_replace("_"," ", $fieldName);
// 		return ucfirst($fieldName);
// 	}
}

//generate a random password
//$start: start number of mt_rand
//$end: end number of mt_rand
function randString($length,$start = 33, $end = 126)
{
	$random = '';
	for ($i = 0; $i < $length; $i++)
	{
		$random .= chr(mt_rand($start, $end));
	}
	return $random;
}

//generate a random string
//$charNumb:number of characters of the final string
//$allowedChars: allowed characters
function generateString($charNumb = 8,$allowedChars = '0123456789abcdefghijklmnopqrstuvwxyz')
{
	$str = null;
	for ($i = 0; $i < $charNumb; $i++)
	{
		$str .= substr($allowedChars, mt_rand(0, strlen($allowedChars)-1), 1);
	}
	return $str;
}


function getIp()
{
    $ip = "";

    if (isset($_SERVER))
    {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = sanitizeIp($_SERVER["HTTP_X_FORWARDED_FOR"]);
        } else if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = sanitizeIp($_SERVER["HTTP_CLIENT_IP"]);
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = sanitizeIp($_SERVER["REMOTE_ADDR"]);
        }
    } else {
        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) !== false ) {
            $ip = sanitizeIp(getenv( 'HTTP_X_FORWARDED_FOR' ));
        } else if ( getenv( 'HTTP_CLIENT_IP' ) !== false ) {
            $ip = sanitizeIp(getenv( 'HTTP_CLIENT_IP' ));
        } else if ( getenv( 'REMOTE_ADDR' ) !== false ) {
            $ip = sanitizeIp(getenv( 'REMOTE_ADDR' ));
        }
    }
    return $ip;
}



function getUserAgent() {
	if (isset($_SERVER['HTTP_USER_AGENT']))
	{
		return md5($_SERVER['HTTP_USER_AGENT']);
	} 
	else
	{
		return md5('firefox');
	}
}

//encode a string to drop ugly characters
function encode($url)
{
	$url = utf8_decode(html_entity_decode($url,ENT_QUOTES,'UTF-8'));
	
	$temp = null;
	
	for ($i=0;$i<eg_strlen($url); $i++)
	{
// 		echo substr($url,$i,1)."<br />";
		if (strcmp(substr($url,$i,1),' ') === 0)
		{
			$temp .= '_';
		}
		else if (strcmp(substr($url,$i,1),"'") === 0)
		{
			$temp .= '';
		}
		else
		{
			if (preg_match('/^[a-zA-Z\_0-9]$/',substr($url,$i,1)))
			{
				$temp .= substr($url,$i,1);
			}
			else
			{
				$temp .= '_';
			}
		}
	}

	$temp = urlencode($temp);
	return $temp;
}

function callFunction($function, $string, $caller = "CallFunction")
{
	if (strstr($function,'::')) //static method
	{
		$temp = explode('::',$function);
		
		if (!method_exists($temp[0],$temp[1]))
		{
			throw new Exception('Error in <b>'.$caller.'</b>: method <b>'.$temp[1].'</b> of class <b>'.$temp[0].'</b> does not exists.');
		}
		
		return call_user_func(array($temp[0], $temp[1]),$string);
	}
	else if (strstr($function,'.')) //method
	{
		$temp = explode('.',$function);
		
		$obj = new $temp[0]; //new instance of the object
		
		if (!method_exists($obj,$temp[1]))
		{
			throw new Exception('Error in <b>'.$caller.'</b>: method <b>'.$temp[1].'</b> of class <b>'.$temp[0].'</b> does not exists.');
		}
		
		return call_user_func(array($obj, $temp[1]),$string);
	}
	else //function
	{
		if (!function_exists($function)) {
			throw new Exception('Error in <b>'.$caller.'</b>: function <b>'.$function.'</b> does not exists.');
		}
		//apply the function
		return call_user_func($function,$string);
	}
}

function xml_encode($string)
{
	$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
	foreach ($trans as $k=>$v)
	{
		$trans[$k]= "&#".ord($k).";";
	}
	
	return strtr($string, $trans);
}

//Convert Hex Color to RGB
//http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

function wclk($string)
{
	return "like '%$string%'";
}

function arrayToAttributeString($array)
{
	$html = "";
	
	foreach ($array as $k => $v)
	{
		$html .= " $k='$v' ";
	}
	
	return $html;
}
