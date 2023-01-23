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

class DocumentilingueModel extends GenericModel {

	public function __construct() {
		$this->_tables='documenti_lingue';
		$this->_idFields='id_documento_lingua';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'documento' => array("BELONGS_TO", 'DocumentiModel', 'id_doc',null,"CASCADE"),
			'lingua' => array("BELONGS_TO", 'LingueModel', 'id_lingua',null,"CASCADE"),
        );
    }
    
    public static function lingueCheMancano($idDoc)
    {
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"attiva"	=>	1,
		))->orderBy("descrizione")->sWhere(array("id_lingua not in (select id_lingua from documenti_lingue where id_doc = ?)",array((int)$idDoc)))->toList("id_lingua", "descrizione")->send();
    }
    
	public function insert()
	{
		$this->recuperaCodiceLingua();
		
		if (isset($this->values["id_doc"]))
		{
			$this->values["id_page"] = DocumentiModel::g(false)->clear()->where(array(
				"id_doc"	=>	(int)$this->values["id_doc"],
			))->field("id_page");
		}
		
		return parent::insert();
	}
}
