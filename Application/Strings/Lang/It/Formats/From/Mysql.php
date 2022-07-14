<?php

// MvcMyLibrary is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2014  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of MvcMyLibrary
//
// MvcMyLibrary is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// MvcMyLibrary is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with MvcMyLibrary.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Lang_It_Formats_From_Mysql
{

	//convert the string from MySQL decimal format to It decimal format 
	public function decimal($string)
	{
		return str_replace(".",",",$string);
	}
	
	//convert the string from MySQL float format to It float format
	public function float($string)
	{
		return str_replace(".",",",$string);
	}
	
	//convert the string from MySQL double format to It double format
	public function double($string)
	{
		return str_replace(".",",",$string);
	}
	
	//convert the string from MySQL date format to It date format
	public function date($date)
	{
		$date = nullToBlank($date);
		
		if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/',$date))
		{
			$dateArray = explode('-',$date);
			return $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
		}
		return $date;
	}
	
	//convert the string from MySQL enum format to En enum format 
	public function enum($string)
	{
		switch ($string)
		{
			case "Y":
				return "Sì";
			case "N":
				return "No";
		}
		return $string;
	}
	
	public function time($string)
	{
		$string = nullToBlank($string);
		
		if (preg_match('/^[0-9]{2}\:[0-9]{2}\:[0-9]{2}$/',$string))
		{
			$array = explode(":",$string);
			
			return $array[0].":".$array[1];
		}
		
		return $string;
	}
	
}
