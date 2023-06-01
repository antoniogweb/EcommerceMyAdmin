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

class CorrelatiModel extends GenericModel {

	public $cart_uid = null;
	
	public function __construct() {
		$this->_tables='prodotti_correlati';
		$this->_idFields='id_pc';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'prodotti_correlati.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}

	public function insert()
	{
		$clean["id_page"] = (int)$this->values["id_page"];
		$clean["id_corr"] = (int)$this->values["id_corr"];
		$clean["accessorio"] = (int)$this->values["accessorio"];
		
		$p = new PagesModel();
		$res2 = $p->clear()->where(array("id_page"=>$clean["id_corr"],"principale"=>"Y"))->send();
		
		if (count($res2))
		{
			if ($clean["id_page"] === $clean["id_corr"])
			{
				$this->notice = "<div class='alert'>Non puoi associare l'elemento stesso</div>";
			}
			else
			{
				$res = $this->clear()->where(array(
					"id_page"	=>	$clean["id_page"],
					"id_corr"	=>	$clean["id_corr"],
					"accessorio"=>	$clean["accessorio"],
				))->send();
				
				if (count($res) > 0)
				{
					$this->notice = "<div class='alert'>Questo elemento è già stato collegato alla pagina</div>";
				}
				else
				{
					parent::insert();
					
					return true;
				}
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Questo elemento non esiste</div>";
		}
		
		return false;
	}
}
