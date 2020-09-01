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

//class to manage a file di log
//this is a singleton class
class Files_Log
{
	
	const DS = DIRECTORY_SEPARATOR;
	
	// array of instances of the class
	//key: name of the instance, value:instance. The name of the instance is also the name of the log file to open
	private static $instance = array();

	public static $logFolder = './'; //the log folder
	public static $logExtension = '.log'; //the extension of the log files
	public static $logPermission = 0777;
	
	private $splFile; //SplFileObject
	
	//$fileName: the file to open
	private function __construct($fileName)
	{
		$finalChar = self::$logFolder[strlen(self::$logFolder) - 1];
		if (strcmp($finalChar,self::DS) !== 0) self::$logFolder .= self::DS;
		
		$path = self::$logFolder . $fileName . self::$logExtension;
		$this->splFile = new SplFileObject($path,'a+');
		//change the permission of the file
		@chmod($path,self::$logPermission);
	}

	// The singleton method
	// $instanceName: name of the key of self::$instance. It is also the name of the log file to open
	public static function getInstance($instanceName)
	{
		if (!isset(self::$instance[$instanceName])) {
			$className = __CLASS__;
			self::$instance[$instanceName] = new $className($instanceName);
		}

		return self::$instance[$instanceName];
	}

	//write the string $string at the end of the file
	public function writeString($string,$format = 'Y-m-d H:i:s')
	{
		$date = date($format);
		$this->splFile->fwrite("[$date]\t".$string."\n");
	}

	//get the date string of the line $line
	public function getDateString($line)
	{
		if (preg_match('/^[\[]{1}([a-zA-Z0-9:\-\s])*[\]]{1}/',$line,$match))
		{
			$match[0] = str_replace('[',null,$match[0]);
			$match[0] = str_replace(']',null,$match[0]);
			return $match[0];
		}
		else
		{
			return false;
		}
	}

	//delete all the lines older than a number of days equal to $days
	public function clearBefore($days = 30)
	{
		$tempArray = array();
		$newTime = time() - (int)$days * 24 * 3600;
		foreach ($this->splFile as $line)
		{
			$lineTime = strtotime($this->getDateString($line));
			if ($lineTime !== false and $lineTime > $newTime)
			{
				$tempArray[] = $line;
			}
		}
		$this->splFile->ftruncate(0);
		foreach ($tempArray as $row)
		{
			$this->splFile->fwrite($row);
		}
	}
	
	// Prevent users to clone the instance
	public function __clone()
	{
		throw new Exception('error in '. __METHOD__.': clone is not allowed');
	}

}
