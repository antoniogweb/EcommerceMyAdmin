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

class Output
{
	public static $html = true;
	public static $json = false;
	
	public static $header = array(
		"Status"	=>	"not-logged",
		"UserId"	=>	"",
	);
	
	public static $body = array();
	public static $contents = array();
	
	public static function setJson()
	{
		self::$html = false;
		self::$json = true;
	}
	
	public static function setHeaderValue($key, $value)
	{
		self::$header[$key] = $value;
	}
	
	public static function setBodyValue($key, $value)
	{
		self::$body[$key] = $value;
	}
	
	public static function setContents()
	{
		self::$contents = array(
			"Header"	=>	self::$header,
			"Body"		=>	self::$body,
		);
	}
	
	public static function printOutput()
	{
		header('Content-Type: application/json');
		
		self::setContents();
		
		if (self::$json)
			return json_encode(self::$contents);
		
		return "";
	}
}
