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

//Helper class to create the HTML of the page list
class Helper_Pages extends Helper_Html
{

	protected $_urlViewAction; //url of the current page
	protected $_currentPage; //number of the page
	protected $_numbOfPages; //number of pages
	protected $_variableArg = ''; //value of the $viewArgs key that has to be modified

	public $previousString = null; //string of the link to the previous page
	public $nextString = null; //string of the link to the next page
	public $showNext = true;
	public $showPrev = true;
	
	public $previousClass = "";
	public $nextClass = "";
	public $currentClass = "";
	public $linkClass = "";
	public $dividerPre = "";
	public $dividerPost = "";
	public $showDivider = "";
	
	public $showFirstLast = false;
	public $firstLastDividerHtml = "";
	
	public static $staticPreviousClass = "previous_page";
	public static $staticNextClass = "next_page";
	public static $staticCurrentClass = "currentPage";
	public static $staticLinkClass = "itemListPage";
	public static $staticDividerPre = "";
	public static $staticDividerPost = "";
	public static $staticShowDivider = "none";
	public static $showOnlyNext = false;
	
	public static $staticShowFirstLast = false; //if to show the first and last element
	public static $staticFirstLastDividerHtml = ""; //html before the current pagination and the first/last element
	public static $staticPreviousString = "";
	public static $staticNextString = "";
	
	//instance of Lang_{language}_Generic
	public $strings = null;
	
	public static $pageLinkWrapClass = array();
	public static $pageLinkWrap = array();
	
// 	Ex:
// 	Helper_Pages::$pageLinkWrap = array("li");

	public function __construct()
	{
		//get the generic language class
		$this->strings = Factory_Strings::generic(Params::$language);
		
		if (isset(self::$staticPreviousClass))
		{
			$this->previousClass = self::$staticPreviousClass;
		}
		
		if (isset(self::$staticNextClass))
		{
			$this->nextClass = self::$staticNextClass;
		}
		
		if (isset(self::$staticCurrentClass))
		{
			$this->currentClass = self::$staticCurrentClass;
		}
		
		if (isset(self::$staticLinkClass))
		{
			$this->linkClass = self::$staticLinkClass;
		}
		
		if (isset(self::$staticDividerPre))
		{
			$this->dividerPre = self::$staticDividerPre;
		}
		
		if (isset(self::$staticDividerPost))
		{
			$this->dividerPost = self::$staticDividerPost;
		}
		
		if (isset(self::$staticShowDivider))
		{
			$this->showDivider = self::$staticShowDivider;
		}
		
		if (isset(self::$staticShowFirstLast))
		{
			$this->showFirstLast = self::$staticShowFirstLast;
		}
		
		if (isset(self::$staticFirstLastDividerHtml))
		{
			$this->firstLastDividerHtml = self::$staticFirstLastDividerHtml;
		}
	}
	
	public function build($urlViewAction = '' , $variableArg = 'page', $previousString = 'previous', $nextString = 'next', $model = null)
	{
		$this->_variableArg = $variableArg;
		$this->_urlViewAction =$urlViewAction; //url of the controller and (/) main action
		$this->previousString = $this->strings->gtext($previousString);
		$this->nextString = $this->strings->gtext($nextString);
		$this->model = $model;
	}

	//return the number of pages
	public function getNumbOfPages()
	{
		return $this->_numbOfPages;
	}

	//get the limit of the select query clause
	public function getLimit($currentPage,$recordNumber,$recordPerPage)
	{
		$this->_currentPage = $currentPage;
		$this->_numbOfPages=(($recordNumber%$recordPerPage)===0) ? (int) ($recordNumber/$recordPerPage) : ((int) ($recordNumber/$recordPerPage))+1;
		$start=(($currentPage-1)*$recordPerPage);
		return "$start,$recordPerPage";
	}

