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

//class to manage the scaffold of the controller
class Scaffold
{

	protected $_type = null; //the type of the scaffold. It can be 'main' or 'form'
	protected $_queryType = null; //it can be insert or update

	protected $_primaryKey = null; //the primary key of the table
	protected $_controller = null; //the name of the controller
	
	public static $numbersOfPages = array(2,5);
	
	public $application = null; //the name of the application
	public $applicationUrl = null; //the url of the application
	public $controller = null; //the name of the controller
	public $action = null; //the action called
	public $model = null; //the reference to the model associated with the controller

	public $viewArgs = array(); //the associative array representing the status args of the main action of the controller.

	public $params = array(); //associative array containing the parameters of the scaffold
	public $html = array(); //associative array containing the HTML of the scaffold ('pageList'=>HTML,..)

	public $mainMenu = null; //the reference to the MenuHelper object
	public $pageList = null; //the reference to the PageDivisionHelper object
	public $itemList = null; //the reference to the ListHelper object
	public $popupMenu = null; //the reference to the PopupHelper object

	public $list = null; //alias of $itemList
	public $pages = null; //alias of $pageList
	public $menu = null; //alias of $mainMenu
	
	public $form = null; //the reference to the form object
	public $entries = null; //the entries of the form (string having entries separated by comma)
	public $values = array(); //the values inserted in the form (taken from the table if $this->queryType === 'update' or if an error occured during the databse query, otherwise taken from the $_POST array)
	
	//the list of fields of the select query
	public $fields = null;

	//instance of Lang_{language}_Generic
	public $strings = null;
	
	public static $autoParams = array(
		'mainAction'		=>	'main',
		'modifyAction'		=>	'form/update',
		'associateAction'	=>	'associate',
		'panelController'	=>	'panel',
		'pageList'			=>	true,
		'pageVariable'		=>	'page',
		'recordPerPage'		=>	40,
		'mainMenu'			=>	'panel,add',
		'formMenu'			=>	'panel,back',
		'popup'				=>	false,
		'popupType'			=>	'exclusive'
	);
	
	public function __construct($type,$application, $controller, $action, $model,$viewArgs,$params = null) {

		$this->_type = $type;
		$this->application = $application;
		$this->controller = $controller;
		$this->action = $action;
		$this->model = $model;
		$this->viewArgs = $viewArgs;

		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);
		
		$autoParams = self::$autoParams;
		
		if (!array_key_exists("postSubmitValue",$autoParams))
		{
			$autoParams['postSubmitValue'] = $this->strings->gtext('Save');
		}

		//set the $this->scaffold->params array
		if (is_array($params)) {
			foreach ($params as $key => $value) {
				$autoParams[$key] = $value;
			}
		}
		$this->params = $autoParams;

