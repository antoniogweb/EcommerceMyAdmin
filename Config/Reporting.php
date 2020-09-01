<?php 

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');


//ERROR REPORTING DIRECTIVES

//set RUNTIME_CONFIGURATION to true if you can't access the php.ini file of your PHP installation and you need to modify some PHP directives
//set RUNTIME_CONFIGURATION to false if you can access the php.ini file. In this case, modify the PHP directives in the php.ini file.
define ('RUNTIME_CONFIGURATION',true);

// !!! the following four directives will be applied only if RUNTIME_CONFIGURATION has been set to true !!!

//set the php.ini error_reporting directive
define ('ERROR_REPORTING_DIRECTIVE',E_ALL);

//set the php.ini display_errors directive
//set to On or Off
define ('DISPLAY_ERRORS','On');

//set if the error file (see the next directive) has to be created or not
//set ERROR_REPORTING_FILE to true if you want that EasyGiant saves the errors in the LOG_ERROR_FILE (next), otherwise set ERROR_REPORTING_FILE to false
define ('ERROR_REPORTING_FILE',true);

//only if ERROR_REPORTING_FILE has been set to true
//set the file where the errors will be saved
//default: EasyGiant_root/Logs/Errors.log
//check that the LOG_ERROR_FILE is writeble (by the apache user if you are using mod_php)
define ('LOG_ERROR_FILE','default');


//max length of each $_POST element
//set MAX_POST_LENGTH equal to 0 if you don't want to set un upper limit in the length of the $_POST elements
define ('MAX_POST_LENGTH',100000);

//max length of the REQUEST_URI
//set MAX_REQUEST_URI_LENGTH equal to 0 if you don't want to set an upper limit in the length of the REQUEST_URI
define ('MAX_REQUEST_URI_LENGTH',400);
