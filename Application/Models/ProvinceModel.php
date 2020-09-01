<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class ProvinceModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'province';
		$this->_idFields = 'id_prov';

		parent::__construct();
	}
	
	public function selectTendina()
	{
		return array(""=>"Seleziona") + $this->orderBy("provincia")->toList("codice_provincia","provincia")->send();
	}
	
	public function findDaCodice($codice)
	{
		$record = $this->clear()->where(array(
			"codice_provincia"	=>	sanitizeAll($codice),
		))->record();
		
		if (!empty($record))
			return $record["id_prov"];
		
		return 0;
	}
	
	public function selectArray($isSpedizione = false)
	{
		$whereArray = array();
		
		if ($isSpedizione)
		{
			$whereArray = array(
				"visibile_spedizione"	=>	1
			);
		}
		
		$res = $this->where($whereArray)->orderBy("provincia")->send(false);
		
		$selectArray = array(
			array(
				"codice_provincia"	=>	"",
				"provincia"	=>	"Seleziona",
			)
		);
		
		foreach ($res as $r)
		{
			$selectArray[] = array(
				"codice_provincia"	=>	$r["codice_provincia"],
				"provincia"	=>	htmlentitydecode($r["provincia"]),
			);
		}
		
		return $selectArray;
	}
}
