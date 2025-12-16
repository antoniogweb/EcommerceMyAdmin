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
	public static $arrayPause = array(
		"LOGIN"				=>	array("6"	=>	60),
		"RECUPERO_PASSWORD"	=>	array("5"	=>	60),
	);
	
	private static $instance = null; //instance of this class
	
	private $azione = "LOGIN";
	
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
	
	private function inPausa($email, $azione)
	{
		$contesto = App::$isFrontend ? "FRONT" : "BACK";
		
		$where = array(
			"contesto"	=>	sanitizeAll($contesto),
			"email"		=>	sanitizeAll($email),
			"azione"	=>	sanitizeAll($azione)
		);
		
		$numeroInPausa = self::$instance->clear()->where($where)->aWhere(array(
			"gt"	=>	array(
				"in_pausa_fino_a_time"	=> time(),
			)
		))->forUpdate()->rowNumber();
		
		return $numeroInPausa ? true : false;
	}
	
	private function getNumeroMinutiDiPausaEmailAzione($email, $azione)
	{
		$contesto = App::$isFrontend ? "FRONT" : "BACK";
		
		$where = array(
			"risultato"	=>	1,
			"contesto"	=>	sanitizeAll($contesto),
			"email"		=>	sanitizeAll($email),
			"azione"	=>	sanitizeAll($azione)
		);
		
		$idUltimoSuccesso = self::$instance->clear()->where($where)->orderBy("id_log_account desc")->forUpdate()->field("id_log_account");
		
		if (!$idUltimoSuccesso)
			$idUltimoSuccesso = 0;
		
		$where["risultato"] = 0;
		
		$fallimenti = self::$instance->clear()->where($where)->aWhere(array(
			"gte"	=>	array(
				"id_log_account"	=>	(int)$idUltimoSuccesso,
			)
		))->orderBy("id_log_account desc")->forUpdate()->send(false);
		
		$numeroFallimenti = count($fallimenti);
		$numeroFallimenti++;
		
		if (isset(self::$arrayPause[$azione]))
		{
			foreach (self::$arrayPause[$azione] as $limite => $minuti)
			{
				if ($numeroFallimenti >= $limite)
					return $minuti;
			}
		}
		
		return 0;
	}
	
	public function check($email)
	{
		$azione = self::$instance->getAzione();
		
		self::$instance->db->beginTransaction();
		
		$inPausa = self::$instance->inPausa($email, $azione);
		
		if ($inPausa)
		{
			self::$instance->db->commit();
			
			return false;
		}
		
		if( !session_id() )
			session_start();
		
		$minuti = self::$instance->getNumeroMinutiDiPausaEmailAzione($email, $azione);
		$secondiPausa = $minuti * 60;
		
		$uniqueId = randomToken(33);
		
		self::$instance->sValues(array(
			"data_creazione"	=>	date("Y-m-d H:i:s"),
			"time_creazione"	=>	time(),
			"id_user"			=>	User::$id,
			"email"				=>	$email,
			"ip"				=>	getIp(),
			"azione"			=>	$azione,
			"risultato"			=>	$minuti ? 1 : 0,
			"contesto"			=>	App::$isFrontend ? "FRONT" : "BACK",
			"in_pausa_fino_a_time"	=>	$minuti ? (time() + $secondiPausa) : 0,
			"unique_id"			=>	$uniqueId,
		), "sanitizeAll");
		
		$_SESSION["log_account_unique_id"] = $uniqueId;
		
		self::$instance->insert();
		
		self::$instance->db->commit();
		
		return true;
	}
	
	public function hasId()
	{
		$id = (int)$this->restoreFromSession()->lId;
		
		return $id ? true : false;
	}
	
	public function restoreFromSession()
	{
		if( !session_id() )
			session_start();
		
		if (isset($_SESSION["log_account_unique_id"]))
		{
			$clean["uniqueId"] = sanitizeAll($_SESSION["log_account_unique_id"]);
			
			$contesto = App::$isFrontend ? "FRONT" : "BACK";
			
			$idLogAccount = (int)self::$instance->clear()->where(array(
				"contesto"	=>	sanitizeAll($contesto),
				"unique_id"	=>	$clean["uniqueId"],
				"ne"	=>	array(
					"unique_id"	=>	"",
				)
			))->field("id_log_account");
			
			if ($idLogAccount)
				self::$instance->lId = $idLogAccount;
		}
		
		return self::$instance;
	}
	
	public function set($risultato = 1)
	{
		if (self::$instance->lId)
		{
			self::$instance->setValue("risultato", (int)$risultato);
			self::$instance->setValue("in_pausa_fino_a_time", 0);
		
			self::$instance->update((int)self::$instance->lId);
		}
	}
	
	public function remove()
	{
		if (self::$instance->lId)
		{
			self::$instance->del((int)self::$instance->lId);
		}
	}
}
