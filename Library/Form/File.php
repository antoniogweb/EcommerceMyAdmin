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

//create the HTML of a file upload entry
class Form_File extends Form_Entry
{

	public function __construct($entryName = null)
	{
		$this->entryName = $entryName;
	}

	public function render($value = null)
	{
		if ($this->report and $this->skipIfEmpty and strcmp($value,"") === 0) return "";
		
		$wrap = $this->getWrapElements($value);
		$returnString = $wrap[0];
		$returnString .= "<div class='".$this->getEntryClass()."'>\n\t";
		$returnString .= $wrap[1];
		$returnString .= $this->getLabelTag();
		$returnString .= $wrap[2];
		
		$entryValue = $this->fill ? $value : null;
		
		if ($this->report)
		{
			$returnString .= "<div class='report_field report_field_".$this->entryName."'>".$entryValue."</div>";
		}
		else
		{
			$returnString .= Html_Form::fileUpload($this->entryName, $entryValue, $this->className, $this->idName, $this->attributes);;
		}
		
		if (is_array($this->deleteButton))
		{
			$sname = isset($this->deleteButton[0]) ? $this->deleteButton[0] : 'delete_'.$this->entryName;
			$svalue = isset($this->deleteButton[1]) ? $this->deleteButton[1] : 'delete';
			$sclass = isset($this->deleteButton[2]) ? "class='".$this->deleteButton[2]."'" : null;
			$returnString .= "<input $sclass type='submit' name='$sname' value='$svalue'>\n";
		}
		$returnString .= $wrap[3];
		$returnString .="</div>\n";
		$returnString .= $wrap[4];
		return $returnString;
	}

}
