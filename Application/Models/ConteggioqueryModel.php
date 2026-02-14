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
	public static $codice = 200;
	public static $attacco = 0;
	private static $logDir = null;
	
	public function __construct() {
		$this->_tables='conteggio_query';
		$this->_idFields='id_conteggio';
		
		parent::__construct();
	}

	private static function salvaSuFile()
	{
		return (int)v("salva_conteggio_query_su_file") === 1;
	}

	private static function getLogDir()
	{
		if (self::$logDir === null)
		{
			self::$logDir = rtrim(LIBRARY . "/Logs/ConteggioQuery", "/");
		}

		return self::$logDir;
	}

	private static function ensureLogDir()
	{
		$dir = self::getLogDir();

		if (!is_dir($dir))
			@mkdir($dir, 0777, true);
	}

	private static function scriviFile($numero)
	{
		self::ensureLogDir();

		$fileName = self::getLogDir() . "/" . microtime(true) . "-" . uniqid("", true) . ".log";

		$record = array(
			"time"		=>	microtime(true),
			"numero"	=>	(int)$numero,
			"ip"		=>	getIp(),
			"url"		=>	isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "",
			"codice"	=>	self::$codice,
			"attacco"	=>	self::$attacco,
		);

		return @file_put_contents($fileName, json_encode($record), LOCK_EX) !== false;
	}

	private static function cancellaFileVecchi()
	{
		$minutiPulizia = (int)v("elimina_file_conteggio_query_ogni_minuti");

		if ($minutiPulizia <= 0)
			$minutiPulizia = 10;

		$limite = microtime(true) - ($minutiPulizia * 60);
		$dir = self::getLogDir();

		if (!is_dir($dir))
			return;

		$files = glob($dir . "/*.log");

		if (empty($files))
			return;

		foreach ($files as $file)
		{
			$ctime = (float)basename($file);

			if (!$ctime)
				$ctime = (float)@filemtime($file);

			if ($ctime > 0 && $ctime < $limite)
				@unlink($file);
		}
	}

	private static function getWhitelistIps()
	{
		$ips = array();

		try
		{
			$ipFilter = new IpfilterModel();
			$ips = $ipFilter->clear()->select("ip")->where(array(
				"whitelist"	=>	1,
			))->toList("ip")->send();
		}
		catch (Throwable $e)
		{
			$ips = array();
		}

		return is_array($ips) ? $ips : array();
	}

	private static function numeroDaFile($soglia, $secondi, $numeroIpStessarete, $attacco = null)
	{
		self::cancellaFileVecchi();
		
		$inizio = microtime(true) - $secondi;

		$dir = self::getLogDir();

		if (!is_dir($dir))
			return array();

		$whitelistIps = self::getWhitelistIps();
		$ipSito = sanitizeIp(v("ip_sito"));

		$conteggiIp = array();
		$conteggiRete = array();

		$files = glob($dir . "/*.log");

		if (empty($files))
			return array();

		foreach ($files as $file)
		{
			$contenuto = @file_get_contents($file);
			$dati = json_decode($contenuto, true);

			if (!is_array($dati))
			{
				@unlink($file);
				continue;
			}

			$tempo = isset($dati["time"]) ? (float)$dati["time"] : (float)@filemtime($file);

			if ($tempo < $inizio)
				continue;

			$ip = isset($dati["ip"]) ? trim($dati["ip"]) : "";

			if ($ip === "" || $ip === $ipSito || in_array($ip, $whitelistIps))
				continue;

			if ($attacco !== null && (int)$dati["attacco"] !== (int)$attacco)
				continue;

			$numero = isset($dati["numero"]) ? (int)$dati["numero"] : 0;

			if ($numero <= 0)
				$numero = 1;

			$conteggiIp[$ip] = isset($conteggiIp[$ip]) ? $conteggiIp[$ip] + $numero : $numero;

			$subIp = implode(".", array_slice(explode(".", $ip), 0, 3));

			if ($subIp)
			{
				if (!isset($conteggiRete[$subIp]))
					$conteggiRete[$subIp] = array("numero_query" => 0, "ip" => array());

				$conteggiRete[$subIp]["numero_query"] += $numero;
				$conteggiRete[$subIp]["ip"][$ip] = true;
			}
		}

		$resIp = array();

		foreach ($conteggiIp as $ip => $numero)
		{
			if ($numero > $soglia)
				$resIp[$ip] = $numero;
		}

		$resRange = array();

		foreach ($conteggiRete as $subIp => $info)
		{
			$numeroIp = isset($info["ip"]) ? count($info["ip"]) : 0;

			if ($numeroIp > $numeroIpStessarete && $info["numero_query"] > $soglia)
				$resRange[$subIp] = $info["numero_query"];
		}

		return $resIp + $resRange;
	}
	
	public static function aggiungiConCodice($numero, $codice, $attacco = 0)
	{
		$tmp = self::$codice;
		$tmpAttacco = self::$attacco;
		
		self::$codice = $codice;
		self::$attacco = $attacco;
		
		self::aggiungi($numero);
		
		self::$codice = $tmp;
		self::$attacco = $tmpAttacco;
	}
	
	public static function aggiungi($numero)
	{
		if (self::salvaSuFile())
			self::scriviFile($numero);

		$cq = new ConteggioqueryModel();
		
		$cq->setValues(array(
			"numero"	=>	$numero,
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"ip"		=>	getIp(),
			"url"		=>	isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "",
			"codice"	=>	self::$codice,
			"attacco"	=>	self::$attacco,
			"user_agent"	=>	isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "",
			"bot_name"	=>	Device::getBotName(),
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
	
	public static function numeroAttacchi($soglia = 4, $secondi = 60, $numeroIpStessarete = 10)
	{
		if (self::salvaSuFile())
			return self::numeroDaFile($soglia, $secondi, $numeroIpStessarete, 1);

		$secondi = time() - $secondi;
		
		$dataOra = date("Y-m-d H:i:s", $secondi);
		
		$cq = new ConteggioqueryModel();
		
		$sWhereIp = self::getSWhereIp();
		
		// Cerca singolo IP
		$resIp = $cq->clear()->select("count(numero) as numero_attacchi,ip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
			"attacco"	=>	1,
		))
		->sWhere($sWhereIp)
		->groupBy("ip having numero_attacchi > ".(int)$soglia)->toList("ip", "aggregate.numero_attacchi")->send();
		
		// Cerca range
		$resRange = $cq->clear()->select("count(numero) as numero_attacchi,count(distinct ip) as numero_ip,substring_index( ip, '.', 3 ) as subip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
			"attacco"	=>	1,
		))
		->sWhere($sWhereIp)
		->groupBy("subip having numero_ip >= ".(int)$numeroIpStessarete." && numero_attacchi > ".(int)$soglia)->toList("aggregate.subip", "aggregate.numero_attacchi")->send();
		
		return $resIp + $resRange;
	}
	
	public static function numeroQuery($soglia = 1000, $secondi = 60, $numeroIpStessarete = 30, $forzaCheckSoloRete = false)
	{
		if (self::salvaSuFile())
			return self::numeroDaFile($soglia, $secondi, $numeroIpStessarete);

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
		
		if ($forzaCheckSoloRete)
			$resIp = [];
		
		// Cerca range
		$resRange = $cq->clear()->select("SUM(numero) as numero_query,count(distinct ip) as numero_ip,substring_index( ip, '.', 3 ) as subip")->aWhere(array(
			"gte"	=>	array(
				"data_creazione"	=>	sanitizeAll($dataOra),
			),
		))
		->sWhere($sWhereIp)
		->groupBy("subip having numero_ip >= ".(int)$numeroIpStessarete." && numero_query > ".(int)$soglia)->toList("aggregate.subip", "aggregate.numero_query")->send();
		
		return $resIp + $resRange;
	}
	
	public static function svuotaConteggioQueryPiuVecchioDiGiorni($giorni)
	{
		if (self::salvaSuFile())
			self::cancellaFileVecchi();
		
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
