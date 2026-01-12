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

class CronController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if (v("attiva_cron_web") && VariabiliModel::checkToken("token_comandi_cron_web") && VariabiliModel::checkToken("token_migrazioni_no_admin"))
			$this->loginController = "cron";
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
// 		$this->clean();
		
		ini_set("memory_limit","512M");
		ini_set("max_execution_time","300");
	}
	
	public function migrazioni($c = "", $mostra = 0)
	{
// 		$this->clean();
		
		if (is_string($c) && v("codice_cron") && (string)$c === (string)v("codice_cron"))
		{
			$data["esitoMigrazioni"] = Migrazioni::up($mostra);
			$data["titoloPagina"] = gtext("Esito migrazioni");
			
			$this->append($data);
			$this->load("output");
		}
	}
}
