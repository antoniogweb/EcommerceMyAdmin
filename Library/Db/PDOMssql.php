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

//class to manage the database
//singleton!
class Db_PDOMssql
{
	
	private $autocommit = true;
	private $transactionBatchSize = 100;
	
	public $transactionBatch = array();
	
	public $query = null; //the last query executed
	public $queries = array(); //array containing all the queries executed
	
	public $charsetError = true; //true: non eccor occurred during the modification of the connection charset, false: one error occurred
	public $charset = null; //the charset of the client connection

	private static $instance = null; //instance of this class

	private $db;
	
	private $charTypes = array('varchar','char');
	private $textTypes = array('tinytext','text','mediumtext','longtext');
	private $integerTypes = array('tinyint','smallint','int','mediumint','bigint');
	private $floatTypes = array('real','float','double');
	private $dateTypes = array('date');
	private $enumTypes = array('enum');
	private $decimalTypes = array('decimal');
	private $uniqueIndexStrings = array('UNI');
	
	private $fieldsType = array();

	//PHP-Mysql charset translation table
	private $charsetTranslationTable = array(
		'UTF-8'			=> 	'utf8',
		'ISO-8859-1'	=> 	'latin1',
		'EUC-JP'		=>	'ujis',
		'SJIS'			=>	'sjis'
	);
	
	/**

	*connect to the database
	*'host','user','password','db_name'

	*/

	private function __construct($host,$user,$pwd,$db_name)
	{
		$this->fieldsType = array_merge($this->integerTypes, $this->floatTypes);
		
// 		$charset = array_key_exists(DEFAULT_CHARSET,$this->charsetTranslationTable) ? $this->charsetTranslationTable[DEFAULT_CHARSET] : 'utf8';
		
		$dsn = "sqlsrv:Server=$host ; Database = $db_name";
// 		$dsn = "dblib:host=$host;dbname=$db_name;charset=".DEFAULT_CHARSET;
// 		$this->db = new mysqli($host,$user,$pwd,$db_name);
		
		
		try {
// 			new PDO ("dblib:host=$this->hostname:$this->port;dbname=$this->dbname", "$this->username", "$this->pwd");
			$this->db = new PDO($dsn, $user, $pwd, array(PDO::SQLSRV_ATTR_DIRECT_QUERY => true));
// 			$this->db = new PDO($dsn, $user, $pwd);
			
// 			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Connect Error (' . $e->getMessage() . ') ';
			die();
		}
// 		if (mysqli_connect_error())
// 		{
// 			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
// 		}
		
// 		if (!@$this->db->set_charset($charset)) $this->charsetError = false;
		
// 		$this->charset = $charset;

	}

	//return the $this->db property
	public function getDb()
	{
		return $this->db;
	}

