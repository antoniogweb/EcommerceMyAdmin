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

class Controller {

	protected $m = array(); //associative array referencing different models
	protected $h = array(); //associative array referencing different helpers
	protected $s = array(); //associative array referencing different sessions objects (users_checkAdmin objects: see library/users/checkAdmin.php)
	protected $c = array(); //associative array referencing different controllers

	protected $_queryString = array(); //the array of args coming from the url

	public $application = null;
	public $applicationUrl = null; //the url of the application
	public $controller;
	public $action;
	public $currPage; //the URL of the current page

	public $request = null; //reference to a Request object

	public $modelName;

	public $argKeys = array(); //the array of keys representing the status args of the view action of the controller (validate function after colon)
	public $argDefault = array(); //the array containing the default values of the $viewArgs array

	public $argFunc = array(); //the array containing the functions to be applied upon the $viewArgs array
	
	public $viewArgs = array(); //the associative array representing the status args of the main action of the controller. It is the combination of $argKeys and $queryString
	public $viewStatus = ''; //string containing the additional url string to get the status of the view action of the controller (derived from $this->viewArgs)

	public $theme;
	public $baseUrl = null; //the base url of the website: http://domainname
	public $baseUrlSrc = null; //the base url of the website (http://domainname) if MOD_REWRITE_MODULE has been set to false

	public $headerObj; //reference to headerObj class

// 	protected $_users; //object to manage access

	protected $scaffold = null; //the reference to the scaffold object

	function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		$this->application = $application;
		$this->controller = $controller;
		$this->action = $action;
		$this->modelName = $model;
		$this->_queryString = $queryString;

		$this->theme = new Theme($application, $controller);
		$this->baseUrl = $this->theme->baseUrl;
		$this->baseUrlSrc = $this->theme->baseUrlSrc;

		$this->headerObj = new HeaderObj(DOMAIN_NAME);
		$this->request = new Request();
		
