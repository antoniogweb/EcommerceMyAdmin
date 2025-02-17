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

class BaseCronController extends Controller
{
	protected $estratiDatiGenerali = false;
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_cron_web"))
			$this->responseCode(403);
		
		if (!VariabiliModel::checkToken("token_comandi_cron_web"))
			$this->responseCode(403);
	}
	
	private function getOpzioni($opzioni, $elencoOpzioni)
	{
		foreach ($elencoOpzioni as $o)
		{
			if (isset($_GET[$o]))
				$opzioni[$o] = $_GET[$o];
		}
		
		return $opzioni;
	}
	
	public function traduci()
	{
		if (!v("attiva_gestione_traduttori"))
			$this->responseCode(403);
		
		$this->clean();
		
		$options = array(
			"azione" => isset($_GET["azione"]) ? (string)$_GET["azione"] : "",
		);
		
		$options = $this->getOpzioni($options, array("lingua", "id_record", "limit"));
		
		echo "<pre>";
		require_once(ROOT . "/admin/Application/Commands/azioni/traduzioni.php");
		echo "</pre>";
	}
}
