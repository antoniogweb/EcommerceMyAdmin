<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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
	public static $elencoNazioniInEvidenza = array();
	public static $elencoCoordinateNazioni = null;
	public static $elencoSogliePerSpedizione = null;
	public static $nazioniConProvince = null;
	
	public static $selectTipi = array(
		"UE"	=>	"UE",
		"EX"	=>	"EXTRA UE",
	);
	
	public function __construct() {
		$this->_tables = 'nazioni';
		$this->_idFields = 'id_nazione';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pages' => array("HAS_MANY", 'RegioniModel', 'id_nazione', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'clienti' => array("HAS_MANY", 'RegusersnazioniModel', 'id_nazione', null, "CASCADE"),
        );
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
						"<div class='form_notice'>".gtext("Usata nel caso venga superata la soglia annuale di vendite in Unione Europea")."</div>"
					),
				),
				'soglia_iva_italiana'	=>	array(
					'labelString'=>	'Soglia per IVA italiana (solo su spedizioni estere UE)',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Soglia sotto alla quale si può applicare l'IVA italiana anche per vendite all'estero")."</div>"
					),
				),
				'soglia_spedizioni_gratuite'	=>	array(
					'labelString'=>	'Soglia di spesa sopra la quale la spedizione è gratuita per questa nazione',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Una soglia a 0,00 significa che la spedizione non è mai gratuita per questa nazione")."</div>"
					),
				),
				'in_evidenza'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'In evidenza?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà evidenziata nel sito (in home, nei menù, etc), in funzione del tema")."</div>"
					),
				),
				'lingua'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Lingua di default impostata per questa nazione',
					'options'	=>	LingueModel::getValoriAttivi(),
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Al cambio nazione nel tema (se impostato), il link verso questa nazione avrà questa lingua")."</div>"
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
	
	public static function esistente($codice)
	{
		return self::g()->clear()->where(array(
			"iso_country_code"	=>	sanitizeDb($codice),
		))->rowNumber();
	}
	
	public function getNome($codice)
	{
		return $this->clear()->where(array(
			"iso_country_code"	=>	sanitizeDb($codice),
		))->field("titolo");
	}
	
	public function getPrefissoTelefonicoDaCodice($codice, $usaZeri = true)
	{
		$prefisso = $this->clear()->select("prefisso_telefonico")->where(array(
			"iso_country_code"	=>	sanitizeDb($codice),
		))->field("prefisso_telefonico");
		
		if ($usaZeri)
			$prefisso = str_replace("+", "00", $prefisso);
		
		return $prefisso;
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
		{
			Cache_Db::removeTablesFromCache(array("nazioni"));
			self::$elencoNazioni = $this->clear()->toList("iso_country_code", "titolo")->send();
		}
		
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
	
	public static function gElencoNazioniAttiveAll()
	{
		$n = new NazioniModel();

		return $n->clear()->where(array(
			"attiva"	=>	"1",
		))->orderBy("titolo")->send(false);
	}
	
	public static function getNazioniSpedizioneOrdini()
	{
		$n = new NazioniModel();
		
		return $n->clear()->select("iso_country_code,titolo")->sWhere("iso_country_code in (select distinct nazione_spedizione from orders)")->orderBy("titolo")->toList("iso_country_code", "titolo")->send();
	}
	
	public static function gElencoNazioni()
	{
		$n = new NazioniModel();
		
		return $n->clear()->orderBy("titolo")->toList("iso_country_code","titolo")->send();
	}
	
	public static function gElencoNazioniAttive()
	{
		$n = new NazioniModel();
		
		return $n->selectNazioniAttive();
	}
	
	// Elenco nazioni che hanno la VAT
	public static function elencoNazioniConVat()
	{
		$n = new NazioniModel();
		
		return $n->clear()->where(array(
			"campo_p_iva"	=>	1
		))->toList("iso_country_code")->send();
	}
	
	public function aggiungiaprodotto($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_page"]))
		{
			$pr = new PagesregioniModel();
			
			$pr->setValues(array(
				"id_page"		=>	(int)$_GET["id_page"],
				"id_regione"	=>	0,
				"id_nazione"	=>	(int)$id,
			), "sanitizeDb");
			
			$pr->pInsert();
		}
    }
    
    public function elencoClientiDaCodice($codiceNazione)
    {
		return $this->clear()->select("regusers.username")->inner(array("clienti"))->inner("regusers")->on("regusers_nazioni.id_user = regusers.id_user")->where(array(
			"nazioni.iso_country_code"	=>	sanitizeAll($codiceNazione),
		))->toList("regusers.username")->send();
    }

	public static function getSogliaSpedizioneGratuita($nazione)
	{
		if (!isset(self::$elencoSogliePerSpedizione))
		{
			$nModel = new NazioniModel();

			self::$elencoSogliePerSpedizione = $nModel->select("iso_country_code,soglia_spedizioni_gratuite")->toList("iso_country_code", "soglia_spedizioni_gratuite")->send();
		}

		if (isset(self::$elencoSogliePerSpedizione[$nazione]))
			return self::$elencoSogliePerSpedizione[$nazione];

		return 0;
	}

    // Verifica se la spedizione è gratuita per quella nazione
    public static function spedizioneGratuita($nazione, $subtotale)
	{
		if (v("soglia_spedizioni_gratuite_diversa_per_ogni_nazione"))
		{
			$soglia = self::getSogliaSpedizioneGratuita($nazione);

			if ($soglia > 0 && $subtotale >= $soglia)
				return true;
		}
		else
		{
			if ((v("soglia_spedizione_gratuita_attiva_in_tutte_le_nazioni") || $nazione == v("nazione_default")) && ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"] > 0 && $subtotale >= ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"])
				return true;
		}

		return false;
	}
	
	public static function nazioniConProvince()
	{
		if (!v("gestisci_province_estere"))
			return array(v("nazione_default"));
		
		if (!isset(self::$nazioniConProvince))
		{
			$pModel = new ProvinceModel();
			
			$pModel->clear()->select("distinct nazione")->toList("nazione");
			
			if (v("mostra_solo_province_attive") && App::$isFrontend)
				$pModel->aWhere(array(
					"attiva"	=>	1,
				));
			
			if (App::$isFrontend)
				$pModel->sWhere("nazione in (select iso_country_code from nazioni where attiva = 1 or attiva_spedizione = 1)");
			
			self::$nazioniConProvince = $pModel->send();
		}
		
		return self::$nazioniConProvince;
	}
	
	public static function conProvince($nazione)
	{
		return in_array($nazione, NazioniModel::nazioniConProvince()) ? true : false;
	}
}
