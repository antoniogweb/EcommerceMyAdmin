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

//class to create the HTML of a list of records
class Helper_List extends Helper_Html {

	//table attributes
	static public $tableAttributes = array('class'=>'listTable','cellspacing'=>'0');
	
	private $__rowArray = array(); //the current associative array representing the database record

	protected $_itemsList = array(); //2-dimensional associative array containing the list items
	//keys: type,table:field,controller/action,value
	protected $_head = array(); //2-dimensional array containing the head of the table
	protected $_identifierName;

	protected $_recordNumber = null; //number of records from the table

	protected $_allowedItems = array('simpleLink','simpleText','rawText','delForm','editForm','associateForm','moveupForm','movedownForm','Form','ledit','link','text','checkbox','input','ldel','lmoveup','lmovedown'); //type of items allowed

	//it can be: both, top, bottom, none
	protected $_boundaries = 'none';

	//array of filters
	protected $_filters = array();
	
	//array of bulk actions
	protected $_bulkActions = array();
	
	//set to false if you don't want that the filters are created inside the table
	public $showFilters = true;
	
	//set to false if you don't want that the filters are created inside the tableAttributes
	//if present, it sets $showFilters in __construct
	public static $staticShowFilters = null;
	
	//if the filter forms have to be aggregated in a unique form or if they have to be left separated
	public $aggregateFilters = false;
	
	//if the filter forms have to be aggregated in a unique form or if they have to be left separated
	//if present, it sets $aggregateFilters in __construct
	public static $staticAggregateFilters = null;
	
	//set if the submit buttons have to be images or not (it can be yse or not)
	public $submitImageType = 'yes';

	//set the files of the images
	public $submitImages = array();

	//set the titles of the input:submit
	public $submitTitles = array();

	//properties of columns
	public $colProperties = array();

	//$position: array. First element: page number, second element: number of pages
	public $position = array();

	//instance of Lang_{language}_Generic
	public $strings = null;

	//the url (controller/action) of the current page
	public $url = null;

	public $pageArg = null; //the key of the viewArgs array representing the page number. $this->viewArgs[$this->pageArg] is set to 1 if $this->pageArg !== null
	
	//set $renderToCsv if you want that the render() method create a CSV format in place of the HTML code
	public $renderToCsv = false;
	
	//allowed types of columns if $renderToCsv is set to true
	public $allowedCsvColumnsTypes = array("text","simpleText","link","simpleLink");
	
	public $csvColumnsSeparator = "|";
	
	//function to be applied on the cell before printing (for CSV)
	public $functionUponCsvCellValue = null;
	
	public static $submitEditText = array();
	
	//layout of the action buttons
	public static $actionsLayout = array();
	
	//layout of the filters form
	public static $filtersFormLayout = array();
	
	//if the resulting table has to be wrapped with <table></table>
	public $wrapTable = true;
	
	//if the resulting table has to be wrapped with <tbody></tbody>
	public $wrapTBody = true;
	
	//array of row attributes
	public $rowAttributes = null;
	
	public function __construct() {
		
		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);

		$baseUrl = Url::getFileRoot().'Public/Img/Icons/elementary_2_5/';

		//set the files of the images
		$this->submitImages = array(
			'up'	=>	$baseUrl.'up.png',
			'down'	=>	$baseUrl.'down.png',
			'edit'	=>	$baseUrl.'edit.png',
			'del'	=>	$baseUrl.'delete.png',
			'link'	=>	$baseUrl.'link.png',
		);
	
		$this->submitTitles = array(
			'edit'		=>	$this->strings->gtext('edit'),
			'del'		=>	$this->strings->gtext('delete'),
			'up'		=>	$this->strings->gtext('move up'),
			'down'		=>	$this->strings->gtext('move down'),
			'link'		=>	$this->strings->gtext('associate')
		);

		if (isset(self::$staticShowFilters))
		{
			$this->showFilters = self::$staticShowFilters;
		}
		
