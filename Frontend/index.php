<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

define('EG','allowed');

define('FRONT', ROOT);
define('APPLICATION_PATH','Application'); //name of the folder that contains the application files

// call the config file and the bootstrap file
require_once (ROOT . DS . 'Config' . DS . 'Config.php');
require_once (ROOT . DS . 'Config' . DS . 'Route.php');
require_once (LIBRARY . DS . 'Library' . DS . 'Bootstrap.php');

// echo 'It works!';

function Custom_autoload($className)
{
	$backupName = $className;

	if (strstr($className,'_'))
	{
		$parts = explode('_',$className);
		$className = implode(DS,$parts);
	}
	
	if (file_exists(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php'))
	{
		require_once(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php');
	}
	else if (file_exists(LIBRARY . DS . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php'))
	{
		require_once(LIBRARY . DS . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php');
	}
	else if (file_exists(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Controllers' . DS . $backupName . '.php'))
	{
		require_once(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Controllers' . DS . $backupName . '.php');
	}
	else if (file_exists(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Strings' . DS . $className . '.php'))
	{
		require_once(LIBRARY . DS . "Frontend/" . APPLICATION_PATH . DS . 'Strings' . DS . $className . '.php');
	}
}
