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

class Url {

	//get the url starting from the root folder
	public static function getRoot($pathFromRootFolder = null) {
		
		$langUrl = isset(Params::$lang) ? "/".Params::$lang : null;
		
		$protocol = Params::$useHttps ? "https" : "http";
		
		$url = MOD_REWRITE_MODULE === true ? "$protocol://" . DOMAIN_NAME . $langUrl . '/' . $pathFromRootFolder : "$protocol://" . DOMAIN_NAME . '/index.php/' . $langUrl . $pathFromRootFolder;
		return $url;
	}

	//get the url starting from the root folder
	public static function getFileRoot($pathFromRootFolder = null) {
	
		$protocol = Params::$useHttps ? "https" : "http";
		
		$url = MOD_REWRITE_MODULE === true ? "$protocol://" . DOMAIN_NAME . '/' . $pathFromRootFolder : "$protocol://" . DOMAIN_NAME . '/index.php/' . $pathFromRootFolder;
		return $url;
	}
	
	public static function create(array $values, array $modifications = array())
	{
		foreach ($modifications as $k => $v)
		{
			if (isset($values[$k])) $values[$k] = $v;
		}
		
		return "?".http_build_query($values, '', '&', PHP_QUERY_RFC3986);
	}
	
	//create an url string (element1/element2/element4) from the values of the array $valuesArray considering only the elements indicated in the numeric string $numericString 
	//$forceRewrite: if true it always rewrite the status variables
	public static function createUrl($variablesArray, $numericString = null, $forceRewrite = false) {
		$elementsArray = explode(',',$numericString);
		$valuesArray = array_values($variablesArray);
		$keysArray = array_keys($variablesArray);
		$urlString = null;
		for ($i = 0; $i < count($valuesArray); $i++)
		{
			if (isset($numericString)) {
				if (isset($valuesArray[$i]) and in_array($i,$elementsArray)) {
					$urlString .= (Params::$rewriteStatusVariables or $forceRewrite) ? "/".$valuesArray[$i] : "&".$keysArray[$i]."=".$valuesArray[$i];
				}
			} else {
				if (isset($valuesArray[$i])) {
					$urlString .= (Params::$rewriteStatusVariables or $forceRewrite) ? "/".$valuesArray[$i] : "&".$keysArray[$i]."=".$valuesArray[$i];
				}
			}
		}
		return (Params::$rewriteStatusVariables or $forceRewrite) ? $urlString : "?".ltrim($urlString,"&");
	}

} 
