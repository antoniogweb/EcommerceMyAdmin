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

class ListeregalopagesModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'liste_regalo_pages';
		$this->_idFields = 'id_lista_regalo_page';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE"),
			'combinazione' => array("BELONGS_TO", 'CombinazioniModel', 'id_c',null,"CASCADE"),
        );
    }
    
    public function insert()
    {
		$this->values["time_creazione"] = time();
		
		return parent::insert();
    }
    
    public function checkAccesso($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && ListeregaloModel::numeroListeUtente(User::$id, $record["id_lista_regalo"]))
			return true;
		
		return false;
    }
    
    public function elimina($id)
	{
		if ($this->checkAccesso((int)$id))
			return $this->del((int)$id);
		
		return false;
	}
}
