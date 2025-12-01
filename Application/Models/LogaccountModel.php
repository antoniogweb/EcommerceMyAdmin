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

class LogaccountModel extends GenericModel
{
	private static $instance = null; //instance of this class
	
	private $azione = "LOGIN";
	
	public $inizializzato = false;
	
	protected function __construct() {
		$this->_tables='log_account';
		$this->_idFields='id_log_account';
		
		parent::__construct();
	}
	
	public static function getInstance($azione = "")
	{
		if (!isset(self::$instance))
			self::$instance = new LogaccountModel();
		
		if ($azione)
			self::$instance->setAzione($azione);
		
		return self::$instance;
	}
	
	public function getAzione()
	{
		return $this->azione;
	}
	
	public function setAzione($azione)
	{
		$this->azione = $azione;
	}
	
	public function check($email)
	{
		self::$instance->sValues(array(
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"time_creazione"	=>	time(),
			"id_user"			=>	User::$id,
			"email"				=>	$email,
			"ip"				=>	getIp(),
			"azione"			=>	self::$instance->getAzione(),
			"contesto"			=>	App::$isFrontend ? "FRONT" : "BACK",
		), "sanitizeAll");
		
		self::$instance->inizializzato = true;
	}
	
	public function set($risultato = 1)
	{
		if (self::$instance->inizializzato)
		{
			self::$instance->setValue("risultato", (int)$risultato);
		
			self::$instance->insert();
		}
	}
}
