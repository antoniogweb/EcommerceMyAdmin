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

class Model_Tree extends Model_Base {

	public function __construct() {
		parent::__construct();
	}
	
	public function getSWhereClause()
	{
		$sWhereArray = array();
		
		if (DATABASE_TYPE === 'PDOMysql' || DATABASE_TYPE === 'PDOMssql')
		{
			foreach ($this->sWhere as $sWhere)
			{
				if (is_array($sWhere))
				{
					$sWhereArray[] = $sWhere[0];
					$this->bindedValues = array_merge($this->bindedValues,$sWhere[1]);
				}
				else
				{
					$sWhereArray[] = $sWhere;
				}
			}
		}
		else
			$sWhereArray = $this->sWhere;
		
		return implode(" AND ", $sWhereArray);
	}
	
	//method to create the where clause and the list of tables and fields of the select query
	//$tableName: the table name ($this->_tablesArray)
	//$choice:all->all the tables in $this->_arrayTables,  other value->only the table of $this->_arrayTables ad index $index
	//return: $elements = array('tables'=>$tables,'where'=>$where,'fields'=>$fields)
	public function treeQueryElements($tableName,$choice = 'all')
	{
		$this->bindedValues = array();
		if (DATABASE_TYPE === 'PDOMysql' || DATABASE_TYPE === 'PDOMssql')
			$where = $this->createWhereClausePDO();
		else
			$where = $this->createWhereClause();
		
		if (isset($where))
		{
			if (!empty($this->sWhere))
			{
// 				$sWhere = implode(" AND ", $this->sWhere);
				$sWhere = $this->getSWhereClause();
				$where .= " AND " . $sWhere;
			}
		}
		else if (!empty($this->sWhere))
		{
// 			$sWhere = implode(" AND ", $this->sWhere);
			$sWhere = $this->getSWhereClause();
			$where = $sWhere;
		}

		$tables = isset($this->from) ? $this->from : $tableName;
		
		if (DATABASE_TYPE === 'PDOMssql')
			$fields = "[".get_class($this)."]";
		else
			$fields = $tableName.".*";
		
		$on = $this->on;
		
		return array('tables' => $tables,'where' => $where, 'fields' => $fields, 'on' => $on, 'binded' => $this->bindedValues);
	}


	//method to obtain the values of the whole tree
	//$choice:all->all the tables in $this->_arrayTables,  other value->only the table of $this->_arrayTables ad index $index
	public function getAll($choice = 'all') {
		return $this->getFields('',$choice);
	}
	
	public function replaceQueryFields($match)
	{
		$modelString = $match[2];
		
		$model = new $modelString();
		$processedFields = $model->getProcessedSelectedFieldsInQuery();
		return implode(",",$processedFields);
	}
	
