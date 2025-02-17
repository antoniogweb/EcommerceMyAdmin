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

class OrdiniivaripartitaModel extends GenericModel
{	
	public function __construct() {
		$this->_tables = 'orders_iva_ripartita';
		$this->_idFields = 'id_orders_iva_ripartita';
		
		parent::__construct();
	}
	
	public function inserisciRipartizioni($id_o, $ripartizioni)
	{
		$this->del(null, array(
			"id_o"	=>	(int)$id_o,
		));
		
		$i = new IvaModel();
		
		foreach ($ripartizioni as $idIva => $ripartizione)
		{
			$this->sValues(array(
				"id_o"			=>	(int)$id_o,
				"id_iva"		=>	(int)$idIva,
				"aliquota_iva"	=>	$i->getValore((int)$idIva),
				"ripartizione"	=>	$ripartizione,
				"ripartizione_su_ivato"	=>	v("prezzi_ivati_in_prodotti"),
			));
			
			$this->insert();
		}
	}
}
