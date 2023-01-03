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

class CombinazionimovimentiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='combinazioni_movimenti';
		$this->_idFields='id_combinazione_movimento';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'combinazione' => array("BELONGS_TO", 'CombinazioniModel', 'id_c',null,"CASCADE","Si prega di selezionare la comnbinazione"),
			'riga' => array("BELONGS_TO", 'RigheModel', 'id_r',null,"CASCADE"),
        );
    }
    
    public function tipoCrud($record)
    {
		if ($record["combinazioni_movimenti"]["titolo"] == "CARICO")
			return "<i class='text text-primary fa fa-arrow-up'></i>";
		else if ($record["combinazioni_movimenti"]["titolo"] == "SCARICO")
			return "<i class='text text-success fa fa-arrow-down'></i>";
		else
			return $record["combinazioni_movimenti"]["titolo"];
    }
    
    public function resettaCrud($record)
    {
		if ($record["combinazioni_movimenti"]["resetta"])
			return "<i class='text text-success fa fa-check'></i>";
		
		return "";
    }
    
    public function statoOrdineCrud($record)
    {
		if (!isset(StatiordineModel::$recordTabella))
			StatiordineModel::g(false)->setRecordTabella("codice");
		
// 		print_r(self::$recordTabella);
		
		if (isset(StatiordineModel::$recordTabella[$record["combinazioni_movimenti"]["stato_ordine"]]))
			return "<span class='text-bold label label-".StatiordineModel::$recordTabella[$record["combinazioni_movimenti"]["stato_ordine"]]["classe"]."'>".StatiordineModel::$recordTabella[$record["combinazioni_movimenti"]["stato_ordine"]]["titolo"]."</span>";
		
		return "";
    }
}