	//method to get the values of the selected fields
	//it walks the tree by means of a join query
	//$fields: the fields that have to be excracted from the tableName
	public function getFields($fields = '',$choice = 'all', $showTable = true)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0],$choice);

		$queryFields = (strcmp($fields,'') === 0) ? $elements['fields'] : $fields;
		
		$queryFields = preg_replace_callback('/(\[)([a-zA-Z0-9]{1,})(\])/', array($this, 'replaceQueryFields') ,$queryFields);
		
		//if pagination active
		if ($this->recordsPerPage > 0)
		{
			$this->numberOfRecords = $this->db->get_num_rows($elements['tables'],$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join,$elements['binded']);
			
			$this->numberOfPages = (($this->numberOfRecords%$this->recordsPerPage)===0) ? (int) ($this->numberOfRecords/$this->recordsPerPage) : ((int) ($this->numberOfRecords/$this->recordsPerPage))+1;
			
			$start=(($this->pageNumber-1)*$this->recordsPerPage);
			
			$this->limit = "$start, ".$this->recordsPerPage;
		}
		
		$row = $this->db->select($elements['tables'],$queryFields,$elements['where'],$this->groupBy,$this->orderBy,$this->limit,$elements['on'],$this->using,$this->join, $showTable, $elements['binded']);
		
		//process the result data
		if ($this->process)
		{
			$row = $this->getProcessed($row, $showTable);
		}
		
		//convert from MySQL values
		if ($this->convert and $showTable)
		{
			if (count($row) > 0)
			{
				$types = array();
				
				$tablesList = array_keys($row[0]);
				
				foreach ($tablesList as $table)
				{
					if ($table !== Params::$aggregateKey)
					{
						$types[$table] = $this->db->getTypes($table, "*", false, true);
					}
				}
				
				for ($i=0;$i< count ($row); $i++)
				{
					foreach ($tablesList as $table)
					{
						$row[$i][$table] = isset($types[$table]) ?  $this->convertFromMysqlT($types[$table], $row[$i][$table]) : $row[$i][$table];
					}
				}
			}
		}
		
		return $row;
	}

	public function send($showTable = true)
	{
		$table = $this->getFields($this->select,"all",$showTable);
		
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

	//call the getAll method with $tableName = $this->_tablesArray[0]
	//the fields that have to be extracted from the table
	public function getTable($fields = null) {
		return isset($fields) ? $this->getFields($fields) : $this->getAll();
	}

	//select the values of a specified record
	//$id: the id (primary key) of the record
	//$fields: the comma separated list of fields that have to be extracted
	public function selectId($id,$fields = null) {
	
		$this->save()->clear()->setWhereQueryClause(array($this->_idFieldsArray[0] => (int)$id));
		
		$this->using = array();
		
		if (isset($fields))
		{
			$values = $this->getFields($fields,'other');
		}
		else
		{
			$values = $this->getFields("",'other');
// 			$values = $this->getAll('other');
		}
		
		$this->restore(true);
		
		return (count($values) > 0) ? $values[0][$this->_tablesArray[0]] : array();
		
	}

	//get the number of records ()
	//the number of records of the table $tableName is returned
	public function rowNumber() {
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->get_num_rows($elements['tables'],$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded'],"distinct ".$this->_tables.".".$this->_idFields);
	}
	
	public function getMax($field)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->getMax($elements['tables'],$field,$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded']);
	}
	
	public function getMin($field)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->getMin($elements['tables'],$field,$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded']);
	}
	
	public function getSum($field)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->getSum($elements['tables'],$field,$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded']);
	}

	public function getAvg($field)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->getAvg($elements['tables'],$field,$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded']);
	}
	
	//check if the table has the field $field equal to $value
	public function has($field,$value)
	{
		$elements = $this->treeQueryElements($this->_tablesArray[0]);
		return $this->db->recordExists($elements['tables'],$field,$value,$elements['where'],$this->groupBy,$elements['on'],$this->using,$this->join, $elements['binded']);
	}
	
	//check referential integrity during delete
	public function checkOnDeleteIntegrity($id = null, $whereClause = null)
	{
		$result = true;
		
// 		$this->setForeignKeys();
		
		if (count($this->foreignKeys) > 0)
		{
			foreach ($this->foreignKeys as $f)
			{
				if (preg_match('/^(.*?)\s(parent of)\s(.*?)\((.*?)\)(\s(on delete)\s(cascade|restrict)\s\((.*?)\))?$/i',$f,$matches))
				{
					$this->save();
					
					$parentKey = $matches[1];
					$childModel = $matches[3];
					$childField = $matches[4];
					
					if (isset($whereClause))
					{
						$this->clear()->sWhere($whereClause);
					}
					else
					{
						$this->clear()->where(array($this->_idFields=>(int)$id));
					}
					
					$keys = sanitizeDbDeep($this->toList($parentKey)->send());
					$this->restore(true);
					
					if (count($keys) > 0)
					{
						$child = new $childModel();
						
						if ((defined('NEW_WHERE_CLAUSE_STYLE') and NEW_WHERE_CLAUSE_STYLE) || Params::$newWhereClauseStyle)
						{
							$childrenIds = $child->clear()->where(array(
								"in" => array($child->_tables.".".$childField => $keys),
							))->toList($child->getPrimaryKey())->send();
						}
						else
						{
							$childrenIds = $child->clear()->where(array($child->_tables.".".$childField=>"in('".implode("','",$keys)."')"))->toList($child->getPrimaryKey())->send();
						}
						
						if (count($childrenIds) > 0)
						{
							if (isset($matches[7]) and strcmp($matches[7],"cascade") === 0)
							{
								foreach ($childrenIds as $childId)
								{
									$child->del((int)$childId);
								}

								if (strcmp($matches[8],"") !== 0)
								{
									$this->notice .= "<div class='".Params::$infoStringClassName."'>".$matches[8]."</div>";
									
									$this->errors["Query"][] = strip_tags($matches[8]);
								}
							}
							else
							{
								$errorString = isset($matches[8]) ? "<div class='".Params::$errorStringClassName."'>".$matches[8]."</div>" : $this->_resultString->getString('associate');
								
								$this->notice .= $errorString;
								
								$this->errors["Query"][] = strip_tags($errorString);
								
								return false;
							}
						}
					}

				}
			}
		}
		
		return $result;
	}
	
	//check referential integrity during insert or update
	public function checkOnUpdateIntegrity($queryType)
	{
		$result = true;
		
// 		$this->setForeignKeys();
		
		if (count($this->foreignKeys) > 0)
		{
			foreach ($this->foreignKeys as $f)
			{
				if (preg_match('/^(.*?)\s(child of)\s(.*?)\((.*?)\)(\s(on update)\s(restrict)\s\((.*?)\))?$/i',$f,$matches))
				{
					$childKey = $matches[1];
					$ParentModel = $matches[3];
					$ParentField = $matches[4];
					
					$notice = isset($matches[8]) ? "<div class='".Params::$errorStringClassName."'>".$matches[8]."</div>" : "";
					
					if (array_key_exists($childKey,$this->values))
					{
						$parent = new $ParentModel();
						$res = $parent->clear()->where(array($ParentField=>sanitizeDb($this->values[$childKey])))->send();
						
						if (count($res) === 0)
						{
							$this->notice .= $notice;
							
							$this->errors["Query"][] = strip_tags($notice);
							
							$this->result = false;
							$result = false;
						}
					}
					else if ($queryType === "insert")
					{
						$this->notice .= $notice;
						
						$this->errors["Query"][] = strip_tags($notice);
						
						$this->result = false;
						$result = false;
					}
				}
			}
		}
		
		return $result;
	}
	
	//get the first extracted full record
	public function record()
	{
		$res = $this->getFields($this->select);
		
		if (count($res) > 0)
		{
			return $res[0][$this->_tables];
		}
		return array();
	}
	
	//get a single field from the first extracted record
	public function field($fieldName)
	{
		$res = $this->save()->select($fieldName)->send();
		$this->restore(true);
		
		$tableName = $this->_tables;
		
		//check if the table name is already in the $fieldName
		if ($this->hasTableName($fieldName))
		{
			$temp = explode(".",$fieldName);
			$tableName = $temp[0];
			$fieldName = $temp[1];
		}
		
		if (count($res) > 0 and isset($res[0][$tableName][$fieldName]))
		{
			return $res[0][$tableName][$fieldName];
		}
		return "";
	}
	
	//get the types of the fields in $this->values
	public function getTypes($full = false)
	{
		return $types = $this->db->getTypes($this->_tables,implode(",",array_keys($this->values)),$full);
	}
	
	//automatically set the values conditions
	public function setValuesConditionsFromDbFields($queryType)
	{
		$fields = array_keys($this->values);
		$fieldsAsString = implode(",",$fields);
		
		if (!Params::$setValuesConditionsFromDbTableStruct)
		{
			return true;
		}
		
		$types = $this->getTypes(true);
		$fieldKeys = $this->db->getKeys($this->_tables,$fieldsAsString,true,false);
		$nullKeys = $this->db->getFieldsFeature("Null",$this->_tables,$fieldsAsString,true,false);
		
		if (count($this->values) > 0)
		{
			if (!$types)
			{
				$errorString = $this->_resultString->getString('not-existing-fields');
				
				$this->notice .= $errorString;
				
				$this->errors["Query"][] = strip_tags($errorString);
				
				$this->result = false;
				return false;
			}
			else
			{
				$this->saveConditions("values");
				$this->saveConditions("soft");
				$this->saveConditions("database");
				
				if (Params::$setValuesConditionsFromDbTableStruct)
				{
					foreach ($types as $index => $t)
					{
						if (!in_array("char",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getCharTypes()).')\(([0-9]*?)\)$/i',$t,$matches))
						{
							$this->addValuesCondition($queryType,'checkLength|'.$matches[2],$fields[$index]);
						}
						else if (!in_array("integer",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getIntegerTypes()).')/i',$t,$matches))
						{
							$this->addValuesCondition($queryType,'checkInteger',$fields[$index]);
						}
						else if (!in_array("float",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getFloatTypes()).')$/i',$t,$matches))
						{
							$this->addValuesCondition($queryType,'checkNumeric',$fields[$index]);
						}
						else if (!in_array("date",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getDateTypes()).')$/i',$t,$matches))
						{
							if (isset($nullKeys[$index]) && strcmp(strtolower($nullKeys[$index]),"yes") === 0)
								$this->addSoftCondition($queryType,'checkIsoDate',$fields[$index]);
							else
								$this->addValuesCondition($queryType,'checkIsoDate',$fields[$index]);
						}
						else if (!in_array("enum",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getEnumTypes()).')\((.*?)\)$/i',$t,$matches))
						{
							$temp = array();
							$strings = explode(",",$matches[2]);
							for ($i=0;$i<count($strings);$i++)
							{
								$temp[] = trim($strings[$i],"'\"");
							}
							$this->addValuesCondition($queryType,'checkIsStrings|'.implode(",",$temp),$fields[$index]);
						}
						else if (!in_array("decimal",Params::$doNotAutomaticallySetValuesConditionsForTheseTypes) and preg_match('/^('.implode("|",$this->db->getDecimalTypes()).')\((.*?)\)$/i',$t,$matches))
						{
							$this->addValuesCondition($queryType,'checkDecimal|'.$matches[2],$fields[$index]);
						}
					}
					
					//set unique conditions
					foreach ($fieldKeys as $index => $fk)
					{
						if (preg_match('/^('.implode("|",$this->db->getUniqueIndexStrings()).')$/i',$fk,$matches))
						{
							if ($queryType === "insert")
							{
								$this->addDatabaseCondition($queryType,'checkUnique',$fields[$index]);
							}
							else
							{
								$this->addDatabaseCondition($queryType,'checkUniqueCompl',$fields[$index]);
							}
						}
					}
				}
				
				foreach (Params::$valuesConditionsFromFormatsOfFieldsNames as $regExpr => $function)
				{
					foreach ($fields as $f)
					{
						if (preg_match($regExpr,$f,$matches))
						{
							$this->addValuesCondition($queryType,$function,$f);
						}
					}
					
				}
			}
		}
		
