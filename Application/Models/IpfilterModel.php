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

class IpfilterModel extends GenericModel
{
	public $campoTitolo = "ip";
	
	public function __construct() {
		$this->_tables = 'ip_filter';
		$this->_idFields = 'id_ip_filter';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'whitelist'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Whitelist / Blacklist",
					"options"	=>	array(
						"1"	=>	"Whitelist",
						"0"	=>	"Blacklist",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function insert()
	{
		$this->values["time_creazione"] = time();
		
		return parent::insert();
	}
	
	public function modalitaCrud($record)
	{
		if ($record["ip_filter"]["whitelist"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function check($ip, $whitelist = 1)
	{
		return $this->clear()->where(array(
			"ip"		=>	sanitizeAll($ip),
			"whitelist"	=>	(int)$whitelist,
		))->rowNumber();
	}
	
	public function blocca($ip, $minuti = 0)
	{
		$this->sValues(array(
			"ip"	=>	sanitizeAll($ip),
			"whitelist"	=>	0,
		));
		
		$this->insert();
	}
}
