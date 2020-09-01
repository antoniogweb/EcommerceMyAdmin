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

//create the HTML of the whole form
class Form_Form {

	//default attributes of the entries
	public static $defaultEntryAttributes = array(
		"entryClass"	=>	null,
		"className" 	=>	null,
		"idName"		=>	null,
		"submitClass"	=>	"btn btn-primary",
		"formWrap"		=>	null,
	);
	
	public $entry = array(); //associative array containing the entries of the form (objects that inherit the class form_entryModel). Each element of the array corresponds to one field of the table

	public $action = null; //the action of the form
	public $name = null; //the name of the form
	public $className = 'formClass'; //the class of the form
	public $id = null;
	public $submit = array(); //the submit entries array('name'=>'value')
	public $method = 'POST'; //the transmission method: POST/GET
	public $enctype = null; //enctype attribute of the form
	public $report = false; //if the form has to be shown as report
	
	public function __construct($action,$submit = array('generalAction'=>'save'),$method = 'POST',$enctype = null)
	{
		$this->action = $action; //action of the form: controller/action
		$this->submit = $submit;
		$this->method = $method;
		$this->enctype = $enctype;
	}

	//method to manage the $this->entry associative array
	//entryType: the type of the object to be initialized, $entryName: the name of the entry
	//$options: the list of options (if the entry is a <select> entry)
	public function setEntry($entryName,$entryType,$options = null)
	{
		$entryObjName = 'Form_'.$entryType;
		if (!class_exists($entryObjName))
		{
			throw new Exception("class <b>$entryObjName</b> not defined: the entry <b>$entryName</b> can't be set");
		}

		$this->entry[$entryName] = new $entryObjName($entryName);
		$this->entry[$entryName]->labelString = getFieldLabel($entryName);
		//set the type
		$this->entry[$entryName]->type = $entryType;
		if (isset($options))
		{
			$this->entry[$entryName]->options = $options;
		}
	}

	//set all the entries
	//$entryStruct : the struct of the entries
	public function setEntries($entryStruct = array())
	{
		foreach ($entryStruct as $name => $entry)
		{
			$type = array_key_exists('type',$entry) ? $entry['type'] : 'InputText';
			$options = array_key_exists('options',$entry) ? $entry['options'] : null;
			$this->setEntry($name,$type,$options);
		
			$entryClass = array_key_exists('entryClass',$entry) ? $entry['entryClass'] : self::$defaultEntryAttributes['entryClass'];
			$labelString = array_key_exists('labelString',$entry) ? $entry['labelString'] : getFieldLabel($name);
			$idName = array_key_exists('idName',$entry) ? $entry['idName'] : self::$defaultEntryAttributes['idName'];
			$className = array_key_exists('className',$entry) ? $entry['className'] : self::$defaultEntryAttributes['className'];
			$labelClass = array_key_exists('labelClass',$entry) ? $entry['labelClass'] : null;
			$defaultValue = array_key_exists('defaultValue',$entry) ? $entry['defaultValue'] : null;
			$wrap = array_key_exists('wrap',$entry) ? $entry['wrap'] : array();
			$deleteButton = array_key_exists('deleteButton',$entry) ? $entry['deleteButton'] : null;
			$reverse = array_key_exists('reverse',$entry) ? $entry['reverse'] : null;
			$attributes = array_key_exists('attributes',$entry) ? $entry['attributes'] : null;
			
			if (array_key_exists('fill',$entry))
			{
				$fill = $entry['fill'];
			}
			else
			{
				if ($type === "Password")
				{
					$fill = false;
				}
				else
				{
					$fill = true;
				}
			}
			
			$this->entry[$name]->entryClass = $entryClass;
			$this->entry[$name]->labelString = $labelString;
			$this->entry[$name]->idName = $idName;
			$this->entry[$name]->className = $className;
			$this->entry[$name]->labelClass = $labelClass;
			$this->entry[$name]->defaultValue = $defaultValue;
			$this->entry[$name]->wrap = $wrap;
			$this->entry[$name]->deleteButton = $deleteButton;
			$this->entry[$name]->reverse = $reverse;
			$this->entry[$name]->attributes = $attributes;
			$this->entry[$name]->fill = $fill;
		}
	}

