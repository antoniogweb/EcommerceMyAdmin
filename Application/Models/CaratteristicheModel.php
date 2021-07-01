<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class CaratteristicheModel extends GenericModel {

	public $lId = 0;
	
	public static $filtriUrl = array();
	
	public function __construct() {
		$this->_tables='caratteristiche';
		$this->_idFields='id_car';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'caratteristiche.titolo';
		$this->_lang = 'It';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo|Si prega di compilare il campo <i>Titolo caratteristica</i>");
		
		parent::__construct();

	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_car', null, "CASCADE"),
			'tipologia' => array("BELONGS_TO", 'TipologiecaratteristicheModel', 'id_tipologia_caratteristica',null,"CASCADE"),
        );
    }
	
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo caratteristica',
				),
				'id_car'	=>	array(
					'type'		=>	'Hidden'
				),
				'tipo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array(
						""			=>	"--",
						"MATERIALE"	=>	"Materiale",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_tipologia_caratteristica'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipologia caratteristica",
					"options"	=>	array(0 => 'Seleziona') + $this->selectTipologia(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function selectTipologia()
	{
		$t = new TipologiecaratteristicheModel();
		
		return $t->clear()->orderBy("id_order")->toList("id_tipologia_caratteristica","titolo")->send();
	}
	
	public function insert()
	{
		if (isset($this->values["alias"]))
			$this->checkAliasAll(0);
		
		$res = parent::insert();
		
		if ($res)
			$this->controllaLingua($this->lId, "id_car");
		
		return $res;
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean["id"] = (int)$id;
		
		$a = new CaratteristichevaloriModel();
		$res = $a->clear()->where(array("id_car"=>$clean["id"]))->send();
		
		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questa caratteristica ha deli valori associati, si prega di cancellare prima tali valori</div>";
			$this->result = false;
		}
		else
		{
			return parent::del($clean["id"]);
		}
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["alias"]))
			$this->checkAliasAll($id);
		
		$res = parent::update($id, $where);
		
		if ($res)
			$this->controllalingua($id, "id_car");
		
		return $res;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_car", "-car-");
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["caratteristiche"]["id_car"], $lingua, "id_car");
	}
	
	// Restituisce i filtri nell'URL ma impostando la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getFiltriCaratteristica($aliasCar, $aliasCarVal)
	{
		$temp = self::$filtriUrl;
		
		$temp[$aliasCar] = array(
			$aliasCarVal
		);
		
		return $temp;
	}
	
	// Restituisce un array per URL con filtri a partire dalla caratteristica $aliasCar impostata a $aliasCarVal
	public static function getArrayUrlCaratteristiche($aliasCar, $aliasCarVal)
	{
		$filtri = self::getFiltriCaratteristica($aliasCar, $aliasCarVal);
		
		return self::getArrayFiltri($filtri);
	}
	
	public static function getArrayFiltri($filtri)
	{
		$urlFiltriArray = array();
		
		if (count($filtri) > 0)
			$urlFiltriArray[] = v("divisorio_filtri_url");
		
		foreach ($filtri as $car => $carVals)
		{
			$urlFiltriArray[] = $car;
			
			foreach ($carVals as $carVal)
			{
				$urlFiltriArray[] = $carVal;
			}
		}
		
		return $urlFiltriArray;
	}
	
	// Restituisce un array per URL con filtri togliendo la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getUrlCaratteristicheTutti($aliasCar = null)
	{
		$temp = self::$filtriUrl;
		
		if ($aliasCar && isset($temp[$aliasCar]))
			unset($temp[$aliasCar]);
		
		return self::getArrayFiltri($temp);
	}
	
	public static function filtroSelezionato($aliasCar, $aliasCarVal)
	{
		if (isset(self::$filtriUrl[$aliasCar]))
		{
			foreach (self::$filtriUrl[$aliasCar] as $alVal)
			{
				if ($alVal == $aliasCarVal)
					return true;
			}
		}
		
		return false;
	}
	
	public static function filtroTuttiSelezionato($aliasCar)
	{
		if (!isset(self::$filtriUrl[$aliasCar]))
			return true;
		
		return false;
	}
}
