<?php

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

define('EG','allowed');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('APPLICATION_PATH','Application'); //name of the folder that contains the application files

// call the config file and the bootstrap file
require_once (ROOT . DS . 'Config' . DS . 'Config.php');
require_once (ROOT . DS . 'Config' . DS . 'Route.php');
require_once (ROOT . DS . 'Library' . DS . 'Bootstrap.php');

// echo 'It works!';