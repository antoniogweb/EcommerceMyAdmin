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

class Airichiesteresponse extends GenericModel
{
	public static $idRichiesta = 0;
	public static $tipo = "";
	
	public function __construct() {
		$this->_tables='ai_richieste_response';
		$this->_idFields='id_ai_richieste_response';
		
		parent::__construct();
	}
	
	public static function aggiungi($request, $response)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$model = new Airichiesteresponse();
		
		$model->sValues(array(
			"id_ai_richiesta"	=>	(int)self::$idRichiesta,
			"ip"		=>	getIp(),
			"request"	=>	$request,
			"response"	=>	$response,
			"user_agent"	=>	$_SERVER['HTTP_USER_AGENT'] ?? "",
			"tipo"		=>	self::$tipo,
		), "sanitizeDb");
		
		$model->insert();
	}
}
