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
	
	private static function getSWhereIp()
	{
		return array(
			"conteggio_query.ip != '' and conteggio_query.ip != ? and conteggio_query.ip not in (select ip from ip_filter where whitelist = 1)",
			array(sanitizeIp(v("ip_sito")))
		);
	}
	
	// Restituisce i network che stanno facendo più query
	public static function numeroQueryNetwork($soglia = 1000, $secondi = 60)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$resNetwork = $cq->clear()->select("SUM(numero) as numero_query,concat(ip_location.network,'-',ip_location.nazione) as network_nazione")->inner("ip_location")->on("conteggio_query.ip = ip_location.ip and ip_location.network != ''")->aWhere(array(
			"gte"	=>	array(
				"conteggio_query.data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere(self::getSWhereIp())
		->groupBy("ip_location.network,ip_location.nazione having numero_query > ".(int)$soglia)->toList("aggregate.network_nazione", "aggregate.numero_query")->send();
		
		return $resNetwork;
	}
	
	// Restituisce l'elenco degli IP delle nazioni $nazioni indicate negli ultimi $secondi
	public static function ipNazioni($secondi = 60, $nazioni = array())
	{
		if (empty($nazioni))
			return array();
		
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$resNazioni = $cq->clear()->select("SUM(numero) as numero_query,conteggio_query.ip")->inner("ip_location")->on(array(
			"conteggio_query.ip = ip_location.ip and ip_location.nazione in (".$cq->placeholdersFromArray($nazioni).")",
			sanitizeAllDeep($nazioni)
		))->aWhere(array(
			"gte"	=>	array(
				"conteggio_query.data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere(self::getSWhereIp())
		->groupBy("conteggio_query.ip")
		->toList("conteggio_query.ip", "aggregate.numero_query")->send();
		
		return $resNazioni;
	}
	
	// Restituisce le nazioni network che stanno facendo più query
	public static function numeroQueryNazione($soglia = 1000, $secondi = 60)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$resNazioni = $cq->clear()->select("SUM(numero) as numero_query,ip_location.nazione")->inner("ip_location")->on("conteggio_query.ip = ip_location.ip and ip_location.nazione != ''")->aWhere(array(
			"gte"	=>	array(
				"conteggio_query.data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere(self::getSWhereIp())
		->groupBy("ip_location.nazione having numero_query > ".(int)$soglia)->toList("ip_location.nazione", "aggregate.numero_query")->send();
		
		return $resNazioni;
	}
	
// 	public static function numeroQueryGlobali($soglia = 1000, $secondi = 60)
// 	{
// 		$secondi = time() - $secondi;
// 		
// 		$dataOra = date("Y-m-d H:i:s", $secondi);
// 		
// 		$cq = new ConteggioqueryModel();
// 		
// 		$sWhereIp = self::getSWhereIp();
// 		
// 		$res = $cq->clear()->select("SUM(numero) as numero_query")->aWhere(array(
// 			"gte"	=>	array(
// 				"data_creazione"	=>	sanitizeAll($dataOra),
// 			),
// 		))
// 		->sWhere($sWhereIp)
// 		->groupBy("having numero_query > ".(int)$soglia)->toList("aggregate.numero_query")->send();
// 		
// 		return $res;
// 	}
	
	public static function numeroQuery($soglia = 1000, $secondi = 60, $numeroIpStessarete = 30)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$sWhereIp = self::getSWhereIp();
		
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
	
	public static function geolocalizzaIp($secondi, $limit = 100)
	{
		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$ips = $resIp = $cq->clear()->select("distinct ip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere(self::getSWhereIp())->limit($limit)->orderBy("id_conteggio desc")->toList("ip")->send();
		
		$ipLocationModel = new IplocationModel();
		
		foreach ($ips as $ip)
		{
			if (trim($ip) && $ip != "127.0.0.1")
			{
				list ($nazione, $network) = $ipLocationModel->getNazione($ip);
				
				echo $ip.": ".$nazione." - Network: $network\n";
			}
		}
	}
}
