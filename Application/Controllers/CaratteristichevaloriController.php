<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class CaratteristichevaloriController extends BaseController {
	
	public $tabella = "valori variante";
	
	public $argKeys = array('id_car:sanitizeAll'=>'tutti');
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->model("ContenutitradottiModel");
		
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost("titolo");
		
		if ($this->viewArgs["id_car"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("id_car", $this->viewArgs["id_car"]);
		}
		
		parent::form($queryType, $id);
		
// 		if (strcmp($queryType,'update') === 0)
// 		{
// 			$data["contenutiTradotti"] = $this->m["ContenutitradottiModel"]->clear()->where(array(
// 				"id_cv"	=>	(int)$id,
// 			))->send(false);
// 			
// 			$this->append($data);
// 		}
	}
}
