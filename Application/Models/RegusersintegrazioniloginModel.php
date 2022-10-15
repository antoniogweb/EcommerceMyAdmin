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

class RegusersintegrazioniloginModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='regusers_integrazioni_login';
		$this->_idFields='id_user_integrazione';
		
		parent::__construct();
	}
    
	public function insert()
	{
		$this->values["time_ultimo_accesso"] = time();
		
		return parent::insert();
	}
	
	public function getIdUtenteDaIdApp($codiceApp, $idApp)
	{
		return (int)$this->clear()->where(array(
			"codice"		=>	sanitizeAll($codiceApp),
			"user_id_app"	=>	sanitizeAll($idApp),
		))->field("id_user");
	}
}
