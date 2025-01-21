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

class SessionitwoModel extends GenericModel
{
	protected $uidt = null;
	protected $twoFactorCookieDurationTime = 86400; // two factor cookie duration
	protected $twoFactorCookiePath = "/"; // two factor cookie duration
	protected $cookieName = "uidt";
	protected $tempoDurataVerificaCodice = 60;
	
	public function __construct($cookieName = "uidt", $twoFactorCookieDurationTime = 86400, $twoFactorCookiePath = "/", $tempoDurataVerificaCodice = 60)
	{
		$this->_tables='adminsessions_two';
		$this->_idFields='id_adminsession_two';
		
		$this->cookieName = $cookieName;
		$this->twoFactorCookieDurationTime = $twoFactorCookieDurationTime;
		$this->twoFactorCookiePath = $twoFactorCookiePath;
		$this->tempoDurataVerificaCodice = $tempoDurataVerificaCodice;
		
		parent::__construct();
		
		$this->cleanSessions();
	}
	
	public function cleanSessions()
	{
		// Verifica durata two factor NON attivo
		$time = time() - (int)$this->tempoDurataVerificaCodice;
		
		$this->del(null, array(
			"time_creazione < ? and attivo = 0",
			array(
				$time
			)
		));
		
		// Verifica durata two factor attivo
		$time2 = time() - (int)$this->twoFactorCookieDurationTime;
		
		$this->del(null, array(
			"time_per_scadenza < ? and attivo = 1",
			array(
				$time2
			)
		));
	}
	
	public function getUidt()
	{
		$this->uidt = isset($_COOKIE[$this->cookieName]) ? sanitizeAll($_COOKIE[$this->cookieName]) : null;
		
		return $this->uidt;
	}
	
	public function creaSessione($idUser, $uid)
	{
		$this->uidt = randomToken();
		
		$this->sValues(array(
			"id_user"	=>	(int)$idUser,
			"uid_two"	=>	sanitizeAll($this->uidt),
			"user_agent_md5"	=>	getUserAgent(),
			"user_agent"	=>	$_SERVER['HTTP_USER_AGENT'] ?? "",
			"codice_verifica"	=>	generateString(v("autenticazione_due_fattori_numero_cifre_admin"), "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
			"uid"		=>	$uid,
			"ip"		=>	getIp(),
			"time_creazione"	=>	time(),
			"time_per_scadenza"	=>	time(),
		));
		
		if ($this->insert())
		{
			$expirationTime = time() + $this->twoFactorCookieDurationTime;
			Cookie::set($this->cookieName, $this->uidt, $expirationTime, $this->twoFactorCookiePath, true, 'Lax');
			$_COOKIE[$this->cookieName] = $this->uidt;
			
			return "two-factor";
		}
		else
			return "not-logged";
	}
	
	public function getStatus($idUser, $uid = "", $loggedState = "logged")
	{
		$uidt = $this->getUidt();
		
		if ($uidt)
		{
			$numero = $this->clear()->where(array(
				"id_user"	=>	(int)$idUser,
				"uid_two"	=>	sanitizeAll($uidt),
				"user_agent_md5"	=>	getUserAgent(),
				"attivo"	=>	1,
			))->rowNumber();
			
			if ($numero > 0)
				return $loggedState;
			else
			{
				if ($this->clear()->where(array(
					"id_user"	=>	(int)$idUser,
					"uid"		=>	sanitizeAll($uid),
					"uid_two"	=>	sanitizeAll($uidt),
					"user_agent_md5"	=>	getUserAgent(),
					"attivo"	=>	0,
				))->rowNumber())
					return "two-factor";
			}
		}
		
		return "not-logged";
	}
	
	public function delSession($uid)
	{
		if (trim($uid))
			return $this->del(null, array(
				"uid"		=>	sanitizeAll($uid),
				"attivo"	=>	0,
			));
		
		return false;
	}
	
	public function inviaCodice($sessioneTwo, $user, $campo = "email")
	{
		$email = trim($user[$campo]);
		
		if ($email && checkMail($email))
		{
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($email),
				"oggetto"	=>	"invio codice di verifica",
				"tipologia"	=>	"INVIO_CODICE_TWO",
				"testo_path"	=>	"Elementi/Mail/Clienti/codice_due_fattori.php",
				"array_variabili_tema"	=>	array(
					"CODICE"		=>	$sessioneTwo["codice_verifica"],
					"NOME_CLIENTE"	=>	$user["username"],
				),
			));
			
			if ($res)
			{
				$this->sValues(array(
					"numero_invii_codice"	=>	((int)$sessioneTwo["numero_invii_codice"] + 1),
					"time_creazione"		=>	time(),
				));
				
				$this->pUpdate($sessioneTwo["id_adminsession_two"]);
			}
			
			return $res;
		}
		
		return false;
	}
	
	public function checkCodice($sessioneTwo, $codice)
	{
		$numero = $this->clear()->where(array(
			"codice_verifica"	=>	sanitizeAll($codice),
			"uid_two"	=>	sanitizeAll($sessioneTwo["uid_two"]),
			"attivo"	=>	0,
			"user_agent_md5"	=>	getUserAgent(),
		))->rowNumber();
		
		if ($numero)
		{
			$this->sValues(array(
				"attivo"				=>	1,
				"time_per_scadenza"		=>	time(),
			));
			
			$res = true;
		}
		else
		{
			$this->sValues(array(
				"tentativi_verifica"	=> ((int)$sessioneTwo["tentativi_verifica"] + 1),
			));
			
			$res = false;
		}
		
		$this->pUpdate($sessioneTwo["id_adminsession_two"]);
		
		return $res;
	}
}
