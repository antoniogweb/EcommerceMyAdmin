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

class SessionitwoModel extends GenericModel
{
	use SessionitwotraitModel;
	
	private static $instance = null; //instance of this class
	
	protected $uidt = null;
	protected $twoFactorCookieDurationTime = 86400; // two factor cookie duration
	protected $twoFactorCookiePath = "/"; // two factor cookie duration
	protected $cookieName = "uidt";
	protected $tempoDurataVerificaCodice = 60;
	protected $numeroCifreCodice = 6;
	protected $userModel = null;
	
	public function __construct($cookieName = "uidt", $twoFactorCookieDurationTime = 86400, $twoFactorCookiePath = "/", $tempoDurataVerificaCodice = 60, $numeroCifreCodice = 6, $userModel = null)
	{
		$this->_tables='adminsessions_two';
		$this->_idFields='id_adminsession_two';
		
		$this->setValoriConfigurazione($cookieName, $twoFactorCookieDurationTime, $twoFactorCookiePath, $tempoDurataVerificaCodice, $numeroCifreCodice, $userModel);
		
		parent::__construct();
		
		$this->cleanSessions();
	}
}
