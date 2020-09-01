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

class CorrierispeseController extends BaseController {
	
	public $tabella = "scaglioni corrieri";
	
	public $argKeys = array('id_corriere:sanitizeAll'=>'tutti');
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost("peso,prezzo");
		
		if ($this->viewArgs["id_corriere"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("id_corriere", $this->viewArgs["id_corriere"]);
		}
		
		parent::form($queryType, $id);
	}
}
