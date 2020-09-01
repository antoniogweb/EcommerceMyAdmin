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

//class to validate associative arrays
class Array_Validate_Base
{ 

	public $strength;
	public $errorString = null; //string containing the list fields not found
	public $errorsNumb = null; //numbers of errors

	protected $_lang; //language of notices
	protected $_resultString; //reference to the class arraycheckStrings containing all the result strings
	
	public $errors = array();

	public function __construct($lang = 'En')
	{
		$this->_lang = $lang;
		$stringClass = 'Lang_'.$this->_lang.'_ValCondStrings';
		if (!class_exists($stringClass))
		{
			$stringClass = 'Lang_En_ValCondStrings';
		}
		$this->_resultString = new $stringClass();
	}

	public function checkNotEmpty($associativeArray,$keyString)
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (is_array($associativeArray[$keyArray[$i]]))
				{
					$error = $this->_resultString->getNotDefinedResultString($keyArray[$i]);
					$errorString .= $error;
					$this->errors[$keyArray[$i]][] = strip_tags($error);
					
					$numb++;
				}
				else
				{
					if (strcmp(trim($associativeArray[$keyArray[$i]]),'') === 0)
					{
						$error = $this->_resultString->getNotDefinedResultString($keyArray[$i]);
						$errorString .= $error;
						$this->errors[$keyArray[$i]][] = strip_tags($error);
						
						$numb++;
					}
				}
			}
			else
			{
				$error = $this->_resultString->getNotDefinedResultString($keyArray[$i]);
				$errorString .= $error;
				
				$this->errors[$keyArray[$i]][] = strip_tags($error);
				
				$numb++;
			}
		}
		
		$this->errorString = $errorString;
		$this->errorNumb = $numb;
		return $numb === 0 ? true : false;
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are not '' and are equal (===) to each other
	public function checkEqual($associativeArray,$keyString)
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		
		//set the first value to null
		$firstValue = null;
		
		foreach ($keyArray as $key)
		{
			if (array_key_exists($key,$associativeArray))
			{
				$firstValue = $associativeArray[$key];
				break;
			}
		}
		
		if (isset($firstValue))
		{
			for ($i = 0; $i < count($keyArray); $i++)
			{
				if (array_key_exists($keyArray[$i],$associativeArray))
				{
					if (strcmp($associativeArray[$keyArray[$i]],$firstValue) !== 0)
					{
						$numb++;
						$error = $this->_resultString->getNotEqualResultString($keyString);
						$errorString = $error;
					}
				}
			}
		}
		
		if ($numb > 0)
		{
			foreach ($keyArray as $key)
			{
				$this->errors[$key][] = strip_tags($this->_resultString->getNotEqualResultString($key));
			}
		}
		
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are alphabetic values
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkAlpha($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'ctype_alpha','getNotAlphabeticResultString');
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are alphanumeric values
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkAlphaNum($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'ctype_alnum','getNotAlphanumericResultString');
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are decimal digits
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkDigit($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'ctype_digit','getNotDecimalDigitResultString');
	}
	

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) have mail format
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkMail($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'checkMail','getNotMailFormatResultString');
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are integer strings
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkInteger($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'checkInteger','getNotIntegerFormatResultString');
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) is a number (integer or number). It makes use of the is_numeric PHP built-in function
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkNumeric($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'is_numeric','getNotNumericResultString');
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are an ISO date.
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkIsoDate($associativeArray,$keyString,$strength = 'strong')
	{
		return $this->checkGeneric($associativeArray,$keyString,$strength,'checkIsoDate','getNotDateResultString');
	}
	
	//apply a generic check function
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	//$func: the function to apply
	//$strFunc: the method of the object $this->_resultString to apply
	private function checkGeneric($associativeArray,$keyString,$strength,$func,$strFunc)
	{

		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (strcmp($associativeArray[$keyArray[$i]],'') !== 0 or $this->strength === 'strong')
				{
					if (!call_user_func($func,$associativeArray[$keyArray[$i]]))
					{
						$numb++;
						$error = call_user_func(array($this->_resultString,$strFunc),$keyArray[$i]);
						
						$errorString .= $error;
						
						$this->errors[$keyArray[$i]][] = strip_tags($error);
					}
				}
			}
		}
		
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;

	}
	
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) have a number of chars smaller than $maxLenght
	public function checkLength($associativeArray,$keyString,$maxLength = 10)
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (eg_strlen($associativeArray[$keyArray[$i]]) > $maxLength)
				{
					$numb++;
					$error = $this->_resultString->getLengthExceedsResultString($keyArray[$i],$maxLength);
					$errorString .= $error;
					
					$this->errors[$keyArray[$i]][] = strip_tags($error);
				}
			}
		}
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;

	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are different from the values indicated in the argument $strings (a comma-separated list of words)
	public function checkIsNotStrings($associativeArray,$keyString,$strings = '')
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		//get the array from the comma-separated list of strings
		$stringsArray = explode(',',$strings);
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				foreach ($stringsArray as $string)
				{
					if (strcmp($associativeArray[$keyArray[$i]],$string) === 0)
					{
						$numb++;
						
						$error = $this->_resultString->getIsForbiddenStringResultString($keyArray[$i],$strings);
						$errorString .= $error;
						
						$this->errors[$keyArray[$i]][] = strip_tags($error);
					}
				}
			}
		}
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are one of the values indicated in the argument $strings (a comma-separated list of words)
	//$strength: hard or soft. If $strength is set equal to soft than non check is made upon array values equalt to '' or null
	public function checkIsStrings($associativeArray,$keyString,$strings = '',$strength = 'strong')
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		//get the array from the comma-separated list of strings
		$stringsArray = explode(',',$strings);
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (strcmp($associativeArray[$keyArray[$i]],'') !== 0 or $this->strength === 'strong')
				{
					if (!in_array($associativeArray[$keyArray[$i]],$stringsArray))
					{
						$numb++;
						
						$error = $this->_resultString->getIsNotStringResultString($keyArray[$i],$strings);
						$errorString .= $error;
						
						$this->errors[$keyArray[$i]][] = strip_tags($error);
					}
				}
			}
		}
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;
	}

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) match the regular expression $regExp
	public function checkMatch($associativeArray,$keyString,$regExp = '/./',$strength = 'strong')
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (strcmp($associativeArray[$keyArray[$i]],'') !== 0 or $this->strength === 'strong')
				{
					if (!preg_match($regExp,$associativeArray[$keyArray[$i]]))
					{
						$numb++;
						
						$error = $this->_resultString->getDoesntMatchResultString($keyArray[$i],$regExp);
						$errorString .= $error;
						
						$this->errors[$keyArray[$i]][] = strip_tags($error);
					}
				}
			}
		}
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are decimal with the format indicated in $format
	//$format: M,D M is the maximum number of digits, D is the number of digits to the right of the decimal point
	public function checkDecimal($associativeArray,$keyString,$format = '10,2')
	{
		$errorString = null;
		$keyArray = explode(',',$keyString);
		$numb = 0;
		for ($i = 0; $i < count($keyArray); $i++)
		{
			if (array_key_exists($keyArray[$i],$associativeArray))
			{
				if (strcmp($associativeArray[$keyArray[$i]],'') !== 0 or $this->strength === 'strong')
				{
					if (!checkDecimal($associativeArray[$keyArray[$i]],$format))
					{
						$numb++;
						
						$error = $this->_resultString->getNotDecimalResultString($keyArray[$i],$format);
						$errorString .= $error;
						
						$this->errors[$keyArray[$i]][] = strip_tags($error);
					}
				}
			}
		}
		$this->errorString = $errorString;
		return $numb === 0 ? true : false;
	}
	
}
