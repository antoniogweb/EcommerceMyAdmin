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

class CorrierispeseModel extends GenericModel {

	public function __construct() {
		$this->_tables='corrieri_spese';
		$this->_idFields='id_spesa';
		
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"peso,prezzo");
		
		parent::__construct();
	}
	
	public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nazione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(true),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function getPrezzo($idCorriere, $peso)
	{
		$res = $this->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
			"gte"	=>	array("peso" => (float)$peso)
		))->orderBy("peso")->limit(1)->toList("prezzo")->send();
		
		if (count($res))
			return (float)$res[0];
		
		$prezzo = $this->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
		))->getMax("prezzo");
		
		if ($prezzo)
			return $prezzo;
		
		return 0;
	}
	
	public function nazione($record)
	{
		if ($record["corrieri_spese"]["nazione"] != "W")
			return findTitoloDaCodice($record["corrieri_spese"]["nazione"]);
		
		return "Tutte";
	}
}