	//set all the fields as report fields
	public function setReport($skipIfEmpty = false)
	{
		$this->report = true;
		
		foreach ($this->entry as $field => $obj)
		{
			$obj->report = true;
			
			if ($skipIfEmpty) $obj->skipIfEmpty = true;
		}
	}
	
	//function to create the HTML of the form
	//$values: an associative array ('entryName'=>'value')
	//$subset: subset to print (comma seprated list of string or array)
	public function render($values = null, $subset = null)
	{
		
		if ($values === null)
		{
			$values = array();
			foreach ($this->entry as $key => $value)
			{
				$values[$key] = $value->defaultValue;
			}
		}
		
		$fid = isset($this->id) ? "id='".$this->id."'" : null;
		$fname = isset($this->name) ? "name='".$this->name."'" : null;
		$fclass = isset($this->className) ? "class='".$this->className."'" : null;
		$fenctype = isset($this->enctype) ? " enctype='".$this->enctype."' " : null;
		$htmlForm = "<form $fname $fclass $fid action='".Url::getRoot($this->action)."' method='".$this->method."' $fenctype>\n";

		if (!isset($subset))
		{
			$subset = array_keys($values);
		}
		else
		{
			$subset = !is_array($subset) ? explode(',',$subset) : $subset;
		}
// 		$subset = (isset($subset)) ? explode(',',$subset) : array_keys($values);
		
		//first cicle: write the HTML of tabs if there are any
		$tabsHtml = null;
		$fCount = 0;
		foreach ($subset as $key => $entry)
		{
			if (is_array($entry))
			{
				$currClass = $fCount === 0 ? "current_tab" : null;
				$cleanKey = encode($key);
				$tabsHtml .= "\t<li class='form_tab_li $currClass'><a rel='tab_$cleanKey' class='form_tab_a form_tab_a_$cleanKey' href='#'>$key</a></li>\n";
				$fCount++;
			}
		}
		if (isset($tabsHtml))
		{
			$htmlForm .= "<ul class='form_tab_ul'>\n$tabsHtml\n</ul>\n";
		}
		
		$fCount = 0;
		foreach ($subset as $k => $entry)
		{
			
			$cleanK = encode($k);
			if (!is_array($entry))
			{
				if (array_key_exists($entry,$this->entry))
				{
					$value = array_key_exists($entry,$values) ? $values[$entry] : $this->entry[$entry]->defaultValue;
					$htmlForm .= $this->entry[$entry]->render($value);
				}
			}
			else
			{
				$tHtml = null;
				$displClass = $fCount === 0 ? null : "display_none";
				foreach ($entry as $e)
				{
					if (array_key_exists($e,$this->entry))
					{
						$value = array_key_exists($e,$values) ? $values[$e] : $this->entry[$e]->defaultValue;
						$tHtml .= $this->entry[$e]->render($value);
					}
				}
				$htmlForm .= "<div id='tab_$cleanK' class='tab_description_item $displClass'>$tHtml</div>";
				$fCount++;
			}
		}
		
		if (!$this->report)
		{
			$htmlForm .= "<div class='submit_entry'>";
			foreach ($this->submit as $name => $value)
			{
				if (!is_array($value))
				{
					$submitClass= "";
					if (!is_array(self::$defaultEntryAttributes['submitClass']))
					{
						$submitClass = self::$defaultEntryAttributes['submitClass'];
					}
					else
					{
						if (array_key_exists($value,self::$defaultEntryAttributes['submitClass']))
						{
							$submitClass = self::$defaultEntryAttributes['submitClass'][$value];
						}
					}
					$htmlForm .= "<span class='submit_entry_$value'>".Html_Form::submit($name, $value, $submitClass, $name)."</span>";
				}
				else
				{
					array_unshift($value,$name);
					$htmlForm .= call_user_func_array(array("Html_Form","submit"),$value);
				}
			}
			$htmlForm .= "</div>";
		}
		$htmlForm .= "</form>\n";
		
		if (isset(self::$defaultEntryAttributes["formWrap"]) and is_array(self::$defaultEntryAttributes["formWrap"]) and count(self::$defaultEntryAttributes["formWrap"]) === 2)
		{
			return self::$defaultEntryAttributes["formWrap"][0] . $htmlForm . self::$defaultEntryAttributes["formWrap"][1];
		}
		return $htmlForm;
	}

}
