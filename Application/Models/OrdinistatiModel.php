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

class OrdinistatiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'orders_stati';
		$this->_idFields = 'id_o_stato';
		
		parent::__construct();
	}
	
	public function insert()
	{
		if (App::$isFrontend)
			$this->values["id_user"] = (int)User::$id;
		else
			$this->values["id_admin"] = (int)User::$idAdmin;
		
		$this->values["time_creazione"] = time();
		
		return parent::insert();
	}
	
	public function aggiungi($idO, $stato)
	{
		$lastStato = $this->lastStatoInserito($idO);
		
		if ($stato != $lastStato)
		{
			$this->sValues(array(
				"id_o"	=>	(int)$idO,
				"stato"	=>	sanitizeAll($stato),
			));
			
			return $this->insert();
		}
	}
	
	public function lastStatoInserito($idO)
	{
		return $this->clear()->select("stato")->where(array(
			"id_o"	=>	(int)$idO,
		))->orderBy("id_o_stato desc")->limit(1)->field("stato");
	}
}
