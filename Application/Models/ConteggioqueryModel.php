<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class ConteggioqueryModel extends GenericModel
{
	public function __construct() {
		$this->_tables='conteggio_query';
		$this->_idFields='id_conteggio';
		
		parent::__construct();
	}
	
	public static function aggiungi($numero)
	{
		$cq = new ConteggioqueryModel();
		
		$cq->setValues(array(
			"numero"	=>	$numero,
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"ip"		=>	getIp(),
			"url"		=>	isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "",
		));
		
		$cq->insert();
	}
	
	public static function numeroQuery($soglia = 1000, $secondi = 60)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$res = $cq->clear()->select("SUM(numero) as numero_query,ip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
		))->groupBy("ip having numero_query > ".(int)$soglia)->toList("ip", "aggregate.numero_query")->send();
		
		return $res;
	}
}
