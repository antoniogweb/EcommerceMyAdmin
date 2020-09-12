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

class NazioniController extends BaseController {
	
	public $mainFields = array("[[ledit]];nazioni.titolo;","nazioni.iso_country_code","tipo","attiva","attivaSpedizione");
	
	public $mainHead = "Titolo,Codice nazione,Tipo,Attiva,Spedizione attiva";
	
	public $orderBy = "titolo";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti', 'tipo:sanitizeAll'=>'tutti', 'attiva:sanitizeAll'=>'tutti', 'attiva_spedizione:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function main()
	{
		$this->shift();
		
		$attivaDisattiva = array(
			"tutti"	=>	"Attiva / Disattiva",
			"1"		=>	"Attiva",
			"0"		=>	"Non attiva",
		);
		
		$attivaDisattivaSped = array(
			"tutti"	=>	"Sped. Attiva / Disattiva",
			"1"		=>	"Sped. attiva",
			"0"		=>	"Sped. non attiva",
		);
		
		$this->filters = array("titolo",array("tipo",null,array(
			"tutti"	=>	"Tipo",
			"UE"	=>	"UE",
			"EX"	=>	"EXTRA UE",
		)), array(
			"attiva",null,$attivaDisattiva
		),  array(
			"attiva_spedizione",null,$attivaDisattivaSped
		));
			
		$this->m[$this->modelName]->where(array(
				"OR"	=>	array(
					"lk" => array("titolo" => $this->viewArgs["titolo"]),
					" lk" => array("iso_country_code" => $this->viewArgs["titolo"]),
				),
				"tipo"	=>	$this->viewArgs["tipo"],
				"attiva"	=>	$this->viewArgs["attiva"],
				"attiva_spedizione"	=>	$this->viewArgs["attiva_spedizione"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,iso_country_code,attiva,attiva_spedizione');
		
		parent::form($queryType, $id);
	}
}
