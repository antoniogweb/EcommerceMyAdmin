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

class SpedizionieriModel extends GenericModel {

	public function __construct() {
		$this->_tables='spedizionieri';
		$this->_idFields='id_spedizioniere';
		
		$this->_idOrder='id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					'type'		=>	'Select',
					'options'	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
				),
			),
		);
	}
	
	public function attivoCrud($record)
	{
		return $record["spedizionieri"]["attivo"] ? "Sì" : "No";
	}
	
	public function relations() {
        return array(
			'ordini' => array("HAS_MANY", 'OrdiniModel', 'id_page', null, "RESTRICT", "L'elemento è collegato ad alcuni ordini e non può essere eliminato."),
        );
    }
    
    public function selectTendina()
	{
		return array(0=>"Seleziona") + $this->orderBy("id_order")->toList("id_spedizioniere","titolo")->send();
	}
}
