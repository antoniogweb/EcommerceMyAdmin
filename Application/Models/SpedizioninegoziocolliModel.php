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

class SpedizioninegoziocolliModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_colli';
		$this->_idFields='id_spedizione_negozio_collo';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'spedizione' => array("BELONGS_TO", 'SpedizioninegozioModel', 'id_spedizione_negozio',null,"CASCADE"),
        );
    }
    
    public function pesoCrud($record)
    {
		if (SpedizioninegozioModel::g()->deletable($record["spedizioni_negozio_colli"]["id_spedizione_negozio"]))
			return "<input id-riga='".$record["spedizioni_negozio_colli"]["id_spedizione_negozio_collo"]."' style='max-width:60px;' class='form-control' name='quantity' value='".$record["spedizioni_negozio_colli"]["peso"]."' />";
		else
			return $record["spedizioni_negozio_colli"]["peso"];
    }
    
    public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			return SpedizioninegozioModel::g()->deletable($record["id_spedizione_negozio"]);
		
		return false;
	}
}
