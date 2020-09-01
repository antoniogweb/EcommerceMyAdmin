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

if (!defined('LIBRARY'))
	define('LIBRARY', ROOT);

/* SANITIZE SUPERGLOBAL ARRAYS */
function sanitizeSuperGlobal()
{
	$_GET = stripslashesDeep($_GET);

	$_POST   = stripslashesDeep($_POST);

	$_COOKIE = stripslashesDeep($_COOKIE);

	$_SERVER = stripslashesDeep($_SERVER);
}

function checkPostLength($checkArray = null)
{
	$a = isset($checkArray) ? $checkArray : $_POST;
	
	if (MAX_POST_LENGTH !== 0)
	{
		foreach ($a as $key => $value)
		{
			if (is_array($value))
			{
				checkPostLength($value);
			}
			else
			{
				if (strlen($value) > MAX_POST_LENGTH) die('the length of some of the $_POST values is too large');
			}
		}
	}
}

//remove elements that are arrays
//applied to $_POST and $_GET
function fixArray($array)
{
	$temp = array();
	
	foreach ($array as $key => $value)
	{
		$temp[$key] = is_array($value) ? "" : $value;
	}
	
	return $temp;
}

function checkRequestUriLength()
{
	if (MAX_REQUEST_URI_LENGTH !== 0)
	{
		if (strlen($_SERVER['REQUEST_URI']) > MAX_REQUEST_URI_LENGTH) die('the length of the REQUEST_URI is too large');
	}
}

function checkRegisterGlobals()
{
    if (ini_get('register_globals')) die('register globals is on: easyGiant works only with register globals off');
}

//geth the name of the current application used
function getApplicationName()
{
	if (isset(Params::$currentApplication))
	{
		return Params::$currentApplication;
	}
	return null;
}

//geth the path of the current application used
//add the trailing slash to the application name
function getApplicationPath()
{
	if (isset(Params::$currentApplication))
	{
		return "Apps".DS.ucfirst(Params::$currentApplication).DS;
	}
	return null;
}

function languageInUrl($url)
{
	$url = trim($url,"/");
	
	if (in_array($url,Params::$frontEndLanguages))
	{
		return $url."/";
	}
	return false;
}

