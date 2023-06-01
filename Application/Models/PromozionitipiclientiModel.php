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

class PromozionitipiclientiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='promozioni_tipi_clienti';
		$this->_idFields='id_promo_tipo_cliente';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'promozione' => array("BELONGS_TO", 'PromozioniModel', 'id_p',null,"CASCADE"),
			'tipo_cliente' => array("BELONGS_TO", 'TipiclientiModel', 'id_tipo_cliente',null,"CASCADE"),
        );
    }
    
	public function insert()
	{
		$clean["id_p"] = (int)$this->values["id_p"];
		$clean["id_tipo_cliente"] = (int)$this->values["id_tipo_cliente"];
		
		$u = new TipiclientiModel();
		
		$cat = $u->selectId($clean["id_tipo_cliente"]);
		
		if (!empty($cat))
		{
			$res3 = $this->clear()->where(array("id_tipo_cliente"=>$clean["id_tipo_cliente"],"id_p"=>$clean["id_p"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert'>Questo elemento è già stato associato</div>";
			}
			else
			{
				$this->values["codice"] = sanitizeAll($cat["codice"]);
				
				return parent::insert();
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Questo elemento non esiste</div>";
		}
	}
	
}
