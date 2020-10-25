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

class Theme {

	protected $_data = array();
	protected $_viewFiles = array(); //view files to require
	protected $_lastView = null;
	
	public $baseUrl = null; //the base url of the website: http://domainname
	public $baseUrlSrc = null; //the base url of the website (http://domainname) in the case MOD_REWRITE_MODULE has been set to false

	public $viewArgs = array();
	public $viewStatus = '';
	public $controller = 'controller';
	public $application = null;
	public $applicationUrl = null; //the url of the application
	public $action = '';
	public $currPage; //the URL of the current page
	
	function __construct($application, $controller) {
	
		$this->controller = $controller;
		$this->application = $application;
		
		$langUrl = isset(Params::$lang) ? "/".Params::$lang : null;
		$protocol = Params::$useHttps ? "https" : "http";
		
		$this->baseUrl = MOD_REWRITE_MODULE === true ? "$protocol://" . DOMAIN_NAME . $langUrl : "$protocol://" . DOMAIN_NAME . '/index.php' . $langUrl;
		
		$this->baseUrlSrc = "$protocol://" . DOMAIN_NAME;
		
		$this->applicationUrl = isset($application) ? $application . "/" : null;
	}


	public function set($values)
	{
		$this->_data = $values;
	}

	public function append($values)
	{
		$this->_data = array_merge($this->_data,$values);
	}

	//clean the $this->viewFiles array
	public function clean() {
		$this->_viewFiles = array();
		$this->_lastView = null;
	}

	public function load($fileName,$option = 'none') {
		if ((strcmp($option,'last') !== 0) and (strcmp($option,'none') !== 0)) {
			throw new Exception('"'.$option. '" argument not allowed in '.__METHOD__.' method');
		}
		if ($option === 'last') {
			$this->_lastView = $fileName;
		} else {
			$this->_viewFiles[] = $fileName;
		}
	}

	//change $old view file with $new view file
	public function changeViewFile($old, $new)
	{
		for ($i=0; $i < count($this->_viewFiles); $i++)
		{
			if (strcmp($this->_viewFiles[$i],$old) === 0)
			{
				$this->_viewFiles[$i] = $new;
			}
		}
		
		if (strcmp($this->_lastView,$old) === 0)
		{
			$this->_lastView = $new;
		}
	}
	
	public function viewPath($file)
	{
		//find the View subfolder where to look for view files
		$subfolder = isset(Params::$viewSubfolder) ? Params::$viewSubfolder . DS : null;
		
		if (isset($this->application) and file_exists(ROOT . DS . APPLICATION_PATH . DS . "Apps" . DS . ucfirst($this->application). DS . 'Views' . DS .$subfolder. ucwords($this->controller) . DS . $file . '.php'))
		{
			return ROOT . DS . APPLICATION_PATH . DS . "Apps" . DS . ucfirst($this->application). DS . 'Views' . DS . $subfolder. ucwords($this->controller) . DS . $file . '.php';
		}
		else if (isset($this->application) and file_exists(ROOT . DS . APPLICATION_PATH . DS . "Apps" . DS . ucfirst($this->application). DS . 'Views' . DS . $subfolder. $file . '.php'))
		{
			return ROOT . DS . APPLICATION_PATH . DS . "Apps" . DS . ucfirst($this->application). DS . 'Views' . DS .$subfolder. $file . '.php';
		}
		else if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Views' . DS .$subfolder. ucwords($this->controller) . DS . $file . '.php')) {
			return ROOT . DS . APPLICATION_PATH . DS . 'Views' . DS .$subfolder. ucwords($this->controller) . DS . $file . '.php';
		} else {
			return ROOT . DS . APPLICATION_PATH . DS . 'Views' . DS .$subfolder. $file . '.php';
		}
	}
	
	public function render() {
		extract($this->_data);

		foreach ($this->_viewFiles as $file) {
			include ($this->viewPath($file));
		}

		if (isset($this->_lastView)) {
			include ($this->viewPath($this->_lastView));
		}

    }

}
