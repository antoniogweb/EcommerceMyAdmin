<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class ImportController extends Controller {

	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		ini_set("memory_limit","512M");
		
		$this->model("LingueModel");
		
		BaseController::$traduzioni = $this->m["LingueModel"]->clear()->where(array(
			"principale"	=>	0,
			"attiva"		=>	1,
		))->orderBy("id_order")->toList("codice")->send();
		
		$this->model('ImpostazioniModel');
		
		$this->m["ImpostazioniModel"]->getImpostazioni();
		
		$this->session('admin');
		$this->s['admin']->check();
	}
	
	public function prodotti($c = "")
	{
		if (is_string($c) && $c == "sdf8734jhbsdf78jhsd8dfgmbjhbf34")
		{
			Import::prodotti();
		}
	}
	
	public function news($c = "")
	{
		if (is_string($c) && $c == "sdf8734jhbsdf78jhsd8dfgmbjhbf34")
		{
			Import::news();
		}
	}
	
	public function utenti($c = "")
	{
		if (is_string($c) && $c == "sdf8734jhbsdf78jhsd8dfgmbjhbf34")
		{
			Import::utenti();
		}
	}
}
