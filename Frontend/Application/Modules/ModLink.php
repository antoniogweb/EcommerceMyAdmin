<?php

// All MvcMyLibrary code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

//module to print an HTML link
//extends the ModBase class
class ModLink extends ModBase {
	
	public function render()
	{
		$link = "<a ".$this->getHtmlClass()." href='".$this->simpleXmlObj->href[0]."'>".$this->simpleXmlObj->text[0]."</a>";
		return $this->wrapDiv($link)."\n";
	}
		
}