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

//class to create the popup menu
class Helper_Popup extends Helper_Html {

	//the HTML of the popup
	public static $popupHtml = array(
		"before_loop" => "",
		"top" => "<div class='row col-md-2 ext_menu_block ext_menu_block_[[field]]'><ul class='menuBlock'><li class='innerItem inner_item_[[field]]'>[[name]]<ul class='innerList'>\n",
		"middle"	=>	"</ul>\n</li>\n</ul>\n",
		"bottom"	=>	"</div>",
		"after_loop" => "",
	);
	
	public $popupArray = array();  //array of popup objects (see popup.php)
	public $url = null; //the url (controller/action) to link by means of the popup menù
// 	public $fieldArg = null; //the key of the viewArgs array to set to the field in the where clause
// 	public $valueArg = null; //the key of the viewArgs array to be set to the value in the where clause 
	public $pageArg = null; //the key of the viewArgs array representing the page number. $this->viewArgs[$this->pageArg] is set to 1 if $this->pageArg !== null
	
	//the type of the popup. If type !=exclusive, than each voice selected is added to the where clause. If type=exclusive, the selection of a popup voice causes the unselection of the other voices
	public $type = null;

	public $allString = null;
	
	//list of popup names
	public $popupItems = array();

	//if it has to print the filter legend
	public $printLegend = false;

	//popup legend
	public $legend = array();

	public function __construct()
	{
		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);
		
		$this->allString = $this->strings->gtext('All');
	}
	
	public function build($url, $popupArray = null, $type = 'exclusive', $pageArg = null, $printLegend = false, $model = null) {
		$this->url = $url;
		$this->popupArray = $popupArray;
		$this->pageArg = $pageArg;
		$this->type = $type;
		$this->printLegend = $printLegend;
		$this->model = $model;
		
		foreach ($this->popupArray as $field => $popup)
		{
			$this->popupItems[] = $field;
		}
	}

	//check that the ViewArgs array is complete
	public function checkViewArgs()
	{
		foreach ($this->popupArray as $field => $popup)
		{
			if (!array_key_exists($field,$this->viewArgs)) return false;
		}
		return true;
	}

	//unselect the voices different from the current one
	public function unselect($currentVoice)
	{
		foreach ($this->popupItems as $item)
		{
			if (strcmp($item,$currentVoice) !== 0) $this->viewArgs[$item] = Params::$nullQueryValue;
		}
	}

	public function replacePlaceholders($string, $field, $name)
	{
		$string = str_replace("[[field]]",$field,$string);
		$string = str_replace("[[name]]",$name,$string);
		
		return $string;
	}
	
	//create the HTML of the popup
	public function render() {
		$returnString = self::$popupHtml["before_loop"];
		if ($this->checkViewArgs())
		{
			if (isset($this->viewArgs[$this->pageArg]))
			{
				$this->viewArgs[$this->pageArg] = 1;
			}
			foreach ($this->popupArray as $field => $popup)
			{
				//default legend
				$this->legend[$field] = Params::$nullQueryValue;
				
				if ($this->type === 'exclusive') $this->unselect($field);
				//save the value of the current ViewArg
				$tempArg = $this->viewArgs[$field];
				$this->legend[$field] = $tempArg;
				
				$returnString .= $this->replacePlaceholders(self::$popupHtml["top"],$field,$popup->name);
				
				for ($i = 0; $i < count($popup->itemsValue); $i++)
				{
					$this->viewArgs[$field] = $popup->itemsValue[$i];
					$viewStatus = Url::createUrl($this->viewArgs);
					$returnString .=  "<li><a href='".Url::getRoot($this->url).$viewStatus."'>".$popup->itemsName[$i]."</a></li>\n";

					//set the legend
					if (strcmp($popup->itemsValue[$i],$this->legend[$field]) === 0)
					{
						$this->legend[$field] = $popup->itemsName[$i];
					}
				}
				$this->viewArgs[$field] = Params::$nullQueryValue;
				$viewStatus = Url::createUrl($this->viewArgs);
				$returnString .=  "<li><a href='".Url::getRoot($this->url).$viewStatus."'>".$this->allString."</a></li>\n";
				$returnString .= self::$popupHtml["middle"];
				$this->viewArgs[$field] = $tempArg;
				
				if ($this->printLegend)
				{
					$returnString .= "<div class='popup_legend_item popup_legend_item_$field'>".$this->legend[$field]."</div>";
				}
				
				$returnString .= self::$popupHtml["bottom"];
			}
		}
		$returnString .= self::$popupHtml["after_loop"];
		return $returnString;
	}

}
