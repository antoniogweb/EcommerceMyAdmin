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

class ContenutiController extends BaseController {
	
	public $tabella = "fascia";
	
	public $argKeys = array('id_page:sanitizeAll'=>'tutti', 'id_c:sanitizeAll'=>'tutti', 'tipo:sanitizeAll'=>'tutti');
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
// 		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo,lingua,immagine_1,immagine_2,descrizione,link_contenuto,link_libero,target");
		
		$fields = "titolo,id_tipo,lingua,attivo";
		
		if ($this->viewArgs["tipo"] == "GENERICO")
			$fields .= ",descrizione,immagine_1";
		else if ($this->viewArgs["tipo"] == "MARKER")
			$fields .= ",descrizione,coordinate";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
		if ($this->viewArgs["id_c"] != "tutti")
			$this->m[$this->modelName]->setValue("id_c", $this->viewArgs["id_c"]);
		
		if ($this->viewArgs["tipo"] != "tutti")
			$this->m[$this->modelName]->setValue("tipo", $this->viewArgs["tipo"]);
		
		parent::form($queryType, $id);
	}
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
}
