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

class ElementitemaModel extends GenericModel {
	
	public static $percorsi = null;
	
	public static $variantiPagina = array();
	
	public $esportaTema = false;
	
	public function __construct() {
		$this->_tables='elementi_tema';
		$this->_idFields='id_elemento_tema';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nome_file'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Layout",
					"options"	=>	$this->selectLayout($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		if ($res && $this->esportaTema)
			$this->esportaInTema();
		
		return $res;
	}
	
	public function esportaInTema()
	{
		$cartellaTema = v("theme_folder");
		
		$percorsoTema = Tema::getElencoTemi($cartellaTema, true);
		
		if (count($percorsoTema) > 0)
		{
			$jsonVarianti = $this->clear()->send();
			
			$strutt = array(
				"varianti"	=>	$jsonVarianti,
			);
			
			$path = $percorsoTema[0]["path"];
			
			if (@is_dir($path))
				file_put_contents(rtrim($path,"/")."/layout.json", json_encode($strutt));
		}
	}
	
	// Imposta tutti i valori al default
	public function resetta()
	{
		$this->setValues(array(
			"nome_file"	=>	"default",
		));
		
		$this->update(null, "id_elemento_tema > 1");
		
		$this->esportaInTema();
	}
	
	public function selectLayout($id)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record))
			return Tema::getSelectElementi($record["percorso"], false);
		
		return array();
	}
	
	public static function getPercorsi()
	{
		$et = new ElementitemaModel();
		
		$result = $et->clear()->findAll(false);
		
		foreach ($result as $r)
		{
			self::$percorsi[$r["codice"]] = $r;
		}
	}
	
	public static function p($codice, $correlato = "", $record = null)
	{
		if (!isset(self::$percorsi))
		{
			self::getPercorsi();
		}
		
		if (!isset(self::$percorsi[$codice]) && is_array($record))
		{
			$et = new ElementitemaModel();
			
			$record["codice"] = $codice;
			
			$et->setValues($record);
			
			if ($et->insert())
				self::getPercorsi();
		}
		
		if (isset(self::$percorsi[$codice]))
		{
			if (!isset(self::$variantiPagina[$codice]))
				self::$variantiPagina[$codice] = self::$percorsi[$codice];
			
			return self::$percorsi[$codice]["percorso"]."/".self::$percorsi[$codice]["nome_file"].$correlato.".php";
		}
		
		return "";
	}
	
	public static function preparaStrutturaVarianti()
	{
		$struttura = array();
		
		foreach (self::$variantiPagina as $codice => $v)
		{
			$temp = $v;
			
			$opzioni = Tema::getSelectElementi($v["percorso"], false);
			
			if (count($opzioni) > 1)
			{
				$tt = array();
				
				foreach ($opzioni as $k	=>	$v)
				{
					$tt[] = array(
						"k"	=>	$k,
						"v"	=>	$v,
					);
				}
				
				$temp["opzioni"] = $tt;
				
				
				$struttura[] = $temp;
			}
		}
		
		return $struttura;
	}
	
	public function edit($record)
	{
		return $record[$this->_tables][$this->campoTitolo];
	}
}