	public static function getInstance($host = null, $user = null, $pwd = null, $db_name = null)
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className($host,$user,$pwd,$db_name);
		}

		return self::$instance;
	}

	public function getUniqueIndexStrings()
	{
		return $this->uniqueIndexStrings;
	}
	
	public function getTextTypes()
	{
		return $this->textTypes;
	}
	
	public function getDecimalTypes()
	{
		return $this->decimalTypes;
	}
	
	public function getEnumTypes()
	{
		return $this->enumTypes;
	}
	
	public function getCharTypes()
	{
		return $this->charTypes;
	}
	
	public function getIntegerTypes()
	{
		return $this->integerTypes;
	}
	
	public function getFloatTypes()
	{
		return $this->floatTypes;
	}
	
	public function getDateTypes()
	{
		return $this->dateTypes;
	}
	
	//close the connection
	public function disconnect()
	{
		$this->db = null;
	}

	//the text of the error message from previous MySQL operation
	public function getError()
	{
		return $this->db->errorInfo();
	}

	//the numerical value of the error message from previous MySQL operation
	public function getErrno()
	{
		return $this->db->errorCode();
	}

	public function getJoinString($string)
	{
		if (strstr($string,':'))
		{
			$tArray = explode(':',$string);
			switch($tArray[0])
			{
				case 'i':
					$jString = ' INNER JOIN ' . $tArray[1];
					break;
				case 'l':
					$jString = ' LEFT JOIN ' . $tArray[1];
					break;
				case 'r':
					$jString = ' RIGHT JOIN ' . $tArray[1];
					break;
				default:
					$jString = ' INNER JOIN ' . $tArray[1];
					break;
			}
			return $jString;
		}
		else
		{
			return ' INNER JOIN '.$string;
		}
	}
	
	public function createSelectQuery($table,$fields='*',$where=null,$group_by=null,$order_by=null,$limit=null,$on=array(),$using=array(),$join=array())
	{
// 		$maxValue = max(count($on),count($using),count($join));
		$maxValue = max(count((array)$on),count((array)$using),count((array)$join));
		
		$joinString = null;
		for ($i=0; $i < $maxValue; $i++)
		{
			$joinString .= isset($join[$i]) ? $this->getJoinString($join[$i]) : null;
			if (isset($using[$i]))
			{
				$joinString .= ' USING ('.$using[$i].')';
			}
			else if (isset($on[$i]))
			{
				$joinString .= ' ON '.$on[$i];
			}
		}
		
		if (isset($where))
		{
			$where='WHERE '.$where;
		}
		if (isset($order_by)) {
			$order_by='ORDER BY '.$order_by;
		}
		if (isset($group_by)) {
			$group_by='GROUP BY '.$group_by;
		}
		if (isset($limit)) {
			$limitArray = explode(",",$limit);
			
			if (count($limitArray) === 2)
				$limit=" OFFSET ".$limitArray[0]." ROWS FETCH NEXT ".$limitArray[1]." ROWS ONLY";
			else
				$limit=" OFFSET 0 ROWS FETCH NEXT ".$limitArray[0]." ROWS ONLY";
		}

		$query="SELECT $fields FROM $table $joinString $where $group_by $order_by $limit;";
		return $query;
	}
	
	public function get_num_rows($table,$where=null,$group_by=null,$on=array(),$using=array(),$join=array(),$binded=array(),$selectField = "*") {

		$select = isset($group_by) ? $selectField : 'count('.$selectField.') as number';
		
		$query = $this->createSelectQuery($table,$select,$where,$group_by,null,null,$on,$using,$join);

		$this->query = $query;
		$this->queries[] = $query . $this->bindedValuesString($binded);
		
		if (empty($binded))
			$ris = $stmt = $this->db->query($query);
		else
		{
			$stmt = $this->db->prepare($query);
			$ris = $stmt->execute($binded);
		}
		
// 		$ris = $this->db->query($query);
		if ($ris) {
			
			if (isset($group_by))
			{
				$rows = $stmt->fetchAll();
				$num_rows = count($rows);
			}
			else
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$num_rows = $row['number'];
			}
			
			$ris = $stmt = null;
			
			return (int)$num_rows;
		} else {
			return 0;
		}
	}

	public function getMath($func,$table,$field,$where=null,$group_by = null, $on=array(),$using=array(),$join=array(), $binded=array())
	{
		$query = $this->createSelectQuery($table,"$func($field) AS m",$where,$group_by,null,null,$on,$using,$join);

		$this->query = $query;
		$this->queries[] = $query;
		
		if (empty($binded))
			$result = $stmt = $this->db->query($query);
		else
		{
			$stmt = $this->db->prepare($query);
			$result = $stmt->execute($binded);
		}
		
// 		$result = $this->db->query($query);
		if ($result)
		{
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$result = $stmt = null;
			return $row['m'];
		}
		else
		{
			return false;
		}
	}

	//get the maximum value of the field $field of the table $table having the $where conditions
	public function getMax($table,$field,$where=null,$group_by = null,$on=array(),$using=array(),$join=array(),$binded=array())
	{
		return $this->getMath('MAX',$table,$field,$where,$group_by,$on,$using,$join,$binded);
	}

	//get the minimum value of the field $field of the table $table having the $where conditions
	public function getMin($table,$field,$where=null,$group_by = null,$on=array(),$using=array(),$join=array(),$binded=array())
	{
		return $this->getMath('MIN',$table,$field,$where,$group_by,$on,$using,$join,$binded);
	}

	//get the sum of the fields
	public function getSum($table,$field,$where=null,$group_by = null,$on=array(),$using=array(),$join=array(),$binded=array())
	{
		return $this->getMath('SUM',$table,$field,$where,$group_by,$on,$using,$join,$binded);
	}

	//get the average of the fields
	public function getAvg($table,$field,$where=null,$group_by = null,$on=array(),$using=array(),$join=array(),$binded=array())
	{
		return $this->getMath('AVG',$table,$field,$where,$group_by,$on,$using,$join,$binded);
	}
	
	public function bindedValuesString($bindValues)
	{
		if (empty($bindValues))
			return "";
		
		return " (VALUES: ".implode(",",array_values($bindValues)).")";
	}
	
	public function select($table,$fields='*',$where=null,$group_by=null,$order_by=null,$limit=null,$on=array(),$using=array(),$join=array(), $showTable = true, $bindValues = array())
	{
		$query = $this->createSelectQuery($table,$fields,$where,$group_by,$order_by,$limit,$on,$using,$join);
		
		$this->query = $query;
		
		if (empty($bindValues))
		{
			$stmt = $this->db->prepare($query);
			$result = $stmt->execute();
			
			$this->queries[] = $query;
		}
		else
		{
			
			$stmt = $this->db->prepare($query);
			$result = $stmt->execute($bindValues);
			
			$this->queries[] = $query . $this->bindedValuesString($bindValues);
		}
		
		return $this->getData($stmt, $showTable);
	}


