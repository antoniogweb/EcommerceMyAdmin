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

class SegnapostoModel extends GenericModel {
	
	public static $valori = null;
	public static $valoriLista = null;
	
	public function __construct() {
		$this->_tables='segnaposto';
		$this->_idFields='id_segnaposto';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
    
	public static function gValori()
	{
		if (isset(self::$valori))
			return self::$valori;
		
		self::$valori = SegnapostoModel::g()->where(array(
			"attivo"	=>	1,
		))->orderBy("id_order")->send(false);
		
		foreach (self::$valori as $v)
		{
			self::$valoriLista[$v["codice"]] = $v["titolo"];
		}
    }
    
    public static function sostituisci($string, $record = null, $model = null)
    {
		self::gValori();
		
		foreach (self::$valori as $valore)
		{
			$codice = $valore["codice"];
			$metodo = $valore["metodo"];
			$variabile = $valore["variabile"];
			
			$valoreSostituitoto = null;
			$lingua = (isset($record["lingua"]) && $record["lingua"]) ? $record["lingua"] : "it";
			
			if ($metodo)
			{
				if (method_exists($model,$metodo))
					$valoreSostituitoto = call_user_func(array($model, $metodo), $lingua);
			}
			else if ($variabile)
			{
				if (isset(VariabiliModel::$valori[$variabile]))
					$valoreSostituitoto = v($variabile);
			}
			else
			{
				$field = strtolower($codice);
				
				if ($record && isset($record[$field]) && $record[$field])
					$valoreSostituitoto = $record[$field];
			}
			
			if ($valoreSostituitoto)
				$string = str_replace("[$codice]", $valoreSostituitoto, $string);
		}
		
		return $string;
    }
    
    // Crea la lagenda dei segnaposto usata nel pannello
	public static function getLegenda($model = null)
	{
		self::gValori();
		
		$wrap = "<div class='callout callout-info'>".gtext("È possibile utilizzare i seguenti SEGNAPOSTO, che  verranno poi riempiti con i valori corretti nella preparazione e invio della mail").":";
		
		foreach (self::$valoriLista as $pl => $titolo)
		{
			$wrap .= "<div><b>[$pl]</b>: ".gtext($titolo)."</div>";
		}
		
		$wrap .= "</div>";
		
		return $wrap;
	}
    
}
