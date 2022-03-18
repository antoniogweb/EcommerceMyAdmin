<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

require_once(ROOT."/Application/Include/import.php");

class ImportController extends BaseController {

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		ini_set("memory_limit","512M");
		
		$this->session('admin');
		$this->s['admin']->check();
		
		$this->clean();
	}
	
	public function prodotti($c = "")
	{
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			Import::prodotti();
		}
	}
	
	public function news($c = "")
	{
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			Import::news();
		}
	}
	
	public function utenti($c = "")
	{
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			Import::utenti();
		}
	}
	
	public function contenuti($c = "")
	{
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			Import::contenuti();
		}
	}
}