// 	public function select($table,$fields='*',$where=null,$group_by=null,$order_by=null,$limit=null) {
// 		$query = $this->selectQuery($table,$fields,$where,$group_by,$order_by,$limit);
// 		return $this->getData($query);
// 	}


	//obtain an associative array containing the result values (keys:tableName_fieldsName)
	//$par = 'single/multi' single table,multi table
	public function getData($result, $showTable = true) {
		$data = array(); //data from the query
		$temp = array(); //temporary array (values of a single record)
// 		$result = $this->db->query($query);
		if ($result) {
			$fieldsNumber = $result->columnCount();
			
			if ($showTable)
			{
				while ($row = $result->fetch(PDO::FETCH_NUM)) {
// 					print_r($row);
					for ($i = 0;$i < $fieldsNumber;$i++) {
						$finfo = $result->getColumnMeta($i);
						$fieldName = $finfo["name"];
						$fieldNameArray = explode("___",$fieldName);
						$tableName = "";
						if (count($fieldNameArray) > 1)
						{
							$tableName = $fieldNameArray[0];
							$fieldName = $fieldNameArray[1];
						}
// 						print_r($finfo);
// 						$tableName = $finfo["table"];
						
						if (strcmp($tableName,'') === 0) $tableName = Params::$aggregateKey;
						
						$temp["$tableName"][$fieldName] = $row[$i];
					}
					array_push($data,$temp);
				}
			}
			else
			{
				while ($row = $result->fetch(PDO::FETCH_NUM)) {
					for ($i = 0;$i < $fieldsNumber;$i++) {
						$finfo = $result->getColumnMeta($i);
						$fieldName = $finfo["name"];
						$temp[$fieldName] = $row[$i];
					}
					array_push($data,$temp);
				}
			}
			$result = null;
			return $data;
		} else {
			return array();
		}
	}

	public function getFieldsFeature($feature, $table, $fields, $full = false, $associative = false )
	{
		if ($feature === "Null")
			$feature = "IS_NULLABLE";
		
		$query = "exec sp_columns $table;";
		$result = $this->db->query($query);
		$temp = array();
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			if (strcmp($feature,"TYPE_NAME") === 0)
			{
				$e = explode(' ',$row[$feature]);
				
				if (strtolower($row['TYPE_NAME']) == "decimal")
					$temp[$row['COLUMN_NAME']] = $full ? reset($e)."(".$row['PRECISION'].",".$row['SCALE'].")" : reset($e);
				else
					$temp[$row['COLUMN_NAME']] = $full ? reset($e)."(".$row['LENGTH'].")" : reset($e);
			}
			else if (strcmp($feature,"COLUMN_DEF") === 0)
			{
				$e = trim(rtrim(ltrim($row['COLUMN_DEF'],"("),")"),"'");
				
				$temp[$row['COLUMN_NAME']] = $e;
			}
			else
			{
				$temp[$row['COLUMN_NAME']] = $row[$feature];
			}
		}
		$result = null;
