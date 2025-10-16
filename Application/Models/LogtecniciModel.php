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

class LogtecniciModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='log_tecnici';
		$this->_idFields='id_log_tecnico';
		
		parent::__construct();
	}
	
	public static function aggiungiMolti($tipo, $descrizione, $ipS)
	{
		$lt = new LogtecniciModel();
		
		$lt->sValues(array(
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"tipo"			=>	$tipo,
			"da_notificare_via_mail"	=>	1,
		), "sanitizeDb");
		
		foreach ($ipS as $ip => $numero)
		{
			$lt->setValue("ip", $ip);
			$lt->setValue("descrizione", $descrizione." (numero: $numero)");
		}
		
		$lt->insert();
	}
	
	public static function aggiungi($tipo, $descrizione, $daNotificare = 1)
	{
		$lt = new LogtecniciModel();
		
		$lt->sValues(array(
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"ip"			=>	getIp(),
			"tipo"			=>	$tipo,
			"descrizione"	=>	$descrizione,
			"da_notificare_via_mail"	=>	$daNotificare,
		), "sanitizeDb");
		
		$lt->insert();
	}
	
	public static function controllaIp()
	{
		$lt = new LogtecniciModel();
		
		$daControllare = $lt->clear()->where(array(
			"notificato"	=>	0,
			"da_notificare_via_mail"	=>	1,
			"check_ip"		=>	0,
		))->sWhere("IS_IPV4(ip)")->groupBy("ip")->orderBy()->toList("ip")->send();
		
		foreach ($daControllare as $ip)
		{
			$res = IpcheckerModel::checkIp($ip);
			
			$daNotificare = (!$res) ? 0 : 1;
			
			$lt->sValues(array(
				"da_notificare_via_mail"	=>	$daNotificare,
				"check_ip"					=>	1,
				"check_ip_date_time"		=>	date("Y-m-d H:i:s"),
			));
			
			$lt->update(null, array(
				"ip"		=>	sanitizeAll($ip),
				"check_ip"	=>	0,
			));
		}
	}
	
	public static function notifica($log = null)
	{
		$lt = new LogtecniciModel();
		
		$daInviare = $lt->clear()->where(array(
			"notificato"	=>	0,
			"da_notificare_via_mail"	=>	1,
		))->orderBy()->send(false);
		
		$struttura = array();
		
		$idNotificati = array();
		
		foreach ($daInviare as $r)
		{
			$struttura[] = $r["data_creazione"]."<br />\nIP: ".$r["ip"]."<br />\nTipo: ".$r["tipo"]."<br />\n".$r["descrizione"];
			
			$idNotificati[] = $r["id_log_tecnico"];
		}
		
		$testoMail = implode("<br /><br />", $struttura);
		
		MailordiniModel::inviaMailLog("Log tecnici piattaforma ".ImpostazioniModel::$valori["nome_sito"], $testoMail, "LOG_TECNICI");
		
		foreach ($idNotificati as $id)
		{
			$lt->sValues(array(
				"notificato"	=>	1,
			));
			
			$lt->update((int)$id);
			
			if ($log)
				$log->writeString("Notificato log ID ".(int)$id);
		}
		
		return $struttura;
	}
}