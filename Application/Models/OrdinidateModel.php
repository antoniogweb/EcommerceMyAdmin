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

class OrdinidateModel extends GenericModel
{	
	public function __construct() {
		$this->_tables = 'orders_date';
		$this->_idFields = 'id_o_date';
		
		parent::__construct();
	}
	
	public function inserisci($ordine, $annullaPagato = 1)
	{
		if ($annullaPagato)
		{
			$this->sValues(array(
				"data_pagamento"	=>	$ordine["data_pagamento"],
				"time_pagamento"	=>	$ordine["time_pagamento"],
				"pagato"			=>	$ordine["pagato"],
			));
		}
		else
		{
			$this->sValues(array(
				"data_annullamento"	=>	$ordine["data_annullamento"],
				"time_annullamento"	=>	$ordine["time_annullamento"],
				"annullato"			=>	$ordine["annullato"],
			));
		}
		
		$this->setValue("id_o", (int)$ordine["id_o"]);
		$this->setValue("id_user", (int)User::$id);
		
		$this->insert();
	}
}
