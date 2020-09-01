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

//class to parse an XML text in order to create the modules corresponding to the elements of the XML text.
//the <type>module name</type> tag defines the name of the object that has to be instantiate and saved in the
//$modules property (that is an array referencing different module objects) array(moduleObj1,moduleObj2, ...)
//if the module class corresponding ot the <type>module name</type> tag does not exists, than no module is created and the next <type>module name</type> is checked
class BoxParser {

	public $modules = array(); //array referencing different module classes --> array(moduleObj1,moduleObj2, ...) See files inside the Application/Modules folder
	
	//$simpleXMLText: it has to be an XML text
	//$type; it can be string or file.
	public function __construct($simpleXMLText, $type = 'string')
	{
		if ($type === 'string')
		{
			if (@simplexml_load_string($simpleXMLText))
			{
				$simpleXmlObj = simplexml_load_string($simpleXMLText);
				$this->populate($simpleXmlObj);
			}
		}
		else if ($type === 'file')
		{
			if (@simplexml_load_file($simpleXMLText))
			{
				$simpleXmlObj = simplexml_load_file($simpleXMLText);
				$this->populate($simpleXmlObj);
			}	
		}
	}

	//inistantiate the module objects and save them in the $this->modules property array
	private function populate($simpleXmlObj)
	{
		foreach ($simpleXmlObj as $mod)
		{
			$className = 'Mod'.ucwords((string)$mod->type);
			if (class_exists($className))
			{
				if (file_exists(ROOT . DS . APPLICATION_PATH . DS . 'Modules' . DS . $className . '.php'))
				{
					$newModule = new $className($mod);
					if ($newModule instanceof ModAbstract)
					{
						$this->modules[] = $newModule;
					}
				}
			}
		}
	}

	//create the HTML of the modules
	public function render()
	{
		$HTML = null;
		foreach ($this->modules as $module)
		{
			$HTML .= $module->render();
		}
		return $HTML;
	}

}
