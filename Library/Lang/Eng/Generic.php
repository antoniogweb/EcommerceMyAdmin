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

//generic strings
class Lang_Eng_Generic
{

	//English to English
	public $translations = array(
		'edit'		=>	'edit',
		'delete'	=>	'delete',
		'move up'	=>	'move up',
		'move down'	=>	'move down',
		'associate'	=>	'associate',
		'up'		=>	'su',
		'down'		=>	'down',
		'link'		=>	'link',
		'del'		=>	'del',
		'back'		=>	'back',
		'Back'		=>	'Back',
		'add a new record'	=>	'add a new record',
		'Add'		=>	'Add',
		'back to the Panel'	=>	'back to the Panel',
		'Panel'		=>	'Panel',
		'previous'	=>	'previous',
		'next'		=>	'next',
		'All'		=>	'All',
		'pages'		=>	'pages',
		'filter'	=>	'filter',
		'clear the filter'	=>	'clear the filter',
		'Save'		=>	'Save',
		'Actions'	=>	'Actions',
		'-- Select bulk action --' => '-- Select bulk action --',
	);

	public function gtext($string)
	{
		if (array_key_exists($string,$this->translations))
		{
			return $this->translations[$string];
		}

		return $string;
	}

}
