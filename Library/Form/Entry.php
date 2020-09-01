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

//base class of the form entries
abstract class Form_Entry {
	
	public static $defaultWrap = array();
	public static $defaultEntryClass = null;
	public static $defaultLabelClass = 'entryLabel';
	
	public $entryName = null; //the name of the entry
	public $entryClass = null; //the class of the entry
	public $idName = null; //the id of the input entry
	public $className = null; //the class of the input entry
	public $labelString = null; //label of the form
	public $labelClass = null; //the class of the tag of the label
	public $options = array(); //options (if the entry is a <select> entry or a radio button). Associative array or comma-divided list.
	public $reverse = null; //reverse label with value in select entries
	public $attributes = null; //attributes of the field
	public $defaultValue = '';
	public $wrap = array();
	public $deleteButton = null;
	public $type = null; //the type of the entry
	public $fill = true; //true or false: fill or not the entry
	
	public $report = false; //print as simple text
	public $skipIfEmpty = false; //do not print if value equal to empty string
	
	//create the label of each entry of the form
	public function getLabelTag()
	{
		$labelTagClass = isset($this->labelClass) ? $this->labelClass : self::$defaultLabelClass;
		
		if ($this->report)
		{
			return isset($this->labelString) ? "<div class='$labelTagClass'><b>".$this->labelString.":</b></div>\n\t" : null;
		}
		else
		{
			return isset($this->labelString) ? "<label class='$labelTagClass'>".$this->labelString."</label>\n\t" : null;
		}
	}

	//get the class of the entry
	public function getEntryClass()
	{
		if (isset($this->entryClass))
		{
			$class = $this->entryClass;
		}
		else if (isset(self::$defaultEntryClass))
		{
			$class = self::$defaultEntryClass;
		}
		else
		{
			switch($this->type)
			{
				case 'InputText':
					$class = 'form_input_text';
					break;
				case 'Checkbox':
					$class = 'form_checkbox';
					break;
				case 'File':
					$class = 'form_input_file';
					break;
				case 'Textarea':
					$class = 'form_textarea';
					break;
				case 'Password':
					$class = 'form_input_text form_input_password';
					break;
				default:
					$class = 'form_input_text';
					break;
			}
		}
		
		if ($this->report) $class = "report_entry report_entry_".strtolower(get_class($this))." ".$class;
		
		return $class;
	}

	public function getWrapElements($value = null)
	{
		//replace the ;;value;; variable
		for ($i = 0; $i < count($this->wrap); $i++)
		{
			if ( preg_match('/;;(.*)\|value;;/',$this->wrap[$i],$m) )
			{
				if (!function_exists($m[1])) {
					throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$m[1].'</b> does not exists..');
				}
				//apply the function
				$v = call_user_func($m[1],$value);
				$this->wrap[$i] = str_replace(";;".$m[1]."|value;;",$v,$this->wrap[$i]);
			}
			else if ( preg_match('/;;value;;/',$this->wrap[$i]) )
			{
				$this->wrap[$i] = str_replace(';;value;;',$value,$this->wrap[$i]);
			}
		}
		
		if ($this->report)
		{
			return array(null,null,null,null,null);
		}
		
		$wrap[0] = isset(self::$defaultWrap[0]) ? self::$defaultWrap[0] : null;
		$wrap[1] = isset(self::$defaultWrap[1]) ? self::$defaultWrap[1] : null;
		$wrap[2] = isset(self::$defaultWrap[2]) ? self::$defaultWrap[2] : null;
		$wrap[3] = isset(self::$defaultWrap[3]) ? self::$defaultWrap[3] : null;
		$wrap[4] = isset(self::$defaultWrap[4]) ? self::$defaultWrap[4] : null;
		
		$wrap[0] = isset($this->wrap[0]) ? $this->wrap[0] : $wrap[0];
		$wrap[1] = isset($this->wrap[1]) ? $this->wrap[1] : $wrap[1];
		$wrap[2] = isset($this->wrap[2]) ? $this->wrap[2] : $wrap[2];
		$wrap[3] = isset($this->wrap[3]) ? $this->wrap[3] : $wrap[3];
		$wrap[4] = isset($this->wrap[4]) ? $this->wrap[4] : $wrap[4];
		
		return $wrap;
	}

	abstract public function render($value = null);

}
