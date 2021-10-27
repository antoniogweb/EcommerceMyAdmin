<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class EventiretargetinggruppiModel extends GenericModel {
	
	public static $arrayIdModel = null;
	
	public function __construct() {
		$this->_tables='eventi_retargeting_gruppi';
		$this->_idFields='id_gruppo_retargeting';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'retargeting' => array("HAS_MANY", 'EventiretargetingModel', 'id_gruppo_retargeting', null, "RESTRICT", "L'elemento non può essere eliminato perché ha degli eventi retargeting collegati"),
			'fonti' => array("HAS_MANY", 'EventiretargetinggruppifontiModel', 'id_gruppo_retargeting', null, "CASCADE"),
		);
    }
    
    public static function getIdGruppiModel()
    {
		if (!isset(self::$arrayIdModel))
			self::$arrayIdModel = EventiretargetinggruppiModel::g(false)->toList("id_gruppo_retargeting", "model")->send();
		
		return self::$arrayIdModel;
    }
    
    public function getArrayFonti($idGruppoRetargeting)
    {
		return EventiretargetinggruppifontiModel::g(false)->select("eventi_retargeting_fonti.fonte")->inner(array("fonti"))->where(array(
			"id_gruppo_retargeting"				=>	(int)$idGruppoRetargeting,
			"eventi_retargeting_fonti.attivo"	=>	1,
		))->toList("eventi_retargeting_fonti.fonte")->send();
    }
}
