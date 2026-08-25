<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class PagesarticoliModel extends GenericModel
{
	public function __construct()
	{
		$this->_tables = 'pages_articoli';
		$this->_idFields = 'id_page_articolo';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function relations()
	{
		return array(
			'articolo' => array("BELONGS_TO", 'MagazzinoarticoliModel', 'id_articolo',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
		);
	}
	
	public function variantiCrud($record)
	{
		return CombinazioniModel::g()->getStringa($record["magazzino_articoli_combinazioni"]["id_c"], "<br />");
	}
	
	public function primaImmagineCarrelloCrud($record)
    {
		return ProdottiModel::tagImmagineCarrello($record["combinazioni"]["id_page"], $record["combinazioni"]["id_c"]);
    }
}