function callHook()
{
	
	$currentUrl = null;
	
	if (MOD_REWRITE_MODULE === true)
	{
		if (isset($_GET['url']))
		{
			if (!languageInUrl($_GET['url']))
			{
				$url = $_GET['url'];
			}
			else
			{
				$url = languageInUrl($_GET['url']) . DEFAULT_CONTROLLER . '/' . DEFAULT_ACTION;
			}
		}
		else
		{
			$url = DEFAULT_CONTROLLER . '/' . DEFAULT_ACTION;
		}
	}
	else
	{
		if (strcmp(getQueryString(),"") !== 0)
		{
			if (!languageInUrl(getQueryString()))
			{
				$url = getQueryString();
			}
			else
			{
				$url = languageInUrl(getQueryString()) . DEFAULT_CONTROLLER . '/' . DEFAULT_ACTION;
			}
		}
		else
		{
			$url = DEFAULT_CONTROLLER . '/' . DEFAULT_ACTION;
		}
	}
	
	$arriveUrl = $url;
	
	$urlArray = array();
	$urlArray = explode("/",$url);
	
	//get the language
	if (count(Params::$frontEndLanguages) > 0)
	{
		if (in_array($urlArray[0],Params::$frontEndLanguages))
		{
			Params::$lang = sanitizeAll($urlArray[0]);
			array_shift($urlArray);
		}
		else
		{
			Params::$lang = Params::$defaultFrontEndLanguage;
/*			
			if (isset($_GET['url']) and Params::$redirectToDefaultLanguage)
			{
				$h = new HeaderObj(DOMAIN_NAME);
				
				$h->redirect($arriveUrl);
			}*/
		}
	}

	$url = implode("/",$urlArray);
	
// 	rewrite the URL
	if (Route::$rewrite === 'yes')
	{
		$res = rewrite($url);
		$url = $res[0];
		$currentUrl = $res[1];
	}

// 	echo $url;
	
	$urlArray = explode("/",$url);
	$controller = DEFAULT_CONTROLLER;
	$action = DEFAULT_ACTION;
	
	
	//check if an application name is found in the URL
	if (isset(Params::$installed) and isset($urlArray[0]) and strcmp($urlArray[0],'') !== 0 and in_array($urlArray[0],Params::$installed))
	{
		Params::$currentApplication = strtolower(trim($urlArray[0]));
		
		array_shift($urlArray);
	}
	
	if (isset($urlArray[0]))
	{
		$controller = (strcmp($urlArray[0],'') !== 0) ? strtolower(trim($urlArray[0])) : DEFAULT_CONTROLLER;
	}

	array_shift($urlArray);

	if (isset($urlArray[0]))
	{
		$action = (strcmp($urlArray[0],'') !== 0) ? strtolower(trim($urlArray[0])) : DEFAULT_ACTION;
	}

	//set ERROR_CONTROLLER and ERROR_ACTION
	$errorController = ERROR_CONTROLLER !== false ? ERROR_CONTROLLER : DEFAULT_CONTROLLER;
	$errorAction = ERROR_ACTION !== false ? ERROR_ACTION : DEFAULT_ACTION;

	/*
		CHECK COUPLES CONTROLLER,ACTION
	*/
	if (!in_array('all',Route::$allowed))
	{
		$couple = "$controller,$action";
		if (getApplicationName() !== null)
		{
			$couple = getApplicationName().",".$couple;
		}
		if (!in_array($couple,Route::$allowed))
		{
			Params::$currentApplication = null;
			$controller = $errorController;
			$action = $errorAction;
			$urlArray = array();
		}
	}
	
	/*
	VERIFY THE ACTION NAME
	*/	
	if (method_exists('Controller', $action) or !ctype_alnum($action) or (strcmp($action,'') === 0))
	{
		Params::$currentApplication = null;
		$controller = $errorController;
		$action = $errorAction;
		$urlArray = array();
	}

	/*
	VERIFY THE CONTROLLER NAME
	*/
	if (!ctype_alnum($controller) or (strcmp($controller,'') === 0))
	{
		Params::$currentApplication = null;
		$controller = $errorController;
		$action = $errorAction;
		$urlArray = array();
	}

	//check that the controller class belongs to the application/controllers folder
	//otherwise set the controller to the default controller
	// 	if (!file_exists(ROOT.DS.APPLICATION_PATH.DS.'Controllers'.DS.ucwords($controller).'Controller.php') and !file_exists(ROOT.DS.APPLICATION_PATH.DS.getApplicationPath().'Controllers'.DS.ucwords($controller).'Controller.php'))
	if (!file_exists(ROOT.DS.APPLICATION_PATH.DS.getApplicationPath().'Controllers'.DS.ucwords($controller).'Controller.php'))
	{
		Params::$currentApplication = null;
		$controller = $errorController;
		$action = $errorAction;
		$urlArray = array();
	}

	//set the controller class to DEFAULT_CONTROLLER if it doesn't exists
	if (!class_exists(ucwords($controller).'Controller'))
	{
		Params::$currentApplication = null;
		$controller = $errorController;
		$action = $errorAction;
		$urlArray = array();
	}

	//set the action to DEFAULT_ACTION if it doesn't exists
	if (!method_exists(ucwords($controller).'Controller', $action))
	{
		Params::$currentApplication = null;
		$controller = $errorController;
		$action = $errorAction;
		$urlArray = array();
	}
	
	array_shift($urlArray);
	$queryString = $urlArray;
	//set the name of the application
	$controllerName = $controller;
	$controller = ucwords($controller);
	$model = $controller;
	$controller .= 'Controller';
	$model .= 'Model';

// 	echo $controller."-".$action;
	//include the file containing the set of actions to carry out before the initialization of the controller class
	Hooks::load(ROOT . DS . APPLICATION_PATH . DS . 'Hooks' . DS . 'BeforeInitialization.php');

	if (class_exists($controller))
	{
		$dispatch = new $controller($model,$controllerName,$queryString, getApplicationName(), $action);
		
		//pass the action to the controller object
		$dispatch->action = $action;
		
		$dispatch->currPage = $dispatch->baseUrl.'/'.$dispatch->controller.'/'.$dispatch->action;
		if (isset($currentUrl))
		{
			$dispatch->currPage = $dispatch->baseUrl.'/'.$currentUrl;
		}
		
		//require the file containing the set of actions to carry out after the initialization of the controller class
		Hooks::load(ROOT . DS . APPLICATION_PATH . DS . 'Hooks' . DS . 'AfterInitialization.php');

		$templateFlag= true;

		if (method_exists($dispatch, $action) and is_callable(array($dispatch, $action)))
		{
			//pass the action to the theme object
			$dispatch->theme->action = $action;
			$dispatch->theme->currPage = $dispatch->baseUrl.'/'.$dispatch->controller.'/'.$dispatch->action;
			if (isset($currentUrl))
			{
				$dispatch->theme->currPage = $dispatch->baseUrl.'/'.$currentUrl;
			}
		
			call_user_func_array(array($dispatch,$action),$queryString);
		}
		else
		{
			$templateFlag= false;
		}

		if ($templateFlag)
		{
			$dispatch->theme->render();
		}

	}
	else
	{
		echo "<h2>the '$controller' controller is not present!</h2>";
	}

}


