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

//class to manage arrays
class ArrayExt { 

	public $errorString = null; //string containing the list fields not found
	public $errorsNumb = null; //numbers of errors

	//get the subset of the associative array $associativeArray defined by the keys in the string $keyString (keys separated by comma)
	public function subset($associativeArray,$keyString,$func = 'none') {
		if (!in_array($func,explode(',',Params::$allowedSanitizeFunc))) {
			throw new Exception('"'.$func. '" argument not allowed in '.__METHOD__.' method');
		}
		$tempArray = array();
		if (strcmp($keyString,'') !== 0) {
			$keyArray=explode(',',$keyString);
			for ($i = 0; $i < count($keyArray); $i++)
			{
				$temp = array();
				//extract the function after the colon
				if (strstr($keyArray[$i],':')) {
					$temp = explode(':',$keyArray[$i]);
				} else {
					$temp[0] = $keyArray[$i];
					$temp[1] = 'none';
				}
				//exception
				if (!in_array($temp[1],explode(',',Params::$allowedSanitizeFunc))) {
					throw new Exception('"'.$temp[1]. '" function not allowed');
				}
				if (array_key_exists($temp[0],$associativeArray) and !is_array($associativeArray[$temp[0]])) {
					$tempArray[$temp[0]] = call_user_func($temp[1],$associativeArray[$temp[0]]);
				} else {
					$tempArray[$temp[0]] = '';
				}
			}
		}
		return call_user_func($func.'Deep',$tempArray); //clean the array values
	}

	//exctract the complementary subset from an associative array ($associativeArray) of the subset identified by the keys $keyString
	public function subsetComplementary($associativeArray,$keyString,$func = 'none') {
		if (!in_array($func,explode(',',Params::$allowedSanitizeFunc))) {
			throw new Exception('"'.$func. '" argument not allowed in '.__METHOD__.' method');
		}
		$keyArray=explode(',',$keyString);
		$complementaryKeyArray = array();
		$keys = array_keys($associativeArray);
		foreach ($keys as $key) {
			if (!in_array($key,$keyArray)) {
				$complementaryKeyArray[] = $key;
			}
		}
		$complementaryKeyString = implode(',',$complementaryKeyArray);
		return $this->subset($associativeArray,$complementaryKeyString,$func);
	}

}
