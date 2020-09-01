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
class Array_Validate_Strong extends Array_Validate_Base
{ 
	
	public function __construct($lang = 'En')
	{
		parent::__construct($lang);
		
		$this->strength = "strong";
	}

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are not '' and are equal (===) to each other
	public function checkEqual($associativeArray,$keyString)
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkEqual($associativeArray,$keyString);
			
		} else {
			return false;
		}
	}

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are alphabetic values
	public function checkAlpha($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkAlpha($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are alphanumeric values
	public function checkAlphaNum($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkAlphaNum($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are decimal digits
	public function checkDigit($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkDigit($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}
	

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) have mail format
	public function checkMail($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkMail($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}


	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are an ISO date.
	public function checkIsoDate($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkIsoDate($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) is a number (integer or number). It makes use of the is_numeric PHP built-in function
	public function checkNumeric($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkNumeric($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) is an integer string.
	public function checkInteger($associativeArray,$keyString,$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkInteger($associativeArray,$keyString,'strong');
			
		} else {
			return false;
		}
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) have a number of chars smaller than $maxLenght
	public function checkLength($associativeArray,$keyString,$maxLength = 10)
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkLength($associativeArray,$keyString,$maxLength);
			
		} else {
			return false;
		}
	}
	
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are different from the values indicated in the argument $strings (a comma-separated list of words)
	public function checkIsNotStrings($associativeArray,$keyString,$strings = '')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkIsNotStrings($associativeArray,$keyString,$strings);
			
		} else {
			return false;
		}
	}
	
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are one of the values indicated in the argument $strings (a comma-separated list of words)
	public function checkIsStrings($associativeArray,$keyString,$strings = '',$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkIsStrings($associativeArray,$keyString,$strings,'strong');
			
		} else {
			return false;
		}
	}

	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) match the regular expression $regExp
	public function checkMatch($associativeArray,$keyString,$regExp = '/./',$strength = 'strong')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkMatch($associativeArray,$keyString,$regExp,'strong');
			
		} else {
			return false;
		}
	}
	
	//verify that the values of the associative array ($associativeArray) indicated by the key string ($keyString) are decimal with the format indicated in $format
	//$format: M,D M is the maximum number of digits, D is the number of digits to the right of the decimal point
	public function checkDecimal($associativeArray,$keyString,$format = '10,2')
	{
		if ($this->checkNotEmpty($associativeArray,$keyString))
		{
			
			return parent::checkDecimal($associativeArray,$keyString,$format);
			
		} else {
			return false;
		}
	}
}
