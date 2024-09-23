<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class CommessiModel extends GenericModel
{
	public function __construct() {
		$this->_tables='commessi';
		$this->_idFields='id_commesso';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordini' => array("HAS_MANY", 'OrdiniModel', 'id_commesso', null, "RESTRICT", "L'elemento ha degli ordini associati e non può essere eliminato."),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	self::$entryAttivo,
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public static function getTitolo($codiceStato)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("id_commesso");
		
		return isset(self::$recordTabella[$codiceStato]["titolo"]) ? self::$recordTabella[$codiceStato]["titolo"] : null;
	}
}
