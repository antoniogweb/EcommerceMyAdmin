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

class SpedizioninegozioeventiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_eventi';
		$this->_idFields='id_spedizione_negozio_evento';
		
		parent::__construct();
	}
	
	// Insertisci un nuovo evento con uno stato uguale a $stato
	// Controlla che lo stato I non sia già presente nella spedizione avente ID = $idSpedizione
	public function inserisci($idSpedizione, $stato = "I")
	{
		$titolo = SpedizioninegoziostatiModel::getCampoG($stato, "titolo");
		
		if (isset($titolo))
		{
			// Verifico che non sia già stato inviato
			if ($stato == "I")
			{
				if ($this->clear()->where(array(
					"id_spedizione_negozio"	=>	(int)$idSpedizione,
					"codice"	=>	"I",
				))->rowNumber())
					$stato = "II";
			}
			
			$this->sValues(array(
				"id_spedizione_negozio"	=>	(int)$idSpedizione,
				"titolo"				=>	$titolo,
				"codice"				=>	$stato,
			));
			
			$res = $this->insert();
			
			return $res;
		}
		
		return false;
	}
}
