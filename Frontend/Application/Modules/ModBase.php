<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

//module to print an HTML link
//extends the ModAbstract class inside the Library folder
class ModBase extends ModAbstract {
	
	public function render()
	{
		return null;
	}
	
	public function getHtmlClass()
	{
		if (isset($this->simpleXmlObj->classname))
		{
			return " class='".$this->simpleXmlObj->classname[0]."' ";
		}
		return null;
	}
	
	//wrap the html with a <div>
	//look for the <div> tag in the xml in order to set the class of the div
	public function wrapDiv($string)
	{
		$divOpen = "<div class='box_module'>";
		$divClose = "</div>";
		
		if (isset($this->simpleXmlObj->div))
		{
			$divOpen = "<div class='".$this->simpleXmlObj->div."'>";
		}
		
		return $divOpen . $string . $divClose;
	}
	
}
