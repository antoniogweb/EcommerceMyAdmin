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

class SpedizioninegozioinfoModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_info';
		$this->_idFields='id_spedizione_negozio_info';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'spedizione' => array("BELONGS_TO", 'SpedizioninegozioModel', 'id_spedizione_negozio',null,"CASCADE"),
        );
    }
    
    // Salva il log dell'invio
    public function inserisci($idSpedizione, $codice, $descrizione, $estensione = "XML")
    {
		$spModel = new SpedizioninegozioModel();
		
		$spedizione = $spModel->clear()->select("*")->inner(array("spedizioniere"))->where(array(
			"id_spedizione_negozio"	=>	(int)$idSpedizione,
		))->first();
		
		if (!empty($spedizione))
		{
			$modulo = SpedizioninegozioModel::getModulo((int)$idSpedizione);
			
			if ($modulo && $modulo->isAttivo())
			{
				$path = $modulo->getLogPath((int)$idSpedizione);
				
				$this->sValues(array(
					"id_spedizione_negozio"	=>	(int)$idSpedizione,
					"codice_info"			=>	$codice,
					"codice_corriere"		=>	$spedizione["spedizionieri"]["codice"],
					"descrizione"			=>	$descrizione,
				), "sanitizeDb");
				
				if ($this->insert())
					FilePutContentsAtomic($path."/$codice.$estensione", $descrizione);
			}
		}
	}
}