// 		die();
		$this->queries[] = $query;
		
		//return all fields types
		if ($fields === "*")
		{
			$fields = implode(",",array_keys($temp));
		}
		
		$types = array();
		$fields = explode(',',$fields);
		for ($i = 0; $i < count($fields); $i++)
		{
			if (!array_key_exists($fields[$i],$temp)) return false;
			
			if ($associative)
			{
				$types[$fields[$i]] = $temp[$fields[$i]];
			}
			else
			{
				$types[] = $temp[$fields[$i]];
			}
		}

		return $types;
	}
	
	//return an array containing all the keys of the fields (indicated in $fields) of a table (indicated in $table)
	public function getKeys($table, $fields, $full = false, $associative = false)
	{
		return array();
// 		return $this->getFieldsFeature('Key', $table, $fields, $full, $associative);
	}
	
	//return an array containing all the default values of the fields (indicated in $fields) of a table (indicated in $table)
	public function getDefaultValues($table, $fields, $full = false, $associative = false)
	{
		return $this->getFieldsFeature('COLUMN_DEF', $table, $fields, $full, $associative);
	}
	
	//return an array containing all the types of the fields (indicated in $fields) of a table (indicated in $table)
	public function getTypes($table, $fields, $full = false, $associative = false)
	{
		return $this->getFieldsFeature('TYPE_NAME', $table, $fields, $full, $associative);
	}
	
	public function update($table,$fields,$values,$where) {

		#$table and $where are two strings
		#$fields has to be a string with comma as separator: name1,name2,...
		#$values has to be an array
		
		$values = array_values($values);
// 		if (isset($where)) {
			$where='WHERE '.$where;
// 		}
		#get the array from the $fields string
		if (strcmp($fields,'') !== 0)
		{
			$preparedValues = array();
			
			$fields = explode(',',$fields);
			$str = array();

			for ($i=0;$i<count($fields);$i++)
			{
				$preparedValues[":".$fields[$i]] = $values[$i];
				$str[$i]= $fields[$i].'=:'.$fields[$i];
			}

			#set the string name1=value1,name2=...
			$str=implode(',',$str);
			$query="UPDATE $table SET $str $where;";
			$this->query=$query;
			$this->queries[] = $query . $this->bindedValuesString($preparedValues);
			
			if ($this->autocommit)
			{
				$ris = $this->db->prepare($query);
				$ris = $ris->execute($preparedValues);

				#check the result
				if ($ris) {
					return true;
				} else {
					return false;
				}
			}
			else
			{
				$this->transactionBatch[] = array($query,$preparedValues);
				return true;
			}
		} else {
			return false;
		}

	}
	
	public function insert($table,$fields,$values) {
		#$table is a string
		#$fields has to be a string with comma as separator: name1,name2,...
		#$values has to be an array

		$values = array_values($values);
		
		$fieldsArray = explode(",",$fields);
		
		if (strcmp($fields,'') !== 0)
		{
			$preparedValues = array();
			
			for($i = 0; $i < count($values); $i++)
			{
				$preparedValues[":".$fieldsArray[$i]] = $values[$i];
			}

			$values = implode(',',array_keys($preparedValues));
			$query="INSERT INTO $table ($fields) VALUES ($values);";
			$this->query=$query;
			$this->queries[] = $query . $this->bindedValuesString($preparedValues);

			if ($this->autocommit)
			{
				$ris = $this->db->prepare($query);
				$ris = $ris->execute($preparedValues);
				
				#check the result
				if ($ris) {
					return true;
				} else {
					return false;
				}
			}
			else
			{
				$this->transactionBatch[] = array($query,$preparedValues);
				return true;
			}
		} else {
			return false;
		}
	}

	//set the autocommit attribute
	public function setAutocommit($value)
	{
// 		if ($value === true or $value === false)
// 		{
// 			$this->autocommit = $value;
// 			
// 			$val = $value ? 1 : 0;
// 			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT,$val);
// 		}
// 		else
// 		{
// 			$this->autocommit = true;
// 			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
// 		}
	}
	
	//set the transactionBatchSize attribute
	public function setTransactionBatchSize($size)
	{
		$this->transactionBatchSize = abs($size);
	}
	
	//begin transaction
	public function beginTransaction()
	{
// 		$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
		$this->db->beginTransaction();
	}
	
	//commit transaction
	public function commit()
	{
		$this->db->commit();
	}
	
	//commit a batch of queries
	//$batch: array of queries
	public function commitBatch($batch)
	{
		foreach ($batch as $sql)
		{
			$ris = $this->db->prepare($sql[0]);
			$ris = $ris->execute($sql[1]);
				
// 			$this->db->query($sql);
		}
		
		if (!$this->autocommit and $this->db->commit())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//commit the transaction
	public function commitTransaction()
	{
		$returnArray = array();
		
		if (!$this->autocommit)
		{
			if (count($this->transactionBatch) > 0)
			{
				if ($this->transactionBatchSize === 0)
				{
					$returnArray[] = $this->commitBatch($this->transactionBatch);
				}
				else
				{
					$batchArray = array_chunk($this->transactionBatch, $this->transactionBatchSize);
					
					foreach ($batchArray as $batch)
					{
						$returnArray[] = $this->commitBatch($batch);
					}
				}
			}
		}
		
		if (count(array_filter($returnArray)) === count($returnArray))
		{
			$this->transactionBatch = array();
			return true;
		}
		
		return false;
	}
	
	// 	Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT). 
	public function lastId()
	{
		return $this->db->lastInsertId();
	}
	
	public function del($table,$where) {

		#$table and $where are two strings
// 		if (isset($where)) {
			$where='WHERE '.$where;
// 		}
		$query="DELETE FROM $table $where;";
		$this->query=$query;
		$this->queries[] = $query;
		
		$ris = $this->db->query($query);
		#check the result

		if ($ris) {
			return true;
		} else {
			return false;
		}

	}

	//function to check if exist the record having the field $id_name=$id_value
	public function recordExists($table,$fieldName,$fieldValue,$where = null,$groupBy=null,$on=array(),$using=array(),$join=array(), $binded=array())
	{
		if (isset($where))
		{
			$where=' AND '.$where;
		}

		$fieldValue = '"'.$fieldValue.'"';

		$num = $this->get_num_rows($table,$fieldName.'='.$fieldValue.$where,$groupBy,$on,$using,$join,$binded);
		$res=($num>0) ? true : false;
		return $res;
	}
	
	//alias of query but without table name
	public function QueryArray($sql)
	{
		return $this->query($sql, false, true);
	}

	/**
	 * Inserts a row into a table in the connected database
	 *
	 * @param string $tableName The name of the table
	 * @param array $valuesArray An associative array containing the column
	 *                            names as keys and values as data.
	 * @return integer true or false
	 */
	public function InsertRow($tableName, $valuesArray)
	{
		$fields = implode(",",array_keys($valuesArray));
		
		$values = array_values($valuesArray);
		
		return $this->insert($tableName,$fields,$values);
	}
	
	/**
	 * Updates rows in a table based on a WHERE filter
	 * (can be just one or many rows based on the filter)
	 *
	 * @param string $tableName The name of the table
	 * @param array $valuesArray An associative array containing the column
	 *                            names as keys and values as data.
	 * @param array $where String containing the where clause
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function UpdateRows($tableName, $valuesArray, $where)
	{
		$fields = implode(",",array_keys($valuesArray));
		
		$values = array_values($valuesArray);
		
		return $this->update($tableName, $fields, $values, $where);
	}
	
	//like QueryArray but it gets only the first row
	/**
	* 
	* @param string $sql The sql of the query to the DB
	* @return array
	*/
	public function QuerySingleRowArray($sql)
	{
		$res = $this->query($sql, false, true);
		
		if (!empty($res))
		{
			return $res[0];
		}
		
		return array();
	}
	
	//send a generic query to the database
	//$query: the query to be sent
	//$forceSelect: if it is a select
	public function query($query, $showTable = true, $forceSelect = false)
	{
		$select = false;
		
		if (is_array($query))
		{
			$stmt = $this->db->prepare($query[0]);
			$result = $stmt->execute($query[1]);
			
			if (strstr(strtolower($query[0]), 'select'))
				$select = true;
			
			$this->query = $query[0] . $this->bindedValuesString($query[1]);
			$this->queries[] = $query[0] . $this->bindedValuesString($query[1]);
		}
		else
		{
			$result = $stmt = $this->db->query($query);
			
			if (strstr(strtolower($query), 'select'))
				$select = true;
			
			$this->query = $query;
			$this->queries[] = $query;
		}
		
		if ($select && $result)
			return $this->getData($stmt, $showTable);
		
		return $result;
	}

	//send a select query to the database
	//$query: the query to be sent
	//$forceSelect: if it is a select
	public function queryAll($query, $showTable = true, $forceSelect = false)
	{
		if (is_array($query))
		{
			$stmt = $this->db->prepare($query[0]);
			$result = $stmt->execute($query[1]);
			
			$this->query = $query[0] . $this->bindedValuesString($query[1]);
			$this->queries[] = $query[0] . $this->bindedValuesString($query[1]);
		}
		else
		{
			$result = $stmt = $this->db->query($query);
			
			$this->query = $query;
			$this->queries[] = $query;
		}
		
		if ($result)
			return $this->getData($stmt, $showTable);
	}
	
	// Prevent users to clone the instance
	public function __clone()
	{
		throw new Exception('error in '. __METHOD__.': clone is not allowed');
	}
	
}
