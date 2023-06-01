<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class Lang_It_ValCondStrings extends Lang_En_ValCondStrings {
	
	//if the element is not defined
	public function getNotDefinedResultString($element)
	{
		return "<div class='evidenzia'>class_$element</div>\n";
	}
	
	//if the elements are not equal
	public function getNotEqualResultString($element)
	{
		return "<div class='alert'>Differenti valori: $element</div>\n";
	}
	
	//if the element is not alphabetic
	public function getNotAlphabeticResultString($element)
	{
		return "<div class='alert'>".$element." deve essere una stringa di soli caratteri alfabetici</div>\n";
	}

	//if the element is not alphanumeric
	public function getNotAlphanumericResultString($element)
	{
		return "<div class='alert'>".$element." deve essere una stringa di soli caratteri alfanumerici</div>\n";
	}
	
	//if the element is not a decimal digit
	public function getNotDecimalDigitResultString($element)
	{
		return "<div class='alert'>".$element." deve essere una stringa di soli numeri decimali</div>\n";
	}

	//if the element has the mail format
	public function getNotMailFormatResultString($element)
	{
		return "<div class='alert'>".$element." non sembra un indirizzo e-mail</div>\n";
	}

	//if the element is numeric
	public function getNotNumericResultString($element)
	{
		return "<div class='alert'>".$element." deve essere un numero</div>\n";
	}

	//if the element (string) length exceeds the value of characters (defined by $maxLength)
	public function getLengthExceedsResultString($element,$maxLength)
	{
		return "<div class='alert'>".$element." non deve essere composto da pi&ugrave di $maxLength caratteri</div>\n";
	}

	//if the element is one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsForbiddenStringResultString($element,$stringList)
	{
		return "<div class='alert'>".$element." non pu&ograve assumere uno dei seguenti valori: $stringList</div>\n";
	}

	//if the element is not one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsNotStringResultString($element,$stringList)
	{
		return "<div class='alert'>".$element." deve assumere uno dei seguenti valori: $stringList</div>\n";
	}

	//if the element is not one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getDoesntMatchResultString($element,$regExp)
	{
		return "<div class='alert'>".$element." deve soddisfare la seguente espressione regolare: $regExp</div>\n";
	}
	
	//if the element is not a real date
	public function getNotDateResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>".$element."</i> deve essere una data (YYYY-MM-DD)</div>\n";
	}
	
	//if the element is not an integer
	public function getNotIntegerFormatResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>".$element."</i> deve essere un intero</div>\n";
	}
	
	//if the element is not decimal
	public function getNotDecimalResultString($element, $format)
	{
		$t = explode(",",$format);
		$M = (int)$t[0];
		$D = (int)$t[1];
		$I = $M - $D;
		return "<div class='".Params::$errorStringClassName."'><i>".$element."</i> deve essere un numero decimale (numero massimo di cifre intere:$I, numero massimo di cifre decimali:$D)</div>\n";
	}
}
