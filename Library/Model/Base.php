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

abstract class Model_Base
{
	//instance of Lang_{language}_Generic
	public $strings = null;
	
	public $foreignKeys = array(); //list of foreign keys
	
	public $fields = ''; //the fields that have to be manipulated by the update and insert query
	public $values = array(); //the values that corresponding to the $this->fields fields
	public $form = null; //reference to a Form_Form object
	public $formStruct = array("entries" => array()); //the form structure
	
	public $submitName = null; //the current submitName (from the form)
	public $identifierName = 'identifier';

	public $errors = array(); // Array of errors
	
	public $notice = null; //a string explaining the result of the query applied (or not if an error occured): executed, error, etc 
	public $result = true; //the result of validate conditions, database conditions and query. It can be 'true' or 'false'
	public $queryResult = false; //the result of the query

	//conditions that the $_POST array has to satisfy (strong)
	public $strongConditions = array();

	//conditions that the $_POST array has to satisfy (soft)
	public $softConditions = array();
	
	//conditions that $this->values has to satisfy (strong)
	public $valuesConditions = array();
	
	//array where the conditions are temporary saved when the saveConditions is called
	public $backupConditions = array();
	
	//conditions that have to be satisfied before applying the query
	//check that the new values inserted satisfy some conditions
	//Ex: 'update'=>'checkUniqueCompl:titolo,autore;checkUnique:titolo','insert'=>'checkUnique:titolo'
	public $databaseConditions = array();

	public $popupArray = array(); //array of popup objects (see popup.php)

	public $supplInsertValues = array(); //associative array defining supplementary values to be inserted on each insert query. It has to have the following form: array(field1 => value1,field2 => value2, ...)
	
	public $supplUpdateValues = array(); //associative array defining supplementary values to be inserted on each update query. It has to have the following form: array(field1 => value1,field2 => value2, ...)

	public $backupSelect = array(); //where the status of the where clause is stored when the save() method is called
	public $select = null; //fields that have to be selected in select queries
	public $sWhere = array(); //string: free where clause
	public $where = array(); //associative array containing all the where clauses ($field => $value)
	//group by, order by and limit clauses
	public $groupBy = null;
	public $orderBy = null;
	public $limit = null;
	
	public $convert = false; //It can be tru or false. If true the extracted values are converted from MySQL format to $_lang format
	
	public $from = null; //from clause of the select queries
	public $on = array(); //on array
	public $using = array(); //using array
	public $join = array(); //join array
	
	public $toList = false; //if the result have to be given in a list format
	public $listArray = array(); //array containing the $key and the $value to be used to extract a list from a resultSet
	
	public $process = false; //if the data result has to be processed
	
	public $pageNumber = 0; //page number in pagination (for automatic create the limit query)
	public $recordsPerPage = 0; //number of records in a page (show all records ifset to 0)
	public $numberOfRecords = 0; //number of records in the table
	public $numberOfPages = 0; //number of pages in the table
	
	public static $defaultRecordsPerPage = 50; //default number of records in a page
	
	//logic operator between statements in the where clause of select queries
	public $logicalOperators = array('AND');

	public $files = null; //reference to the Files_Upload class
	
	public $application = null;
	public $applicationUrl = null; //the url of the application
	public $controller; //controller that called the model
	public $action; //action of the controller that called the model
	public $currPage; //the URL of the current page
	
	public $bindedValues = array(); //array with values for prepared statement array(":name"=>"Johm",":surname"=>"Doe")
	
	public $applySoftConditionsOnPost = false; // apply soft conditions on $_POST too
	
	protected $_tables='itemTable,boxTable,item_boxTable';
	protected $_idFields='id_item,id_box';
	protected $_tablesArray=array();
	protected $_idFieldsArray=array();
	protected $_where = array();
	
	//the name of the field that has to be used to order the rows of the main table of the model
	protected $_idOrder = null;
	
	protected $_onDelete = 'check'; //can be 'nocheck' or 'check'. check: referential integrity check. nocheck: no referential integrity check
	protected $_reference = null; //array containing the child table that have a reference to this table and the foreign key of the child table-> array($childTable,$foreignKey)
	
	protected $_popupItemNames = array(); //the fields to be used as names in the popupArray elements. Associative array ($itemNameField1 => $itemNameValue1, ...)

	//the labels of the pop-up menus
	protected $_popupLabels = array();
	
	//functions that have to be applied upon the label fields of the popup menu
	protected $_popupFunctions = array();
	
	protected $_popupWhere = array(); //where clause for the pupup menu
	
	protected $_popupOrderBy = array(); //order by clause for the pupup menu
	
	protected $_resultString; //reference to the class containing all the result strings of the db queries
	protected $_dbCondString; //reference to the class containing all the result strings of the database conditions 

	protected $_conversionToDbObject = null; //reference to the class to convert the values from current lang formats to MySQL formats
	protected $_conversionFromDbObject = null; //reference to the class to convert the values from MySQL formats to current lang formats
	
	protected $_backupFields = ''; //field saved after the delFields method has been applied 
	protected $_backupValues = array(); //values saved after the delFields method has been applied 
	protected $_allowedDbMethods = array('update','insert','del','moveup','movedown'); //methods that can be called by the updateTable method
	
	protected $submitNames = array(
		'update' => 'updateAction',
		'insert' => 'insertAction',
		'del' =>'delAction',
		'moveup' =>'moveupAction',
		'movedown' =>'movedownAction'
	);
	
	protected $identifierValue = null; //the value of the identifier ($_POST[$this->identifier])
	protected $arrayExt; //arrayExt object (see library/arrayExt.php)
	
	protected $_arrayStrongCheck; //Array_Validate_Strong object
	protected $_arraySoftCheck; //Array_Validate_Soft object
	protected $_arrayValuesCheck; //Array_Validate_Values object
	
	public $db; //reference to the database layer class
	protected $_lang = null; //language of notices
	
	protected $id = null; //the ID of the record that have to be managed
	
	public function __construct() {
		$this->_tablesArray = explode(',',$this->_tables);
		$this->_idFieldsArray = explode(',',$this->_idFields);
		$this->_where[$this->_idFieldsArray[0]] = $this->_tablesArray[0];
		$this->arrayExt = new ArrayExt();
		
		//set the language of notices
		$this->_lang = Params::$language;
		
		//initialize the validate objects
		$this->_arrayStrongCheck = new Array_Validate_Strong($this->_lang);
		$this->_arraySoftCheck = new Array_Validate_Soft($this->_lang);
		$this->_arrayValuesCheck = new Array_Validate_Values($this->_lang);
		
		$this->identifierName = $this->_idFieldsArray[0];

		//create the $_resultString object (result strings of the db queries)
		$modelStringClass = 'Lang_'.$this->_lang.'_ModelStrings';
		if (!class_exists($modelStringClass))
		{
			$modelStringClass = 'Lang_En_ModelStrings';
		}
		$this->_resultString = new $modelStringClass();
		
		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);
		
		//create the $_dbCondString object (result strings of the database conditions)
		$dbCondStringClass = 'Lang_'.$this->_lang.'_DbCondStrings';
		if (!class_exists($dbCondStringClass))
		{
			$dbCondStringClass = 'Lang_En_DbCondStrings';
		}
		$this->_dbCondString = new $dbCondStringClass();

		//create the references of the classes to convert to and from MySQL formats
		if (DATABASE_TYPE !== "None")
		{
			$_conversionToDbObject = 'Lang_'.$this->_lang.'_Formats_To_Mysql';
			if (!class_exists($_conversionToDbObject))
			{
				$_conversionToDbObject = 'Lang_En_Formats_To_Mysql';
			}
			$this->_conversionToDbObject = new $_conversionToDbObject();
			
			$_conversionFromDbObject = 'Lang_'.$this->_lang.'_Formats_From_Mysql';
			if (!class_exists($_conversionFromDbObject))
			{
				$_conversionFromDbObject = 'Lang_En_Formats_From_Mysql';
			}
			$this->_conversionFromDbObject = new $_conversionFromDbObject();
		}
		
		//instantiate the database class
		$this->db = Factory_Db::getInstance(DATABASE_TYPE);

		//instantiate the Files_Upload class
		$params = array(
			'filesPermission'	=>	0777,
			'language'			=>	$this->_lang,
			'allowedExtensions'	=>	'png,jpg,jpeg,gif',
			'maxFileSize'		=>	20000000,
			'fileUploadKey'		=>	'userfile'
		);
		
		$this->files = new Files_Upload(ROOT."/media/",$params);
		
