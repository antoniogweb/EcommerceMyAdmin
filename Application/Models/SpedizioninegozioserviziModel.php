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

class SpedizioninegozioserviziModel extends GenericModel
{
	public static $dateTime = null;
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_servizi';
		$this->_idFields='id_spedizione_negozio_servizio';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'spedizione' => array("BELONGS_TO", 'SpedizioninegozioModel', 'id_spedizione_negozio',null,"CASCADE"),
        );
    }
    
    public function insert()
    {
		if (isset($this->values["codice"]))
			$this->setValue("titolo",OpzioniModel::label("GLS_SERVIZI_AGGIUNTIVI", $this->values["codice"]));
		
		if (!$this->clear()->where(array(
			"id_spedizione_negozio"	=>	(int)$this->values["id_spedizione_negozio"],
			"codice"				=>	sanitizeAll($this->values["codice"]),
		))->rowNumber())
			return parent::insert();
		else
		{
			$this->notice = '<div class="alert alert-danger">'.gtext("Attenzione, il servizio selezionato è già stato inserito").'</div><div style="display:none;" rel="hidden_alert_notice">codice</div>';
			return false;
		}
    }
    
    public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			return SpedizioninegozioModel::g()->deletable($record["id_spedizione_negozio"]);
		
		return false;
	}
}
