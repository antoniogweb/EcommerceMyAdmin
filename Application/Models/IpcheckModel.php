<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class IpcheckModel extends Model_Tree
{
	public function __construct() {
		$this->_tables = 'ip_check';
		$this->_idFields = 'id_ip_check';
		
		parent::__construct();
	}
	
	private function getNumeroSecondi($secondi, $chiave = "")
	{
		$timeSecondi = time() - (int)$secondi;
		
		$sql = "select count(id_ip_check) as NUMERO from ip_check where ip='".getIp()."' and chiave = '".sanitizeAll($chiave)."' and time_creazione > $timeSecondi for update";
		$resOra = $this->query($sql);
		
		return isset($resOra[0]["aggregate"]["NUMERO"]) ? (int)$resOra[0]["aggregate"]["NUMERO"] : 0;
	}
	
	public static function check($chiave = "")
	{
		if (App::$isFrontend && v("attiva_check_ip") && !User::$adminLogged)
		{
			$ipcModel = new IpcheckModel();
			
			$timeSecondi = time() - (3600 * 30);
			
			$ipcModel->query("delete from ip_check where time_creazione < $timeSecondi");
			
			$ipcModel->db->beginTransaction();
			
			$okLimite = true;
			
			$ipcModel->sValues(array(
				"time_creazione"	=>	time(),
				"ip"				=>	getIp(),
				"chiave"			=>	$chiave,
			));
			
			$numero = $ipcModel->getNumeroSecondi(5, $chiave);
			
			if ($numero >= v("limite_ip_chiave_contemporanee"))
			{
				$ipcModel->values["superato_limite_istantaneo"] = 1;
				$okLimite = false;
			}
			
			if ($okLimite)
			{
				$numero = $ipcModel->getNumeroSecondi(60, $chiave);
				
				if ($numero >= v("limite_ip_chiave_minuto"))
				{
					$ipcModel->values["superato_limite_minuto"] = 1;
					$okLimite = false;
				}
			}
			
			if ($okLimite)
			{
				$numero = $ipcModel->getNumeroSecondi(3600, $chiave);
				
				if ($numero >= v("limite_ip_chiave_orario"))
				{
					$ipcModel->values["superato_limite_orario"] = 1;
					$okLimite = false;
				}
			}
			
			$ipcModel->insert();
			
			$ipcModel->db->commit();
			
			if (!$okLimite)
			{
				header('HTTP/1.0 404 Not Found');
				die();
			}
		}
	}
}