// 		print_r($fields);
// 		print_r($types);
		
		return true;
	}
	
	//convert values of the $this->values to MySQL formats
	public function convertValuesToDb()
	{
		if (Params::$automaticConversionToDbFormat)
		{
			if (isset($this->_conversionToDbObject))
			{
				$types = $this->getTypes();
				
				if ($types)
				{
					$fields = array_keys($this->values);
					
					foreach ($types as $index => $t)
					{
						if (method_exists($this->_conversionToDbObject,strtolower($t)))
						{
							$this->values[$fields[$index]] = call_user_func(array($this->_conversionToDbObject, strtolower($t)), $this->values[$fields[$index]]);
						}
					}
				}
			}
		}
	}
	
	public function insert()
	{
		$this->db->setAutocommit(true);
		
		$this->notice = null;
		$this->errors = array();
		
		$this->queryResult = false;
		
		$this->convertValuesToDb();
		
		if ($this->checkOnUpdateIntegrity("insert"))
		{
			//set the values conditions from the table description
			if ($this->setValuesConditionsFromDbFields("insert"))
			{
				if ($this->applyDatabaseConditions("insert",null))
				{
					$this->restoreConditions("database");
					if ($this->applyValidateConditions("insert",'values'))
					{
						$this->restoreConditions("values");
						if ($this->applyValidateConditions("insert",'soft',true))
						{
							$this->restoreConditions("soft");
							return parent::insert();
						}
						$this->restoreConditions("soft");
					}
					$this->restoreConditions("values");
				}
				$this->restoreConditions("database");
			}
		}

		return false;

	}
	
	public function update($id = null, $whereClause = null)
	{
		$this->db->setAutocommit(true);
		
		$this->notice = null;
		$this->errors = array();
		
		$this->queryResult = false;
		
		$this->convertValuesToDb();
		
		if ($this->checkOnUpdateIntegrity("update"))
		{
			//set the values conditions from the table description
			if ($this->setValuesConditionsFromDbFields("update"))
			{
				if (!isset($id) or $this->applyDatabaseConditions("update",(int)$id))
				{
					$this->restoreConditions("database");
					//check the values conditions
					if ($this->applyValidateConditions("update",'values'))
					{
						$this->restoreConditions("values");
						if ($this->applyValidateConditions("update",'soft',true))
						{
							$this->restoreConditions("soft");
							return parent::update($id, $whereClause);
						}
						$this->restoreConditions("soft");
					}
					$this->restoreConditions("values");
				}
				$this->restoreConditions("database");
			}
		}
		
		return false;
	}
	
	//method to call the delete query (overriding of the del method of Model.php)
	//check the referential integrity
	public function del($id = null, $whereClause = null)
	{
		$this->notice = null;
		$this->errors = array();
		
		$this->queryResult = false;
		
		if ($this->checkOnDeleteIntegrity($id, $whereClause))
		{
			return parent::del($id, $whereClause);
		}
		else
		{
			return false;
		}
// 		if (isset($whereClause))
// 		{
// 			return parent::del(null,$whereClause);
// 		}
// 		else
// 		{
// 			if ($this->_onDelete === 'check' and isset($this->_reference))
// 			{
// 				if (isset($this->_reference[0]) and isset($this->_reference[1]))
// 				{
// 					if ($this->db->recordExists($this->_reference[0],$this->_reference[1],(int)$id))
// 					{
// 						$this->notice = $this->_resultString->getString('associate');
// 						$this->identifierValue = null;
// 						$this->result = false;
// 					}
// 					else
// 					{
// 						return parent::del((int)$id);
// 					}
// 				}
// 				else
// 				{
// 					throw new Exception('you have forgotten to set \'$this->_reference\' or you have forgotten to set $this->_onDelete = \'nocheck\'');
// 				}
// 			}
// 			else
// 			{
// 				return parent::del((int)$id);
// 			}
// 		}
// 		return false;
	}

	//method to obtain one columns from the tables $this->_tablesArray as an associative array
	//$valueField: the column that have to be extracted (array_values of the resulting associative array), $keyField: the column that have to play the role of array_keys
	public function getFieldArray($valueField,$keyField = null, $groupBy = null, $orderBy = null, $limit = null) {

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

		if (strcmp($keyFieldTable,$valueFieldTable) !== 0) {
			throw new Exception("the tables '$valueFieldTable' and '$keyFieldTable' do not match in ".__METHOD__);
		}

		if (!in_array($keyFieldTable,$this->_tablesArray)) {
			throw new Exception("the table '$keyFieldTable' is not allowed in ".__METHOD__);
		}

		$elements = $this->treeQueryElements($keyFieldTable,'');

		$table = $this->db->select($elements['tables'],$fields,$elements['where'],$groupBy,$orderBy,$limit,$elements['on'],$this->using);
		$this->where = $temp;

		$returnArray = array();
		foreach ($table as $record) {
			$returnArray[$record[$keyFieldTable][$keyFieldName]] = $record[$valueFieldTable][$valueFieldName];
		}

		return $returnArray;

	}

}