		if (isset(self::$staticAggregateFilters))
		{
			$this->aggregateFilters = self::$staticAggregateFilters;
		}
	}
	
	public function build($identifierName = 'identifier', $url = null, $pageArg = null, $model = null)
	{
		$this->_identifierName = $identifierName;
		$this->url = $url;
		$this->pageArg = $pageArg;
		$this->model = $model;
	}

	public function setIdentifierName($identifierName)
	{
		$this->_identifierName = $identifierName;
	}

	//add a list Item. $type: the type of the item, $field: the table.field to exctract (use colon to separate the table and the field),$action: controller/action,$value=if type == link->the value of the link
	public function addItem($type, $action = '', $field = '', $name = '', $value = '', $title = '') {
		if (!in_array($type,$this->_allowedItems)) {
			throw new Exception('"'.$type. '" argument not allowed in '.__METHOD__.' method');
		}
		
		$temp=array();
		$temp['type'] = $type;
		$temp['action'] = $action;
		$temp['field'] = $field;
		$temp['name'] = $name;
		$temp['value'] = $value;
		$temp['title'] = $title;
		$this->_itemsList[] = $temp;

		//set the $this->_head array
		$head = array();
		$head['type'] = $type;

		if ($type === 'simpleText') {
			$head['action'] = $this->extractFieldName($action);
		} else {
			$head['action'] = '&nbsp';
		}
		$this->_head[] = $head;
	}


	//set the head of the table
	//$columnsName: name of the columns. It has to be a comma-separated list of strings
	public function setHead($columnsName = '')
	{
		//get the array from the list
		$columnsArray = explode(',',$columnsName);
		for ($i = 0; $i < count($columnsArray); $i++)
		{
			if ($i < count($this->_itemsList)) $this->_head[$i]['action'] = $columnsArray[$i];
		}
	}

	//set the filters
	//$filters: array whose elements are the viewArgs to be used for the filters forms
	//or null
	public function setFilters($filters)
	{
		$this->_filters = $filters;
	}
	
	//set $this->aggregateFilters equal to true
	public function aggregateFilters()
	{
		$this->aggregateFilters = true;
	}
	
	//set the bulk actions
	//$bulkActions: associative array.
	//key: class of the inputs whose elements have to be selected and serialized by javascript in the following way: data-primary-key:value_attribute|data-primary-key:value_attribute|data-primary-key:value_attribute|...
	//value: array with two elements whose first element is the bulk action to be sent as a key of $_POST, second element is the human readable name of the action, third element can be the string "confirm" (if the user has to confirm the action) or undefined
	//example:
	// 	$bulkActions = array(
	// 		"input_category_id_order"	=>	array("sortAction","Sort elements"),
	// 		"checkbox_category_id"		=>	array("deleteAction","Delete elements","confirm"),
	// 	);
	public function setBulkActions($bulkActions)
	{
		$this->_bulkActions = $bulkActions;
	}

	//check that the ViewArgs array is complete
	public function checkViewArgs()
	{
		foreach ($this->_filters as $field)
		{
			$key = !is_array($field) ? $field : $field[0];
			if (!array_key_exists($key,$this->viewArgs) and strcmp($key,null) !== 0) return false;
		}
		return true;
	}
	
	//$method to extract the field name from the $action string (;table:field;). Used for the head
	public function extractFieldName($string) {
		$string = str_replace(';','',$string);
		return $string;
	}

	//replace the strings wrapped by ; with their correspondent value taken by the $recordArray associative array (a row of the select query)
	public function replaceFields($string,$rowArray) {
		$this->__rowArray = $rowArray; //used by the replaceField  method

		$hash = md5(microtime().uniqid(mt_rand(),true));
		
		$string = str_replace($this->viewStatus,$hash,$string);
		$string = preg_replace_callback('/(\;)([a-zA-Z0-9\_\.\|\:]{1,})(\;)/', array($this, 'replaceField') ,$string);
		$string = str_replace($hash,$this->viewStatus,$string);
		
		return $string;
	}

	//get : or . as char used to separate table and field
	public function getChar($string)
	{
		return strstr($string,':') ? ':' : '.';
	}

	//replace a single string wrapped by ; with its correspondent value taken by the $recordArray associative array (a row of the select query)
	public function replaceField($match)
	{
		$string = $match[2];
		
		//check if a function has been indicated
		if (strstr($string,'|'))
		{
			//get the function
			$firstArray = explode('|',$string);
			if (strstr($firstArray[1],':') or strstr($firstArray[1],'.'))
			{
				$func = $firstArray[0];
				//replace the fields
				$char = $this->getChar($firstArray[1]);
				$temp =  explode($char,$firstArray[1]);
				
				if (isset($this->__rowArray[$temp[0]]) and array_key_exists($temp[1], $this->__rowArray[$temp[0]]))
				{
					$string = $this->__rowArray[$temp[0]][$temp[1]];
					
					$string = callFunction($func,$string,__METHOD__);
				}
			}
		}
		else
		{
			if (strstr($string,':') or strstr($string,'.'))
			{
				$char = $this->getChar($string);
				$temp = explode($char,$string);
				
				if (isset($this->__rowArray[$temp[0]]) and array_key_exists($temp[1], $this->__rowArray[$temp[0]]))
				{
					$string = $this->__rowArray[$temp[0]][$temp[1]];
				}
			}
			else
			{
				if (isset($this->model) and method_exists($this->model, $string))
				{
					$string = call_user_func(array($this->model, $string), $this->__rowArray);
				}
			}
		}

		return $string;
	}

	//call the method replaceFields upon the $items array using the associative array $rowArray
	public function replaceAll($item,$rowArray) {
		$item['action'] = $this->replaceFields($item['action'],$rowArray);
		$item['field'] = $this->replaceFields($item['field'],$rowArray);
		$item['name'] = $this->replaceFields($item['name'],$rowArray);
		$item['value'] = $this->replaceFields($item['value'],$rowArray);
		$item['title'] = $this->replaceFields($item['title'],$rowArray);
		return $item;
	}

	//wrap the column with the tag td
	public function wrapColumn($string, $className = null, $tag = "td") {
		if (!$this->renderToCsv)
		{
			return wrap($string,array($tag=>$className));
		}
		else
		{
			if (isset($this->functionUponCsvCellValue))
			{
				$string = call_user_func($this->functionUponCsvCellValue, $string);
			}
			
			return '"'.trim(strip_tags(str_replace("&nbsp","",$string))).'"'.$this->csvColumnsSeparator;
		}
	}

	//wrap the row with the tag tr
	public function wrapRow($string,$className = null) {
		if (!$this->renderToCsv)
		{
			return wrap($string,array('tr'=>$className));
		}
		else
		{
			return $string;
		}
	}

	public function wrapList($string) {
		if (!$this->renderToCsv)
		{
			return $this->wrapTable ? wrap($string,array('table'=>self::$tableAttributes)) : $string;
		}
		else
		{
			return $string;
		}
	}

	//method to create the HTML of the head of the table
	public function createHead() {
		$htmlHead = null;
		
		$count = 0;
		foreach ($this->_head as $item) {
			$temp = $item['action'];
			
			if (preg_match('/\[\[bulkselect:(.*?)\]\]/',$temp,$matches))
			{
				$temp = Html_Form::checkbox("bulkselect_".$matches[1],"","BS","bulk_select_checkbox",null,"data-class='".$matches[1]."'");
			}
			
			//if renderToCsv is set to true skip what is not a simple text
			if ($this->renderToCsv and !in_array($item["type"],$this->allowedCsvColumnsTypes))
			{
				$count++;
				continue;
			}
			
			$prop = $item['type'];
			if (isset($this->colProperties[$count]))
			{
				$prop = $this->colProperties[$count];
			}
			
			$htmlHead .= $this->wrapColumn($temp, $prop, "th");
			
			$count++;
		}

		return $htmlHead;
	}

	//return an array with all the filters in a 1-dimensional array
	public function getFiltersList()
	{
		$filterList = array();
		
		foreach ($this->_filters as $f)
		{
			if (is_array($f))
			{
				$filterList[] = $f[0];
			}
			else
			{
				$filterList[] = $f;
			}
		}
		
		return $filterList;
	}
	
	//method to create the HTML of the filters input
	public function createFilters() {
		$htmlFilters = null;

		if (count($this->_filters) > 0)
		{
			if ($this->checkViewArgs())
			{
				$count = 0;
				
				$list = !$this->aggregateFilters ? $this->_head : $this->_filters;
				
				foreach ($list as $item) {

					if (!$this->aggregateFilters)
					{
						$prop = $item['type'];
						if (isset($this->colProperties[$count]))
						{
							$prop = $this->colProperties[$count];
						}
					}

					$html = '&nbsp';
					if (isset($this->_filters[$count]))
					{
						if (!is_array($this->_filters[$count]))
						{
							$html = $this->filterForm($this->_filters[$count]);
						}
						else
						{
							$html = call_user_func_array(array($this,"filterForm"),$this->_filters[$count]);
// 							$html = $this->filterForm($this->_filters[$count][0],$this->_filters[$count][1]);
						}
					}
					
					//wrap single cell if filters doesn't have to be aggregate
					if (!$this->aggregateFilters)
					{
						$htmlFilters .= !$this->showFilters ? $html : $this->wrapColumn($html,$prop);
					}
					else
					{
						$htmlFilters .= $html;
					}
					
					$count++;
				}
				
				//wrap an aggregate cell if filters have to be aggregate
				if ($this->aggregateFilters)
				{
					$colspan = count($this->_itemsList);
					
					$attributes = isset(self::$filtersFormLayout["form"]["attributes"]) ? arrayToAttributeString(self::$filtersFormLayout["form"]["attributes"]) : "class='list_filter_form'";
					
					$innerWrap = (isset(self::$filtersFormLayout["form"]["innerWrap"]) and is_array(self::$filtersFormLayout["form"]["innerWrap"]) and count(self::$filtersFormLayout["form"]["innerWrap"]) === 2) ? self::$filtersFormLayout["form"]["innerWrap"] : array("","");
					
					$formTop = "<form $attributes action='".Url::getFileRoot($this->url)."' method='GET'>\n".$innerWrap[0];
					
					$imgSrc = Url::getFileRoot('Public/Img/Icons/elementary_2_5/find.png');
					
					$formBottom = "";
					$emptyFilterStatusArray = array();
					$filtersList = $this->getFiltersList();
					foreach ($this->viewArgs as $k => $v)
					{
						if (!in_array($k,$filtersList))
						{
							$emptyFilterStatusArray[] = "$k=$v";
							$formBottom .= "<input type='hidden' name='".$k."' value='$v' />\n";
						}
					}
					$emptyFilterStatus = implode("&",$emptyFilterStatusArray);
					
					$wrap = (isset(self::$filtersFormLayout["submit"]["wrap"]) and is_array(self::$filtersFormLayout["submit"]["wrap"]) and count(self::$filtersFormLayout["submit"]["wrap"]) === 2) ? self::$filtersFormLayout["submit"]["wrap"] : array("","");
					
					$formBottom .= $wrap[0];
					
					if (isset(self::$filtersFormLayout["clear"]))
					{
						if (isset(self::$filtersFormLayout["clear"]["text"]))
						{
							$attributes = isset(self::$filtersFormLayout["clear"]["attributes"]) ? arrayToAttributeString(self::$filtersFormLayout["submit"]["attributes"]) : "";
							$formBottom .= "<a $attributes href='".Url::getFileRoot($this->url)."?".$emptyFilterStatus."'>".self::$filtersFormLayout["clear"]["text"]."</a>";
						}
					}
					else
					{
						$formBottom .= "<a class='list_filter_clear_link' title='".$this->strings->gtext('clear the filter')."' href='".Url::getFileRoot($this->url)."?".$emptyFilterStatus."'><img src='".Url::getFileRoot()."/Public/Img/Icons/elementary_2_5/clear_filter.png' /></a>";
					}
					
					if (isset(self::$filtersFormLayout["submit"]))
					{
						$attributes = isset(self::$filtersFormLayout["submit"]["attributes"]) ? arrayToAttributeString(self::$filtersFormLayout["submit"]["attributes"]) : "";
						$text = isset(self::$filtersFormLayout["submit"]["text"]) ? self::$filtersFormLayout["submit"]["text"] : "Filter";
						$formBottom .= "<button $attributes>".$text."</button>\n";
					}
					else
					{
						$formBottom .= "<input class='list_filter_submit' type='image' title='".$this->strings->gtext('filter')."' src='".$imgSrc."' value='trova'>\n";
					}
					
					$formBottom .= $wrap[1];
					
					$formBottom .= $innerWrap[1]."</form>";
					
					$htmlFilters = !$this->showFilters ? $formTop.$htmlFilters.$formBottom : $this->wrapColumn($formTop.$htmlFilters.$formBottom,array("class"=>"aggregate_filters_td","colspan"=>$colspan));
					
				}
			}
		}
		
		return $htmlFilters;
	}

	//create the HTML of the select of the bulk actions
	public function createBulkActionsSelect()
	{
		$htmlBulkSelect = null;
		$colspan = count($this->_itemsList);
		
		if (count($this->_bulkActions) > 0)
		{
			$htmlBulkSelect .= "<span class='bulk_actions_select_label'>".$this->strings->gtext('Actions')."</span>: <select data-url='".Url::getFileRoot(null).$this->url.$this->viewStatus."' class='bulk_actions_select' name='bulk_select'>";
			
			$htmlBulkSelect .= "<option data-class='0' value='0'>".$this->strings->gtext('-- Select bulk action --')."</option>";
			
			foreach ($this->_bulkActions as $class => $action)
			{
				$class = str_replace("+","",$class);
				$class = str_replace(" ","",$class);
				$confirm = isset($action[2]) ? "data-confirm='Y'" : "data-confirm='N'";
				$htmlBulkSelect .= "<option $confirm data-class='$class' value='".$action[0]."'>".$action[1]."</option>";
			}
			
			$htmlBulkSelect .= "</select>";
			$htmlBulkSelect = $this->wrapColumn($htmlBulkSelect,array("class"=>"bulk_actions_td","colspan"=>$colspan));
		}
		
		return $htmlBulkSelect;
	}
	
	//create the HTML of a single row (values taken from the associative array $rowArray)
	public function getRowList($rowArray) {
		$htmlList = null;

		$count = 0;
		foreach ($this->_itemsList as $item) {
			
			//if renderToCsv is set to true skip what is not a simple text
			if ($this->renderToCsv and !in_array($item["type"],$this->allowedCsvColumnsTypes))
			{
				$count++;
				continue;
			}
			
			$item = $this->replaceAll($item,$rowArray);
			
			$prop = $item['type']." scaffold_field_$count";
			if (isset($this->colProperties[$count]))
			{
				$prop = $this->colProperties[$count];
			}

			if (($this->_boundaries === 'top' and ($item['type'] === 'moveupForm' or $item['type'] === 'lmoveup')) or ($this->_boundaries === 'bottom' and ($item['type'] === 'movedownForm' or $item['type'] === 'lmovedown')) or ($this->_boundaries === 'both' and ($item['type'] === 'moveupForm' or $item['type'] === 'movedownForm' or $item['type'] === 'lmoveup' or $item['type'] === 'lmovedown')))
			{
				$htmlList .= $this->wrapColumn('&nbsp',$prop);
			}
			else
			{
				$temp = call_user_func_array(array($this,$item['type']),array($item));

				$htmlList .= $this->wrapColumn($temp,$prop);
			}
			$count++;
		}
		return $htmlList;
	}

	//$index: record number
	public function ifInBoundaries($index)
	{
		$this->_boundaries = 'none';
		
		if (!empty($this->position))
		{
			if ($this->_recordNumber === 1 and strcmp($this->position[0],1) === 0)
			{
				$this->_boundaries = 'both';
			}
			else if ($index === 0 and strcmp($this->position[0],1) === 0)
			{
				$this->_boundaries = 'top';
			}
			else if ($index === ($this->_recordNumber-1) and strcmp($this->position[0],$this->position[1]) === 0)
			{
				$this->_boundaries = 'bottom';
			}
		}

	}

	//create the HTML of the entire list. $queryResult: the array coming from the select query
	public function render($queryResult)
	{
		//set the number of records
		$this->_recordNumber = count($queryResult);
		$htmlList = null;
		
		//create the HTML of the head of the record list
		if (!$this->renderToCsv)
		{
			$htmlList .= "<thead>\n".$this->wrapRow($this->createHead(),'listHead')."</thead>\n";
			
			if ($this->wrapTBody)
			{
				//create the HTML of the filters
				$htmlList .= "<tbody>\n";
			}
		}
		else
		{
			$htmlList .= rtrim($this->wrapRow($this->createHead(),'listHead'),$this->csvColumnsSeparator)."\n";
		}
		
		
		if (!$this->renderToCsv)
		{
			$bulkActionsHtml = $this->createBulkActionsSelect();
			if (isset($bulkActionsHtml))
			{
				$htmlList .= $this->wrapRow($bulkActionsHtml,'bulk_actions_tr');
			}

			if ($this->showFilters)
			{
				$filtersHtml = $this->createFilters();
				if (isset($filtersHtml))
				{
					$htmlList .= $this->wrapRow($filtersHtml,'listFilters');
				}
			}
		}
		
		for ($i = 0; $i < count($queryResult); $i++)
		{
			$this->ifInBoundaries($i);
			$temp = $this->getRowList($queryResult[$i]);
			
			if (!$this->renderToCsv)
			{
				if ($this->rowAttributes)
				{
					$tempAttr = array();
					
					foreach ($this->rowAttributes as $k => $v)
					{
						$tempAttr[$k] = $this->replaceFields($v, $queryResult[$i]);
					}
					
					$htmlList .= $this->wrapRow($temp,$tempAttr);
				}
				else
					$htmlList .= $this->wrapRow($temp,'listRow');
			}
			else
			{
				$htmlList .= rtrim($temp,$this->csvColumnsSeparator)."\n";
			}
		}
		
		if (!$this->renderToCsv)
		{
			if ($this->wrapTBody)
			{
				return $this->wrapList($htmlList."</tbody>\n");
			}
			else
			{
				return $this->wrapList($htmlList."\n");
			}
		}
		else
		{
			return $htmlList;
		}
	}

	public function generalForm($itemArray, $submitName, $submitValue)
	{
		$string = "<form class='listItemForm' action='".Url::getFileRoot(null).$itemArray['action'].$this->viewStatus."' method='POST'>\n";
		$name = (strcmp($itemArray['name'],'') !== 0) ? $itemArray['name'] : $submitName;
		$value = (strcmp($itemArray['value'],'') !== 0) ? $itemArray['value'] : $submitValue;

		$oldValue = $value;
		$value = $this->strings->gtext($value);
		
		if (strcmp($itemArray['title'],'') !== 0)
		{
			$title = "title='".$itemArray['title']."'";
		}
		else
		{
			$title = isset($this->submitTitles[$oldValue]) ? "title='".$this->submitTitles[$oldValue]."'" : null;
		}
		
		if (strcmp($this->submitImageType,'yes') === 0 and isset($this->submitImages[$oldValue]))
		{
			$imgSrc = $this->submitImages[$oldValue];
			
			$string .= "<input type='image' $title src='".$imgSrc."' value='$value'>\n";
			$string .= "<input type='hidden' name='".$name."' value='$value'>\n";
		}
		else
		{
			$string .= "<input type='submit' $title name='".$name."' value='$value'>\n";
		}
		
		$string .= "<input type='hidden' name='".$this->_identifierName."' value='".$itemArray['field']."'>\n";
		$string .= "</form>\n";
		return $string;
	}

	public function Form($itemArray)
	{
		return $this->generalForm($itemArray, 'name_missing', 'value_missing');
	}
	
	public function moveupForm($itemArray)
	{
		return $this->generalForm($itemArray, 'moveupAction', 'up');
	}

	public function movedownForm($itemArray)
	{
		return $this->generalForm($itemArray, 'movedownAction', 'down');
	}

	public function editForm($itemArray)
	{
		return $this->generalForm($itemArray, 'generalAction', 'edit');
	}

	public function delForm($itemArray)
	{
		return $this->generalForm($itemArray, 'delAction', 'del');
	}

	public function associateForm($itemArray)
	{
		return $this->generalForm($itemArray, 'generalAction', 'link');
	}

	public function rawText($itemArray) {
		$text = strcmp($itemArray['action'],'') !== 0 ? $itemArray['action'] : '&nbsp';
		$string = "<span class='textItem'>".$text."</span>\n";
		return $string;
	}
	
	public function simpleText($itemArray) {
		$emptyChar = isset(Params::$defaultSanitizeHtmlFunction) ? "" : '&nbsp';
		
		$text = strcmp($itemArray['action'],'') !== 0 ? $itemArray['action'] : $emptyChar;
		
		if (isset(Params::$defaultSanitizeHtmlFunction))
			$text = call_user_func(Params::$defaultSanitizeHtmlFunction, $text);
		
		$string = "<span class='textItem'>".$text."</span>\n";
		return $string;
	}
	
	public function text($itemArray)
	{
		return $this->simpleText($itemArray);
	}
	
	public function simpleLink($itemArray) {
		$string = "<a title='".$itemArray['field']."' class='linkItem' href='".Url::getFileRoot(null).$itemArray['action'].$this->viewStatus."'>".$itemArray['name']."</a>\n";
		return $string;
	}

	public function link($itemArray)
	{
		return $this->simpleLink($itemArray);
	}

	public function ledit($itemArray)
	{
		if (isset(self::$submitEditText["edit"]))
		{
			$text = self::$submitEditText["edit"];
		}
		else if (isset($this->submitImages['edit']))
		{
			$text = "<img src='".$this->submitImages['edit']."'>";
		}
		else
		{
			$text = $itemArray['name'];
		}
		
		$lowerAction = strtolower($itemArray['field']);
		
		if (!isset(self::$actionsLayout[$lowerAction]))
		{
			$title = isset($this->submitTitles['edit']) ? $this->submitTitles['edit'] : $itemArray['field'];
			$string = "<a title='".$title."' class='linkItem' href='".Url::getFileRoot(null).$itemArray['action'].$this->viewStatus."'>$text</a>\n";
		}
		else
		{
			$attributes = isset(self::$actionsLayout[$lowerAction]["attributes"]) ?  arrayToAttributeString(self::$actionsLayout[$lowerAction]["attributes"]) : "";
			
			if (isset(self::$actionsLayout[$lowerAction]["text"])) $text = self::$actionsLayout[$lowerAction]["text"];
			
			$string = "<a $attributes href='".Url::getFileRoot(null).$itemArray['action'].$this->viewStatus."'>$text</a>\n";
		}
		
		return $string;
	}

	private function genericLinkAction($itemArray, $submitName, $submitValue)
	{
		$submitName = (strcmp($itemArray['name'],'') !== 0) ? $itemArray['name'] : $submitName;
		
		if (Params::$rewriteStatusVariables)
		{
			$viewStatus = $this->viewStatus . "?".$this->_identifierName."=".$itemArray['field']."&$submitName=Y";
		}
		else
		{
			$temp = $this->viewArgs;
			
			$temp[$this->_identifierName] = $itemArray['field'];
			$temp[$submitName] = "Y";
			
			$viewStatus = Url::createUrl($temp);
		}
		
		if (!isset(self::$actionsLayout[$submitValue]))
		{
			$text = isset($this->submitImages['del']) ? "<img src='".$this->submitImages[$submitValue]."'>" : $itemArray['value'];
			$title = isset($this->submitTitles['del']) ? $this->submitTitles[$submitValue] : $itemArray['value'];
			$string = "<a title='".$title."' class='linkItem' href='".Url::getFileRoot(null).$itemArray['action'].$viewStatus."'>$text</a>\n";
		}
		else
		{
			$attributes = isset(self::$actionsLayout[$submitValue]["attributes"]) ?  arrayToAttributeString(self::$actionsLayout[$submitValue]["attributes"]) : "";
			
			$text = isset(self::$actionsLayout[$submitValue]["text"]) ? self::$actionsLayout[$submitValue]["text"] : $submitValue;
			
			$string = "<a $attributes href='".Url::getFileRoot(null).$itemArray['action'].$viewStatus."'>$text</a>\n";
		}
		
		return $string;
	}
	
	//link to del a record
	public function ldel($itemArray)
	{
		return $this->genericLinkAction($itemArray, "delAction", "del");
	}
	
	//link to move up a record
	public function lmoveup($itemArray)
	{
		return $this->genericLinkAction($itemArray, "moveupAction", "up");
	}
	
	//link to move down a record
	public function lmovedown($itemArray)
	{
		return $this->genericLinkAction($itemArray, "movedownAction", "down");
	}
	
	//create the HTML of a checkbox
	public function checkbox($itemArray)
	{
		return Html_Form::checkbox($itemArray['action'],$itemArray['field'],$itemArray['name'],"checkbox_".encode($itemArray['action']),null,"data-primary-key='".$itemArray['value']."'");
	}
	
	//create the HTML of an input text
	public function input($itemArray)
	{
		return Html_Form::input($itemArray['action'],$itemArray['field'],"input_".encode($itemArray['action']),null,"data-primary-key='".$itemArray['name']."'");
	}
	
	//create the HTML of the filter
	public function filterForm($viewArgsName, $filterString = null, $filterValues = null)
	{
		$cleanName = str_replace('n!',null,$viewArgsName);
		$cleanName = str_replace('-',null,$cleanName);

		if (isset($this->viewArgs[$this->pageArg]))
		{
			$this->viewArgs[$this->pageArg] = 1;
		}
		
		$temp = $value = $this->viewArgs[$viewArgsName];
		//set the viewArg to the null query value
		if (Params::$nullQueryValue)
		{
			$this->viewArgs[$viewArgsName] = Params::$nullQueryValue;
			$viewStatus = Url::createUrl($this->viewArgs);
			if (strcmp($value,Params::$nullQueryValue) === 0 and !isset($filterValues))
			{
				$value = '';
			}
		}
		else
		{
			$viewStatus = $this->viewStatus;
		}
		$this->viewArgs[$viewArgsName] = $temp;
		
		$action = Url::getFileRoot($this->url).$viewStatus;
		$imgSrc = Url::getFileRoot('Public/Img/Icons/elementary_2_5/find.png');
		$title = $this->strings->gtext('filter');
		$clearLinkTitle = $this->strings->gtext('clear the filter');
		
		$html = null;
		
		if (!$this->aggregateFilters)
		{
			$html .= "<form class='list_filter_form list_filter_form_$cleanName' action='".$action."' method='GET'>\n";
		}
		
		$html .= isset($filterString) ? " <span class='list_filter_span list_filter_span_$cleanName'>".$filterString."</span> " : null;
		
		if (!isset(self::$filtersFormLayout["filters"][$viewArgsName]))
		{
			if (!isset($filterValues))
			{
				$html .= "<input id='list_filter_input_$cleanName' class='list_filter_input list_filter_input_$cleanName' type='text' name='$viewArgsName' value='".$value."'>";
			}
			else
			{
				$filterValues = array(Params::$nullQueryValue => $this->strings->gtext('All')) + $filterValues;
				$html .= Html_Form::select($viewArgsName,$value,$filterValues,"list_filter_input list_filter_input_$cleanName",null,"yes");
			}
		}
		else
		{
			$filterLayout = self::$filtersFormLayout["filters"][$viewArgsName];
			
			$attributes = isset($filterLayout["attributes"]) ? arrayToAttributeString($filterLayout["attributes"]) : "";
			
			$type = isset($filterLayout["type"]) ? $filterLayout["type"] : "input";
			
			$wrap = (isset(self::$filtersFormLayout["filters"][$viewArgsName]["wrap"]) and is_array(self::$filtersFormLayout["filters"][$viewArgsName]["wrap"]) and count(self::$filtersFormLayout["filters"][$viewArgsName]["wrap"]) === 2) ? self::$filtersFormLayout["filters"][$viewArgsName]["wrap"] : array("","");
			
			$html .= $wrap[0];
			
			switch ($type)
			{
				case "input":
					$html .= "<input id='list_filter_input_$cleanName' $attributes type='text' name='$viewArgsName' value='".$value."'>";
					break;
				case "select":
					if (isset($filterValues))
					{
						$html .= Html_Form::select($viewArgsName,$value,$filterValues,"",null,"yes",$attributes);
					}
					break;
			}
			
			$html .= $wrap[1];
		}
		
		if (!$this->aggregateFilters)
		{
			$html .= "<a class='list_filter_clear_link list_filter_clear_link_$cleanName' title='$clearLinkTitle' href='$action'><img src='".Url::getFileRoot()."/Public/Img/Icons/elementary_2_5/clear_filter.png' /></a>";
			$html .= "<input class='list_filter_submit list_filter_submit_$cleanName' type='image' title='$title' src='".$imgSrc."' value='trova'>\n";
		
			if (!Params::$rewriteStatusVariables)
			{
				foreach ($this->viewArgs as $k => $v)
				{
					if (strcmp($k,"$viewArgsName") !== 0)
					{
						$html .= "<input type='hidden' name='".$k."' value='$v' />";
					}
				}
			}
			$html .= "</form>\n";
		}

		return $html;
	}
	
}
