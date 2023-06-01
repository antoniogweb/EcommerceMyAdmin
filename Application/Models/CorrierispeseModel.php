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

class CorrierispeseModel extends GenericModel {

	public function __construct() {
		$this->_tables='corrieri_spese';
		$this->_idFields='id_spesa';
		
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"peso");
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
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
				'peso'		=>	array(
					'labelString'=>	'Fino al peso di (kg)',
				),
				'prezzo'		=>	array(
					'labelString'=>	'Prezzo IVA ESCLUSA (€)',
				),
				'prezzo_ivato'	=>	array(
					'labelString'=>	'Prezzo IVA INCLUSA (€)',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function getPrezzo($idCorriere, $peso, $nazione)
	{
		return self::sGetPrezzo($idCorriere, $peso, $nazione);
	}
	
	public static function sGetPrezzo($idCorriere = null, $peso = null, $nazione = null)
	{
		$sp = new CorrierispeseModel();
		
		if (!$idCorriere)
		{
			$corr = new CorrieriModel();
			
			$corrieri = $corr->clear()->select("distinct corrieri.id_corriere,corrieri.*")->inner("corrieri_spese")->using("id_corriere")->orderBy("corrieri.id_order")->send(false);
			
			if (count($corrieri) > 0)
				$idCorriere = $corrieri[0]["id_corriere"];
		}
		
		// Provo la nazione
		$res = $sp->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
			"nazione"		=>	sanitizeAll($nazione),
			"gte"	=>	array("peso" => (float)$peso)
		))->orderBy("peso")->limit(1)->toList("prezzo")->send();
		
		if (count($res))
			return (float)$res[0];
		
		$prezzo = $sp->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
			"nazione"		=>	sanitizeAll($nazione),
		))->getMax("prezzo");
		
		if ($prezzo)
			return $prezzo;
		
		// Ricado su MONDO
		$res = $sp->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
			"nazione"		=>	"W",
			"gte"	=>	array("peso" => (float)$peso)
		))->orderBy("peso")->limit(1)->toList("prezzo")->send();
		
		if (count($res))
			return (float)$res[0];
		
		$prezzo = $sp->clear()->where(array(
			"id_corriere"	=>	(int)$idCorriere,
			"nazione"		=>	"W",
		))->getMax("prezzo");
		
		if ($prezzo)
			return $prezzo;
		
		return 0;
	}
	
	public function setPriceNonIvato()
	{
		if (v("prezzi_ivati_in_prodotti") && isset($this->values["prezzo_ivato"]))
		{
			$valore = Parametri::$iva;
			
			$this->values["prezzo"] = number_format(setPrice($this->values["prezzo_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function insert()
	{
		$this->setPriceNonIvato();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->setPriceNonIvato();
		
		return parent::update($id, $where);
	}
	
	public function peso($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."/corrierispese/form/update/".$record["corrieri_spese"]["id_spesa"]."?partial=Y&nobuttons=Y&procedi=1&nazione=".$record["corrieri_spese"]["nazione"]."'>".$record["corrieri_spese"]["peso"]."</a>";
		
		return "--";
	}
	
	public function prezzoivato($record)
	{
		if ($record["corrieri_spese"]["prezzo_ivato"] != "0,00")
			return $record["corrieri_spese"]["prezzo_ivato"];
		
		return "--";
	}
}
