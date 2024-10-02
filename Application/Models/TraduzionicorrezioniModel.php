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

class TraduzionicorrezioniModel extends GenericModel
{
	public static $correzioni = null;

	public function __construct() {
		$this->_tables='traduzioni_correzioni';
		$this->_idFields='id_t_c';
		
		parent::__construct();
	}

	public static function getCorrezioni()
	{
		if (!isset(self::$correzioni))
		{
			$correzioni = TraduzionicorrezioniModel::g(false)->clear()->send(false);

			foreach ($correzioni as $c)
			{
				self::$correzioni[$c["successivo"]][$c["lingua"]][$c["parola_tradotta_da_correggere"]] = $c["parola_tradotta_corretta"];
			}
		}
	}

	public static function correggi($lingua, $testo, $successivo = 1)
	{
		self::getCorrezioni();

		if (isset(self::$correzioni[$successivo][$lingua]))
		{
			foreach (self::$correzioni[$successivo][$lingua] as $daCorreggere => $corretta)
			{
				$testo = str_replace($daCorreggere, $corretta, $testo);
			}
		}

		return $testo;
	}
}
