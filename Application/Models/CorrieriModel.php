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

class CorrieriModel extends GenericModel {

	public function __construct() {
		$this->_tables='corrieri';
		$this->_idFields='id_corriere';
		
		$this->_idOrder='id_order';
		
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'prezzi' => array("HAS_MANY", 'CorrierispeseModel', 'id_corriere', null, "CASCADE"),
        );
    }
    
    public function getIdsCorrieriNazione($nazione)
	{
		$clean["nazione"] = sanitizeAll($nazione);
		
		return $this->clear()->select("distinct corrieri.id_corriere")->inner(array("prezzi"))->where(array(
			"OR"	=>	array(
				"corrieri_spese.nazione"	=> $clean["nazione"],
				"-corrieri_spese.nazione"	=> "W",
			),
		))->toList("corrieri.id_corriere")->orderBy("corrieri.id_corriere")->send();
	}
	
	public function elencoCorrieri()
	{
		return $this->clear()->select("distinct corrieri.id_corriere,corrieri.*")->inner("corrieri_spese")->using("id_corriere")->orderBy("corrieri.id_order")->send(false);
	}
	
	public function spedibile($idCorriere, $nazione)
	{
		$elencoCorrieri = $this->elencoCorrieri();
		
		if (count($elencoCorrieri) > 0)
		{
			$idsCorrieri = $this->getIdsCorrieriNazione($nazione);
			
			if (in_array($idCorriere, $idsCorrieri))
				return true;
		}
		
		return true;
	}
}
