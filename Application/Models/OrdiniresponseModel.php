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

class OrdiniresponseModel extends GenericModel
{
	private static $deletedExpired = false;
	
	public function __construct() {
		$this->_tables = 'orders_gateway_response';
		$this->_idFields = 'id_order_gateway_response';
		
		parent::__construct();
		
		if (v("tempo_log_gateway_response"))
			$this->eliminaScaduti();
	}
	
	public function eliminaScaduti()
	{
		if (!self::$deletedExpired && v("tempo_log_gateway_response"))
		{
			$giorni = (int)v("tempo_log_gateway_response");
			
			$dateTime = new DateTime();
			$dateTime->modify("- $giorni days");
			
			$this->del(null,array(
				"data_creazione < ?",
				array(sanitizeAll($dateTime->format("Y-m-d H:i:s")))
			));
			
			self::$deletedExpired = true;
		}
	}
	
	public static function numeroLog()
	{
		$lModel = new OrdiniresponseModel();
		
		return $lModel->clear()->rowNumber();
	}
	
	public static function aggiungi($cartUid, $resoponse, $result)
	{
		$or = new OrdiniresponseModel();
		
		$or->setValues(array(
			"cart_uid"	=>	$cartUid,
			"response"	=>	$resoponse,
			"risultato_transazione"	=>	$result ? 1 : 0,
		));
		
		$or->insert();
	}

	public static function responsoPresente($cartUid)
	{
		$or = new OrdiniresponseModel();

		return $or->clear()->where(array(
			"cart_uid"	=>	sanitizeAll($cartUid),
		))->rowNumber();
	}
}
