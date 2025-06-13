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

class SociModel extends BasicsectionModel {
	
	public $hModelName = "SocicatModel";
	
	public function overrideFormStruct()
	{
		$this->formStruct["entries"]["url"]["labelString"] = gtext("Link sito web");
		$this->formStruct["entries"]["email_contatto_evento"]["labelString"] = gtext("Email");
		$this->formStruct["entries"]["telefono_contatto_evento"]["labelString"] = gtext("Telefono");
		$this->formStruct["entries"]["indirizzo_localita_evento"]["labelString"] = gtext("Indirizzo");
		$this->formStruct["entries"]["localita_evento"]["labelString"] = gtext("Località");
	}
	
	public function insert()
	{
		$res = parent::insert();
		
		if ($res)
			$this->aggiornaTabellaLocalita($this->lastId());
		
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		if ($res)
			$this->aggiornaTabellaLocalita($id);
		
		return $res;
	}
	
	public function setFilters() {}
}
