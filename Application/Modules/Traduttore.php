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

	public static $tabellaCorrezioni = array(
		"en"	=>	array(
			"Taglia"	=>	"Size",
			"colore"	=>	"color",
			"Color"		=>	"Color",
			"Bianco"	=>	"White",
			"Panna"		=>	"Cream",
			"Perla"		=>	"Pearl",
			"Verde"		=>	"Green",
			"Sabbia"	=>	"Sand",
			"terracotta"	=>	"fired clay",
			"Terracotta"	=>	"Fired clay",
			"Rosso"		=>	"Red",
			"Giallo"	=>	"Yellow",
			"Cielo"		=>	"Sky",
			"Lama"		=>	"Llama",
			"Terrazzo Crema"	=>	"Cream Terrace",
			"Latte"		=>	"Milk",
			"Acqua"		=>	"Water",
			"aqua"		=>	"water",
			"Fiori"		=>	"Flowers",
			"Dimensione"=>	"Size",
		),
	);

	public function estraiPlaceholder($matches)
	{
		$numero = count($this->placeholders) + 1;
		$token = "ABCDABCD".$numero;

		$this->placeholders[$token] = $matches[1];

		return "[$token]";
	}

	public function ripristinaMarchio($matches)
	{
		self::getMarchi();

		if (isset(self::$marchi[$matches[1]]))
			return self::$marchi[$matches[1]];
		// $testo = str_ireplace($marchio, "[MMM_".$id."]", $testo);
	}

	public function ripristinaPlaceholder($testo)
	{
		foreach ($this->placeholders as $token => $placeholder)
		{
			$testo = str_replace("[$token]", "[$placeholder]", $testo);
		}

		$testo = preg_replace_callback('/\[MMM\_([0-9]{1,})\]/', array($this, "ripristinaMarchio") ,$testo);

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
			$testo = str_ireplace($marchio, "[MMM_".$id."]", $testo);
		}

		return $testo;
	}

	public function elaboraTesto($testo)
	{
		$this->placeholders = [];

		$testo = preg_replace_callback('/\[([0-9a-zA-Z\_\-\s]{1,})\]/', array($this, "estraiPlaceholder") ,$testo);

		$testo = self::estraiMarchi($testo);

		return $testo;
	}
}
