<?php

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

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