	//return the page list string
	public function render($pageNumber,$numberOfPages)
	{
		if (self::$staticPreviousString)
			$this->previousString = self::$staticPreviousString;
		
		if (self::$staticNextString)
			$this->nextString = self::$staticNextString;
		
		$pageList = null;
		
		if (!self::$showOnlyNext)
		{
			if ($this->showPrev)
			{
				$pageList .= $this->pageLink($this->_currentPage-1,$this->previousString);
			}
			
			if ($this->showFirstLast and $pageNumber > 1 and ($this->_numbOfPages > 1))
			{
				$pageList .= $this->pageLink("1","1");
				$pageList .= $this->firstLastDividerHtml;
			}
			
			$pageList .= $this->recursiveLink($pageNumber,$numberOfPages);
			
			if ($this->showFirstLast and ($pageNumber + $numberOfPages) <= $this->_numbOfPages)
			{
				$pageList .= $this->firstLastDividerHtml;
				$pageList .= $this->pageLink($this->_numbOfPages,$this->_numbOfPages);
			}
		}
		
		if ($this->showNext)
		{
			$pageList .= $this->pageLink($this->_currentPage+1,$this->nextString);
		}
		return $pageList;
	}

	//recorsive function in order to write the page list
	public function recursiveLink($pageNumber,$numberOfPages)
	{
		
		if ($numberOfPages === 0) return null;
		
		if ($numberOfPages === 1) {
			return $this->pageLink($pageNumber);
		} else {
			return $this->pageLink($pageNumber) . $this->recursiveLink($pageNumber+1,$numberOfPages-1);
		}
	}

	public function pageLink($pageNumber, $string = null) {
		if ($pageNumber > 0 and $pageNumber <= $this->_numbOfPages) {
			return $this->html($pageNumber,$string);
		} else {
			return null;
		}
	} 

	//return the html link
	public function html($pageNumber,$string = null) {
		if (isset($string)) {
			$strNumber = $string;
			if ((int)$pageNumber < $this->_currentPage)
			{
				$strClass = "class='".$this->linkClass." ".$this->previousClass."'";
			}
			else
			{
				$strClass = "class='".$this->linkClass." ".$this->nextClass."'";
			}
		} else {
			if (strcmp($pageNumber,$this->_currentPage) === 0)
			{
				$strNumber = $pageNumber;
				$strClass = "class='".$this->linkClass." ".$this->currentClass."'";
			}
			else
			{
				$strNumber = $pageNumber;
				$strClass = "class='".$this->linkClass."'";
			}
		}
		$this->viewArgs[$this->_variableArg] = $pageNumber;
		$viewStatus = Url::createUrl($this->viewArgs);
		$href= Url::getRoot(null) . $this->_urlViewAction .$viewStatus;
		return $this->getATag($href,$strNumber,$strClass);
	}

	//get the HTMl of the tag
	//$href: href of the link
	//$text: the text of the link
	//$strClass: the class of the link
	public function getATag($href,$text,$strClass)
	{
		switch ($this->showDivider)
		{
			case "pre":
				$dividerPre = "<span class='divider divider_pre divider_$text'>".$this->dividerPre."</span>";
				$dividerPost = "";
				break;
			case "post":
				$dividerPre = "";
				$dividerPost = "<span class='divider divider_post divider_$text'>".$this->dividerPost."</span>";
				break;
			case "both":
				$dividerPre = "<span class='divider divider_pre divider_$text'>".$this->dividerPre."</span>";
				$dividerPost = "<span class='divider divider_post divider_$text'>".$this->dividerPost."</span>";
				break;
			default:
				$dividerPre = $dividerPost = "";
				break;
		}
		
		$linkStrClass = $strClass;
		
		if (count(Helper_Pages::$pageLinkWrap) > 0 && (int)count(self::$pageLinkWrapClass) === 0)
		{
			$linkStrClass = "";
		}
		
		$html = "$dividerPre<a $linkStrClass href='$href'>$text</a>$dividerPost";
		
		$indice = 0;
		foreach (Helper_Pages::$pageLinkWrap as $k)
		{
			$strClassWrapper = isset(self::$pageLinkWrapClass[$indice]) ? "class='".self::$pageLinkWrapClass[$indice]."'" : $strClass;
			$html = "<$k $strClassWrapper>$html</$k>";
			
			$indice++;
		}
		
		return $html;
	}

}
