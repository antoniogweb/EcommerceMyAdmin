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

class IpfilterModel extends GenericModel
{
	public $campoTitolo = "ip";
	
	public function __construct() {
		$this->_tables = 'ip_filter';
		$this->_idFields = 'id_ip_filter';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'whitelist'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Whitelist / Blacklist",
					"options"	=>	array(
						"1"	=>	"Whitelist",
						"0"	=>	"Blacklist",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function insert()
	{
		$this->values["time_creazione"] = time();
		
		return parent::insert();
	}
	
	public function modalitaCrud($record)
	{
		if ($record["ip_filter"]["whitelist"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function check($ip, $whitelist = 1)
	{
		return $this->clear()->where(array(
			"ip"		=>	sanitizeAll($ip),
			"whitelist"	=>	(int)$whitelist,
			"rete"		=>	"",
		))->rowNumber();
	}
	
	public function blocca($ip, $minuti = 0)
	{
		$this->sValues(array(
			"ip"	=>	sanitizeAll($ip),
			"whitelist"	=>	0,
		));
		
		$this->insert();
	}
	
	public static function getArrayIp($ip)
	{
		$pos = strpos($ip, "/");
		
		$arrayIp = [];
		
		if ($pos === false)
		{
			if (filter_var($ip, FILTER_VALIDATE_IP))
				$arrayIp[] = $ip;
		}
		else
		{
			$ipList = self::getIpList($ip);
			
			foreach ($ipList as $ipInLista)
			{
				if (filter_var($ipInLista, FILTER_VALIDATE_IP))
					$arrayIp[] = $ipInLista;
			}
		}
		
		return $arrayIp;
	}
	
	public static function rimuoviDaWhitelist($ip, $rete, $log)
	{
		$arrayIp = self::getArrayIp($ip);
		
		if ((int)count($arrayIp) === 0)
			return;
		
		$ifModel = new IpfilterModel();
		
		if (v("usa_transactions"))
			$ifModel->db->beginTransaction();
		
		foreach ($arrayIp as $ipToAdd)
		{
			$ifModel->del(null, array(
				"ip"	=>	sanitizeAll($ipToAdd),
				"rete"	=>	sanitizeAll($rete),
			));
		}
		
		if ($log)
			$log->writeString("Rimosso IP $ip in rete $rete da WHITELIST");
		
		if (v("usa_transactions"))
			$ifModel->db->commit();
	}
	
	public static function aggiungiInWhitelist($ip, $rete, $log)
	{
		$pos = strpos($ip, "/");
		
		$arrayIp = self::getArrayIp($ip);
		
		if ((int)count($arrayIp) === 0)
			return;
		
		$ifModel = new IpfilterModel();
		
		if (v("usa_transactions"))
			$ifModel->db->beginTransaction();
		
		foreach ($arrayIp as $ipToAdd)
		{
			$numero = $ifModel->clear()->where(array(
				"ip"	=>	sanitizeAll($ipToAdd),
				"rete"	=>	sanitizeAll($rete),
			))->rowNumber();
			
			$ifModel->sValues(array(
				"ip"		=>	$ipToAdd,
				"whitelist"	=>	1,
				"rete"		=>	$rete,
			));
			
			if (!$numero)
				$ifModel->insert();
		}
		
		if ($log)
			$log->writeString("Aggiunto IP $ip in rete $rete in WHITELIST");
		
		if (v("usa_transactions"))
			$ifModel->db->commit();
	}
	
	public static function loadIpBot($log = null)
	{
		$codici = OpzioniModel::codice("URL_IP_BOT");
		
		$ifModel = new IpfilterModel();
		
		foreach ($codici as $url => $titolo)
		{
			if ($log)
				$log->writeString("Sto caricando gli IP di $titolo");
			
			$ifModel->del(null, array(
				"rete"	=>	sanitizeAll($titolo),
			));
			
			if (v("usa_transactions"))
				$ifModel->db->beginTransaction();
			
			$struttura = file_get_contents($url);
			
			$struttura = json_decode($struttura, true);
			
			if (isset($struttura["prefixes"]))
			{
				foreach ($struttura["prefixes"] as $p)
				{
					if (isset($p["ipv4Prefix"]))
					{
						$ipList = self::getIpList($p["ipv4Prefix"]);
						
						foreach ($ipList as $ip)
						{
							$ifModel->sValues(array(
								"ip"		=>	$ip,
								"whitelist"	=>	1,
								"rete"		=>	$titolo,
							));
							
							$ifModel->insert();
						}
					}
				}
			}
			
			if (v("usa_transactions"))
				$ifModel->db->commit();
		}
	}
	
	public static function getIpList($ip_addr_cidr)
	{
		$ip_arr = explode("/", $ip_addr_cidr);    
		$bin = "";

		for($i=1;$i<=32;$i++) {
			$bin .= $ip_arr[1] >= $i ? '1' : '0';
		}

		$ip_arr[1] = bindec($bin);

		$ip = ip2long($ip_arr[0]);
		$nm = $ip_arr[1];
		$nw = ($ip & $nm);
		$bc = $nw | ~$nm;
		$bc_long = ip2long(long2ip($bc));

		for($zm=1;($nw + $zm)<=($bc_long - 1);$zm++)
		{
			$ret[]=long2ip($nw + $zm);
		}
		
		return $ret;
	}
}
