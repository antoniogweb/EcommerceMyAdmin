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

class LayerController extends BaseController {
	
	public $orderBy = "id_layer desc";
	
	public $argKeys = array('id_page:sanitizeAll'=>'tutti');
	
	public $tabella = "layer";
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost("titolo,testo,immagine,larghezza_1,larghezza_2,larghezza_3,larghezza_4,x_1,x_2,x_3,x_4,y_1,y_2,y_3,y_4,animazione,url");
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		}
		
		parent::form($queryType, $id);
	}
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
}
