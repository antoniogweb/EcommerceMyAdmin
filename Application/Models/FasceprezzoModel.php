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

class FasceprezzoModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'fasce_prezzo';
		$this->_idFields = 'id_fascia_prezzo';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'da'		=>	array(
					'labelString'=>	'Da prezzo IVA ESCLUSA (€)',
				),
				'a'		=>	array(
					'labelString'=>	'A prezzo IVA ESCLUSA (€)',
				),
				'da_ivato'		=>	array(
					'labelString'=>	'Da prezzo IVA INCLUSA (€)',
				),
				'a_ivato'		=>	array(
					'labelString'=>	'A prezzo IVA INCLUSA (€)',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function setPriceNonIvato()
	{
		$valore = Parametri::$iva;
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			if (isset($this->values["da_ivato"]))
				$this->values["da"] = number_format(setPrice($this->values["da_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["a_ivato"]))
				$this->values["a"] = number_format(setPrice($this->values["a_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function insert()
	{
		$this->setPriceNonIvato();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->setPriceNonIvato();
		
		return parent::update($id, $where);
	}
}
