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

class OrdiniacquistostatistoricoModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ordini_acquisto_stati_storico';
		$this->_idFields = 'id_ordine_acquisto_stato_storico';
		
		parent::__construct();
	}
	
	public function insert()
	{
		if (!App::$isFrontend)
			$this->values["id_admin"] = (int)User::$idAdmin;
		
		$this->values["time_creazione"] = time();
		
		return parent::insert();
	}
	
	public function aggiungi($idO, $idStato)
	{
		$lastIdStato = $this->lastStatoInserito($idO);
		
		if ($idStato != $lastIdStato)
		{
			$this->sValues(array(
				"id_ordine_acquisto"	=>	(int)$idO,
				"id_ordine_acquisto_stato"	=>	(int)$idStato,
			));
			
			return $this->insert();
		}
	}
	
	public function lastStatoInserito($idO)
	{
		$idStato = $this->clear()->select("id_ordine_acquisto_stato")->where(array(
			"id_ordine_acquisto"	=>	(int)$idO,
		))->orderBy("id_ordine_acquisto_stato_storico desc")->limit(1)->field("id_ordine_acquisto_stato");
		
		return $idStato;
	}
	
	public static function numero($idO)
	{
		$model = new OrdiniacquistostatistoricoModel();
		
		return $model->clear()->where(array(
			"id_ordine_acquisto"	=>	(int)$idO,
		))->rowNumber();
	}
}
