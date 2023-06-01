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

class RigheelementiModel extends GenericModel
{
	public $campoTitolo = "email";
	
	public function __construct() {
		$this->_tables = 'righe_elementi';
		$this->_idFields = 'id_riga_elemento';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'riga' => array("BELONGS_TO", 'RigheModel', 'id_r',null,"CASCADE"),
        );
    }
    
    public static function getElementiRiga($idR)
	{
		$re = new RigheelementiModel();
		
		return $re->clear()->where(array(
			"id_r"	=>	(int)$idR,
		))->send(false);
	}
}