		$this->applicationUrl = isset($application) ? $application . "/" : null;
	}

	//redirect to $path after the time $time
	final public function redirect($path,$time = 0,$string = null)
	{
		$this->headerObj->redirect($path,$time,$string);
	}

	//set the $_data structure of the theme
	final public function set($value)
	{
		$this->theme->set($value);
	}

	//append values to the $_data structure of the theme
	final public function append($value)
	{
		$this->theme->append($value);
	}

	//load a view file
	final public function load($viewFile,$option = 'none') {
		$this->theme->load($viewFile,$option);
	}

	//change $old view file with $new view files
	final public function changeViewFile($old, $new) {
		$this->theme->changeViewFile($old,$new);
	}
	
	//clean the array containing the view files to load
	final public function clean() {
		$this->theme->clean();
	}

	//load an helper class
	final function helper($helperName) {
		$args = func_get_args();
		array_shift($args);
		$name = 'Helper_'.$helperName;
		if (class_exists($name))
		{
			$this->h[$helperName] = new $name();

			if ($this->h[$helperName] instanceof Helper_Html) {
				$this->h[$helperName]->viewArgs = $this->viewArgs;
				$this->h[$helperName]->viewStatus = $this->viewStatus;
			}

			if (method_exists($this->h[$helperName], 'build')) {
				call_user_func_array(array($this->h[$helperName],'build'),$args);
			}
		}

	}

	//load a model class
	//$name: the name of the model class
	final public function model($name = null) {
		$modelName = isset($name) ? $name : $this->modelName;
		if (class_exists($modelName)) {
			$this->m[$modelName] = new $modelName();
			
			$this->m[$modelName]->application = $this->application;
			$this->m[$modelName]->applicationUrl = $this->applicationUrl;
			$this->m[$modelName]->controller = $this->controller;
			$this->m[$modelName]->action = $this->action;
			$this->m[$modelName]->currPage = $this->currPage;
			
		} else {
			throw new Exception('Error in '.__METHOD__.': class "'.$modelName.'" has not been defined');
		}
	}

	//load a controller
	//$controllerName: the name of the controller class to load
	final public function controller($controller)
	{
		if (class_exists($controller)) {
			$model = str_replace('Controller',null,$controller).'Model';
			$application = $this->controller;
			$this->c[$controller] = new $controller($model,$application,$this->_queryString);
			$this->c[$controller]->theme = $this->theme;
		}
	}

	//load a users_checkAdmin class
	//$sessonType: the type of session. It can be 'admin' (in the case of the access of an admin user) or 'registered' (in the case of the access of a registerd user)
	final public function session($sessionType = 'admin') {
		$sessionTypeArray = array('admin','registered');
		if (!in_array($sessionType,$sessionTypeArray)) {
			throw new Exception('Error in '.__METHOD__.': the session type can be \'admin\' or \'registered\' only');
		}
		//admin session
		if ($sessionType === 'admin') {
		
			if (!defined('ADMIN_ALLOW_MULTIPLE_ACCESSES'))
			{
				define("ADMIN_ALLOW_MULTIPLE_ACCESSES", false);
			}
			
			if (!defined('ADMIN_MAX_CLIENT_SESSIONS'))
			{
				define("ADMIN_MAX_CLIENT_SESSIONS", 0);
			}
			
			if (!defined('ADMIN_COOKIE_PERMANENT'))
			{
				define("ADMIN_COOKIE_PERMANENT", false);
			}
			
			$params = array(
				'users_controller' 		=> ADMIN_USERS_CONTROLLER,
				'users_login_action'	=> ADMIN_USERS_LOGIN_ACTION,
				'panel_controller' 		=> ADMIN_PANEL_CONTROLLER,
				'panel_main_action'		=> ADMIN_PANEL_MAIN_ACTION,
				'cookie_name' 			=> ADMIN_COOKIE_NAME,
				'sessionsTable'			=> ADMIN_SESSIONS_TABLE,
				'usersTable' 			=> ADMIN_USERS_TABLE,
				'groupsTable' 			=> ADMIN_GROUPS_TABLE,
				'manyToManyTable' 		=> ADMIN_MANYTOMANY_TABLE,
				'accessesTable' 		=> ADMIN_ACCESSES_TABLE,
				'session_expire' 		=> ADMIN_SESSION_EXPIRE,
				'cookie_path' 			=> ADMIN_COOKIE_PATH,
				'database_type' 		=> DATABASE_TYPE,
				'hijacking_check' 		=> ADMIN_HIJACKING_CHECK,
				'on_hijacking_event' 	=> ADMIN_ON_HIJACKING_EVENT,
				'hijacking_action' 		=> ADMIN_HIJACKING_ACTION,
				'time_after_failure' 	=> ADMIN_TIME_AFTER_FAILURE,
				'password_hash' 		=> PASSWORD_HASH,
				'cookie_domain'			=> ADMIN_COOKIE_DOMAIN,
				'cookie_secure'			=> ADMIN_COOKIE_SECURE,
				'allow_multiple_accesses'	=>	ADMIN_ALLOW_MULTIPLE_ACCESSES,
				'max_client_sessions'	=>	ADMIN_MAX_CLIENT_SESSIONS,
				'cookie_permanent'		=>	ADMIN_COOKIE_PERMANENT,
			);
			$this->s['admin'] = new Users_CheckAdmin($params);
		}
		//registered session
		if ($sessionType === 'registered') {
		
			if (!defined('REG_ALLOW_MULTIPLE_ACCESSES'))
			{
				define("REG_ALLOW_MULTIPLE_ACCESSES", false);
			}
			
			if (!defined('REG_MAX_CLIENT_SESSIONS'))
			{
				define("REG_MAX_CLIENT_SESSIONS", 0);
			}
			
			if (!defined('REG_COOKIE_PERMANENT'))
			{
				define("REG_COOKIE_PERMANENT", false);
			}
			
			$params = array(
				'users_controller' 		=> REG_USERS_CONTROLLER,
				'users_login_action'	=> REG_USERS_LOGIN_ACTION,
				'panel_controller' 		=> REG_PANEL_CONTROLLER,
				'panel_main_action' 	=> REG_PANEL_MAIN_ACTION,
				'cookie_name' 			=> REG_COOKIE_NAME,
				'sessionsTable' 		=> REG_SESSIONS_TABLE,
				'usersTable' 			=> REG_USERS_TABLE,
				'groupsTable' 			=> REG_GROUPS_TABLE,
				'manyToManyTable' 		=> REG_MANYTOMANY_TABLE,
				'accessesTable' 		=> REG_ACCESSES_TABLE,
				'session_expire' 		=> REG_SESSION_EXPIRE,
				'cookie_path' 			=> REG_COOKIE_PATH,
				'database_type' 		=> DATABASE_TYPE,
				'hijacking_check' 		=> REG_HIJACKING_CHECK,
				'on_hijacking_event' 	=> REG_ON_HIJACKING_EVENT,
				'hijacking_action' 		=> REG_HIJACKING_ACTION,
				'time_after_failure' 	=> REG_TIME_AFTER_FAILURE,
				'password_hash' 		=> PASSWORD_HASH,
				'cookie_domain'			=> REG_COOKIE_DOMAIN,
				'cookie_secure'			=> REG_COOKIE_SECURE,
				'allow_multiple_accesses'	=>	REG_ALLOW_MULTIPLE_ACCESSES,
				'max_client_sessions'	=>	REG_MAX_CLIENT_SESSIONS,
				'cookie_permanent'		=>	REG_COOKIE_PERMANENT,
			);
			$this->s['registered'] = new Users_CheckAdmin($params);
		}
	}

	//method to set $this->argKeys. Chenge the string in the array!
	final public function setArgKeys($argKeys) {
// 		$this->argKeys = explode(',',$argKeys);
		$this->argKeys = array_keys($argKeys);
		$this->argDefault = array_values($argKeys);
	}

	//shift the $this->_queryString array a number of times equal to the number indicated by the $number variable and build the $this->viewArgs array and the $this->viewStatus string (additional url)
	final public function shift($number = 0) {
		
		//save the query string array
		$oldQueryString = $this->_queryString;
		
		for ($i = 0; $i < $number; $i++)
		{
			array_shift($this->_queryString);
		}
		
		$this->callInArgKeysFunc();
		
		for ($i = 0; $i < count($this->argKeys); $i++)
		{
			if (isset($_GET[$this->argKeys[$i]]) and strcmp($_GET[$this->argKeys[$i]],'') !== 0)
			{
				$this->viewArgs[$this->argKeys[$i]] = $this->request->get($this->argKeys[$i],null,$this->argFunc[$i]);
// 				continue;
			}
			else if (!isset($this->_queryString[$i]))
			{
				$this->viewArgs[$this->argKeys[$i]] = isset($this->argDefault[$i]) ? $this->argDefault[$i] : null;
// 				continue;
			}
			else
			{
				$this->viewArgs[$this->argKeys[$i]] = $this->_queryString[$i];
			}
			
			$this->viewArgs[$this->argKeys[$i]] = $this->cleanValue($this->viewArgs[$this->argKeys[$i]]);
			
			$this->argKeys[$i] = $this->argKeys[$i].":".$this->argFunc[$i];
		}
			
		$this->viewStatus = Url::createUrl($this->viewArgs);
		$this->updateHelpers();
		
		//update the theme
		$this->theme->viewStatus = $this->viewStatus;
		$this->theme->viewArgs = $this->viewArgs;
		
		//restore the query string array
		$this->_queryString = $oldQueryString;
	}

	final public function cleanValue($value)
	{
		foreach (params::$whereClauseSymbolArray as $symbol)
		{
			if (strpos($value, $symbol) === 0)
			{
				return "";
			}
		}
		
		$regExpr = '/^('.implode("|",Params::$whereClauseTransformSymbols).')\:(.*)$/';
		
		if (preg_match($regExpr,$value))
		{
			return "";
		}
		
		return $value;
	}
	
	//call the functions defined in $this->argKeys after the colon (ex- 'page:forceInt' => apply the forceInt() function upon the $page arg)
	final public function callInArgKeysFunc() {
		for ($i = 0; $i < count($this->argKeys); $i++) {
			
			$this->argFunc[$i] = 'none';
			
			if (strstr($this->argKeys[$i],':')) {
				$temp = explode(':',$this->argKeys[$i]);

				$this->argFunc[$i] = $temp[1];
				
				//exception
				if (!in_array($temp[1],explode(',',params::$allowedSanitizeFunc))) {
					throw new Exception('"'.$temp[1]. '" function not allowed in $this->argKeys');
				}
				$this->argKeys[$i] = $temp[0];
				if (!isset($this->_queryString[$i])) {
					continue;
				}
				$this->_queryString[$i] = call_user_func($temp[1],$this->_queryString[$i]);
			}
		}
	}

	//function to update all the Helper that are instance of the HtmlHelper class. This function update the $viesArgs and $viewStatus properties. This function is called by the shift method.
	final public function updateHelpers() {
		foreach ($this->h as $Helper) {
			if ($Helper instanceof Helper_Html) {
				$Helper->viewArgs = $this->viewArgs;
				$Helper->viewStatus = $this->viewStatus;
			}
		}
	}

	/**
	* @brief create the viewStatus property
	* 
	* @return array
	*/
	final public function buildStatus()
	{
		$this->viewStatus = Url::createUrl($this->viewArgs);
		//update the theme
		$this->theme->viewStatus = $this->viewStatus;
		$this->theme->viewArgs = $this->viewArgs;
	}

	//method to instanciate the scaffold
	final public function loadScaffold($type = "main",$params = null) {

		$typeArray = array('main','form');
		if (!in_array($type,$typeArray)) {
			throw new Exception("the type '$type' is not allowed in ".__METHOD__);
		}
		$this->scaffold = new Scaffold($type,$this->application, $this->controller, $this->action, $this->m[$this->modelName],$this->viewArgs,$params);

		$this->helper('Menu',$this->applicationUrl.$this->controller,$this->scaffold->params['panelController'],$this->m[$this->modelName]);
		$this->scaffold->mainMenu = $this->scaffold->menu = $this->h['Menu'];
		
		$this->m[$this->modelName]->popupBuild();
		$popupArray = $this->m[$this->modelName]->popupArray;

		if ($type === 'main') {
			
			$here = $this->applicationUrl.$this->controller.'/'.$this->scaffold->params['mainAction'];
			
			$this->helper('Pages',$here,$this->scaffold->params['pageVariable'],"previous","next",$this->m[$this->modelName]);
			$this->helper('List',$this->m[$this->modelName]->identifierName,$here,$this->scaffold->params['pageVariable'],$this->m[$this->modelName]);

			$this->helper('Popup',$here,$popupArray,$this->scaffold->params['popupType'],$this->scaffold->params['pageVariable'],true,$this->m[$this->modelName]);

			$this->scaffold->pageList = $this->scaffold->pages = $this->h['Pages'];
			$this->scaffold->itemList = $this->scaffold->list = $this->h['List'];
			$this->scaffold->popupMenu = $this->h['Popup'];
		}
	}

}
