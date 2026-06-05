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

class OrdiniacquistorigheModel extends GenericModel
{
	public $campoTitolo = "titolo";

	public function __construct() {
		$this->_tables = 'ordini_acquisto_righe';
		$this->_idFields = 'id_ordine_acquisto_riga';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'ordine' => array("BELONGS_TO", 'OrdiniacquistoModel', 'id_ordine_acquisto',null,"CASCADE"),
			'articolo' => array("BELONGS_TO", 'MagazzinoarticoliModel', 'id_articolo',null,"CASCADE"),
			'tipologia' => array("BELONGS_TO", 'OrdiniacquistorighetipologieModel', 'id_ordine_acquisto_riga_tipologia',null,"CASCADE"),
		);
    }
	
	public function insert()
	{
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		return parent::update($id, $where);
	}
}
