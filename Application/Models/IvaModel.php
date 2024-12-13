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

class IvaModel extends GenericModel
{
	public static $aliquotaIvaSpedizione = null;
	
	public static $idIvaEstera = null;
	public static $aliquotaEstera = null;
	public static $titoloAliquotaEstera = null;
	public static $nascondiAliquotaEstera = null;
	
	public static $bidIvaEstera = null;
	public static $baliquotaEstera = null;
	public static $btitoloAliquotaEstera = null;
	public static $bnascondiAliquotaEstera = null;
	
	public static $cercaIvaEstera = true;
	
	public static $tipo = array(
		""		=>	"--",
		"B2BUE"	=>	"Acquisto B2B UE",
		"B2BEX"	=>	"Acquisto B2B EXTRA UE",
// 		"B2CUE"	=>	"Acquisto B2C UE",
		"B2CEX"	=>	"Acquisto B2C EXTRA UE",
	);
	
	public static $commercio = array(
		""		=>	"--",
		"COMMERCIO INDIRETTO"	=>	"Beni fisici (COMMERCIO INDIRETTO)",
		"COMMERCIO DIRETTO"		=>	"Beni digitali (COMMERCIO DIRETTO)",
	);
	
	public function __construct() {
		$this->_tables = 'iva';
		$this->_idFields = 'id_iva';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pages' => array("HAS_MANY", 'PagesModel', 'id_iva', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipologia estero",
					"options"	=>	self::$tipo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'commercio'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo commercio",
					"options"	=>	self::$commercio,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'nascondi'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Nascondi al checkout",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Solo per casistiche EXTRA Italia: non viene mostrata al cliente, anche se usata")."</div>"
					),
				),
				'usata_per_spedizione'	=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_nuovo',
					'labelString'=>	'Aliquota usata per la spedizione',
					'options'	=>	self::$attivoSiNo,
					'reverse'	=>	'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se deve essere usata come aliquota iva per la spedizione")."</div>"
					),
				),
			),
		);
	}
	
    public function getValore($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			return $record["valore"];
		
		return 0;
    }
    
    public static function getAliquotaEstera()
    {
		// Controllo che non sia già settata
		if (isset(self::$idIvaEstera) || !self::$cercaIvaEstera)
			return null;
		
		$prezziCorretti = false;
		
		$commercio = v("attiva_spedizione") ? 'COMMERCIO INDIRETTO' : 'COMMERCIO DIRETTO';
		
		$c = new CartModel();
		
		if (isset($_POST["nazione_spedizione"]) && isset($_POST["tipo_cliente"]) && $_POST["nazione_spedizione"] != v("nazione_default"))
		{
			$tipo = "B2B";
			
			if ($_POST["tipo_cliente"] == "privato")
				$tipo = "B2C";
			
			$n = new NazioniModel();
			
			$nazione = $n->clear()->where(array(
				"iso_country_code"	=>	sanitizeAll($_POST["nazione_spedizione"]),
			))->record();
			
			if (!empty($nazione))
			{
				$chiaveIva = $tipo.$nazione["tipo"];
				
				$im = new IvaModel();
				
				$ivaEstera = $im->clear()->where(array(
					"tipo"		=>	sanitizeAll($chiaveIva),
					"commercio"	=>	$commercio,
				))->record();
				
				if (!empty($ivaEstera))
				{
					self::setIvaEstera($ivaEstera);
// 					self::$idIvaEstera = $ivaEstera["id_iva"];
// 					self::$aliquotaEstera = $ivaEstera["valore"];
// 					self::$titoloAliquotaEstera = $ivaEstera["titolo"];
					
					$c->correggiPrezzi();
					$prezziCorretti = true;
				}
				else if ($tipo == "B2C")
				{
					if ($nazione["tipo"] == "UE")
					{
						$totaleFuroiItalia = OrdiniModel::totaleFuoriItaliaEu();
						$totaleFuroiItaliaAnnoPrecedente = OrdiniModel::totaleFuoriItaliaEu(date("Y",strtotime("-1 year")));
	// 					$totaleNazioneAnnoPrecedente = OrdiniModel::totaleNazione($nazione["iso_country_code"], true);
						$recordIva = $im->selectId((int)$nazione["id_iva"]);
						
						if (!empty($recordIva))
						{
							$totaleFuroiItalia += getPrezzoScontatoN() + getSpedizioneN() + getPagamentoN();

							if (($totaleFuroiItalia > v("euro_iva_italiana_vendite_ue") || $totaleFuroiItaliaAnnoPrecedente > v("euro_iva_italiana_vendite_ue")) && $nazione["id_iva"])
							{
								self::setIvaEstera($recordIva);
								
								$c->correggiPrezzi();
								$prezziCorretti = true;
							}
						}
						
						// echo "A: ".$totaleFuroiItalia."<br />B: ".$totaleFuroiItaliaAnnoPrecedente;

// 						$totaleNazione = OrdiniModel::totaleNazione($nazione["iso_country_code"]);
// 	// 					$totaleNazioneAnnoPrecedente = OrdiniModel::totaleNazione($nazione["iso_country_code"], true);
// 						$recordIva = $im->selectId((int)$nazione["id_iva"]);
// 						
// 						if (!empty($recordIva))
// 						{
// 							$totaleNazione += getPrezzoScontatoN();
// 							
// 							if (($totaleNazione > $nazione["soglia_iva_italiana"]) && $nazione["id_iva"])
// 							{
// 								self::setIvaEstera($recordIva);
// 								
// 								$c->correggiPrezzi();
// 								$prezziCorretti = true;
// 							}
// 						}
					}
					else if ($commercio == 'COMMERCIO DIRETTO')
					{
						$recordIva = $im->selectId((int)$nazione["id_iva"]);
						
						if (!empty($recordIva) && $nazione["id_iva"])
						{
							self::setIvaEstera($recordIva);
							
							$c->correggiPrezzi();
							$prezziCorretti = true;
						}
					}
				}
			}
		}
		
		if (!$prezziCorretti)
			$c->correggiPrezzi();
		
		return null;
    }
    
    public static function setIvaEstera($recordIva)
    {
		self::$idIvaEstera = $recordIva["id_iva"];
		self::$aliquotaEstera = $recordIva["valore"];
		self::$titoloAliquotaEstera = $recordIva["titolo"];
		self::$nascondiAliquotaEstera = $recordIva["nascondi"];
    }
    
    public static function resetIvaEstera()
    {
		self::$bidIvaEstera = self::$idIvaEstera;
		self::$baliquotaEstera = self::$aliquotaEstera;
		self::$btitoloAliquotaEstera = self::$titoloAliquotaEstera;
		self::$bnascondiAliquotaEstera = self::$nascondiAliquotaEstera;
		
		self::$idIvaEstera = null;
		self::$aliquotaEstera = null;
		self::$titoloAliquotaEstera = null;
		self::$nascondiAliquotaEstera = null;
    }
    
    public static function restoreIvaEstera()
    {
		self::$idIvaEstera = self::$bidIvaEstera;
		self::$aliquotaEstera = self::$baliquotaEstera;
		self::$titoloAliquotaEstera = self::$btitoloAliquotaEstera;
		self::$nascondiAliquotaEstera = self::$bnascondiAliquotaEstera;
    }
    
    public static function getTitoloDaId($id)
    {
		$i = new IvaModel();
		
		$record = $i->selectId((int)$id);
		
		if (!empty($record))
			return $record["titolo"];
		
		return "";
    }
    
    public function nascondi($record)
	{
		return $record[$this->_tables]["nascondi"] ? gtext("Sì") : gtext("No");
	}
	
	public function usataperspedizione($record)
	{
		return $record[$this->_tables]["usata_per_spedizione"] ? "<i class='fa fa-check text text-success'></i>" : "";
	}
	
	public function update($id = null, $where = null)
	{
		$this->setUsataPerSpedizione();
		
		return parent::update($id, $where);
	}
	
	public function insert()
	{
		$this->setUsataPerSpedizione();
		
		return parent::insert();
	}
	
	public function setUsataPerSpedizione()
	{
		if (isset($this->values["usata_per_spedizione"]) && $this->values["usata_per_spedizione"])
			$this->db->query("update iva set usata_per_spedizione = 0 where 1");
	}
	
	public static function getIvaSpedizione($field = "valore")
	{
		if (!isset(self::$aliquotaIvaSpedizione))
		{
			$i = new IvaModel();
			
			$record = $i->select("id_iva,valore")->where(array(
				"usata_per_spedizione"	=>	1,
			))->record();
			
			if (!empty($record))
				self::$aliquotaIvaSpedizione = $record;
		}
		
		if (isset(self::$aliquotaIvaSpedizione[$field]))
			return self::$aliquotaIvaSpedizione[$field];
		else
			return null;
	}
	
	public static function ricalcolaPrezzo($prezzoIvato, $prezzoNonIvato, $ivaCorrente, $nuovaIva)
	{
		$nuvoPrezzoIvato = number_format(($prezzoIvato / (1 + ($ivaCorrente / 100))) * (1 + ($nuovaIva / 100)), 2, ".", "");
		
		$nuvoPrezzoNonIvato = number_format($nuvoPrezzoIvato / (1 + ($nuovaIva / 100)), v("cifre_decimali"), ".", "");
		
		return array($nuvoPrezzoIvato, $nuvoPrezzoNonIvato);
	}
}