		$this->setForeignKeys();
	}
	
	public function setForeignKeys()
	{
		//relations
		$relations = $this->relations();
		
		foreach ($relations as $rel)
		{
			$rel[4] = isset($rel[4]) ? $rel[4] : "restrict";
			$rel[5] = isset($rel[5]) ? $rel[5] : "";
			
 			switch($rel[0])
			{
				case "HAS_MANY":
					$this->foreignKeys[] = $this->_idFields . " parent of ".$rel[1]."(".$rel[2].") on delete ".strtolower($rel[4])." (".$rel[5].")";
					break;
				case "BELONGS_TO":
					if (strtolower($rel[4]) == "restrict")
					{
						$modelString = $rel[1];
						$model = new $modelString();
						
						$this->foreignKeys[] = $rel[2] . " child of ".$rel[1]."(".$model->_idFields.") on update ".strtolower($rel[4])." (".$rel[5].")";
					}
					break;
				case "MANY_TO_MANY":
					if (isset($rel[3]) and count($rel[3]) === 3)
					{
						$this->foreignKeys[] = $this->_idFields . " parent of ".$rel[3][0]."(".$rel[3][1].") on delete ".strtolower($rel[4])." (".$rel[5].")";
					}
					break;
			}
		}
	}
	
	public function relations() {
        return array();
    }
    
	//getter method of $id
	public function getId()
	{
		return $this->id;
	}
	
	//setter method of $id
	public function setId($id)
	{
		$this->id = (int)$id;
	}
	
	//sanitize all the $values property
	public function sanitize($function = "sanitizeDb")
	{
		if (!function_exists($function)) {
			throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$function.'</b> does not exists.');
		}
		
		$keys = implode(',',array_keys($this->values));
		$this->values = $this->arrayExt->subset($this->values,$keys,$function);
	}

	//return the name of the primary key
	public function getPrimaryKey()
	{
		return $this->_idFields;
	}
	
	//return the name of the table managed by the model
	public function table()
	{
		return $this->_tables;
	}
	
	//change a resulting string from a db query
	public function setString($key,$value)
	{
		$this->_resultString->string[$key] = $value;
	}

	//set the submitNames property (array)
	//$methodName : the method name, $submitName: the submit name of the submit action of the form
	public function setSubmitNames($methodName,$submitName)
	{
		if (!in_array($methodName,$this->_allowedDbMethods))
		{
			throw new Exception('query type <b>"'.$methodName. '"</b> not allowed in '. __METHOD__);
		}
		$this->submitNames[$methodName] = $submitName;
	}

	/**
	* @brief 
	*
	* @param string $fieldsArrayList a comma separated list of fields
	*
	* @return void
	*/
	public function setFormFields($fieldsArrayList)
	{
		$this->fields = $fieldsArrayList;
	}
	
	//get the last query executed
	public function getQuery()
	{
		return $this->db->query;
	}

	//get the where clause of the select query
	public function getWhereQueryClause()
	{
		return $this->where;
	}

	//set the where clause of the select query
	//whereArray = array ($table_field => $value)
	public function setWhereQueryClause($whereArray)
	{
		$this->where = $whereArray;
	}

	//append the whereArray clause to $this_->whereClause
	//whereArray = array ($table_field => $value)
	public function appendWhereQueryClause($whereArray)
	{
		if (count($this->where) > 0)
		{
			$this->where = array_merge($this->where,$whereArray);
		}
		else
		{
			$this->where = $whereArray;
		}
	}

	//drop the char $char from the beginning of the string $string
	public function dropStartChar($string,$char)
	{
		while(strcmp($string[0],$char) === 0)
		{
			$string = substr($string,1);
		}
		return trim($string);
	}

	//get the table name from $this->_where. If the table is not present then return $this->_tablesArray[0]
	public function getTableName($field)
	{
		return isset($this->_where[$field]) ? $this->_where[$field] : $this->_tablesArray[0];
	}

	//check if the string is in the form of table_name.field_name or field_name (without table)
	public function hasTableName($string)
	{
		if (preg_match('/^[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+$/',$string))
		{
			return true;
		}
		
		return false;
	}
	
	public function prepareWhereClause($string)
	{
		$regExpr = '/^('.implode("|",Params::$whereClauseTransformSymbols).')\:(.*)$/';
		
		if (preg_match($regExpr,$string,$matches))
		{
			if (function_exists("wc".$matches[1]))
			{
				return callFunction("wc".$matches[1],$matches[2],__METHOD__);
			}
		}

		return $string;
	}
	
	public function sanitizeValue($value)
	{
		if (defined("SANITIZE_QUERIES") and SANITIZE_QUERIES)
		{
			$deep = is_array($value) ? "Deep" : "";
			
			$value = call_user_func(Params::$defaultSanitizeDbFunction."$deep",$value);
		}
		
		return $value;
	}
	
	public function createWhereClausePDO($level = 0, $whereClauseLevel = null, $operator = null)
	{
		$whereClause = null;
		$whereClauseArray = array();

		$whereClause = isset($whereClauseLevel) ? $whereClauseLevel : $this->where;
		
		foreach ($whereClause as $field => $value)
		{
			if (is_array($value))
			{
				$newValue = null;
				
				if (strstr($field,"OR"))
				{
					$newValue = $this->createWhereClausePDO($level+1, $value, " OR ");
				}
				else if (strstr($field,"AND"))
				{
					$newValue = $this->createWhereClausePDO($level+1, $value, " AND ");
				}
				else
				{
					$fieldName = key($value);
					
					$tableName = $this->hasTableName($fieldName) ? null : $this->getTableName($fieldName).'.';
					
					$fieldName = $tableName.trim($fieldName);
					
					$value = reset($value);
					
					if (Params::$nullQueryValue === false or (!is_array($value) and strcmp($value,Params::$nullQueryValue) !== 0) or (is_array($value) and !empty($value)))
					{
						if (in_array(strtolower(trim($field)),array("in","nin")))
						{
							$placeholders = str_repeat ('?, ',  count ($value) - 1) . '?';
							foreach ($value as $v)
							{
								$this->bindedValues[] = $v;
							}
						}
						switch(strtolower(trim($field)))
						{
							case "nlk":
								$newValue  = $fieldName . " not like ?";
								$this->bindedValues[] = "%".$value."%";
								break;
							case "lk":
								$newValue  = $fieldName . " like ?";
								$this->bindedValues[] = "%".$value."%";
								break;
							case "nin":
								$newValue  = $fieldName . " not in ($placeholders) ";
								break;
							case "in":
								$newValue  = $fieldName . " in ($placeholders) ";
								break;
							case "lt":
								$newValue  = $fieldName . " < ? ";
								$this->bindedValues[] = $value;
								break;
							case "lte":
								$newValue  = $fieldName . " <= ? ";
								$this->bindedValues[] = $value;
								break;
							case "gt":
								$newValue  = $fieldName . " > ? ";
								$this->bindedValues[] = $value;
								break;
							case "gte":
								$newValue  = $fieldName . " >= ? ";
								$this->bindedValues[] = $value;
								break;
							case "ne":
								$newValue  = $fieldName . " != ? ";
								$this->bindedValues[] = $value;
								break;
							case "trim":
								$newValue  = "TRIM(".$fieldName.")" . " = ? ";
								$this->bindedValues[] = $value;
								break;
							default:
								$newValue  = strtolower(trim($field))."(".$fieldName.")" . " = ? ";
								$this->bindedValues[] = $value;
								break;
						}
					}
				}
				
				if (isset($newValue)) $whereClauseArray[] = $newValue;
			}
			else
			{
				$fieldClean = trim($field);
				
				$tableName = $this->hasTableName($fieldClean) ? null : $this->getTableName($fieldClean).'.';
				
				if (Params::$nullQueryValue === false or strcmp($value,Params::$nullQueryValue) !== 0)
				{
					$whereClauseArray[] = $tableName.$fieldClean.'=?';
					$this->bindedValues[] = $value;
				}
			}
		}
		//get the logic operator at the current level
		if (isset($operator))
		{
			$logicOper = $operator;
		}
		else
		{
			$logicOper = isset($this->logicalOperators[$level]) ? ' '.$this->logicalOperators[$level].' ' : ' AND ';
		}
		$whereClause = !empty($whereClauseArray) ? implode($logicOper,$whereClauseArray) : null;
		$whereClause = (isset($whereClause) and $level>0) ? '('.$whereClause.')' : $whereClause;
		return $whereClause;
	}
	
	public function getProcessedSelectedFieldsInQuery()
	{
		$types = $this->db->getTypes($this->_tables, "*", false, true);
		
		$querySelect = array();
		
		foreach ($types as $column => $type)
		{
			$querySelect[] = $this->_tables.".".$column." AS ".$this->_tables."___".$column;
		}
		
		return $querySelect;
	}
	
	//method to create the where clause of the select query from the $this->where array
	//$level: level of the ricorsion
	//$whereClauseLevel: array containing the field=>value statements of the where clause. If $whereClause = null than $this->where is considered
	public function createWhereClause($level = 0, $whereClauseLevel = null, $operator = null)
	{
		$whereClause = null;
		$whereClauseArray = array();

		$whereClause = isset($whereClauseLevel) ? $whereClauseLevel : $this->where;
		
		foreach ($whereClause as $field => $value)
		{
			if (is_array($value))
			{
				$newValue = null;
				
				if (strstr($field,"OR"))
				{
					$newValue = $this->createWhereClause($level+1, $value, " OR ");
				}
				else if (strstr($field,"AND"))
				{
					$newValue = $this->createWhereClause($level+1, $value, " AND ");
				}
				else
				{
					if ((defined('NEW_WHERE_CLAUSE_STYLE') and NEW_WHERE_CLAUSE_STYLE) || Params::$newWhereClauseStyle)
					{
						$fieldName = key($value);
						
						$tableName = (strstr($fieldName,'n!') or $this->hasTableName($fieldName)) ? null : $this->getTableName($fieldName).'.';
						
						$fieldName = str_replace('n!',null,$fieldName);
						$fieldName = $tableName.$this->dropStartChar($fieldName,'-');
						
						$value = reset($value);
						
						$value = $this->sanitizeValue($value);
						
						if (Params::$nullQueryValue === false or (!is_array($value) and strcmp($value,Params::$nullQueryValue) !== 0) or (is_array($value) and !empty($value)))
						{
							switch(strtolower(trim($field)))
							{
								case "nlk":
									$newValue  = $fieldName . " not like '%" . $value . "%' ";
									break;
								case "lk":
									$newValue  = $fieldName . " like '%" . $value . "%' ";
									break;
								case "nin":
									$newValue  = $fieldName . " not in ('" . implode("','",$value) . "') ";
									break;
								case "in":
									$newValue  = $fieldName . " in ('" . implode("','",$value) . "') ";
									break;
								case "lt":
// 									$string = gettype($value) === "integer" ? (int)$value : "'$value'";
									$string = "'$value'";
									$newValue  = $fieldName . " < $string ";
									break;
								case "lte":
// 									$string = gettype($value) === "integer" ? (int)$value : "'$value'";
									$string = "'$value'";
									$newValue  = $fieldName . " <= $string ";
									break;
								case "gt":
// 									$string = gettype($value) === "integer" ? (int)$value : "'$value'";
									$string = "'$value'";
									$newValue  = $fieldName . " > $string ";
									break;
								case "gte":
// 									$string = gettype($value) === "integer" ? (int)$value : "'$value'";
									$string = "'$value'";
									$newValue  = $fieldName . " >= $string ";
									break;
								case "ne":
// 									$string = gettype($value) === "integer" ? (int)$value : "'$value'";
									$string = "'$value'";
									$newValue  = $fieldName . " != $string ";
									break;
							}
						}
					}
					else
					{
						$newValue = $this->createWhereClause($level+1, $value, null);
					}
				}
				
				if (isset($newValue)) $whereClauseArray[] = $newValue;
			}
			else
			{
				$flag = 0; //equal where clause
				if (isset($field))
				{
					//drop the 'n:' and '-' chars from $field
					$fieldClean = str_replace('n!',null,$field);
					$fieldClean = $this->dropStartChar($fieldClean,'-');
					
					$tableName = (strstr($field,'n!') or $this->hasTableName($field)) ? null : $this->getTableName($field).'.';
					
					if ((!defined('NEW_WHERE_CLAUSE_STYLE') or !NEW_WHERE_CLAUSE_STYLE) && !Params::$newWhereClauseStyle)
					{
						$regExpr = '/^('.implode("|",Params::$whereClauseTransformSymbols).')\:('.Params::$nullQueryValue.')$/';
						
						if (Params::$nullQueryValue === false or (strcmp($value,Params::$nullQueryValue) !== 0 and !preg_match($regExpr,$value)))
						{
							$value = $this->prepareWhereClause($value);
							
							foreach (params::$whereClauseSymbolArray as $symbol)
							{
								if (strpos($value, $symbol) === 0)
								{
									//check if write or not the table name
									$whereClauseArray[] = strstr($field,'n!n!') ? $value : $tableName.$fieldClean.' '.$value;
									$flag = 1; //not equal where clause
									break;
								}
							}
							if ($flag === 0)
							{
	// 							$value = '"'.$value.'"';
								//check if write or not the table name
								$whereClauseArray[] = strstr($field,'n!n!') ? $value : $tableName.$fieldClean.'='.'"'.$value.'"';
							}
						}
					}
					else
					{
						if (Params::$nullQueryValue === false or strcmp($value,Params::$nullQueryValue) !== 0)
						{
							if (DATABASE_TYPE === 'PDOMysql')
							{
								$whereClauseArray[] = $tableName.$fieldClean.'=?';
								$this->bindedValues[] = $value;
							}
							else
							{
								$value = $this->sanitizeValue($value);
							
								$whereClauseArray[] = strstr($field,'n!n!') ? $value : $tableName.$fieldClean.'='.'"'.$value.'"';
							}
						}
					}
				}
			}
		}
		//get the logic operator at the current level
		if (isset($operator))
		{
			$logicOper = $operator;
		}
		else
		{
			$logicOper = isset($this->logicalOperators[$level]) ? ' '.$this->logicalOperators[$level].' ' : ' AND ';
		}
		$whereClause = !empty($whereClauseArray) ? implode($logicOper,$whereClauseArray) : null;
		$whereClause = (isset($whereClause) and $level>0) ? '('.$whereClause.')' : $whereClause;
		return $whereClause;
	}


	//get the submitName having its key (the method name)
	public function getSubmitName($key)
	{
		if (!array_key_exists($key,$this->submitNames))
		{
			return 'generalAction';
// 			throw new Exception('query type <b>"'.$key. '"</b> not allowed in '.__METHOD__);
		}
		return $this->submitNames[$key];
		
	}

	//converts values from MySQl to $_lang format when filling the form with values coming from the DB
	public function convertFromMysql($values)
	{
		if (Params::$automaticConversionFromDbFormat)
		{
			if (isset($this->_conversionFromDbObject))
			{
				//get all types as associative array
				$types = $this->db->getTypes($this->_tables, "*", false, true);
				
				if ($types)
				{
					$values = $this->convertFromMysqlT($types, $values, $this->db->getEnumTypes());
				}
			}
		}
		
		return $values;
	}
	
	//convert an array associaive from MySQL format to $_lang format
	//$values: array associative to convert
	//$types: types of the elements of the associative array
	//$excludeTypes: array of type whose conversion has to be avoided
	public function convertFromMysqlT($types, $values, $excludeTypes = array())
	{
		foreach ($values as $field => $value)
		{
			if (array_key_exists($field, $types))
			{
				if (!in_array(strtolower($types[$field]),$excludeTypes))
				{
					if (method_exists($this->_conversionFromDbObject,strtolower($types[$field])))
					{
						$values[$field] = call_user_func(array($this->_conversionFromDbObject, strtolower($types[$field])), $values[$field]);
					}
				}
			}
		}
		return $values;
	}
	
	//set the default values taking it from DB or from type definition
	public function setDefaultFormValues($fields)
	{
		$returnDefaultValues = array();
		
		if (Params::$automaticallySetFormDefaultValues)
		{
			if (isset($this->_conversionFromDbObject))
			{
				//get all types as associative array
				$types = $this->db->getTypes($this->_tables, "*", true, true);
				
				//get all default values as associative array
				$defaultValues = $this->db->getDefaultValues($this->_tables, "*", false, true);
				
				$fieldsArray = explode(",",$fields);
				
				foreach ($fieldsArray as $field)
				{
					if (array_key_exists($field,$defaultValues))
					{
						if (preg_match('/^('.implode("|",$this->db->getCharTypes()).')/i',$types[$field],$matches) or preg_match('/^('.implode("|",$this->db->getTextTypes()).')/i',$types[$field],$matches))
						{
							if (strcmp($defaultValues[$field],"") !== 0)
							{
								$returnDefaultValues[$field] = $defaultValues[$field];
							}
						}
						else if (preg_match('/^('.implode("|",$this->db->getIntegerTypes()).')/i',$types[$field],$matches) or preg_match('/^('.implode("|",$this->db->getFloatTypes()).')$/i',$types[$field],$matches) or preg_match('/^('.implode("|",$this->db->getDecimalTypes()).')/i',$types[$field],$matches))
						{
							if (strcmp($defaultValues[$field],"") !== 0)
							{
								$returnDefaultValues[$field] = method_exists($this->_conversionFromDbObject,strtolower($matches[1])) ? call_user_func(array($this->_conversionFromDbObject, strtolower($matches[1])), $defaultValues[$field]) : $defaultValues[$field];
							}
							else
							{
								$returnDefaultValues[$field] = 0;
							}
						}
						else if (preg_match('/^('.implode("|",$this->db->getDateTypes()).')$/i',$types[$field],$matches))
						{
							$defDate = Params::$useCurrentDateAsDefaultDate ? date("Y-m-d") : "";
							if (strcmp($defaultValues[$field],"") !== 0)
							{
								$defDate = $defaultValues[$field];
							}
							
							if (method_exists($this->_conversionFromDbObject,strtolower($types[$field])))
							{
								$returnDefaultValues[$field] = call_user_func(array($this->_conversionFromDbObject, strtolower($types[$field])), $defDate);
							}
							else
							{
								$returnDefaultValues[$field] = $defDate;
							}
						}
						else if (preg_match('/^('.implode("|",$this->db->getEnumTypes()).')\((.*?)\)$/i',$types[$field],$matches))
						{
							if (strcmp($defaultValues[$field],"") !== 0)
							{
								$returnDefaultValues[$field] = $defaultValues[$field];
							}
							else
							{
								$temp = array();
								$strings = explode(",",$matches[2]);
								for ($i=0;$i<count($strings);$i++)
								{
									$returnDefaultValues[$field] = trim($strings[$i],"'\"");
									break;
								}
							}
						}
					}
				}
			}
		}
		
		return $returnDefaultValues;
	}
	
	public function renderForm($fields, $defaultValues = array(),$functionsIfFromDb = array(),$func = 'sanitizeHtml')
	{
		if (isset($this->id))
		{
			$queryType = $this->id > 0 ? "update" : "insert";
			
			$values = $this->getFormValues($queryType, $func, $this->id, $defaultValues, $functionsIfFromDb);
			
			return $this->form->render($values,$fields);
		}
		
		return "";
	}
	
	//return the values, taken from the $_POST array, to be inserted inside the forms
	//$queryType: insert or update
	//$func: sanitize function to apply upon each value
	//$id: if $queryType='update' that the values are taken from the record (of the main table of this model) having the primary key equal to $id
	//$defaultValues = associative array of the form: array($entry=>$defaultValue)
	//$functionsIfFromDb = associative array of the form: array($entry=>$function_to_be_applied)
	public function getFormValues($queryType = 'insert', $func = 'sanitizeHtml',$id = null,$defaultValues = array(),$functionsIfFromDb = array())
	{
		@session_start();
		
		//get the action array
		$actionArray = strcmp(Params::$actionArray,"POST") === 0 ? $_POST : $_GET;
		
		if (is_array($func))
		{
			$funcPost = $func[0];
			$funcDb = $func[1];
		}
		else
		{
			$funcPost = $func;
			$funcDb = 'none';
		}
		
		$arrayType = array('update','insert');
		$values = array();
		$idName = $this->identifierName;
		if (in_array($queryType,$arrayType))
		{
			$ident = null;
			
			if (isset($id))
			{
				$ident = (int)$id;
			}
			else if (isset($this->id) and ($this->id > 0))
			{
				$ident = (int)$this->id;
			}
			else if (isset($actionArray[$idName]))
			{
				$ident = (int)$actionArray[$idName];
			}
			
			if ($this->result)
			{
				if ($queryType === 'update')
				{
					if (isset($ident))
					{
						$recordArray = $this->selectId($ident);

						$recordArray = $this->convertFromMysql($recordArray);
						
						$fieldsArray = explode(',',$this->fields);
						
						$values = $this->arrayExt->subset($recordArray,$this->fields,$funcDb);
						
						$values[$idName] = $ident;
						
						//apply the functions upon entries
						foreach ($functionsIfFromDb as $entry => $funcUponEntry)
						{
							if (array_key_exists($entry,$values))
							{
								if (!function_exists($funcUponEntry)) {
									throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$funcUponEntry. '</b> does not exist');
								}
								
								$values[$entry] = call_user_func($funcUponEntry,$values[$entry]);
							}
						}

						//set values of $_SESSION array
						foreach ($values as $k => $v)
						{
							if (isset($this->formStruct['entries'][$k]['type']))
							{
								if ($this->formStruct['entries'][$k]['type'] === 'File')
								{
									$_SESSION['form_'.$k] = $v;
								}
							}
						}
					}
				}
				else if ($queryType === 'insert')
				{
					//set the default values taking it from DB or from type definition
					$tempArray = $this->setDefaultFormValues($this->fields);
					
					if (is_array($defaultValues))
					{
						foreach ($defaultValues as $field => $value)
						{
							$tempArray[$field] = $value;
						}
					}
					
					$values = $this->arrayExt->subset($tempArray,$this->fields,$funcPost);
					
				}
			}
			else
			{
				$values = $this->arrayExt->subset($_POST,$this->fields,$funcPost);
				
				if ($queryType === 'update')
				{
					$values[$idName] = $ident;
					
					//take values from $_SESSION array
					$tempFieldArray = explode(',',$this->fields);
					
					for ($i = 0; $i < count($tempFieldArray); $i++)
					{
						if (isset($this->formStruct['entries'][$tempFieldArray[$i]]['type']))
						{
							if ($this->formStruct['entries'][$tempFieldArray[$i]]['type'] === 'File')
							{
								if (isset($_SESSION['form_'.$tempFieldArray[$i]]))
								{
									$values[$tempFieldArray[$i]] = $_SESSION['form_'.$tempFieldArray[$i]];
								}
							}
						}
					}
				}
			}
		}
		
		return $values;
	}

	
	
	//set the $this->values array taking data from $source
	public function setValuesFromDataSource($fields, $func = null, $dataSource)
	{
		if (!isset($func)) $func = Params::$defaultSanitizeFunction;
		
		$this->values = $this->arrayExt->subset($dataSource,$fields,$func);
		$this->fields = $this->extractFields($fields);
		
		//set the backup variables
		$this->_backupFields = $this->fields;
		$this->_backupValues = $this->values;
	}
	
	//method to set the properties $this->fields and $this->values from $_POST
	public function setFields($fields,$func = null)
	{
// 		if (!isset($func)) $func = Params::$defaultSanitizeFunction;
		
		$this->setValuesFromDataSource($fields, $func, $_POST);
		
// 		$this->values = $this->arrayExt->subset($_POST,$fields,$func);
// 		$this->fields = $this->extractFields($fields);
// 		
// 		//set the backup variables
// 		$this->_backupFields = $this->fields;
// 		$this->_backupValues = $this->values;
	}

	//alias of setFields
	public function setValuesFromPost($fields, $func = null)
	{
// 		if (!isset($func)) $func = Params::$defaultSanitizeFunction;
		
		$this->setFields($fields, $func);
	}
	
	//set $this->values taking values from $dataSource associative arrays and taking all of his keys => values
	public function setValues($dataSource, $func = null)
	{
// 		if (!isset($func)) $func = Params::$defaultSanitizeFunction;
		
		$fields = implode(",",array_keys($dataSource));
		
		$this->setValuesFromDataSource($fields, $func, $dataSource);
	}
	
	//set a single value
	public function setValue($field, $value, $func = null)
	{
		if (!isset($func)) $func = Params::$defaultSanitizeFunction;
		
		$this->values[$field] = call_user_func($func,$value);
	}	
	
	//clear the fields list
	public function clearFields()
	{
		$this->_backupFields = $this->fields;
		$this->_backupValues = $this->values;
		$this->fields = '';
		$this->values = array();
	}

	//del the fields written in the $list argument. The $list argument has to be of the type: field1,field2,...
	public function delFields($list)
	{
		$this->_backupFields = $this->fields;
		$this->_backupValues = $this->values;
		$this->values = $this->arrayExt->subsetComplementary($this->values,$list);
// 		$this->fields = implode(',',array_keys($this->values));
	}

	//restore the fields and values saved in $_backupFields and $_backupValues
	public function restoreFields()
	{
		$this->fields = $this->_backupFields;
		$this->values = $this->_backupValues;
	}

	//method to clean the $fields string deleting the colons (and the word after the colon)
	public function extractFields($fields) {
		$fieldsArray = explode(',',$fields);
		$resultString = array();
		foreach ($fieldsArray as $field) {
			if (strstr($field,':')) {
				$temp = explode(':',$field);
				$resultString[] = $temp[0];
			} else {
				$resultString[] = $field;
			}
		}
		return implode(',',$resultString);
	}

	//add the supplementary value on insert and update queries
	//$queryType: insert or update
	public function setSupplValues($queryType)
	{
		if ($queryType === 'insert')
		{
			$supplValues = $this->supplInsertValues;
		}
		else if ($queryType === 'update')
		{
			$supplValues = $this->supplUpdateValues;
		}
		
		$baseFields = implode(',',array_keys($this->values));
		
		$supplFields = implode(',',array_keys($supplValues));
		$supplFields = (strcmp($supplFields,'') === 0) ? $supplFields : ',' . $supplFields;

		$fields = $baseFields . $supplFields;
		$values = array_merge(array_values($this->values),array_values($supplValues));
		
		return array($fields,$values);
	}


	//method to call the update query (overriding of the base_db del method)
	//update the record with the primary key equal to $id (default)
	//if $whereClause is set then use $whereClause as where clause of the update query
	public function update($id = null, $whereClause = null)
	{
		$this->notice = null;
		$this->errors = array();
		
		if (!is_array($this->supplUpdateValues))
		{
			throw new Exception('error in <b>' . __METHOD__ . '</b>: the <b>supplUpdateValues</b> property has to be an array.');
		}
		$el = $this->setSupplValues('update');
		$this->queryResult = false;
		
		if (count($this->values) > 0)
		{
			if (isset($whereClause))
			{
				$result = $this->db->update($this->_tablesArray[0],$el[0],$el[1],$whereClause);
				$this->setNotice($result);
				return $result;
			}
			else
			{
				if (isset($id))
				{
					$where = $this->_idFieldsArray[0].'='.(int)($id);
					$result = $this->db->update($this->_tablesArray[0],$el[0],$el[1],$where);
					$this->setNotice($result);
					return $result;
				}
				else
				{
					$errorString = $this->_resultString->getString('no-id');
					$this->notice .= $errorString;
					$this->errors["Query"][] = strip_tags($errorString);
					
					$this->result = false;
					$this->identifierValue = null;
					return false;
				}
			}
		}
		else
		{
			$errorString = $this->_resultString->getString('no-fields');
			$this->notice .= $errorString;
			$this->errors["Query"][] = strip_tags($errorString);
			$this->result = true;
			$this->queryResult = true;
			return false;
		}
	}

	//method to call the insert query (overriding of the base_db del method)
	public function insert() {
		$this->notice = null;
		$this->errors = array();
		
		$this->queryResult = false;
		if (!is_array($this->supplInsertValues)) {
			throw new Exception('error in <b>' . __METHOD__ . '</b>: the <b>supplInsertValues</b> property has to be an array.');
		}
		
		if (count($this->values) > 0)
		{
			if (isset($this->_idOrder) && !isset($this->values["id_order"]))
			{
				$maxValue = $this->db->getMax($this->_tablesArray[0],$this->_idOrder);
				$this->supplInsertValues[$this->_idOrder] = (int)$maxValue + 1;
			}
			
			$el = $this->setSupplValues('insert');
			
			$result = $this->db->insert($this->_tablesArray[0],$el[0],$el[1]);
			$this->setNotice($result);
			return $result;
		}
		else
		{
			$errorString = $this->_resultString->getString('no-fields');
			$this->notice .= $errorString;
			$this->errors["Query"][] = strip_tags($errorString);
			$this->result = true;
			$this->queryResult = true;
			return false;
		}
	}

	//method to call the delete query (overriding of the base_db del method)
	public function del($id = null, $whereClause = null) {
		
		$this->queryResult = false;
		
		if (isset($whereClause))
		{
			$result = $this->db->del($this->_tablesArray[0],$whereClause);
			$this->setNotice($result);
			return $result;
		}
		else
		{
			if (isset($id)) {
				$where = $this->_idFieldsArray[0].'='.(int)$id;
				$result = $this->db->del($this->_tablesArray[0],$where);
				$this->setNotice($result);
				return $result;
			} else {
				
				$errorString = $this->_resultString->getString('no-id');
				$this->notice .= $errorString;
				$this->errors["Query"][] = strip_tags($errorString);
				
				$this->result = false;
				$this->identifierValue = null;
				return false;
			}
		}
	}

	//move to the top the record having $this->_idOrder = $id
	//where clause
	public function moveup($id)
	{
		return $this->move($id,'up');
	}

	//move to the top the record having $this->_idOrder = $id
	//where clause
	public function movedown($id)
	{
		return $this->move($id,'down');
	}

	//move the record having $this->_tablesArray[0] = $id
	//$par: 'up' or 'down'
	//where clause
	public function move($id,$par = 'up')
	{
		$this->notice = null;
		$this->errors = array();
		
		$this->queryResult = false;
		if (isset($id))
		{
			$increm = ($par === 'up') ? 1 : -1;
			
			$backupLimit = $this->limit;
			$this->limit = null;
			
			$data = $this->getFields($this->_tablesArray[0].'.'.$this->_idFieldsArray[0].','.$this->_tablesArray[0].'.'.$this->_idOrder);
			
			for($i = 0; $i < count($data); $i++)
			{
				if (strcmp($data[$i][$this->_tablesArray[0]][$this->_idFieldsArray[0]],$id) === 0)
				{
					if (($par === 'up' and $i !== 0) or ($par === 'down' and $i !== (count($data)-1)))
					{
						$prevOrder = $data[$i-$increm][$this->_tablesArray[0]][$this->_idOrder];
						$prevId = $data[$i-$increm][$this->_tablesArray[0]][$this->_idFieldsArray[0]];
						$currentOrder = $data[$i][$this->_tablesArray[0]][$this->_idOrder];
						$currentId = $data[$i][$this->_tablesArray[0]][$this->_idFieldsArray[0]];

						//exchange the id_order of the two record
						$res1 = $this->db->update($this->_tablesArray[0],$this->_idOrder,array($prevOrder),$this->_idFieldsArray[0]."='$currentId'");
						$res2 = $this->db->update($this->_tablesArray[0],$this->_idOrder,array($currentOrder),$this->_idFieldsArray[0]."='$prevId'");
						$result = ($res1 and $res2);
						$this->setNotice($result);
						return $result;
					}
				}
			}
			
			$this->limit = $backupLimit;
		}
		else
		{
			$errorString = $this->_resultString->getString('no-id');
			$this->notice .= $errorString;
			$this->errors["Query"][] = strip_tags($errorString);
			
			$this->result = false;
			$this->identifierValue = null;
			return false;
		}
		return false;
	}

	public function setNotice($result) {
		if ($result) {
			$this->notice .= $this->_resultString->getString('executed');
			$this->result = true;
			$this->queryResult = true;
		} else {
			$errorString = $this->_resultString->getString('error');
			$this->notice .= $errorString;
			$this->errors["Query"][] = strip_tags($errorString);
			
			$this->result = false;
			$this->queryResult = false;
		}
	}

