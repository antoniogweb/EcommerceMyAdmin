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

class IntegrazionisezioniinviiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='integrazioni_sezioni_invii';
		$this->_idFields='id_integrazione_sezione_invio';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'integrazione' => array("BELONGS_TO", 'IntegrazioniModel', 'id_integrazione',null,"CASCADE"),
			'sezione' => array("BELONGS_TO", 'IntegrazionisezioniModel', 'id_integrazione_sezione',null,"CASCADE"),
        );
    }
    
    // Restituisce il numero di inviati
    public static function numeroInviati($idIntegrazione, $idIntegrazioneSezione, $idElemento)
    {
		$ii = new IntegrazionisezioniinviiModel();
		
		return $ii->clear()->where(array(
			"id_integrazione"	=>	$idIntegrazione,
			"id_integrazione_sezione"	=>	$idIntegrazioneSezione,
			"id_elemento"	=>	$idElemento,
		))->count();
    }
    
    // Aggiungi a quelli inviati
    public static function aggiungi($idIntegrazione, $idIntegrazioneSezione, $idElemento, $idPiattaforma)
    {
		$ii = new IntegrazionisezioniinviiModel();
		$is = new IntegrazionisezioniModel();
		$i = new IntegrazioniModel();
		
		$integrazione = $i->selectId($idIntegrazione);
		$sezione = $is->selectId($idIntegrazioneSezione);
		
		if (!empty($sezione) && !empty($integrazione))
		{
			if (!self::numeroInviati($idIntegrazione, $idIntegrazioneSezione, $idElemento))
			{
				$ii->setValues(array(
					"id_integrazione"	=>	$idIntegrazione,
					"id_integrazione_sezione"	=>	$idIntegrazioneSezione,
					"id_elemento"	=>	$idElemento,
					"sezione"			=>	$sezione["sezione"],
					"codice_piattaforma"	=>	$idPiattaforma
				));
				
				if ($ii->insert())
					return true;
			}
		}
		
		return false;
    }
    
}
