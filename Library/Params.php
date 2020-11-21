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

//class containing all the parameters necessary to EasyGiant to work properly
class Params
{

	public static $allowedDb = array('Mysql','Mysqli','PDOMysql','PDOMssql','None'); //allowed database type

	public static $allowedSanitizeFunc = 'sanitizeAll,sanitizeDb,sanitizeHtml,forceInt,forceNat,none,md5,sha1,strip_tags'; //allowed sanitize functions

	//sanitize function used as default in insert and updates
	public static $defaultSanitizeFunction = "sanitizeAll";
	
	//sanitize function used as default in select queries
	public static $defaultSanitizeDbFunction = "sanitizeDb";
	
	//HTML sanitize function used as default in scaffolding
	public static $defaultSanitizeHtmlFunction = null;
	
	public static $allowedHashFunc = array('md5','sha1'); //allowed hash functions
	
	//conventional null value for the value of the field in the createWhereClause method of the Model class
	public static $nullQueryValue = false;

	//use HTTPS for links or not
	public static $useHttps = false;
	
	//class name of the div that contains the error strings
	public static $errorStringClassName = 'alert';
	
	//class name of the div that contains the error strings
	public static $infoStringClassName = 'executed';
	
	//table name in the returning structure of the select queries in the case of an aggregate function. Ex count(*),sum(*)
	public static $aggregateKey = 'aggregate';
	
	//htmlentities function charset
	//see http://php.net/manual/en/function.htmlentities.php for a complete list of the allowed values
	public static $htmlentititiesCharset = DEFAULT_CHARSET;
	
	//list of symbols used in the statements of the where clause of the select queries
	public static $whereClauseSymbolArray = array('<','>','!=','<=','>=','in(','not in(','like','between');
	
	//list of symbols used to create the where clause
	public static $whereClauseTransformSymbols = array("lt","lte","gt","gte","not","lk","in","nin");
	
	//is the mbstring extension enabled?
	public static $mbStringLoaded = false;
	
	//subfolder of the View folder where to look for view files
	public static $viewSubfolder = null;

	//global website language used by the models and by the helpers for reporting
	public static $language = 'It';
	
	//array of languages allowed for the website front-end
	public static $frontEndLanguages = array();
	
	//default front-end language
	public static $defaultFrontEndLanguage = "it";
	
	//current front-end language
	public static $lang = null;
	
	//if true, it redirect to current language if language is expected ($frontEndLanguages not empty) but not present
	public static $redirectToDefaultLanguage = false;
	
	//if the URL status variables have to be rewritten or left in the standard query string form
	public static $rewriteStatusVariables = true;

	//if the URL has to be rewritten only if an exact match has been found (true) or if a match has been found starting from the beginning (false) 
	public static $exactUrlMatchRewrite = false;
	
	//list of installed applications
	//the name of the applications has to match a subfolder of tha Apps folder
	public static $installed = array();
	
	//the application called
	public static $currentApplication = null;
	
	//if true, automatically set the values conditions from the table types 
	public static $setValuesConditionsFromDbTableStruct = false;
	
	//array of field types whose conditions haven't to be automatically set
	//empty by default: no field are excluded
	//allowed values: "char","integer","float","date","enum","decimal"
	public static $doNotAutomaticallySetValuesConditionsForTheseTypes = array();
	
	//if true, automatically convert values to MySQL format during insert and update queries (using types taken from table definition)
	public static $automaticConversionToDbFormat = false;
	
	//if true, convert values from MySQl to $_lang format when filling the form with values coming from the DB
	public static $automaticConversionFromDbFormat = false;
	
	//if true, set the default value for each field when filling the form
	//default values are taken from DB where present or from type definition
	public static $automaticallySetFormDefaultValues = false;
	
	//if true, set date("Y-m-d") as default date when setting the default values of the form or of the $this->values array
	public static $useCurrentDateAsDefaultDate = true;
	
	//definition of values conditions from the formats of the fields names
	public static $valuesConditionsFromFormatsOfFieldsNames = array();

	//if true, search for the session uid in GET
	public static $allowSessionIdFromGet = false;
	
	//it can be GET or POST
	//look for primarykey and action inside that array
	//it is used by getFormValues() and updateTable()
	public static $actionArray = "POST";
	
	//used to clean queries if NEW_WHERE_CLAUSE_STYLE is set to false
	public static $cleanSymbol = "[[{{]]}}";
	
	//define if it has to use the new or the old style of the where clause definition (new style suggested!!)
	public static $newWhereClauseStyle = false;
	
	// define the array of data to validate
	public static $arrayToValidate = null;
}
