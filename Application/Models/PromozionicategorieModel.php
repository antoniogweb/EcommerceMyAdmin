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

class PromozionicategorieModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='promozioni_categorie';
		$this->_idFields='id_pc';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'categoria' => array("BELONGS_TO", 'CategorieModel', 'id_c',null,"CASCADE"),
        );
    }
    
	public function insert()
	{
		$clean["id_p"] = (int)$this->values["id_p"];
		$clean["id_c"] = (int)$this->values["id_c"];
		
		$u = new CategoriesModel();
		
		$cat = $u->selectId($clean["id_c"]);
		
		if (!empty($cat))
		{
			$res3 = $this->clear()->where(array("id_c"=>$clean["id_c"],"id_p"=>$clean["id_p"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert'>Questo elemento è già stato associato</div>";
			}
			else
			{
				return parent::insert();
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Questo elemento non esiste</div>";
		}
	}
	
}
