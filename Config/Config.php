<?php 

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

if (!defined('EG')) die('Direct access not allowed!');

include_once(ROOT."/config.php");

/*database parameters*/
define('DB', $db_name);
define('USER', $db_user);
define('PWD', $db_pwd);
define('HOST', $db_host);

/*default controller name*/
define('DEFAULT_CONTROLLER','users');

/*default action*/
define('DEFAULT_ACTION','login');

/*website parameters*/
define('DOMAIN_NAME',$website_domain_name.'/admin');

/*type of database.*/
//it can be: Mysql, Mysqli, PDOMysql, PDOMssql or None (first letter in uppercase)
if (!defined('DATABASE_TYPE'))
	define('DATABASE_TYPE','Mysqli');

/*error controller*/
/*if you set ERROR_CONTROLLER to false, than MvcMyLibrary will set ERROR_CONTROLLER equal to DEFAULT_CONTROLLER*/
define('ERROR_CONTROLLER','panel');

/*error action*/
/*if you set ERROR_ACTION to false, than MvcMyLibrary will set ERROR_ACTION equal to DEFAULT_ACTION*/
define('ERROR_ACTION','main');

/*charset*/
// set the charset used by all the functions that manage multi byte strings (mb_string functions, htmlentitites, etc)
// the database connection will be set to the chosen charset too
// charsets allowed: 'UTF-8','ISO-8859-1','EUC-JP','SJIS'
define('DEFAULT_CHARSET','UTF-8');

/*rewrite settings*/
//set MOD_REWRITE_MODULE to true if you have installed the mod_rewrite module of the server, otherwise MOD_REWRITE_MODULE to false
define('MOD_REWRITE_MODULE',true);

//define if it has to use the new or the old style of the where clause definition (new style suggested!!)
define('NEW_WHERE_CLAUSE_STYLE',true);
