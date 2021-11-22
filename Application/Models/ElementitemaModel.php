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

class ElementitemaModel extends GenericModel {
	
	public static $percorsi = null;
	
	public static $variantiPagina = array();
	
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
	
	public function selectLayout($id)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record))
			return Tema::getSelectElementi($record["percorso"], false);
		
		return array();
	}
	
	public static function p($codice)
	{
		if (!isset(self::$percorsi))
		{
			$et = new ElementitemaModel();
			
			$result = $et->clear()->findAll(false);
			
			foreach ($result as $r)
			{
				self::$percorsi[$r["codice"]] = $r;
			}
		}
		
		if (isset(self::$percorsi[$codice]))
		{
			if (!isset(self::$variantiPagina[$codice]))
				self::$variantiPagina[$codice] = self::$percorsi[$codice];
			
			return self::$percorsi[$codice]["percorso"]."/".self::$percorsi[$codice]["nome_file"].".php";
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
