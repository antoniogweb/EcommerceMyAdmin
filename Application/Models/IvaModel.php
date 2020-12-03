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

class IvaModel extends GenericModel
{
	public static $idIvaEstera = null;
	public static $aliquotaEstera = null;
	public static $titoloAliquotaEstera = null;
	
	public static $bidIvaEstera = null;
	public static $baliquotaEstera = null;
	public static $btitoloAliquotaEstera = null;
	
	public static $cercaIvaEstera = true;
	
	public static $tipo = array(
		""		=>	"--",
		"B2BUE"	=>	"Acquisto B2B UE",
		"B2BEX"	=>	"Acquisto B2B EXTRA UE",
		"B2CEX"	=>	"Acquisto B2C EXTRA UE",
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
    
    public function setFormStruct()
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
					"tipo"	=>	sanitizeAll($chiaveIva),
				))->record();
				
				if (!empty($ivaEstera))
				{
					self::$idIvaEstera = $ivaEstera["id_iva"];
					self::$aliquotaEstera = $ivaEstera["valore"];
					self::$titoloAliquotaEstera = $ivaEstera["titolo"];
					
					$c->correggiPrezzi();
					$prezziCorretti = true;
				}
				else if ($nazione["tipo"] == "UE" && $tipo == "B2C")
				{
// 					print_r($nazione);
					$totaleNazione = OrdiniModel::totaleNazione($nazione["iso_country_code"]);
					
					$recordIva = $im->selectId((int)$nazione["id_iva"]);
					
					if (!empty($recordIva))
					{
						$totaleNazione += getTotalN();
						
						if (($totaleNazione > $nazione["soglia_iva_italiana"]) && $nazione["id_iva"])
						{
							self::$idIvaEstera = $recordIva["id_iva"];
							self::$aliquotaEstera = $recordIva["valore"];
							self::$titoloAliquotaEstera = $recordIva["titolo"];
							
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
    
    public static function resetIvaEstera()
    {
		self::$bidIvaEstera = self::$idIvaEstera;
		self::$baliquotaEstera = self::$aliquotaEstera;
		self::$btitoloAliquotaEstera = self::$titoloAliquotaEstera;
		
		self::$idIvaEstera = null;
		self::$aliquotaEstera = null;
		self::$titoloAliquotaEstera = null;
    }
    
    public static function restoreIvaEstera()
    {
		self::$idIvaEstera = self::$bidIvaEstera;
		self::$aliquotaEstera = self::$baliquotaEstera;
		self::$titoloAliquotaEstera = self::$btitoloAliquotaEstera;
    }
    
    public static function getTitoloDaId($id)
    {
		$i = new IvaModel();
		
		$record = $i->selectId((int)$id);
		
		if (!empty($record))
			return $record["titolo"];
		
		return "";
    }
}
