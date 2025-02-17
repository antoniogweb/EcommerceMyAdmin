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

class EventiretargetinggruppifontiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='eventi_retargeting_gruppi_fonti';
		$this->_idFields='id_gruppo_retargeting_fonte';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'gruppo' => array("BELONGS_TO", 'EventiretargetinggruppiModel', 'id_gruppo_retargeting',null,"CASCADE"),
			'fonti' => array("BELONGS_TO", 'EventiretargetingfontiModel', 'id_fonte',null,"CASCADE"),
		);
    }
}
