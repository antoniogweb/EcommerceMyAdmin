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

//class to write the top menù of the view files
class Helper_Menu extends Helper_Html
{
	public static $htmlLinks = array();
	
	public $panelController; //panel controller
	public $controller;

	public $links = array();

	//instance of Lang_{language}_Generic
	public $strings = null;
	
	public function __construct()
	{
		$baseUrl = "http://".DOMAIN_NAME.'/Public/Img/Icons/elementary_2_5/';

		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);
		
		$this->links = array(

			'back'	=>	array(
				'title'	=>	$this->strings->gtext('back'),
				'class'	=>	'mainMenuItem',
				'text'	=>	$this->strings->gtext('Back'),
				'url'	=>	'main',
				'icon'	=>	$baseUrl."left.png",
				'queryString'	=>	null,
			),

			'add'	=>	array(
				'title'	=>	$this->strings->gtext('add a new record'),
				'class'	=>	'mainMenuItem',
				'text'	=>	$this->strings->gtext('Add'),
				'url'	=>	'form/insert',
				'icon'	=>	$baseUrl."add.png",
				'queryString'	=>	null,
			),

			'panel'	=>	array(
				'title'	=>	$this->strings->gtext('back to the Panel'),
				'class'	=>	'mainMenuItem',
				'text'	=>	$this->strings->gtext('Panel'),
				'url'	=>	'main',
				'icon'	=>	$baseUrl."panel.png",
				'queryString'	=>	null,
			)

		);
		
		foreach (self::$htmlLinks as $k => $v)
		{
			if (!array_key_exists($k, $this->links))
			{
				$this->links[$k] = $v;
			}
			else
			{
				foreach ($v as $subK => $subV)
				{
					$this->links[$k][$subK] = $subV;
				}
			}
		}
	}
	
	public function build($controller = null, $panelController = null, $model = null)
	{
		$this->controller = $controller;
		$this->panelController = $panelController;
		$this->model = $model;
	}

	//$voices: comma-separated list of links you want to print 
	public function render($linksList)
	{
		$linksArray = explode(',',$linksList);
		$menu = null;
		foreach ($linksArray as $linkName)
		{
			//check that the voice exists
			if (array_key_exists($linkName,$this->links))
			{
				//check that the text and the ure are defined
				if (isset($this->links[$linkName]['text']) and isset($this->links[$linkName]['url']))
				{
					$title = isset($this->links[$linkName]['title']) ? "title=\"".$this->links[$linkName]['title']."\"" : null;
					
					$class = isset($this->links[$linkName]['class']) ? "class='".$this->links[$linkName]['class']."'" : null;
					$class = isset(self::$htmlLinks[$linkName]["class"]) ? "class='".self::$htmlLinks[$linkName]["class"]."'" : $class;
					
					$icon = isset($this->links[$linkName]['icon']) ? "<img class='top_menu_icon' src='".$this->links[$linkName]['icon']."'> " : null;
					
					//choose the controller (current or panel)
					$controller = (strcmp($linkName,'panel') === 0) ? $this->panelController.'/' : $this->controller.'/';
					
					if (isset($this->links[$linkName]['controller']))
					{
						$controller = rtrim($this->links[$linkName]['controller'],"/")."/";
					}
					
					$viewStatus = (strcmp($linkName,'panel') === 0) ? null : $this->viewStatus;
					
					$href = Url::getRoot($controller.$this->links[$linkName]['url'].$viewStatus);
					
					$qString = Params::$rewriteStatusVariables ? "?" : "";
					
					if (isset($this->links[$linkName]['queryString']))
					{
						parse_str($this->links[$linkName]['queryString'], $qsArray);
						
						$qStringArray = array();
						
						foreach ($qsArray as $k => $v)
						{
							if (array_key_exists($k,$this->viewArgs))
							{
								$this->viewArgs[$k] = $v;
							}
							else
							{
								$qStringArray[] = "$k=$v";
							}
						}
						
						$qString = !empty($qStringArray) ? "&".implode("&",$qStringArray) : "";
						
						$viewStatus = Url::createUrl($this->viewArgs);
					}
					
					$href = Url::getRoot($controller.$this->links[$linkName]['url'].$viewStatus.$qString);
					
					$text = $this->links[$linkName]['text'];
					$htmlBefore = isset($this->links[$linkName]["htmlBefore"]) ? $this->links[$linkName]["htmlBefore"] : "<div $class>$icon ";
					$htmlAfter = isset($this->links[$linkName]["htmlAfter"]) ? $this->links[$linkName]["htmlAfter"] : "</div>";
					$attributes = isset($this->links[$linkName]["attributes"]) ? $this->links[$linkName]["attributes"] : null;
					
					$menu .= "$htmlBefore<a $title $attributes href='$href'>$text</a>$htmlAfter\n";
				}
			}
		}
		return $menu;
	}

}
