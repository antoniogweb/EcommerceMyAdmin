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
		$numero = 1000 - count($this->placeholders) + 1;
		$token = "ABCD".$numero;

		$this->placeholders[$token] = $matches[1];

		return "#$token";
	}

	// public function ripristinaMarchio($matches)
	// {
	// 	self::getMarchi();
 //
	// 	if (isset(self::$marchi[$matches[1]]))
	// 	{
	// 		if (isset($matches[2]))
	// 			return self::$marchi[$matches[1]].$matches[2];
	// 		else
	// 			return self::$marchi[$matches[1]];
	// 	}
	// }

	// public function ripristinaMarchioBefore($matches)
	// {
	// 	self::getMarchi();
 //
	// 	if (isset($matches[2]) && isset(self::$marchi[$matches[2]]))
	// 		return $matches[1].self::$marchi[$matches[2]];
	// 	else if (isset($matches[1]) && isset(self::$marchi[$matches[1]]))
	// 		return self::$marchi[$matches[1]];
	// }

	// public function ripristinaPlaceholderAfter($matches)
	// {
	// 	if (isset($matches[2]))
	// 		return "[".$this->placeholders[$matches[1]]."]".$matches[2];
	// 	else
	// 		return "[".$this->placeholders[$matches[1]]."]";
	// }
 //
	// public function ripristinaPlaceholderBefore($matches)
	// {
	// 	if (isset($matches[2]) && isset($this->placeholders[$matches[2]]))
	// 		return $matches[2]."[".$this->placeholders[$matches[2]]."]";
	// 	else if (isset($matches[1]) && isset($this->placeholders[$matches[1]]))
	// 		return "[".$this->placeholders[$matches[1]]."]";
	// }

	public function ripristinaPlaceholder($testo)
	{
		foreach ($this->placeholders as $token => $placeholder)
		{
			$testo = str_replace("#$token", "[$placeholder]", $testo);

			// $testo = preg_replace_callback('/\[('.$token.')(.*?)?\]/', array($this, "ripristinaPlaceholderAfter") ,$testo);
			// $testo = preg_replace_callback('/\[(.*?)?('.$token.')\]/', array($this, "ripristinaPlaceholderBefore") ,$testo);
		}

		$testo = str_replace("#MARCHIO_", "", $testo);
		$testo = str_replace("__", " ", $testo);

		// $testo = preg_replace_callback('/\[EFGH\_([0-9]{1,})(.*?)?\]/', array($this, "ripristinaMarchio") ,$testo);
		// $testo = preg_replace_callback('/\[(.*?)?EFGH\_([0-9]{1,})\]/', array($this, "ripristinaMarchioBefore") ,$testo);
  //
		// $testo = preg_replace_callback('/\[EGH\_([0-9]{1,})(.*?)?\]/', array($this, "ripristinaMarchio") ,$testo);
		// $testo = preg_replace_callback('/\[(.*?)?EGH\_([0-9]{1,})\]/', array($this, "ripristinaMarchioBefore") ,$testo);

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

	public function estraiMarcho($matches)
	{
		if (isset($matches[3]))
			return $matches[1]."#MARCHIO_".str_replace(" ","__",$matches[2]).$matches[3];
		else if (isset($matches[2]))
			return $matches[1]."#MARCHIO_".str_replace(" ","__",$matches[2]);

		return "";
	}

	public function estraiMarchoBefore($matches)
	{
		return "#MARCHIO_".str_replace(" ","__",$matches[1]).$matches[2];
	}

	public function estraiMarchi($testo)
	{
		self::getMarchi();

		foreach (self::$marchi as $id => $marchio)
		{
			$testo = preg_replace_callback('/(\s|\;|\.|\,|>)('.preg_quote($marchio, "/").')(<|\s|\;|\.|\,)/i', array($this, "estraiMarcho") ,$testo);
			$testo = preg_replace_callback('/(\s|\;|\.|\,|>)('.preg_quote($marchio, "/").')$/i', array($this, "estraiMarcho") ,$testo);
			$testo = preg_replace_callback('/^('.preg_quote($marchio, "/").')(<|\s|\;|\.|\,)/i', array($this, "estraiMarchoBefore") ,$testo);
		}

		return $testo;
	}

	public function elaboraLink($testo, $linguaCorrente)
	{
		$dom = new DomDocument();
		$dom->loadHTML('<meta charset="UTF-8">'.$testo);

		$cModel = new CategoriesModel();
		$pModel = new PagesModel();
		$mModel = new MarchiModel();

		User::$adminLogged = true;

		foreach ($dom->getElementsByTagName('a') as $item) {
			$href = $item->getAttribute('href');
			$link = $item->c14n();
			$titolo = $item->nodeValue;

			$urlArray = parse_url($href);

			if (isset($urlArray["path"]))
			{
				if (strpos($urlArray["path"], ".html") !== false)
				{
					$aliasArray = explode("/", rtrim($urlArray["path"], "/"));

					if (count($aliasArray) > 0)
					{
						$aliasArray = explode(".", $aliasArray[count($aliasArray) - 1]);

						if (count($aliasArray) > 0)
						{
							$alias = $aliasArray[0];

							if (trim($alias))
							{
								$idPages = $pModel->getIdFromAlias(trim($alias), $linguaCorrente);

								if (count($idPages) > 0)
									$testo = str_replace($link, "[LPAG_".(int)$idPages[0]."]", $testo);
							}
						}
					}
				}
				else
				{
					$urlArray["path"] = rtrim($urlArray["path"], "/");

					$aliasArray = explode("/", $urlArray["path"]);

					if (count($aliasArray) > 0)
					{
						$idMarchio = 0;

						if (v("usa_marchi"))
						{
							$aliasMarchi = MarchiModel::getElencoAliasId();

							$aliasArrayFinale = array();

							foreach ($aliasArray as $aliasE)
							{
								if (isset($aliasMarchi[$aliasE]))
									$idMarchio = $aliasMarchi[$aliasE];
								else
									$aliasArrayFinale[] = $aliasE;
							}
						}

						if (count($aliasArrayFinale) > 0)
							$alias = $aliasArrayFinale[count($aliasArrayFinale) - 1];

						if (trim($alias))
						{
							$idC = (int)$cModel->getIdFromAlias(trim($alias), $linguaCorrente);

							if ($idC)
								$testo = str_replace($link, "[LCAT_".$idC."_".$idMarchio."_$titolo]", $testo);
							else if ($idMarchio)
							{
								$idShop = (int)$cModel->getShopCategoryId();
								$testo = str_replace($link, "[LCAT_".$idShop."_".$idMarchio."_$titolo]", $testo);
							}
						}
					}
				}
			}
		}

		return $testo;
	}

	public function elaboraTesto($testo, $linguaCorrente)
	{
		$this->placeholders = [];

		$testo = preg_replace_callback('/\[([0-9a-zA-Z\_\-\s]{1,})\]/', array($this, "estraiPlaceholder") ,$testo);

		$testo = $this->estraiMarchi($testo);

		echo $testo."\n\n\n\n";

		$testo = $this->elaboraLink($testo, $linguaCorrente);

		return $testo;
	}
}
