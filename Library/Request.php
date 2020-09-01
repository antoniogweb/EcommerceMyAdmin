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

//manage the associative arrays inside the request ($_GET,$_POST,$_COOKIE)
class Request
{

	public function get($name, $default = null, $func = 'none')
	{
		if (!function_exists($func))
		{
			throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$func. '</b> does not exists');
		}
		return isset($_GET[$name]) ? call_user_func($func,$_GET[$name]) : $default;
	}

	public function post($name, $default = null, $func = 'none')
	{
		if (!function_exists($func))
		{
			throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$func. '</b> does not exists');
		}
		return isset($_POST[$name]) ? call_user_func($func,$_POST[$name]) : $default;
	}

	public function getRequest($name, $default = null, $func = 'none')
	{
		if (!function_exists($func))
		{
			throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$func. '</b> does not exists');
		}
		return isset($_REQUEST[$name]) ? call_user_func($func,$_REQUEST[$name]) : $default;
	}
	
	public function cookie($name, $default = null, $func = 'none')
	{
		if (!function_exists($func))
		{
			throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$func. '</b> does not exists');
		}
		return isset($_COOKIE[$name]) ? call_user_func($func,$_COOKIE[$name]) : $default;
	}
	
}
