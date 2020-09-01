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

class Model_Map extends Model_Base {

// 	//many to many model

	public $printAssError = 'yes'; //'yes': print association error if the association/dissociation is already present. 'no': doen't print association error
	public $boxIdentifierName = 'boxIdentifier';//key of the value of the $_POST array that represent the id of the Box that we want to associate with the Item having the id $_POST[$this->identifierName]
	protected $_boxIdentifierValue = null; //the id of the box that has to be associated with the item

	public function __construct()
	{
		//add some submit names (method => form_submit_value)
		$this->submitNames['associate'] = 'associateAction';
		$this->submitNames['dissociate'] = 'dissociateAction';
		//add the allowed methods 
		$this->_allowedDbMethods[] = 'associate';
		$this->_allowedDbMethods[] = 'dissociate';
		parent::__construct();
	}

	public function createMapWhere($choice)
	{ //create the where join clause
		//$choice=(first,last,all)
		$first = $this->_tablesArray[0].'.'.$this->_idFieldsArray[0].'='.$this->_tablesArray[2].'.'.$this->_idFieldsArray[0];
		$last = $this->_tablesArray[1].'.'.$this->_idFieldsArray[1].'='.$this->_tablesArray[2].'.'.$this->_idFieldsArray[1];
		switch ($choice) {
			case 'first':
				return $first;
				break;
			case 'last':
				return $last;
				break;
			case 'all':
				return $first. ' and '.$last;
				break;
		}
	}

	//check if a join query is necessary or not
	//$val: 0 or 1 (items or boxes)
	//$whereClauseLevel: array containing the field=>value statements of the where clause. If $whereClause = null than $this->where is considered
	public function checkIfJoinNeeded($val, $whereClauseLevel = null)
	{
		$whereClause = isset($whereClauseLevel) ? $whereClauseLevel : $this->where;
		
		foreach ($whereClause as $field => $value)
		{
			if (is_array($value))
			{
				if ($this->checkIfJoinNeeded($val, $value) === true) return true;
			}
			else
			{
				if (strcmp($this->getTableName($field),$this->_tablesArray[$val]) !== 0)
				{
					if (strcmp($value,Params::$nullQueryValue) !== 0 or (Params::$nullQueryValue === false)) return true;
				}
			}
		}
		//return false if no where clause has been defined
		return false;
	}

	//method to create the where clause and the list of tables of the select query
	public function mapQueryElements($val)
	{
// 		$val = $element === 'Items' ? 0 : 1;
		$tables = $this->_tablesArray[$val];
		$where = null;
		$fields = $this->_tablesArray[$val].'.*';
		
		if ($this->checkIfJoinNeeded($val))
		{
			$tables = $this->_tables;
			$fields = $this->_tablesArray[$val].'.*';
			$wherePlus = $this->createWhereClause();
			$wherePlus = isset($wherePlus) ? ' AND ' . $wherePlus : null;
			$where = $this->createMapWhere('all') . $wherePlus;
		}
		else
		{
			$where = $this->createWhereClause();
		}
		
		return array('tables' => $tables,'where' => $where,'fields' => $fields);
	}

	//$element: Items or Boxes.
	//get all Item or Boxes
	public function getAll($element = 'Items')
	{
		return $this->getFields('',$element);
	}
	
	//method to get the values of the selected fields
	//$fields: the fields that have to be excracted from the tableName
	public function getFields($fields = '',$element = 'Items')
	{
		//get all Item or Boxes
		if ((strcmp($element,'Items') !== 0) and (strcmp($element,'Boxes') !== 0))
		{
			throw new Exception('<b>"'.$element. '"</b> argument not allowed in <b>'.__METHOD__.'</b> method');
		}		
		$val = $element === 'Items' ? 0 : 1;
		
		$elements = $this->mapQueryElements($val);
		
		$queryFields = (strcmp($fields,'') === 0) ? $elements['fields'] : $fields;
		
		return $row = $this->db->select($elements['tables'],$queryFields,$elements['where'],$this->groupBy,$this->orderBy,$this->limit);

	}

	public function send($element = 'Items')
	{
		$table = $this->getFields($this->select, $element);
		
		if ($this->toList)
		{
			$key = $this->listArray[0];
			$value = isset($this->listArray[1]) ? $this->listArray[1] : null;
			$this->toList = false;
			return $this->getList($table, $key, $value);
		}
		else
		{
			return $table;
		}
	}
	