// 	//method used to verify that the value of a field is not duplicated
// 	//$fieldsList: list of fields to check. Ex: field1,field2,...
// 	//$where: the where clause
// 	public function checkUnique($fieldsList,$where = null)
// 	{
// 		$errorString = null;
// 		$numb = 0;
// 		$fieldsArray = explode(',',$fieldsList);
// 		$queryFieldsArray = explode(',',$this->fields);
// 		foreach ($fieldsArray as $field)
// 		{
// 			if (in_array($field,$queryFieldsArray))
// 			{
// 				if ($this->db->recordExists($this->_tablesArray[0],$field,$this->values[$field],$where))
// 				{
// 					$errorString .= $this->_dbCondString->getNotUniqueString($field);
// 					$numb++;
// 				}
// 			}
// 		}
// 		$this->notice = $errorString;
// 		return $numb === 0 ? true : false;
// 	}
// 
// 	//like checkUnique: check all the records of the table apart from the record that has to be modified
// 	public function checkUniqueCompl($fieldsList,$id = null)
// 	{
// 		if (isset($id))
// 		{
// 			$where = $this->_idFieldsArray[0].'!='.(int)($id);
// 			return $this->checkUnique($fieldsList,$where);
// 		} else {
// 			$this->notice = $this->_resultString->getString('no-id');
// 			return false;
// 		}
// 	}
	
	//method used to verify that the value of a field is not duplicated
	//$fieldsList: list of fields to check. Ex: field1,field2,...
	//$where: the where clause
	public function checkUnique($fieldsList,$where = array())
	{
		$errorString = null;
		$numb = 0;
		$fieldsArray = explode(',',$fieldsList);
		$queryFieldsArray = explode(',',$this->fields);
		foreach ($fieldsArray as $field)
		{
			if (in_array($field,$queryFieldsArray))
			{
				$where[$field] = $this->values[$field];
				$numero = $this->clear()->where($where)->rowNumber();
// 				if ($this->db->recordExists($this->_tablesArray[0],$field,$this->values[$field],$where))
				if ($numero > 0)
				{
					$errorString .= $this->_dbCondString->getNotUniqueString($field);
					$this->errors["Fields"][$field][] = strip_tags($errorString);
					$numb++;
				}
			}
		}
		$this->notice = $errorString;
		return $numb === 0 ? true : false;
	}

	//like checkUnique: check all the records of the table apart from the record that has to be modified
	public function checkUniqueCompl($fieldsList,$id = null)
	{
		if (isset($id))
		{
			$where = array(
				"ne"	=>	array(
					$this->_idFieldsArray[0]	=>	(int)$id,
				)
			);
// 			$where = $this->_idFieldsArray[0].'!='.(int)($id);
			return $this->checkUnique($fieldsList,$where);
		} else {
			$errorString = $this->_resultString->getString('no-id');
			$this->notice = $errorString;
			$this->errors["Query"][] = strip_tags($errorString);
			return false;
		}
	}
	
	public function setErrors($fields, $errorString)
	{
		$fieldsArray = explode(",",$fields);
		
		foreach ($fieldsArray as $field)
		{
			$this->errors["Fields"][$field] = array(strip_tags($errorString));
		}
	}
	
	//method to apply the database conditions listed in the $this->databaseConditions associative array
	//$queryType: indicates what set of validate conditions has to be considered (it's the key of the associative array)
	public function applyDatabaseConditions($queryType,$id = null)
	{
		if (array_key_exists($queryType,$this->databaseConditions))
		{
			if (!is_array($this->databaseConditions[$queryType]))
			{
				throw new Exception('error in method <b>'.__METHOD__.'</b> : <b>databaseConditions['.$queryType.']</b> has to be an associative array');
			}
			
			foreach ($this->databaseConditions[$queryType] as $key => $values)
			{

				//personalized error string
				$altErrorString = null;
				
				//delete all the '+' chars
				$key = $this->dropStartChar($key,'+');
				
				if (strstr($values,'|'))
				{
					$temp = explode('|',$values);
					$altErrorString = "<div class='".Params::$errorStringClassName."'>".$temp[1]."</div>\n";
					$values = $temp[0];
				}

				$allowedMethod = array('checkUnique','checkUniqueCompl');
				if (!in_array($key,$allowedMethod))
				{
					throw new Exception('error in method '.__METHOD__.' : method "'.$key. '" not allowed in the property named databaseConditions');
				}
				if (!call_user_func_array(array($this,$key),array($values,$id)))
				{
					if (isset($altErrorString))
					{
						$this->notice = $altErrorString;
						$this->setErrors($values, $altErrorString);
					}
					
					$this->result = false;
					$this->queryResult = false;
					return false;
				}
			}
			return true;
		} else {
			return true;
		}
	}

	//add a condition
	//$condArray: it can be $this->strongConditions, $this->softConditions or $this->databaseConditions
	//$queryType: insert, update
	//$condition: the condition
	//$field: comma separated list of fields
	private function addCondition(&$condArray,$queryType,$condition,$field)
	{
		if (isset($condArray[$queryType]) and array_key_exists($condition,$condArray[$queryType]))
		{
			$condition = "+".$condition;
			$this->addCondition($condArray,$queryType,$condition,$field);
		}
		else
		{
			$condArray[$queryType][$condition] = $field;
		}
	}

	//choose if to apply insert, update or both conditions
	private function addChooseCondition(&$condArray,$queryType,$condition,$field)
	{
		if ($queryType === "both")
		{
			$this->addCondition($condArray,"insert",$condition,$field);
			$this->addCondition($condArray,"update",$condition,$field);
		}
		else
		{
			$this->addCondition($condArray,$queryType,$condition,$field);
		}
	}

	//add a condition to the strongCondition array
	public function addDatabaseCondition($queryType,$condition,$field)
	{
		if ($queryType === "both")
		{
			$this->addChooseCondition($this->databaseConditions,"insert",$condition,$field);
			$this->addChooseCondition($this->databaseConditions,"update",$condition."Compl",$field);
		}
		else
		{
			$this->addChooseCondition($this->databaseConditions,$queryType,$condition,$field);
		}
	}
	
	//add a condition to the strongCondition array
	public function addStrongCondition($queryType,$condition,$field)
	{
		$this->addChooseCondition($this->strongConditions,$queryType,$condition,$field);
	}
	
	//add a condition to the softCondition array
	public function addSoftCondition($queryType,$condition,$field)
	{
		$this->addChooseCondition($this->softConditions,$queryType,$condition,$field);
	}
	
	//add a condition to the valuesCondition array
	public function addValuesCondition($queryType,$condition,$field)
	{
		$this->addChooseCondition($this->valuesConditions,$queryType,$condition,$field);
	}
	
	//return the correct conditions array
	//$strength: strong,soft,values
	public function &getConditions($strength)
	{
		if ($strength === 'strong')
		{
			return $this->strongConditions;
		}
		else if ($strength === 'values')
		{
			return $this->valuesConditions;
		}
		else if ($strength === 'database')
		{
			return $this->databaseConditions;
		}

		return $this->softConditions;
	}
	
	//save the conditions
	//$strength: strong,soft,values
	public function saveConditions($strength)
	{
		$this->backupConditions[$strength] = $this->getConditions($strength);
	}
	
	//restore the conditions taking them from $this->backupConditions
	public function restoreConditions($strength)
	{
		$c = &$this->getConditions($strength);
		
		if (isset($this->backupConditions[$strength]))
		{
			$c = $this->backupConditions[$strength];
		}
	}
	
	//clear the conditions
	//$strength: strong,soft,values
	public function clearConditions($strength)
	{
		$c = &$this->getConditions($strength);
		$c = array();
	}
	
	//method to apply the validate conditions listed in the $this->strongConditions associative array
	//$queryType: indicates what set of validate conditions has to be considered (it's the key of the associative array)
	//$strength: strong,soft,values
	public function applyValidateConditions($queryType,$strength = 'strong', $checkValues = false)
	{
		$globalArrayToCheck = isset(Params::$arrayToValidate) ? Params::$arrayToValidate : $_POST;
		
		$arrayToCheck = $checkValues ? $this->values : $globalArrayToCheck;
		
		if ($strength === 'strong')
		{
			$validateObj = $this->_arrayStrongCheck;
			$conditions = $this->strongConditions;
			$errString = 'strongConditions';
		}
		else if ($strength === 'values')
		{
			$validateObj = $this->_arrayValuesCheck;
			$conditions = $this->valuesConditions;
			$errString = 'valuesConditions';
			$arrayToCheck = $this->values;
		}
		else
		{
			$validateObj = $this->_arraySoftCheck;
			$conditions = $this->softConditions;
			$errString = 'softConditions';
		}
		
		if (array_key_exists($queryType,$conditions))
		{
			if (!is_array($conditions[$queryType]))
			{
				throw new Exception('error in method <b>'.__METHOD__.'</b> : <b>'.$errString.'['.$queryType.']</b> has to be an associative array');
			}
			
			foreach ($conditions[$queryType] as $key => $values)
			{

				//personalized error string
				$altErrorString = null;

				//delete all the '+' chars
				$key = $this->dropStartChar($key,'+');
				
				if (strstr($values,'|'))
				{
					$temp = explode('|',$values);
					$altErrorString = "<div class='".Params::$errorStringClassName."'>".$temp[1]."</div>\n";
					$values = $temp[0];
				}
				
				$baseArgs = array($arrayToCheck,$values);
				
				if (strstr($key,'|'))
				{
					$funcArray = explode('|',$key);
					$funcName = $funcArray[0];
					array_shift($funcArray);
					$funcArgs = array_merge($baseArgs,$funcArray);
				}
				else
				{
					$funcName = $key;
					$funcArgs = $baseArgs;
				}

				if (!method_exists($validateObj,$funcName) or $funcName === 'checkGeneric')
				{
					throw new Exception('error in method '.__METHOD__.' :method "'.$funcName. '" not allowed in '.$errString);
				}
				if (!call_user_func_array(array($validateObj,$funcName),$funcArgs))
				{
					$this->notice .= (isset($altErrorString)) ? $altErrorString : $validateObj->errorString;
					
					foreach ($validateObj->errors as $field => $errors)
					{
						foreach ($errors as $error)
						{
							$errorString = $altErrorString ? $altErrorString : $error;
							$this->errors["Fields"][$field][] = strip_tags($errorString);
						}
					}
					
					$this->result = false;
					$this->queryResult = false;
					return false;
				}
			}
			return true;
		} else {
			return true;
		}
	}

	//apply, in sequence, the strong,soft and database conditions
	//$methodName: insert,update
	//$id: the id of the record. It is necessary for database conditions
	public function checkConditions($methodName,$id = null)
	{
		if ($this->applyValidateConditions($methodName,'strong'))
		{
			if (!$this->applySoftConditionsOnPost || $this->applyValidateConditions($methodName,'soft'))
			{
				if ($this->applyDatabaseConditions($methodName,$id))
				{
					return true;
				}
			}
		}
		return false;
	}

	//method that calls the function indicated in $this->submitNames. Ex: if $_POST['delAction'] is found, then the "del" method is called.
	public function updateTable($methodsList = '',$id = null)
	{
		//get the action array
		$actionArray = strcmp(Params::$actionArray,"POST") === 0 ? $_POST : $_REQUEST;
		
		$allowedMethodsArray = explode(',',$methodsList);
		$resultArray = array();
		$this->identifierValue = null;
		if (isset($id))
		{
			$this->identifierValue = (int)$id;
		}
		else if (isset($this->id) and ($this->id > 0))
		{
			$this->identifierValue = (int)$this->id;
		}
		else if (isset($actionArray[$this->identifierName]))
		{
			$this->identifierValue = (int)$actionArray[$this->identifierName];
		}
		foreach ($this->submitNames as $methodName => $submitName)
		{
			if (array_key_exists($submitName,$actionArray))
			{
				$this->submitName = $submitName;
				if (method_exists($this,$methodName))
				{
					if (strcmp($methodName,"insert") === 0)
					{
						$this->identifierValue = null;
					}
					//if the method is allowed
					if (in_array($methodName,$allowedMethodsArray))
					{
						if ($this->checkConditions($methodName,$this->identifierValue))
						{
							$this->notice = null;
							$this->errors = array();
							call_user_func_array(array($this,$methodName),array($this->identifierValue));
						}
					}
				} 
				else
				{
					throw new Exception('method <b>'.$methodName.'</b> not defined in class <b>'.__CLASS__.'</b>; error in method <b>'.__METHOD__.'</b>');
				}
				return; //only one cycle!
			}
		}
	}

	//method to build the array of popup objects
	public function popupBuild()
	{
		foreach ($this->_popupItemNames as $field => $itemName)
		{
// 			if (array_key_exists($field,$this->_where))
// 			{
			$fieldClean = str_replace('n!',null,$field);
			$itemNameClean = str_replace('n!',null,$itemName);
			$fieldClean = $this->dropStartChar($fieldClean,'-');
			$itemNameClean = $this->dropStartChar($itemNameClean,'-');
			
			//fields that have to be extracted
			$queryFields = ($fieldClean === $itemNameClean) ? $fieldClean : $fieldClean.','.$itemNameClean;
			
			$table = $this->getTableName($field);
			$this->popupArray[$field] = new Popup();
			
			$popupWhereClause = array_key_exists($field,$this->_popupWhere) ? $this->_popupWhere[$field] : null;
			
			$popupOrderBy = array_key_exists($field,$this->_popupOrderBy) ? $this->_popupOrderBy[$field] : null;
			
			$result = $this->db->select($table,$queryFields,$popupWhereClause,$fieldClean,$popupOrderBy);
			
			if ($result and $result !== false)
			{
				//get the label of the popup menu
				$label = array_key_exists($field,$this->_popupLabels) ? $this->_popupLabels[$field] : $table.' : '.$itemNameClean;
				$this->popupArray[$field]->name = $label;
				
				//get the table name
				$fieldTable = isset($result[0][$table][$fieldClean]) ? $table : Params::$aggregateKey;
				$itemNameTable = isset($result[0][$table][$itemNameClean]) ? $table : Params::$aggregateKey;
				
				foreach ($result as $row)
				{
					$this->popupArray[$field]->itemsValue[] = $row[$fieldTable][$fieldClean];
					
					if (array_key_exists($field,$this->_popupFunctions))
					{
						if (!function_exists($this->_popupFunctions[$field]))
						{
							throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$this->_popupFunctions[$field]. '</b> does not exist');
						}
						
						$tempName = call_user_func($this->_popupFunctions[$field],$row[$itemNameTable][$itemNameClean]);
					}
					else
					{
						$tempName = $row[$itemNameTable][$itemNameClean];
					}
					
					$this->popupArray[$field]->itemsName[] = $tempName;
				}
			}
// 			}
		}
	}


	//get the element before and after the current one
	//$key: the key of the self::$where array that indicates the field to be used in order to find out the records before and after
	//$fields: the fields that have to be extracted
	public function getNeighbours($key,$fields = '')
	{
		//backup of the values
		$tempWhere = $this->where;
		$tempLimit = $this->limit;
		$tempOrderBy = $this->orderBy;
		$this->limit = 1;
		//before
		if ((defined('NEW_WHERE_CLAUSE_STYLE') and NEW_WHERE_CLAUSE_STYLE) || Params::$newWhereClauseStyle)
		{
			unset($this->where[$key]);
			$this->where[" lt"] = array($key =>	$tempWhere[$key]);
		}
		else
		{
			$this->where[$key] = '<"'.$tempWhere[$key].'"';
		}
		$this->orderBy = $this->getTableName($key).'.'.$key.' DESC';
		$dataAfter = $this->getFields($fields);
		//after
		if ((defined('NEW_WHERE_CLAUSE_STYLE') and NEW_WHERE_CLAUSE_STYLE) || Params::$newWhereClauseStyle)
		{
			unset($this->where[" lt"]);
			$this->where[" gt"] = array($key =>	$tempWhere[$key]);
		}
		else
		{
			$this->where[$key] = '>"'.$tempWhere[$key].'"';
		}
		$this->orderBy = $this->getTableName($key).'.'.$key;
		$dataBefore = $this->getFields($fields);
		//restore the previous values
		$this->where = $tempWhere;
		$this->limit = $tempLimit;
		$this->orderBy = $tempOrderBy;
		$result[0] = isset($dataBefore[0]) ? $dataBefore[0] : null;
		$result[1] = isset($dataAfter[0]) ? $dataAfter[0] : null;
		return $result;
	}

	//set the $select property and return the current object
	public function select($fields = null)
	{
		$this->select = $fields;
		return $this;
	}

	//set the $convert property and return the current object
	public function convert($convert = true)
	{
		$this->convert = $convert;
		return $this;
	}
	
	//set the $from property and return the current object
	public function from($tables = null)
	{
		$this->from = isset($tables) ? $tables : $this->_tables;
		return $this;
	}
	
	//set the on property and return the current object
	public function on($joinClause = '-')
	{
		$this->on[] = $joinClause;
		$this->using[] = null;
		return $this;
	}

	//set the $using property and return the current object
	public function using($using = null)
	{
		$this->using[] = $using;
		$this->on[] = null;
		return $this;
	}
	
	//set the $join property and return the current object
	public function left($string = null)
	{
		if (is_array($string))
		{
			foreach ($string as $s)
			{
				$this->createJoin($s, "left");
			}
		}
		else
		{
			$this->join[] = "l:$string";
		}
		
		return $this;
		
// 		$this->join[] = "l:$string";
// 		return $this;
	}

	//set the $join property and return the current object
	public function right($string = null)
	{
		if (is_array($string))
		{
			foreach ($string as $s)
			{
				$this->createJoin($s, "right");
			}
		}
		else
		{
			$this->join[] = "r:$string";
		}
		
		return $this;
		
// 		$this->join[] = "r:$string";
// 		return $this;
	}

	//set the $join property and return the current object
	public function inner($string = null)
	{
		if (is_array($string))
		{
			foreach ($string as $s)
			{
				$this->createJoin($s, "inner");
			}
		}
		else
		{
			$this->join[] = "i:$string";
		}
		
		return $this;
	}
	
	public function createJoin($foreignKey, $type)
	{
		$relations = $this->relations();
		
		if (isset($relations[$foreignKey]))
		{
			$rel = $relations[$foreignKey];
			
			$modelString = $rel[1];
			
			$model = new $modelString();
			
			switch($rel[0])
			{
				case "HAS_MANY":
					$idChild = $rel[2];
					$childTable = $model->table();
					
					$this->$type($childTable)->on($childTable.".".$idChild."=".$this->_tables.".".$this->_idFields);
					break;
				case "BELONGS_TO":
					$idParent = $rel[2];
					$parentTable = $model->table();
					
					$this->$type($parentTable)->on($parentTable.".".$model->_idFields."=".$this->_tables.".".$idParent);
					break;
				case "MANY_TO_MANY":
					$idGroup = $rel[2];
					$groupTable = $model->table();
					
					$modelThroughString = $rel[3][0];
					$modelThrough = new $modelThroughString();
					$throughTable = $modelThrough->table();
					
					$idTableThrough = $rel[3][1];
					$idGroupThrough = $rel[3][2];
					
					$this->$type($throughTable)->on($this->_tables.".".$this->_idFields."=".$throughTable.".".$idTableThrough)->$type($groupTable)->on($throughTable.".".$idGroupThrough."=".$groupTable.".".$idGroup);
					
					break;
			}
		}
	}
	
	//set the $where property and return the current object
	public function where($where = array())
	{
		$this->where = $where;
		return $this;
	}

	//append the $where array to the ::where property and return the current object
	public function aWhere($where = array())
	{
		$this->appendWhereQueryClause($where);
		return $this;
	}
	
	public function sWhere($sWhere)
	{
		$this->sWhere[] = $sWhere;
		return $this;
	}
	
	//set the $groupBy property and return the current object
	public function groupBy($groupBy = null)
	{
		$this->groupBy = $groupBy;
		return $this;
	}

	//set the $orderBy property and return the current object
	public function orderBy($orderBy = null)
	{
		$this->orderBy = $orderBy;
		return $this;
	}

	//set the $limit property and return the current object
	public function limit($limit = null)
	{
		$this->limit = $limit;
		return $this;
	}

	//set the $listArray property
	public function toList($key, $value = null)
	{
		$this->listArray = array($key,$value);
		$this->toList = true;
		return $this;
	}
	
	public function page($pageNumber, $recordsPerPage = null)
	{
		$this->pageNumber = (int)$pageNumber;
		$this->recordsPerPage = isset($recordsPerPage) ? (int)$recordsPerPage : (int)self::$defaultRecordsPerPage;
		
		return $this;
	}
	/**
	* @brief set the process attribute to TRUE for future processing
	* 
	* @return string
	*/
	public function process()
	{
		$this->process = true;
		return $this;
	}
	
	/**
	* @brief return the processed DATA RESULT array
	* 
	* @param array $resultData the DATA RESULT coming from the query
	* @param bool $showTable if the RESULT DATA has the table name or not
	* 
	* @return array
	*/
	public function getProcessed(array $resultData, $showTable)
	{
		$processedResultData = array();
		
		if (count($resultData) > 0)
		{
			if ($showTable)
			{
				foreach ($resultData as $row)
				{
					$tablesList = array_keys($row);
					
					$temp = array();
					
					foreach ($tablesList as $table)
					{
						$temp[$table] = $this->processDataArray($row[$table]);
					}
					
					$processedResultData[] = $temp;
				}
			}
			else
			{
				foreach ($resultData as $row)
				{
					$processedResultData[] = $this->processDataArray($row);
				}
			}
		}
		
		return $processedResultData;
	}
	
	/**
	* @brief process an associative array
	* 
	* @param array the array that has to be processed
	* 
	* @return array
	*/
	public function processDataArray(array $data)
	{
		$processedData = array();
		
		foreach ($data as $k => $v)
		{
			if (strstr($k,'|'))
			{
				$filters = explode("|",$k);
				$key = array_shift($filters);
				
				$value = $v;
				
				foreach ($filters as $f)
				{
					$value = $this->callFunction($value, $f);
				}
				
				$processedData[$key] = $value;
			}
			else
			{
				$processedData[$k] = $v;
			}
		}
		
		return $processedData;
	}
	
	/**
	* @brief call function and return the result. The functions is looked for as method, than as STATIC method and finally as simple function
	* 
	* @param string the string the function has to process
	* @param string the function that has to be called
	* 
	* @return mixed
	*/
	public function callFunction($value, $function)
	{
		if (method_exists($this,$function))
		{
			return call_user_func(array($this, $function),$value);
		}
		else if (method_exists($this->_tables,$function))
		{
			return call_user_func(array($this->_tables, $function),$value);
		}
		else if (function_exists($function))
		{
			return call_user_func($function,$value);
		}
		else
		{
			throw new Exception('Error in <b>'.__CLASS__.'</b>: method/function <b>'.$function.'</b> does not exists.');
		}
	}
	
	//reset all the clauses of the select query
	public function clear()
	{
		$this->select = null;
		$this->where = array();
		$this->sWhere = array();
		$this->groupBy = null;
		$this->orderBy = null;
		$this->limit = null;
		$this->from = null;
		$this->on = array();
		$this->using = array();
		$this->join = array();
		$this->toList = false;
		$this->convert = false;
		$this->process = false;
		$this->pageNumber = 0;
		$this->recordsPerPage = 0;
		$this->numberOfRecords = 0;
		$this->numberOfPages = 0;
		return $this;
	}

	//save all the clauses of the select query
	public function save()
	{
		$tmp = array();
		
		$tmp["select"] = $this->select;
		$tmp["where"] = $this->where;
		$tmp["sWhere"] = $this->sWhere;
		$tmp["groupBy"] = $this->groupBy;
		$tmp["orderBy"] = $this->orderBy;
		$tmp["limit"] = $this->limit;
		$tmp["from"] = $this->from;
		$tmp["on"] = $this->on;
		$tmp["using"] = $this->using;
		$tmp["join"] = $this->join;
		$tmp["toList"] = $this->toList;
		$tmp["convert"] = $this->convert;
		$tmp["process"] = $this->process;
		$tmp["pageNumber"] = $this->pageNumber;
		$tmp["recordsPerPage"] = $this->recordsPerPage;
		$tmp["numberOfRecords"] = $this->numberOfRecords;
		$tmp["numberOfPages"] = $this->numberOfPages;
		
		$this->backupSelect[] = $tmp;
		return $this;
	}
	
	//restored all the saved clauses of the select query
	public function restore($deleteLastOne = false)
	{
		if (count($this->backupSelect) > 0)
		{
			if ($deleteLastOne)
			{
				$back = array_pop($this->backupSelect);
			}
			else
			{
				$back = $this->backupSelect[count($this->backupSelect)-1];
			}
			
			$this->select = $back["select"];
			$this->where = $back["where"];
			$this->sWhere = $back["sWhere"];
			$this->groupBy = $back["groupBy"];
			$this->orderBy = $back["orderBy"];
			$this->limit = $back["limit"];
			$this->from = $back["from"];
			$this->on = $back["on"];
			$this->using = $back["using"];
			$this->join = $back["join"];
			$this->toList = $back["toList"];
			$this->convert = $back["convert"];
			$this->process = $back["process"];
			$this->pageNumber = $back["pageNumber"];
			$this->recordsPerPage = $back["recordsPerPage"];
			$this->numberOfRecords = $back["numberOfRecords"];
			$this->numberOfPages = $back["numberOfPages"];
		}
		
		return $this;
	}
	
