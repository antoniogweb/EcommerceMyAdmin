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

class Lang_Eng_ValCondStrings {
	
	//if the element is not defined
	public function getNotDefinedResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>". $element ." not defined!</div>\n";
	}
	
	//if the elements are not equal
	public function getNotEqualResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>Different values: $element</div>\n";
	}
	
	//if the element is not alphabetic
	public function getNotAlphabeticResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be alphabetic</div>\n";
	}

	//if the element is not alphanumeric
	public function getNotAlphanumericResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be alphanumeric</div>\n";
	}
	
	//if the element is not a decimal digit
	public function getNotDecimalDigitResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be a decimal digit</div>\n";
	}

	//if the element hasn't the mail format
	public function getNotMailFormatResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." doesn't seem an e-mail address</div>\n";
	}

	//if the element is not numeric
	public function getNotNumericResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be a numeric</div>\n";
	}
	
	//if the element is not an integer
	public function getNotIntegerFormatResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be an integer</div>\n";
	}
	
	//if the element is not a real date
	public function getNotDateResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be a real date (YYYY-MM-DD)</div>\n";
	}
	
	//if the element (string) length exceeds the value of characters (defined by $maxLength)
	public function getLengthExceedsResultString($element,$maxLength)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." exceeds the value of $maxLength characters</div>\n";
	}

	//if the element is one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsForbiddenStringResultString($element,$stringList)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." can't be one of the following strings: $stringList</div>\n";
	}

	//if the element is not one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsNotStringResultString($element,$stringList)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to be one of the following strings: $stringList</div>\n";
	}

	//if the element does not match the reg expr indicated by $regExp
	public function getDoesntMatchResultString($element,$regExp)
	{
		return "<div class='".Params::$errorStringClassName."'>".$element." has to match the following regular expression: $regExp</div>\n";
	}
	
	//if the element is not decimal
	public function getNotDecimalResultString($element, $format)
	{
		$t = explode(",",$format);
		$M = (int)$t[0];
		$D = (int)$t[1];
		$I = $M - $D;
		return "<div class='".Params::$errorStringClassName."'><i>".$element."</i> has to be a decimal number (maximum number of integer digits:$I, maximum number of decimal digits: $D)</div>\n";
	}
	
}
