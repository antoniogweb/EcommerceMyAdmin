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

//create the HTML of the inputs of a form
class Html_Form {
	
	//return the HTML of a select
	//$name: name of the select
	//$value: the selected value of the select (set $value equal to null if you don't want to select an option)
	//$options: options of the select. This param can be a comma-separated list of options or an associative array ('name'=>'value')
	//$className: the class name of the select
	//$idName: name of the id
	//$reverse: if option text and option values have to be interchanged (can be "yes" or a different value)
	//$attributes: attributes of the tag (specified as string, ex: "placeholder='name' style='color:red;'"
	static public function select($name, $value, $options, $className = null, $idName = null, $reverse = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		$returnString = null;
		$returnString .= "<select $attributes ".$idStr." $strClass name='".$name."'>\n";
		if (is_string($options)) {
			$tempArray = explode(',',$options);
			foreach ($tempArray as $item)
			{
				if (strstr($item,'optgroupOpen:'))
				{
					$temp = explode(':',$item);
					$optionsArray[$temp[1]] = "optgroupOpen";
				}
				else
				{
					$optionsArray[$item] = $item;
				}
			}
		}
		else
		{
			$optionsArray = $options;
		}

		$flag = 0;
		foreach ($optionsArray as $optionName => $optionValue) {

			$a = $optionName;
			$b = $optionValue;
			
			if (strcmp($reverse,'yes') === 0)
			{
				$b = $optionName;
				$a = $optionValue;
			}
			
			if (strcmp($optionValue,'optgroupOpen') === 0)
			{
				if ($flag === 1) $returnString .= "</optgroup>\n";
				$returnString .= "<optgroup label='" . $optionName . "'>\n";
				$flag = 1;
			}
			else
			{
				$str= (strcmp($value,$b) === 0) ? "selected='$b'" : null;
				$returnString .= "<option value=\"".$b."\" $str>$a</option>\n";
			}
		}
		if ($flag === 1) $returnString .= "</optgroup>\n";
		$returnString .= "</select>";
		return $returnString;
	}

	//return the HTML of an <input type='text' ...>
	//$name: the name of the input
	//$value: the value of the input
	//$className: the class name of the input
	//$idName: name of the id
	static public function input($name, $value, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		$returnString ="<input $attributes ".$idStr." $strClass type='text' name='" .$name. "' value = \"$value\" />";
		return $returnString;
	}

	//return the HTML of an <input type='file' ...>
	//$name: the name of the input
	//$className: the class name of the input
	//$idName: name of the id
	static public function fileUpload($name, $value, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;

		$returnString ="<input $attributes ".$idStr." $strClass type='file' name='" .$name. "' />";
		return $returnString;
	}
	
	//return the HTML of a checkBox
	//$name: name of the checkBox (string)
	//$value: the value of the checkBox (string or number)
	//$option: option of the checkBox (string or number)
	//$className: the class name of the checkBox (string)
	//$idName: name of the id
	static public function checkbox($name, $value, $option, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		$str = (strcmp($value,$option) === 0) ? "checked = 'checked'" : null;
		return "<input $attributes ".$idStr." $strClass type='checkbox' name='".$name."' value=\"".$option."\" $str />";
	}
	
	//return the HTML of a hidden entry
	//$name: name of the hidden entry (string)
	//$value: the value of the hidden entry (string or number)
	static public function hidden($name, $value, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		return "<input $attributes ".$idStr." $strClass type='hidden' name='" .$name. "' value = \"$value\">";
	}

	//return the HTML of a password entry
	//$name: name of the password entry (string)
	//$value: the value of the password entry (string or number)
	//$idName: name of the id
	static public function password($name, $value, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		return "<input $attributes ".$idStr." $strClass type='password' name='" .$name. "' value=\"$value\" />";
	}

	//return the HTML of a textarea
	//$name: name of the textarea (string)
	//$value: the value of the textarea (string or number)
	//$idName: name of the id
	static public function textarea($name, $value, $className = null, $idName = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		return "<textarea $attributes ".$idStr." $strClass name='" .$name. "'>$value</textarea>";
	}
	
	//return the HTML of a radio button
	//$name: name of the radio button
	//$value: the selected value of the radio button (set $value equal to null if you don't want to select an option)
	//$options: options of the radio button. This param can be a comma-separated list of options or an associative array ('name'=>'value')
	//$className: the class name of the radio button
	//$position: position of the strings of the radio with respect to the "circles". It can be before or after
	//$idName: name of the id
	//$reverse: if option text and option values have to be interchanged (can be "yes" or a different value)
	//$attributes: attributes of the tag (specified as string, ex: "placeholder='name' style='color:red;'"
	static public function radio($name, $value, $options, $className = null, $position = 'after', $idName = null, $reverse = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		$returnString = null;
		
		if (is_string($options)) {
			$tempArray = explode(',',$options);
			foreach ($tempArray as $item)
			{
				$optionsArray[$item] = $item;
			}
		} else {
			$optionsArray = $options;
		}
		
		foreach ($optionsArray as $optionName => $optionValue) {
			
			$a = $optionName;
			$b = $optionValue;
			
			if (strcmp($reverse,'yes') === 0)
			{
				$b = $optionName;
				$a = $optionValue;
			}
			
			if ($position === "none")
			{
				$before = null;
				$after = null;
			}
			else if ($position === 'before')
			{
				$before = $a;
				$after = null;
			}
			else
			{
				$before = null;
				$after = $a;
			}
			
// 			if ($position === 'before')
// 			{
// 				$before = $a;
// 				$after = null;
// 			}
// 			else
// 			{
// 				$before = null;
// 				$after = $a;
// 			}
			
			$str= (strcmp($value,$b) === 0) ? "checked='checked'" : null;
			$returnString .= "$before<input $attributes ".$idStr." $strClass type='radio' name='".$name."' value=\"".$b."\" $str />$after";
		}
		
		return $returnString;
	}
	
	//return the HTML of an <input type='submit' ...>
	//$name: the name of the input
	//$value: the value of the input
	//$className: the class name of the input
	//$idName: name of the id
	//$image: url of the image (if it is an image button)
	//$attributes: list of attributes
	static public function submit($name, $value, $className = null, $idName = null, $image = null, $attributes = null)
	{
		$strClass = isset($className) ? "class='".$className."'" : null;
		$idStr = isset($idName) ? "id='".$idName."'" : null;
		
		if (isset($image))
		{
			$returnString = "<input $attributes $idStr $strClass type='image' src='".$image."' value='$value'>";
			$returnString .= "<input type='hidden' name='".$name."' value='$value'>";
		}
		else
		{
			$returnString = '<button '.$idStr.' '.$attributes.' '.$strClass.' type="submit" name="' .$name. '">'.$value.'</button>';
			$returnString .= "<input type='hidden' name='".$name."' value='$value'>";
		}
		
		return $returnString;
	}
	
}