	//the fields that have to be extracted from the table
	public function getTable($fields = null)
	{
		return isset($fields) ? $this->getFields($fields) : $this->getAll();
	}

	//select the values of a specified record
	//$id: the id (primary key) of the record
	//$fields: the comma separated list of fields that have to be extracted
	public function selectId($id,$fields = null)
	{
		$id = (int)$id;

		$tempWhere = $this->where;
		$this->setWhereQueryClause(array($this->_idFieldsArray[0] => $id));

		if (isset($fields))
		{
			$values = $this->getFields($fields,'Items');
		}
		else
		{
			$values = $this->getAll('Items');
		}

		$this->where = $tempWhere;
		
		return (count($values) > 0) ? $values[0][$this->_tablesArray[0]] : array();
	}

	//get the number of records (items or boxes)
	public function recordNumber($element)
	{
		$val = $element === 'Items' ? 0 : 1;

		$elements = $this->mapQueryElements($val);
		return $this->db->get_num_rows($elements['tables'],$elements['where'],$this->groupBy);
	}

	//get the number of records (only items)
	public function rowNumber()
	{
		return $this->recordNumber('Items');
	}

	public function getMax($field)
	{
		$elements = $this->mapQueryElements(0);
		return $this->db->getMax($elements['tables'],$field,$elements['where'],$this->groupBy);
	}

	public function getMin($field)
	{
		$elements = $this->mapQueryElements(0);
		return $this->db->getMin($elements['tables'],$field,$elements['where'],$this->groupBy);
	}

	public function getSum($field)
	{
		$elements = $this->mapQueryElements(0);
		return $this->db->getSum($elements['tables'],$field,$elements['where'],$this->groupBy);
	}

	public function getAvg($field)
	{
		$elements = $this->mapQueryElements(0);
		return $this->db->getAvg($elements['tables'],$field,$elements['where'],$this->groupBy);
	}
	
	//check if the table has the field $field equal to $value
	public function has($field,$value)
	{
		$elements = $this->mapQueryElements(0);
		return $this->db->recordExists($elements['tables'],$field,$value,$elements['where'],$this->groupBy);
	}
	
