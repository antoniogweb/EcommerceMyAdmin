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

class AirichiestecontestiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ai_richieste_contesti';
		$this->_idFields = 'id_ai_richiesta_contesto';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}

	public function relations() {
		return array(
			'richiesta' => array("BELONGS_TO", 'AirichiesteModel', 'id_ai_richiesta',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
		);
    }

    public function insert()
	{
		$idRichiesta = isset($this->values["id_ai_richiesta"]) ? (int)$this->values["id_ai_richiesta"] : 0;

		if (!$idRichiesta)
			return false;

		$numeroContestiDellaRichiesta = $this->clear()->where(array(
			"id_ai_richiesta"	=>	(int)$idRichiesta,
		))->rowNumber();

		if ($numeroContestiDellaRichiesta < v("limite_contesti_per_richiesta"))
			return parent::insert();
		else
		{
			$this->result = false;
			$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione, il numero massimo delle pagina che si possono usare come contesto è")." ".v("limite_contesti_per_richiesta")."</a>";
			return false;
		}

	}
}
