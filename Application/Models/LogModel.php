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

class LogModel extends GenericModel
{
	private $get = array();
	private $post = array();
	private $cartUid = "";
	private $numeroProdotti = 0;
	private $erroriSubmit = "";
	private $spam = false;
	private $userAgent = false;
	private $fullLog = "";
	private $svuota = 1;
	
	private static $deletedExpired = false;
	
	const LOG_CHECKOUT = 'CHECKOUT';
	const ORDINE_ESEGUITO = 'ORDINE ESEGUITO';
	const LOG_REGISTRAZIONE = 'REGISTRAZIONE';
	const REGISTRAZIONE_ESEGUITA = 'REGISTRAZIONE ESEGUITA';
	const ERRORI_VALIDAZIONE = 'ERRORI VALIDAZIONE';
	const SPAM = 'SPAM';
	
	public function __construct() {
		$this->_tables='log_piattaforma';
		$this->_idFields='id_log';
		
		parent::__construct();
		
		// if (v("abilita_log_piattaforma"))
		// {
		$this->get = $_GET;
		$this->post = $_POST;
		$this->cartUid = (string)User::$cart_uid;
		$this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

		if (v("abilita_log_piattaforma"))
			$this->eliminaScaduti();
		// }
	}
	
	public function eliminaScaduti()
	{
		if (!self::$deletedExpired)
		{
			$limit = time() - (v("tempo_log_ore") * 3600); 
			$this->db->del($this->_tables,'svuota = 1 AND time_inserimento < '.(int)$limit);
			
			if (v("tempo_log_permanenti_giorni"))
			{
				$limit = time() - ((int)v("tempo_log_permanenti_giorni") * 3600 * 24); 
				$this->db->del($this->_tables,'time_inserimento < '.(int)$limit);
			}
			
			self::$deletedExpired = true;
		}
	}
	
	public static function numeroLogPermanenti()
	{
		$lModel = new LogModel();
		
		return $lModel->clear()->where(array(
			"svuota"	=>	0
		))->rowNumber();
	}
	
	public function setNumeroProdotti($numero)
	{
		$this->numeroProdotti = $numero;
	}
	
	public function setErroriSubmit($risultato)
	{
		$this->erroriSubmit = $risultato;
	}
	
	public function setSpam()
	{
		$this->spam = true;
	}
	
	public function setFullLog($fullLog)
	{
		$this->fullLog = $fullLog;
	}
	
	public function setSvuota($svuota)
	{
		$this->svuota = $svuota;
	}
	
	public function setCartUid($cartUid)
	{
		$this->cartUid = $cartUid;
	}
	
	public function getLog($tipo, $cardUid)
	{
		return $this->clear()->select("full_log")->where(array(
			"tipo"		=>	sanitizeAll($tipo),
			"cart_uid"	=>	sanitizeAll($cardUid),
		))->orderBy("id_log desc")->limit(1)->first();
	}
	
	public function write($tipo, $risultato, $forza = false)
	{
		if (v("abilita_log_piattaforma") || $forza)
		{
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
			$this->setValues(array(
				"ip"		=>	getIp(),
				"_post"		=>	@json_encode($this->post),
				"_get"		=>	@json_encode($this->get),
				"cart_uid"	=>	$this->cartUid,
				"numero_prodotti_carrello"	=>	$this->numeroProdotti,
				"tipo"		=>	$tipo,
				"errori"	=>	$this->erroriSubmit,
				"risultato"	=>	$this->spam ? self::SPAM : $risultato,
				"time_inserimento"	=>	time(),
				"user_agent"	=>	$this->userAgent,
				"full_log"	=>	$this->fullLog,
				"svuota"	=>	$this->svuota,
			), "sanitizeDb");
			
			$res = $this->insert();
			
			if (v("usa_transactions"))
				$this->db->commit();
			
			return $res;
		}
		
		return false;
	}
}
