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
	
	public function __construct($cookieName = "uidt", $twoFactorCookieDurationTime = 86400, $twoFactorCookiePath = "/")
	{
		$this->_tables='adminsessions_two';
		$this->_idFields='id_adminsession_two';
		
		$this->cookieName = $cookieName;
		$this->twoFactorCookieDurationTime = $twoFactorCookieDurationTime;
		$this->twoFactorCookiePath = $twoFactorCookiePath;

		parent::__construct();
	}
	
	protected function getUidt()
	{
		$this->uidt = isset($_COOKIE[$this->cookieName]) ? sanitizeAlnum($_COOKIE[$this->cookieName]) : null;
		
		return $this->uidt;
	}
	
	protected function creaSessione($idUser, $uid, $uidt = "")
	{
		$this->uidt = randomToken();
		
		$this->sValues(array(
			"id_user"	=>	(int)$idUser,
			"uid_two"	=>	sanitizeAlnum($this->uidt),
			"user_agent_md5"	=>	getUserAgent(),
			"user_agent"	=>	$_SERVER['HTTP_USER_AGENT'] ?? "",
			"codice_verifica"	=>	generateString(6, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
			"uid"		=>	$uid,
			"ip"		=>	getIp(),
		));
		
		if ($this->insert())
		{
			$expirationTime = time() + $this->twoFactorCookieDurationTime;
			Cookie::set($this->cookieName, $this->uidt, $expirationTime, $this->twoFactorCookiePath, true, 'Lax');
			$_COOKIE[$this->cookieName] = $this->uidt;
			
			return "two-factor";
		}
		else
			return "login-error";
	}
	
	public function getStatus($idUser, $uid = "")
	{
		$uidt = $this->getUidt();
		
		if ($uidt)
		{
			$numero = $this->clear()->where(array(
				"id_user"	=>	(int)$idUser,
				"uid_two"	=>	sanitizeAlnum($uidt),
				"user_agent_md5"	=>	getUserAgent(),
				"attivo"	=>	1,
			))->rowNumber();
			
			if ($numero > 0)
				return "logged";
			else
			{
				if ($this->clear()->where(array(
					"id_user"	=>	(int)$idUser,
					"uid"		=>	sanitizeAlnum($uid),
					"uid_two"	=>	sanitizeAlnum($uidt),
					"user_agent_md5"	=>	getUserAgent(),
					"attivo"	=>	0,
				))->rowNumber())
					return "two-factor";
				else
					return $this->creaSessione($idUser, $uid);
			}
		}
		else
			return $this->creaSessione($idUser, $uid);
	}
}
