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

class Lang_It_Formats_To_Mysql
{

	//convert the string from It decimal format to MySQL decimal format
	public function decimal($string)
	{
		$string = nullToBlank($string);
		
		return str_replace(",",".",$string);
	}
	
	//convert the string from It float format to MySQL float format
	public function float($string)
	{
		return str_replace(",",".",$string);
	}
	
	//convert the string from It double format to MySQL double format
	public function double($string)
	{
		return str_replace(",",".",$string);
	}
	
	//convert the string from It date format to MySQL date format
	public function date($date)
	{
		$date = nullToBlank($date);
		
		if (preg_match('/^[0-9]{2}\-[0-9]{2}\-[0-9]{4}$/',$date))
		{
			return getIsoDate($date);
			// $dateArray = explode('-',$date);
			// return $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
		}
		return $date;
	}
	
	//convert the string from It enum format  to MySQL enum format
	public function enum($string)
	{
		return $string;
	}
	
}
