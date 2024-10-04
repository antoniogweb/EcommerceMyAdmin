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

class Traduttore
{
	use Modulo;

	public static $marchi = null;

	public function estraiPlaceholder($matches)
	{
		$numero = count($this->placeholders) + 1;
		$token = "ABCD".$numero;

		$this->placeholders[$token] = $matches[1];

		return "[$token]";
	}

	public function ripristinaMarchio($matches)
	{
		self::getMarchi();

		if (isset(self::$marchi[$matches[1]]))
		{
			if (isset($matches[2]))
				return self::$marchi[$matches[1]].$matches[2];
			else
				return self::$marchi[$matches[1]];
		}
	}

	public function ripristinaMarchioBefore($matches)
	{
		self::getMarchi();

		if (isset($matches[2]) && isset(self::$marchi[$matches[2]]))
			return $matches[1].self::$marchi[$matches[2]];
		else if (isset($matches[1]) && isset(self::$marchi[$matches[1]]))
			return self::$marchi[$matches[1]];
	}

	public function ripristinaPlaceholderAfter($matches)
	{
		if (isset($matches[2]))
			return "[".$this->placeholders[$matches[1]]."]".$matches[2];
		else
			return "[".$this->placeholders[$matches[1]]."]";
	}

	public function ripristinaPlaceholderBefore($matches)
	{
		if (isset($matches[2]) && isset($this->placeholders[$matches[2]]))
			return $matches[2]."[".$this->placeholders[$matches[2]]."]";
		else if (isset($matches[1]) && isset($this->placeholders[$matches[1]]))
			return "[".$this->placeholders[$matches[1]]."]";
	}

	public function ripristinaPlaceholder($testo)
	{
		foreach ($this->placeholders as $token => $placeholder)
		{
			$testo = preg_replace_callback('/\[('.$token.')(.*?)?\]/', array($this, "ripristinaPlaceholderAfter") ,$testo);
			$testo = preg_replace_callback('/\[(.*?)?('.$token.')\]/', array($this, "ripristinaPlaceholderBefore") ,$testo);
		}

		$testo = preg_replace_callback('/\[EFGH\_([0-9]{1,})(.*?)?\]/', array($this, "ripristinaMarchio") ,$testo);
		$testo = preg_replace_callback('/\[(.*?)?EFGH\_([0-9]{1,})\]/', array($this, "ripristinaMarchioBefore") ,$testo);

		$testo = preg_replace_callback('/\[EGH\_([0-9]{1,})(.*?)?\]/', array($this, "ripristinaMarchio") ,$testo);
		$testo = preg_replace_callback('/\[(.*?)?EGH\_([0-9]{1,})\]/', array($this, "ripristinaMarchioBefore") ,$testo);

		$testo = str_replace("<br /> <br /> <br /> <br />", "<br />", $testo);
		$testo = str_replace("<br /> <br /> <br />", "<br />", $testo);
		$testo = str_replace("<br /> <br />", "<br />", $testo);

		return $testo;
	}

	public static function getMarchi()
	{
		if (!isset(self::$marchi))
		{
			$marchi = MarchiModel::g()->clear()->select("id_marchio,titolo")->send(false);

			foreach ($marchi as $marchio)
			{
				self::$marchi[$marchio["id_marchio"]] = htmlentitydecode($marchio["titolo"]);
			}
		}

		return self::$marchi;
	}

	public static function estraiMarchi($testo)
	{
		self::getMarchi();

		foreach (self::$marchi as $id => $marchio)
		{
			$testo = str_ireplace(" ".$marchio, "[EFGH_".$id."]", $testo);
			$testo = str_ireplace($marchio." ", "[EFGH_".$id."]", $testo);
		}

		return $testo;
	}

	public function elaboraLink($testo, $linguaCorrente)
	{
		$dom = new DomDocument();
		$dom->loadHTML($testo);

		$cModel = new CategoriesModel();

		foreach ($dom->getElementsByTagName('a') as $item) {
			$href = $item->getAttribute('href');
			$link = $item->c14n();

			if (strpos($href, ".html") !== false)
			{

			}
			else
			{
				$href = rtrim($href, "/");

				$aliasArray = explode("/", $href);

				if (count($aliasArray) > 0)
				{
					$alias = $aliasArray[count($aliasArray) - 1];

					$idC = (int)$cModel->getIdFromAlias($alias, $linguaCorrente);

					if ($idC)
						$testo = str_replace($link, "[LCAT_$idC]", $testo);
				}
			}
		}

		return $testo;
	}

	public function elaboraTesto($testo, $linguaCorrente)
	{
		$this->placeholders = [];

		$testo = preg_replace_callback('/\[([0-9a-zA-Z\_\-\s]{1,})\]/', array($this, "estraiPlaceholder") ,$testo);

		$testo = self::estraiMarchi($testo);

		$testo = $this->elaboraLink($testo, $linguaCorrente);

		return $testo;
	}
}
