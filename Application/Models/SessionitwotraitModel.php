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

trait SessionitwotraitModel
{
	public static function getInstance($cookieName = "uidt", $twoFactorCookieDurationTime = 86400, $twoFactorCookiePath = "/", $tempoDurataVerificaCodice = 60, $numeroCifreCodice = 6, $userModel = null)
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className($cookieName,$twoFactorCookieDurationTime,$twoFactorCookiePath,$tempoDurataVerificaCodice, $numeroCifreCodice, $userModel);
		}

		return self::$instance;
	}
	
	public function setValoriConfigurazione($cookieName, $twoFactorCookieDurationTime, $twoFactorCookiePath, $tempoDurataVerificaCodice, $numeroCifreCodice, $userModel)
	{
		$this->cookieName = $cookieName;
		$this->twoFactorCookieDurationTime = $twoFactorCookieDurationTime;
		$this->twoFactorCookiePath = $twoFactorCookiePath;
		$this->tempoDurataVerificaCodice = $tempoDurataVerificaCodice;
		$this->numeroCifreCodice = $numeroCifreCodice;
		$this->userModel = $userModel;
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
	
	protected function getCookieName($idUser)
	{
		if (v("autenticazione_due_fattori_usa_id_user"))
			$username = (int)$idUser;
		else
			$username = $this->userModel->clear()->whereId((int)$idUser)->field("username");
		
		if (v("attiva_autenticazione_due_fattori_front"))
			return $this->cookieName."_".md5($username);
		else
			return md5($this->cookieName."_".$username);
	}
	
	public function getUidt($idUser)
	{
		$cookieName = $this->getCookieName($idUser);
		
		$this->uidt = isset($_COOKIE[$cookieName]) ? sanitizeAll(hashToken((string)$_COOKIE[$cookieName])) : null;
		
		return $this->uidt;
	}
	
	private function getOsBrowser()
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$parser = new donatj\UserAgent\UserAgentParser();
		$ua = $parser->parse();
		
		return array($ua->platform(), $ua->browser(), $ua->browserVersion());
	}
	
	public function resettaSessione($idUser, $uid, $force = false)
	{
		$this->del(null, array(
			"id_user"	=>	(int)$idUser,
		));
		
		return $this->creaSessione($idUser, $uid, $force);
	}
	
	public function creaSessione($idUser, $uid, $force = false)
	{
		$this->uidt = randomToken();
		
		list ($os, $browser, $browserVersion) = $this->getOsBrowser();
		
		$this->del(null, array(
			"id_user"	=>	(int)$idUser,
			"attivo"	=>	0,
		));
		
		$this->sValues(array(
			"id_user"	=>	(int)$idUser,
			"uid_two"	=>	sanitizeAll(hashToken($this->uidt)),
			"user_agent_md5"	=>	getUserAgent(),
			"user_agent"	=>	$_SERVER['HTTP_USER_AGENT'] ?? "",
			"codice_verifica"	=>	Aes::encrypt(generateString($this->numeroCifreCodice, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ")),
			"uid"		=>	$uid,
			"ip"		=>	getIp(),
			"time_creazione"	=>	time(),
			"time_per_scadenza"	=>	time(),
			"sistema_operativo"	=>	$os,
			"browser"	=>	$browser,
			"versione_browser"	=>	$browserVersion,
		));
		
		if ($force)
			$this->setValue("attivo", "1");
		
		if ($this->insert())
		{
			$expirationTime = time() + $this->twoFactorCookieDurationTime;
			
			$cookieName = $this->getCookieName($idUser);
			
			Cookie::set($cookieName, $this->uidt, $expirationTime, $this->twoFactorCookiePath, true, 'Lax');
			$_COOKIE[$cookieName] = $this->uidt;
			
			if ($force)
				return "logged";
			else
				return "two-factor";
		}
		else
			return "not-logged";
	}
	
	public function getStatus($idUser, $uid = "", $loggedState = "logged")
	{
		$uidt = $this->getUidt($idUser);
		
		if ($uidt)
		{
			list ($os, $browser, $browserVersion) = $this->getOsBrowser();
			
			$numero = $this->clear()->where(array(
				"id_user"	=>	(int)$idUser,
				"uid_two"	=>	sanitizeAll($uidt),
				"sistema_operativo"	=>	sanitizeAll($os),
				"browser"	=>	sanitizeAll($browser),
				// "versione_browser"	=>	sanitizeAll($browserVersion),
				// "user_agent_md5"	=>	getUserAgent(),
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
	
	public function getSession($idUser)
	{
		$uidt = $this->getUidt($idUser);
		
		return $this->clear()->where(array(
			"uid_two"	=>	sanitizeAll($uidt),
			"attivo"	=>	0,
			"id_user"	=>	(int)$idUser,
		))->record();
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
					"CODICE"		=>	Aes::decrypt($sessioneTwo["codice_verifica"]),
					"NOME_CLIENTE"	=>	$user["username"],
				),
			));
			
			if ($res)
			{
				$this->sValues(array(
					"numero_invii_codice"	=>	((int)$sessioneTwo["numero_invii_codice"] + 1),
					"time_creazione"		=>	time(),
				));
				
				$this->pUpdate($sessioneTwo[$this->_idFields]);
				// $this->pUpdate($sessioneTwo["id_adminsession_two"]);
			}
			
			return $res;
		}
		
		return false;
	}
	
	public function checkCodice($sessioneTwo, $codice, $idUser)
	{
		$record = $this->clear()->where(array(
			// "codice_verifica"	=>	sanitizeAll($codice),
			"uid_two"	=>	sanitizeAll($sessioneTwo["uid_two"]),
			"attivo"	=>	0,
			"user_agent_md5"	=>	getUserAgent(),
			"id_user"	=>	(int)$idUser,
		))->record();
		
		if (!empty($record) && hash_equals($codice, Aes::decrypt($record["codice_verifica"])))
		{
			$this->sValues(array(
				"attivo"				=>	1,
				"time_per_scadenza"		=>	time(),
				"codice_verifica"		=>	"",
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
		
		$this->pUpdate($sessioneTwo[$this->_idFields]);
		// $this->pUpdate($sessioneTwo["id_adminsession_two"]);
		
		return $res;
	}
}