		$this->applicationUrl = isset($application) ? $application . "/" : null;
	}

	//ad some clauses to the select query
	//whereArray = array ($table_field => $value)
	public function appendWhereQueryClause($whereArray)
	{
		$this->model->appendWhereQueryClause($whereArray);
	}

	//set clauses to the select query
	//whereArray = array ($table_field => $value)
	public function setWhereQueryClause($whereArray)
	{
		$this->model->setWhereQueryClause($whereArray);
	}

	//alias of loadMain
	public function loadView($recordList, $theme = 'ledit,ldel')
	{
		$this->loadMain($recordList, null, $theme);
	}
	
	//initialize the main scaffold (ListHelper)
	//$recordList: field of the table to show, $primaryKey: the key of the table
	public function loadMain($recordList, $primaryKey = null, $theme = 'ledit,ldel')
	{
		if (isset($primaryKey))
		{
			$this->_primaryKey = $primaryKey;
		}
		else
		{
			$primaryKey = $this->model->getPrimaryKey();
			$table = $this->model->table();
			$this->_primaryKey = $primaryKey = $table.".".$primaryKey;
		}
		
		if (is_array($recordList) or strcmp($recordList,'') !== 0)
		{
			$recordListArray = is_array($recordList) ? $recordList : explode(',',$recordList);
			
			foreach ($recordListArray as $record) {
				if (preg_match('/\[\[checkbox\]\](\;)(.*?)(\;)/',$record,$matches))
				{
					$this->itemList->addItem("checkbox",encode($matches[2]),";".$matches[2].";","",";".$primaryKey.";");
				}
				else if (preg_match('/\[\[checkbox\:(.*?)\]\](\;)(.*?)(\;)/',$record,$matches))
				{
					$this->itemList->addItem("checkbox",encode($matches[3]),";".$matches[3].";",$matches[1],";".$primaryKey.";");
				}
				else if (preg_match('/\[\[input\]\](\;)(.*?)(\;)/',$record,$matches))
				{
					$this->itemList->addItem("input",encode($matches[2]),";".$matches[2].";",";".$primaryKey.";");
				}
				else if (preg_match('/\[\[ledit\]\](\;)(.*?)(\;)/',$record,$matches))
				{
					$this->itemList->addItem("link",$this->applicationUrl . $this->controller.'/'.$this->params['modifyAction']."/;$primaryKey;","",";".$matches[2].";");
				}
				else if (preg_match('/(RAW\:)(\;)(.*?)(\;)/',$record,$matches))
				{
					$this->itemList->addItem("rawText",";".$matches[3].";");
				}
				else if (strstr($record, ';'))
				{
					$this->itemList->addItem("simpleText","$record");
				}
				else
				{
					$this->itemList->addItem("simpleText",";$record;");
				}
			}
		}

		$themeArray = explode(',',$theme);

		if (strcmp($theme,'') !== 0)
		{
			foreach ($themeArray as $el)
			{
				if (preg_match('/ledit\|(.*)/',$el,$matches))
				{
					$this->itemList->addItem('ledit',$matches[1],'Edit','Edit');
				}
				else
				{
					switch ($el)
					{
						case 'moveup':
							$this->itemList->addItem('moveupForm',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
						case 'movedown':
							$this->itemList->addItem('movedownForm',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
						case 'link':
							$this->itemList->addItem('associateForm',$this->applicationUrl . $this->controller.'/'.$this->params['associateAction'],";".$primaryKey.";");
							break;
						case 'edit':
							$this->itemList->addItem('editForm',$this->applicationUrl . $this->controller.'/'.$this->params['modifyAction'],";".$primaryKey.";");
							break;
						case 'del':
							$this->itemList->addItem('delForm',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
						case 'ledit':
							$this->itemList->addItem('ledit',$this->applicationUrl . $this->controller.'/'.$this->params['modifyAction'].'/;'.$primaryKey.';','Edit','Edit');
							break;
						case 'ldel':
							$this->itemList->addItem('ldel',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
						case 'lmoveup':
							$this->itemList->addItem('lmoveup',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
						case 'lmovedown':
							$this->itemList->addItem('lmovedown',$this->applicationUrl . $this->controller.'/'.$this->params['mainAction'],";".$primaryKey.";");
							break;
					}
				}
			}
		}

	}

	//initialize the form
	//$queryType = insert/update
	//$action: the action of the form (controller/action/queryString)
	public function loadForm($queryType,$action = null, $method = 'POST', $enctype = null)
	{
		$this->queryType = $queryType;
		$submitName = $this->model->getSubmitName($queryType);
		$value = $this->params['postSubmitValue'];
		$viewStatus = Url::createUrl($this->viewArgs);
		
		if (!isset($action) and ($this->model->getId() !== null))
		{
			$application = isset($this->application) ? $this->application."/" : "";
			$action = $application.$this->controller."/".$this->action."/".$queryType."/".$this->model->getId();
		}
		
		$this->model->setForm($action.$viewStatus,array($submitName => $value),$method,$enctype);
		$this->form = $this->model->form;
	}

	//function to obtain the values to use in the form
	//$func = function to validate the values
	//$id = the id of the record (used if $_POST[$this->m[$this->model]->identifierName] is not present)
	public function getFormValues($func = 'sanitizeHtml',$id = null,$defaultValues = array(),$functionsIfFromDb = array())
	{
		if ($this->_type === 'form')
		{
			$this->values = $this->model->getFormValues($this->queryType,$func,$id,$defaultValues,$functionsIfFromDb);
		}
	}

	//set the head of the table
	//$columnsName: name of the columns. It has to be a comma-separated list of strings
	public function setHead($columnsName)
	{
		$this->itemList->setHead($columnsName);
	}

	//method to set the type of the entries of the form
	//$entries: string containing the list of the entries where each entry is separated by comma: entry1,entry2,entry3
	//$entryType: associative array that describes the entries of the form. The key is the entry name while the value is the entry type (textarea,inputText,etc)
	public function setFormEntries($entries = 'model',$entryType = array(),$optionsArray = array())
	{
		if ($this->_type === 'form')
		{
			if ($entries === 'model')
			{
				$this->entries = $this->model->fields;
				if ($this->queryType === 'update')
				{
					$this->entries .= ','. $this->model->identifierName;
				}
			}
			else
			{
				$this->entries = null;
			}
			$entriesArray = explode(',',$this->entries);
			if (isset($this->form))
			{
				foreach ($entriesArray as $entry)
				{
					$type = isset($entryType[$entry]) ? $entryType[$entry] : 'InputText';
					$options = isset($optionsArray[$entry]) ? $optionsArray[$entry] : null;
					$this->form->setEntry($entry,$type,$options);
				}
				if ($this->queryType === 'update')
				{
					$this->form->setEntry($this->model->identifierName,'Hidden');
				}
			}
			else
			{
				throw new Exception('form object has not been initialized. Call the <b>scaffold->loadForm</b> method before');
			}
		}
	}

	//add an item to the list of items
	public function addItem($type, $action = '', $field = '', $name = '', $value = '', $title = '') {
		if ($this->_type === 'main') {
			$this->itemList->addItem($type, $action, $field, $name, $value, $title);
		}
	}

	//update the table
	public function update($methodsList = '',$id = null) {
		$this->model->updateTable($methodsList,$id);
	}

	//method to create the HTML of the scaffold
	//$values: the values to insert in the from entries
	public function render($values = null,$subset = null)
	{
		
		if ($this->_type === 'main')
		{

			$recordNumber = $this->model->rowNumber();
			
			if (isset($this->viewArgs[$this->params['pageVariable']]))
			{
				$page = $this->viewArgs[$this->params['pageVariable']];
			}
			else
			{
				$this->params['pageList'] = false;
			}
			
			$recordPerPage = $this->params['recordPerPage'];
			
			if ($this->params['pageList'] === true)
			{
				$this->model->limit = $this->pageList->getLimit($page,$recordNumber,$recordPerPage);
				$this->html['pageList'] = $this->pageList->render((int)($page-self::$numbersOfPages[0]),self::$numbersOfPages[1]);
				$position = array($page,$this->pageList->getNumbOfPages());
			}
			else
			{
				$this->model->limit = null;
				$this->html['pageList'] = null;
				$position = array(1,1);
			}

			$queryFields = isset($this->fields) ? $this->fields : $this->model->select;
			$values = $this->model->getTable($queryFields);

			$primaryKey = $this->_primaryKey;
			
			//pass the variable position
			$this->itemList->position = $position;
			$this->html['main'] = $this->itemList->render($values);
			
			$this->html['filters'] = $this->itemList->createFilters();
			
			$this->html['menu'] = $this->mainMenu->render($this->params['mainMenu']);
			
			$popupHtml = null;
			if ($this->params['popup'] === true)
			{
				$this->html['popup'] = $this->popupMenu->render();
				$popupHtml = "<div class='verticalMenu'>\n".$this->html['popup']."\n</div>\n";
			}

			$this->html['all'] = "<div class='mainMenu'>".$this->html['menu']."</div>\n".$this->model->notice."\n $popupHtml \n<div class='recordsBox'>\n".$this->html['main']."\n</div>\n"."<div class='viewFooter'>\n<div class='pageList'>\n<span class='page_list_legend'>".$this->strings->gtext('pages').":</span> ".$this->html['pageList']."</div>\n</div>\n\n";

		}
		else if ($this->_type === 'form')
		{
			
			$subset = (!isset($subset)) ? $this->entries : $subset;
			$values = (!isset($values)) ? $this->values : $values;
			$this->html['menu'] = $this->mainMenu->render($this->params['formMenu']);
			$this->html['main'] = $this->form->render($values,$subset);
			$this->html['all'] = "<div class='mainMenu'>\n".$this->html['menu']."\n</div>\n".$this->model->notice."\n<div class='scaffold_form'>\n".$this->html['main']."</div>\n";

		}
		
		return $this->html['all'];
		
	}

}
