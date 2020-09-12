<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class NazioniModel extends GenericModel
{
	public static $elenco = null;
	
	public function __construct() {
		$this->_tables = 'nazioni';
		$this->_idFields = 'id_nazione';
		
		parent::__construct();
	}
	
	public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attiva'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array(
						"1"	=>	"Sì",
						"0"	=>	"No",
					),
					"reverse"	=>	"yes",
				),
				'attiva_spedizione'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array(
						"1"	=>	"Sì",
						"0"	=>	"No",
					),
					"reverse"	=>	"yes",
				),
				'iso_country_code'	=>	array(
					"labelString"	=>"Codice nazione (2 cifre)"
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function getNome($codice)
	{
		return $this->clear()->where(array(
			"iso_country_code"	=>	sanitizeDb($codice),
		))->field("titolo");
	}
	
	public function findCodiceDaTitolo($titolo)
	{
		$nazione = $this->clear()->where(array(
			"titolo"	=>	sanitizeAll($titolo),
		))->record();
		
		if (!empty($nazione))
			return $nazione["iso_country_code"];
		
		return "";
	}
	
	public function tipo($record)
	{
		if ($record["nazioni"]["tipo"] == "UE")
			return "UE";
		
		return "EXTRA UE";
	}
	
	public function attivaCrud($record)
	{
		if ($record["nazioni"]["attiva"])
			return "Sì";
		
		return "No";
	}
	
	public function attivaSpedizioneCrud($record)
	{
		if ($record["nazioni"]["attiva_spedizione"])
			return "Sì";
		
		return "No";
	}
	
	public function attiva($id)
	{
		$this->setValues(array(
			"attiva"	=>	1
		));
		
		$this->update((int)$id);
	}
	
	public function disattiva($id)
	{
		$this->setValues(array(
			"attiva"	=>	0
		));
		
		$this->update((int)$id);
	}
	
	public function attivasped($id)
	{
		$this->setValues(array(
			"attiva_spedizione"	=>	1
		));
		
		$this->update((int)$id);
	}
	
	public function disattivasped($id)
	{
		$this->setValues(array(
			"attiva_spedizione"	=>	0
		));
		
		$this->update((int)$id);
	}
}
