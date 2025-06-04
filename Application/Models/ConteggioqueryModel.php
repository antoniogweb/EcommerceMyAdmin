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
	
	public static function numeroQuery($soglia = 1000, $secondi = 60, $numeroIpStessarete = 30)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$sWhereIp = array(
			"ip != '' and ip != ? and ip not in (select ip from ip_filter where whitelist = 1)",
			array(sanitizeIp(v("ip_sito")))
		);
		
		// Cerca singolo IP
		$resIp = $cq->clear()->select("SUM(numero) as numero_query,ip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere($sWhereIp)
		->groupBy("ip having numero_query > ".(int)$soglia)->toList("ip", "aggregate.numero_query")->send();
		
		// Cerca range
		$resRange = $cq->clear()->select("SUM(numero) as numero_query,count(distinct ip) as numero_ip,substring_index( ip, '.', 3 ) as subip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere($sWhereIp)
		->groupBy("subip having numero_ip > ".(int)$numeroIpStessarete." && numero_query > ".(int)$soglia)->toList("aggregate.subip", "aggregate.numero_query")->send();
		
		return $resIp + $resRange;
	}
	
	public static function svuotaConteggioQueryPiuVecchioDiGiorni($giorni)
	{
		$giorni = (int)$giorni;
		
		$dataOra = new DateTime();
		$dataOra->modify("-$giorni days");
		
		$cq = new ConteggioqueryModel();
		
		$cq->del(null, array(
			"date_format(data_creazione,'%Y-%m-%d') <= ?",
			array($dataOra->format("Y-m-d"))
		));
	}
}
