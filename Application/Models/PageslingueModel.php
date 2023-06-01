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

class PageslingueModel extends GenericModel {

	public function __construct() {
		$this->_tables='pages_lingue';
		$this->_idFields='id_page_lingua';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'lingua' => array("BELONGS_TO", 'LingueModel', 'id_lingua',null,"CASCADE"),
        );
    }
    
    public static function lingueCheMancano($idPage)
    {
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"attiva"	=>	1,
		))->orderBy("descrizione")->sWhere(array("id_lingua not in (select id_lingua from pages_lingue where id_page = ?)",array((int)$idPage)))->toList("id_lingua", "descrizione")->send();
    }
    
	public function insert()
	{
		$this->recuperaCodiceLingua();
		
		return parent::insert();
	}
}
