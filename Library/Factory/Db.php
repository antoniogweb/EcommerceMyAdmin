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

//class to create the database layer class
class Factory_Db {
	
	//start the database connection
	//$dbType: mysql,mysqli,pg
	//$dbArrayParams: array containing the HOST, the USER, the PWD, and the DB of the database (see config.php)
	public static function getInstance($dbType,$dbArrayParams = array()) {
		if (!in_array($dbType,Params::$allowedDb)) {
			throw new Exception('error in ' . __METHOD__ . ' : the database type has to be '.implode(' or ',Params::$allowedDb));
		}
		switch ($dbType) {
			case 'Mysql':
				return call_user_func_array(array('Db_'.$dbType,'getInstance'),$dbArrayParams);
				break;
			case 'Mysqli':
				return call_user_func_array(array('Db_'.$dbType,'getInstance'),$dbArrayParams);
				break;
			case 'PDOMysql':
				return call_user_func_array(array('Db_'.$dbType,'getInstance'),$dbArrayParams);
				break;
			case 'PDOMssql':
				return call_user_func_array(array('Db_'.$dbType,'getInstance'),$dbArrayParams);
				break;
			case 'None':
				return null;
				break;
		}
	}

	//close the database connection
	public static function disconnect($dbType)
	{
		if (!in_array($dbType,Params::$allowedDb)) {
			throw new Exception('error in ' . __METHOD__ . ' : the database type has to be '.implode(' or ',Params::$allowedDb));
		}
		switch ($dbType) {
			case 'Mysql':
				$mysql = Db_Mysql::getInstance();
				$mysql->disconnect();
				break;
			case 'Mysqli':
				$mysqli = Db_Mysqli::getInstance();
				$mysqli->disconnect();
				break;
			case 'PODMysql':
				$mysqli = Db_PODMysql::getInstance();
				$mysqli->disconnect();
				break;
			case 'PODMssql':
				$mysqli = Db_PODMssql::getInstance();
				$mysqli->disconnect();
				break;
			case 'None':
				return null;
				break;
		}
	}

}