// 	//restored all the saved clauses of the select query
// 	public function restore()
// 	{
// 		if (count($this->backupSelect) > 0)
// 		{
// 			$back = array_pop($this->backupSelect);
// 			
// 			$this->select = $back["select"];
// 			$this->where = $back["where"];
// 			$this->sWhere = $back["sWhere"];
// 			$this->groupBy = $back["groupBy"];
// 			$this->orderBy = $back["orderBy"];
// 			$this->limit = $back["limit"];
// 			$this->from = $back["from"];
// 			$this->on = $back["on"];
// 			$this->using = $back["using"];
// 			$this->join = $back["join"];
// 			$this->toList = $back["toList"];
// 			$this->convert = $back["convert"];
// 		}
// 		
// 		return $this;
// 	}
	
	public function getSelectArrayFromEnumField($fieldName)
	{
		$types = $this->db->getTypes($this->_tables, $fieldName, true, true);
		
		if ($types)
		{
			if (preg_match('/^('.implode("|",$this->db->getEnumTypes()).')\((.*?)\)/i',$types[$fieldName],$matches))
			{
				return $this->getSelectArrayFromEnumValues($matches[1], $matches[2]);
			}
		}
	}
	
	public function getSelectArrayFromEnumValues($enumFunc, $enumValues)
	{
		$enumFunc = strtolower($enumFunc);
		
		$temp = array();
		$strings = explode(",",$enumValues);
		for ($i=0;$i<count($strings);$i++)
		{
			$val = trim($strings[$i],"'\"");
			
			if (isset($this->_conversionFromDbObject) and method_exists($this->_conversionFromDbObject, $enumFunc))
			{
				$temp[$val] = call_user_func(array($this->_conversionFromDbObject, $enumFunc), $val);
			}
			else
			{
				$temp[$val] = $val;
			}
		}
		return $temp;
	}
	
	//initialize and populate the ::form property (reference to a Form_Form object)
	public function setForm($defAction = null, $defSubmit = array(), $defMethod = 'POST', $defEnctype = null)
	{
		if (isset($this->id) and isset($this->controller) and isset($this->action))
		{
			$application = isset($this->application) ? $this->application."/" : "";
			
			$defAction = $application.$this->controller."/".$this->action."/".$this->id;
			
			$queryType = $this->id > 0 ? "update" : "insert";
			$submitName = "__SUBMITTED__";
			$submitValue = $this->strings->gtext('Save');
			
			$defSubmit = array($submitName => $submitValue);
		}
		
		if (isset($this->formStruct))
		{
			$action = array_key_exists('action',$this->formStruct) ? $this->formStruct['action'] : $defAction;
			$submit = array_key_exists('submit',$this->formStruct) ? $this->formStruct['submit'] : $defSubmit;
			$entries = array_key_exists('entries',$this->formStruct) ? $this->formStruct['entries'] : null;
			$method = array_key_exists('post',$this->formStruct) ? $this->formStruct['post'] : $defMethod;
			$enctype = array_key_exists('enctype',$this->formStruct) ? $this->formStruct['enctype'] : $defEnctype;
			
			$this->form = new Form_Form($action,$submit,$method,$enctype);
			
			//get the entries from DB definition
			$types = $this->db->getTypes($this->_tables, "*", true, true);
			
			foreach ($types as $field => $type)
			{
				$entryType = "InputText";
				$classType = "varchar_input";
				$options = null;
				
				if (strcmp($field, $this->_idFieldsArray[0]) === 0)
				{
					$entryType = "Hidden";
				}
				else if (preg_match('/^('.implode("|",$this->db->getTextTypes()).')/i',$type,$matches))
				{
					$entryType = "Textarea";
					$classType = "text_input";
				}
				else if (preg_match('/^('.implode("|",$this->db->getDateTypes()).')/i',$type,$matches))
				{
					$classType = "date_input";
				}
				else if (preg_match('/^('.implode("|",$this->db->getEnumTypes()).')\((.*?)\)/i',$type,$matches))
				{
					$entryType = "Select";
					$classType = "select_input";
					$options = $this->getSelectArrayFromEnumValues($matches[1], $matches[2]);
				}
				
				if (array_key_exists($field,$entries))
				{
					if (!array_key_exists("type",$entries[$field]))
					{
						$entries[$field]["type"] = $entryType;
					}
					
					if ($entryType === "Select" and !array_key_exists("options",$entries[$field]))
					{
						$entries[$field]["options"] = $options;
						$entries[$field]["reverse"] = "yes";
					}
					
					if (!array_key_exists("className",$entries[$field]))
					{
						$entries[$field]["className"] = $classType." ".Form_Form::$defaultEntryAttributes['className'];
					}
				}
				else
				{
					$entries[$field]["type"] = $entryType;
						
					if ($entryType === "Select")
					{
						$entries[$field]["options"] = $options;
						$entries[$field]["reverse"] = "yes";
					}
						
					$entries[$field]["className"] = $classType." ".Form_Form::$defaultEntryAttributes['className'];
				}
			}
			
			if (isset($entries))
			{
				$this->form->setEntries($entries);
			}
			
			$copy = $this->form->entry;
			
			foreach ($copy as $name => $entry)
			{
				if (strcmp($entry->type,'Select') === 0 and isset($entry->options))
				{
					if (!is_array($entry->options))
					{
						if (strstr($entry->options,'foreign::'))
						{
							$elements = explode('::',$entry->options);
							
							for ($i = 0; $i < count($elements); $i++)
							{
								if (strcmp($elements[$i],'--') === 0) $elements[$i] = null;
							}
							//send the query
							array_shift($elements);
							$resultSet = call_user_func_array(array($this->db,'select'),$elements);

							$single = true;
							
							if (strstr($elements[1],','))
							{
								$args = explode(',',$elements[1]);
								//add the table name
								$args[0] = $elements[0].'.'.$args[0];
								$args[1] = $elements[0].'.'.$args[1];
								//associative array
								$single = false;
							}
							else
							{
								$args[0] = $elements[0].'.'.$elements[1];
								$args[1] = null;
							}
							
							$list = $this->getList($resultSet,$args[0],$args[1]);
							
							$this->form->entry[$name]->options = ($single) ? implode(',',array_values($list)) : $list;
						}
					}
				}
			}
			
		}
		else
		{
			$this->form = new Form_Form($defAction,$defSubmit,$defMethod,$defEnctype);
		}
	}

	//get a list from a result set
	//$resultSet: the result set coming from a select query
	public function getList($resultSet, $key, $value = null)
	{
		$list = array();
		
		if (strstr($key,'.'))
		{
			$arr = explode('.',$key);
			$keyTable = $arr[0];
			$keyField = $arr[1];
		}
		else
		{
			$keyTable = $this->_tablesArray[0];
			$keyField = $key;
		}
				
		if (!isset($value))
		{
			foreach ($resultSet as $row)
			{
				$list[] = $row[$keyTable][$keyField];
			}
		}
		else
		{
			if (strstr($value,'.'))
			{
				$arr = explode('.',$value);
				$valueTable = $arr[0];
				$valueField = $arr[1];
			}
			else
			{
				$valueTable = $this->_tablesArray[0];
				$valueField = $value;
			}
			
			foreach ($resultSet as $row)
			{
				$list[$row[$keyTable][$keyField]] = $row[$valueTable][$valueField];
			}
			
		}
		return $list;
	}

	// Ad an error
	// $field: the field the error is applied to
	// $error: the error
	public function addError($field, $error)
	{
		$this->errors["Fields"][$field][] = $error;
	}
	
	// 	Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT). 
	public function lastId()
	{
		return $this->db->lastId();
	}

	//send a free query
	public function query($query)
	{
		return $this->db->query($query);
	}
	
	//the text of the error message from previous MySQL operation
	public function getError()
	{
		return $this->db->getError();
	}

	//the numerical value of the error message from previous MySQL operation
	public function getErrno()
	{
		return $this->db->getErrno();
	}

	//define the abstract method to get the value of the record $id of the main table
	abstract public function selectId($id);
	
	//define the abstract method to get the fields from the tables
	abstract public function getFields();

}