	//associate an item with a box
	//$idItem : name of the field of the Items table, $idGroup : name of the field of the Boxes table
	public function associate($idItem = null,$idGroup = null)
	{
		$this->queryResult = false;
		if (isset($idItem) and isset($idGroup))
		{
			$idItem = (int)$idItem;
			$idGroup = (int)$idGroup;
			$values = array($idItem,$idGroup); //values relative to the fields $this->_idFields
			$var = $this->checkAssociation($idItem,$idGroup);
			if (!$var)
			{
				$result = $this->db->insert($this->_tablesArray[2],$this->_idFields,$values);
				$this->setNotice($result);
				return $result;
			}
			else
			{
				if (strcmp($this->printAssError,'yes') === 0) $this->notice = $this->_resultString->getString('linked');
				$this->result = false;
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-id');
			$this->result = false;
		}
		return false;
	}

	//associate an item with a box
	//$idItem : name of the field of the Items table, $idGroup : name of the field of the Boxes table
	public function dissociate($idItem = null,$idGroup = null)
	{
		$this->queryResult = false;
		if (isset($idItem) and isset($idGroup))
		{
			$idItem = (int)$idItem;
			$idGroup = (int)$idGroup;
			$var = $this->checkAssociation($idItem,$idGroup);
			if ($var)
			{
				$result = $this->db->del($this->_tablesArray[2],$this->_idFieldsArray[0].'='.$idItem.' and '.$this->_idFieldsArray[1].'='.$idGroup);
				$this->setNotice($result);
				return $result;
			}
			else
			{
				if (strcmp($this->printAssError,'yes') === 0) $this->notice = $this->_resultString->getString('not-linked');
				$this->result = false;
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-id');
			$this->result = false;
		}
		return false;
	}

	public function checkAssociation($idItem,$idGroup)
	{
		$idItem = (int)$idItem;
		$idGroup = (int)$idGroup;
		$numRow = $this->db->get_num_rows($this->_tablesArray[2],$this->_idFieldsArray[0].'='.$idItem.' and '.$this->_idFieldsArray[1].'='.$idGroup);
		if ($numRow === 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//check what items are associate to a box
	//itemsArray:array of items to check
	public function checkAssociationDeep($itemsArray)
	{
		$associatedItems = array();
		$itemsArray = is_array($itemsArray) ? array_values($itemsArray) : array($itemsArray);
		foreach ($itemsArray as $item) {
			if ($this->db->recordExists($this->_tablesArray[2],$this->_idFieldsArray[0],$item))
			{
				$associatedItems[] = $item;
			}
		}
		return $associatedItems;
	}

	//method to call the delete query (overriding of the del method of Model.php)
	//check the referential integrity
	public function del($id = null, $whereClause = null)
	{
		$this->queryResult = false;
		
		if (isset($whereClause))
		{
			return parent::del(null,$whereClause);
		}
		else
		{
			if ($this->_onDelete === 'check')
			{
				if ($this->db->recordExists($this->_tablesArray[2],$this->_idFieldsArray[0],(int)$id))
				{
					$this->notice = $this->_resultString->getString('associate');
					$this->identifierValue = null;
					$this->result = false;
				}
				else
				{
					return parent::del((int)$id);
				}
			}
			else if ($this->_onDelete === 'nocheck')
			{
				return parent::del((int)$id);
			}
		}
		return false;
	}

	//override of the updateTable method of the parent class
	//method that calls the function indicated in $this->submitNames. Ex: if $_POST['delAction'] is found, then the "del" method is called.
	public function updateTable($methodsList = '',$id = null)
	{
		$allowedMethodsArray = explode(',',$methodsList);
		$resultArray = array();
		$this->identifierValue = null;
		if (isset($id))
		{
			$this->identifierValue = (int)$id;
		}
		else if (isset($_POST[$this->identifierName]))
		{
			$this->identifierValue = (int)$_POST[$this->identifierName];
		}
		foreach ($this->submitNames as $methodName => $submitName) {
			if (array_key_exists($submitName,$_POST))
			{
				$this->submitName = $submitName;
				if (method_exists($this,$methodName))
				{
					if (in_array($methodName,$allowedMethodsArray))
					{
						if ($this->checkConditions($methodName,$this->identifierValue))
						{
							$this->notice = null;
							$methodArray = array('associate','dissociate');
							if (in_array($methodName,$methodArray))
							{
								$this->_boxIdentifierValue = null;
								if (isset($_POST[$this->boxIdentifierName]))
								{
									$this->_boxIdentifierValue = (int)$_POST[$this->boxIdentifierName];
								}
								call_user_func_array(array($this,$methodName),array($this->identifierValue,$this->_boxIdentifierValue));
							}
							else
							{
								call_user_func_array(array($this,$methodName),array($this->identifierValue));
							}
						}
					}
				}
				else
				{
					throw new Exception('method "'.$methodName. '" not defined in class '.__CLASS__.'; error in method '.__METHOD__);
				}
				return; //only one cycle!
			}
		}
	}

	//method to obtain one columns from the tables $this->_tablesArray as an associative array
	//$valueField: the column that have to be extracted (array_values of the resulting associative array), $keyField: the column that have to play the role of array_keys
	//$valueField = field:table, $keyField = field:table
	public function getFieldArray($valueField,$keyField = null, $groupBy = null, $orderBy = null, $limit = null)
	{

		$keyField = isset($keyField) ? $keyField : $valueField;
		$valueFieldArray = explode(':',$valueField);
		$keyFieldArray = explode(':',$keyField);

		$keyFieldTable = $keyFieldArray[0];
		$valueFieldTable = $valueFieldArray[0];

		$keyFieldName = $keyFieldArray[1];
		$valueFieldName = $valueFieldArray[1];

		$fields = implode('.',$keyFieldArray) . ',' . implode('.',$valueFieldArray);

		$temp = $this->where; //save the $this->where array
		$this->where = array();

		$val = array_search($keyFieldTable,$this->_tablesArray);

		if (strcmp($keyFieldTable,$valueFieldTable) !== 0)
		{
			throw new Exception("the tables '$valueFieldTable' and '$keyFieldTable' do not match in ".__METHOD__);
		}

		if ($val === false or !in_array($val,array(0,1)))
		{
			throw new Exception("the table '$keyFieldTable' is not allowed in ".__METHOD__);
		}

		$elements = $this->mapQueryElements($val);

		$table = $this->db->select($elements['tables'],$fields,$elements['where'],$groupBy,$orderBy,$limit);
		$this->where = $temp;

		$returnArray = array();
		foreach ($table as $record) {
			$returnArray[$record[$keyFieldTable][$keyFieldName]] = $record[$valueFieldTable][$valueFieldName];
		}

		return $returnArray;

	}

}
