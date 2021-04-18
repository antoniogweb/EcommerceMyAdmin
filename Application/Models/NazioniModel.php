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
	
	public static $elencoNazioni = null;
	public static $elencoCoordinateNazioni = null;
	
	public static $selectTipi = array(
		"UE"	=>	"UE",
		"EX"	=>	"EXTRA UE",
	);
	
	public function __construct() {
		$this->_tables = 'nazioni';
		$this->_idFields = 'id_nazione';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
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
				'campo_p_iva'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Attiva P.IVA',
					'options'	=>	array(
						"1"	=>	"Sì",
						"0"	=>	"No",
					),
					"reverse"	=>	"yes",
				),
				'iso_country_code'	=>	array(
					"labelString"	=>"Codice nazione (2 cifre)"
				),
				'tipo'	=>	array(
					'type'		=>	'Select',
					'options'	=>	self::$selectTipi,
					"reverse"	=>	"yes",
				),
				'id_iva'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Aliquota Iva',
					'options'	=>	$this->selectIva(),
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Usata nel caso venga superata la soglia annuale indicata sotto</div>"
					),
				),
				'soglia_iva_italiana'	=>	array(
					'labelString'=>	'Soglia per IVA italiana (solo su spedizioni estere UE)',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Soglia sotto alla quale si può applicare l'IVA italiana anche per vendite all'estero</div>"
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function selectIva()
	{
		$iva = new IvaModel();
		
		return array(0 => "-") + $iva->clear()->orderBy("id_order")->toList("id_iva","titolo")->send();
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
	
	public function findTitoloDaCodice($codice, $default = null)
	{
		if (!self::$elencoNazioni)
			self::$elencoNazioni = $this->clear()->toList("iso_country_code", "titolo")->send();
		
		if (isset(self::$elencoNazioni[$codice]))
			return self::$elencoNazioni[$codice];
		
		if (isset($default))
			return $default;
		else
			return $codice;
	}
	
	public static function findCoordinateDaCodice($codice)
	{
		if (!self::$elencoNazioni)
		{
			$nm = new NazioniModel();
			
			$nazioni = $nm->clear()->send(false);
			
			foreach ($nazioni as $n)
			{
				self::$elencoCoordinateNazioni[$n["iso_country_code"]] = array(
					$n["latitudine"],
					$n["longitudine"],
				);
			}
		}
		
		if (isset(self::$elencoCoordinateNazioni[$codice]))
			return self::$elencoCoordinateNazioni[$codice];
		
		return array();
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
	
	public function pivaAttiva($record)
	{
		if ($record["nazioni"]["campo_p_iva"])
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
	
	public function selectNazioniAttive()
	{
		if (v("attiva_ip_location") && v("abilita_solo_nazione_navigazione"))
		{
			$res = $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva"	=>	"1",
				"iso_country_code"	=>	sanitizeDb(User::$nazioneNavigazione),
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
			
			if (count($res) > 0)
				return $res;
			
			return $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva"	=>	"1",
				"iso_country_code"	=>	v("nazione_default"),
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
		}
		else
			return $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva"	=>	"1",
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
	}
	
	public function selectCodiciAttivi()
	{
		return array_keys($this->selectNazioniAttive());
	}
	
	public function selectNazioniAttiveSpedizione()
	{
		if (v("attiva_ip_location") && v("abilita_solo_nazione_navigazione"))
		{
			$res =  $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva_spedizione"	=>	"1",
				"iso_country_code"	=>	sanitizeDb(User::$nazioneNavigazione),
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
			
			if (count($res) > 0)
				return $res;
			
			return $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva_spedizione"	=>	"1",
				"iso_country_code"	=>	v("nazione_default"),
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
		}
		else
			return $this->clear()->select("iso_country_code,titolo")->where(array(
				"attiva_spedizione"	=>	"1",
			))->orderBy("titolo")->toList("iso_country_code","titolo")->send();
	}
	
	public function selectCodiciAttiviSpedizione()
	{
		return array_keys($this->selectNazioniAttiveSpedizione());
	}
	
	// Elenco nazioni che hanno la VAT
	public static function elencoNazioniConVat()
	{
		$n = new NazioniModel();
		
		return $n->clear()->where(array(
			"campo_p_iva"	=>	1
		))->toList("iso_country_code")->send();
	}
}