//rewrite the URL
function rewrite($url)
{
	foreach (Route::$map as $key => $address)
	{
		$oldKey = $key;
		$key = str_replace('\/','/',$key);
		$key = str_replace('/','\/',$key);
		
		$regExpr = Params::$exactUrlMatchRewrite ? '/^'.$key.'$/' : '/^'.$key.'/';

		if (preg_match($regExpr,$url))
		{
			$nurl = preg_replace('/^'.$key.'/',$address,$url);
			return array($nurl,$oldKey);
// 			return preg_replace('/^'.$key.'/',$address,$url);
		}
	}
// 	return $url;
	return array($url,null);
}

function getQueryString()
{

	if (strstr($_SERVER['REQUEST_URI'],'index.php/'))
	{
		return Params::$mbStringLoaded === true ? mb_substr(mb_strstr($_SERVER['REQUEST_URI'],'index.php/'),10) : substr(strstr($_SERVER['REQUEST_URI'],'index.php/'),10);
	}

	return '';
}

// function __autoload($className)
function EG_autoload($className)
{
	$backupName = $className;

	if (strstr($className,'_'))
	{
		$parts = explode('_',$className);
		$className = implode(DS,$parts);
	}

	if (file_exists(LIBRARY . DS . 'Library' . DS . $className . '.php'))
	{
		require_once(LIBRARY . DS . 'Library' . DS . $className . '.php'); 
	}
	else if (getApplicationName() and file_exists(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Controllers' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Controllers' . DS . $backupName . '.php');
	}
	else if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Controllers' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . 'Controllers' . DS . $backupName . '.php');
	}
	else if (getApplicationName() and file_exists(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Models' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Models' . DS . $backupName . '.php');
	}
	else if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . 'Models' . DS . $backupName . '.php');
	}
	else if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Modules' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . 'Modules' . DS . $backupName . '.php');
	}
	else if (getApplicationName() and file_exists(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Strings' . DS . $backupName . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . getApplicationPath() . 'Strings' . DS . $backupName . '.php');
	}
	else if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Strings' . DS . $className . '.php'))
	{
		require_once(ROOT . DS . APPLICATION_PATH . DS . 'Strings' . DS . $className . '.php');
	}
	
}

try {

	spl_autoload_register('EG_autoload');
	
	// Custom autoload
	if (function_exists("Custom_autoload"))
		spl_autoload_register('Custom_autoload');
	
	$_POST = fixArray($_POST);
	$_GET = fixArray($_GET);
	
	//check the length of the $_POST values
	checkPostLength();
	
	//check the length of the REQUEST_URI
	checkRequestUriLength();
	
	//connect to the database
	Factory_Db::getInstance(DATABASE_TYPE,array(HOST,USER,PWD,DB));
	
	//set htmlentities charset
	switch (DEFAULT_CHARSET)
	{
		case 'SJIS':
			Params::$htmlentititiesCharset = 'Shift_JIS';
			break;
	}

	$allowedCharsets = array('UTF-8','ISO-8859-1','EUC-JP','SJIS');
	if (!in_array(DEFAULT_CHARSET,$allowedCharsets)) die('charset not-allowed');

	//check if the mbstring extension is loaded
	if (extension_loaded('mbstring'))
	{
		//set the internal encoding
		mb_internal_encoding(DEFAULT_CHARSET);
		Params::$mbStringLoaded = true;
	}
	
	//load the files defined inside Config/Autoload.php
	foreach (Autoload::$files as $file)
	{
		$extArray = explode('.', $file);
		$ext = strtolower(end($extArray));
		
		$path = ROOT . DS . APPLICATION_PATH . DS . 'Include' . DS . $file;
		if (file_exists($path) and $ext === 'php')
		{
			require_once($path);
		}
	}

	//include the file containing the set of actions to carry out before the check of the super global array
	Hooks::load(ROOT . DS . APPLICATION_PATH . DS . 'Hooks' . DS . 'BeforeChecks.php');

	//sanitize super global arrays
	sanitizeSuperGlobal();

	//report errors
	ErrorReporting();

	//verify that register globals is not active
	checkRegisterGlobals();

	//call the main hook
	callHook();

	//disconnect to the database
	Factory_Db::disconnect(DATABASE_TYPE);

} catch (Exception $e) {

	echo '<div class="alert">Message: '.$e->getMessage().'</div>';

}
