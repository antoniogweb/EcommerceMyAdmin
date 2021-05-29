<?php

// MvcMyLibrary is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2014  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of MvcMyLibrary
//
// MvcMyLibrary is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// MvcMyLibrary is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with MvcMyLibrary.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Lang_It_ValCondStrings extends Lang_En_ValCondStrings {
	
	public function getHiddenAlertElement($element)
	{
		if (GenericModel::$apiMethod == "POST")
			return "\n".parent::getHiddenAlertElement($element);
		
		return "";
	}
	
	//if the element is not defined
	public function getNotDefinedResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Si prega di riempire il campo ". getFieldLabel($element) ."</i></div>".$this->getHiddenAlertElement($element);
	}
	
	//if the elements are not equal
	public function getNotEqualResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'>Sia prega di controllare che i campi <i>".getFieldLabel($element)."</i> abbiamo lo stesso valore</div>\n".$this->getHiddenAlertElement($element);
	}
	
	//if the element is not alphabetic
	public function getNotAlphabeticResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia una stringa di soli caratteri alfabetici</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element is not alphanumeric
	public function getNotAlphanumericResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia una stringa di soli caratteri alfanumerici</div>\n".$this->getHiddenAlertElement($element);
	}
	
	//if the element is not a decimal digit
	public function getNotDecimalDigitResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia una stringa di soli numeri decimali</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element hasn't the mail format
	public function getNotMailFormatResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia un indirizzo e-mail</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element is not numeric
	public function getNotNumericResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia un numero</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element is not an integer
	public function getNotIntegerFormatResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia un numero intero</div>\n".$this->getHiddenAlertElement($element);
	}
	
	//if the element is not a real date
	public function getNotDateResultString($element)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia una data</div>\n".$this->getHiddenAlertElement($element);
	}
	
	//if the element (string) length exceeds the value of characters (defined by $maxLength)
	public function getLengthExceedsResultString($element,$maxLength)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> non sia composto da più di $maxLength caratteri</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element is one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsForbiddenStringResultString($element,$stringList)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> non sia uguale ad uno dei seguenti valori: $stringList</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element is not one of the strings indicated by $stringList (a comma-separated list of strings)
	public function getIsNotStringResultString($element,$stringList)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia uguale ad uno dei seguenti valori: $stringList</div>\n".$this->getHiddenAlertElement($element);
	}

	//if the element does not match the reg expr indicated by $regExp
	public function getDoesntMatchResultString($element,$regExp)
	{
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> soddisfi la seguente espressione regolare: $regExp</div>\n".$this->getHiddenAlertElement($element);
	}
	
	//if the element is not decimal
	public function getNotDecimalResultString($element, $format)
	{
		$t = explode(",",$format);
		$M = (int)$t[0];
		$D = (int)$t[1];
		$I = $M - $D;
		return "<div class='".Params::$errorStringClassName."'><i>Sia prega di controllare che il campo ".getFieldLabel($element)."</i> sia un numero decimale (numero massimo di cifre intere:$I, numero massimo di cifre decimali:$D)</div>\n".$this->getHiddenAlertElement($element);
	}
}
