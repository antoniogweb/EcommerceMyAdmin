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

class LingueModel extends GenericModel
{
	public static $valori = null;
	
	public function __construct() {
		$this->_tables = 'lingue';
		$this->_idFields = 'id_lingua';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function getValori()
	{
		$l = new LingueModel();
		
		if (!isset(self::$valori))
			self::$valori = $l->clear()->orderBy("id_order")->toList("codice","descrizione")->send();
		
		return self::$valori;
	}
	
	public static function getPrincipale()
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"principale"	=>	1
		))->field("codice");
	}
}
