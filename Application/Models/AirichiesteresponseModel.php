<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class AirichiesteresponseModel extends GenericModel
{
	public static $deletedExpired = false;
	public static $idRichiesta = 0;
	public static $tipo = "";
	public static $microtime = 0;
	public static $idLastInsert = 0;
	
	public function __construct() {
		$this->_tables='ai_richieste_response';
		$this->_idFields='id_ai_richieste_response';
		
		parent::__construct();
		
		$this->deleteExpired();
	}
	
	public function deleteExpired()
	{
		if (!self::$deletedExpired)
		{
			$limit = time() - 3900; // limite minuto e limite ora
			
			$this->del(null, array(
				'time_creazione < ?',
				array(
					$limit,
				)
			));
			
			self::$deletedExpired = true;
		}
	}
	
	public static function limiteSuperato($secondi = 60, $max = 10, $tipo = "ROUTING", $checkIp = false)
	{
		// Nessun limite da Backend
		if (!App::$isFrontend)
			return false;
		
		$model = new AirichiesteresponseModel();

		$fromTime = time() - (int)$secondi;
		
		$model->clear()->select("id_ai_richieste_response")->sWhere(array(
			"tipo = ? AND time_creazione >= ?",
			array(
				sanitizeAll($tipo),
				$fromTime
			)
		))->forUpdate();
		
		if ($checkIp)
			$model->aWhere(array(
				"ip"	=>	sanitizeAll(getIp()),
			));
		
		$count = (int)count($model->send(false));
		
		return ($count >= $max) ? true : false;
	}
	
	public static function startTime()
	{
		self::$microtime = microtime(true);
	}
	
	public static function aggiungi($request, $response)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$model = new AirichiesteresponseModel();
		
		$model->sValues(array(
			"id_ai_richiesta"	=>	(int)self::$idRichiesta,
			"ip"		=>	getIp(),
			"request"	=>	$request,
			"response"	=>	$response,
			"user_agent"	=>	$_SERVER['HTTP_USER_AGENT'] ?? "",
			"tipo"		=>	self::$tipo,
			"time_creazione"	=>	time(),
			"tempo"		=>	(microtime(true) - self::$microtime),
		), "sanitizeDb");
		
		if (self::$idLastInsert)
		{
			$model->update(self::$idLastInsert);
			self::$idLastInsert = 0;
		}
		else
			$model->insert();
		
		return $model->lId;
	}
}
