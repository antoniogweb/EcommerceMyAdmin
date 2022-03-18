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

class ClassiscontocategoriesModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='classi_sconto_categories';
		$this->_idFields='id_csc';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function insert()
	{
		$clean["id_classe"] = (int)$this->values["id_classe"];
		$clean["id_c"] = (int)$this->values["id_c"];
		
		$u = new ClassiscontoModel();
		
		$ng = $u->clear()->where(array("classi_sconto.id_classe"=>$clean["id_classe"]))->rowNumber();
		
		if ($ng > 0)
		{
			$res3 = $this->clear()->where(array("id_c"=>$clean["id_c"],"id_classe"=>$clean["id_classe"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert'>Questo elemento è già stato associato a questa categoria</div>";
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
