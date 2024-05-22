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

class RighetipologieModel extends GenericModel {

	public function __construct() {
		$this->_tables='righe_tipologie';
		$this->_idFields='id_riga_tipologia';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function numeroTipologiaInOrdine($idOrdine, $idTipologia)
	{
		$r = new RigheModel();
		
		return $r->clear()->where(array(
			"id_o"				=>	(int)$idOrdine,
			"id_riga_tipologia"	=>	(int)$idTipologia,
		))->rowNumber();
	}
	
	public static function checkInserimentoTipologiaInOrdine($idOrdine, $idTipologia)
	{
		$rt = new RighetipologieModel();
		
		$tipologia = $rt->selectId((int)$idTipologia);
		
		if (!empty($tipologia) && self::numeroTipologiaInOrdine($idOrdine, $idTipologia) < (int)$tipologia["max_numero_in_ordine"])
			return true;
		
		return false;
	}
}
