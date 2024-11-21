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

class SegnapostoModel extends GenericModel {
	
	public static $valori = null;
	public static $valoriLista = null;
	
	public function __construct() {
		$this->_tables='segnaposto';
		$this->_idFields='id_segnaposto';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
    
	public static function gValori($soloVisibili = false, $soloOrdine = false)
	{
		if (isset(self::$valori))
			return self::$valori;
		
		$s = new SegnapostoModel();
		
		$s->where(array(
			"attivo"	=>	1,
		))->orderBy("id_order");
		
		if ($soloVisibili)
			$s->aWhere(array(
				"visibile"	=>	1,
			));
		
		if ($soloOrdine)
			$s->aWhere(array(
				"ordine"	=>	1,
			));
		
		self::$valori = $s->send(false);
		
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
			$hook = $valore["hook"];
			$argomenti = $valore["argomenti"];
			
			$valoreSostituitoto = null;
			$lingua = (isset($record["lingua"]) && $record["lingua"]) ? $record["lingua"] : "it";
			
			if (v("attiva_nazione_nell_url"))
			{
				$nazione = (isset($record["nazione_navigazione"]) && $record["nazione_navigazione"] && $record["nazione_navigazione"] != "W") ? $record["nazione_navigazione"] : v("nazione_default");
				
				$lingua .= Params::$languageCountrySeparator.strtolower($nazione);
			}
			
			if ($metodo)
			{
				if (isset($model) && method_exists($model,$metodo))
					$valoreSostituitoto = call_user_func_array(array($model, $metodo), array($lingua, $record));
			}
			else if ($variabile)
			{
				if (isset(VariabiliModel::$valori[$variabile]))
					$valoreSostituitoto = v($variabile);
			}
			else if ($hook)
			{
				$funcs = array($lingua);
				
				if ($argomenti)
				{
					$argKeys = explode(",",$argomenti);
					
					foreach ($argKeys as $k)
					{
						if (isset($record[$k]))
							$funcs[] = $record[$k];
					}
				}
				
				if (callFunctionCheck($hook, $funcs, true))
					$valoreSostituitoto = callFunctionCheck($hook, $funcs);
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
		if ($model->table() == "stati_ordine")
			self::gValori(true, true);
		else
			self::gValori(true);
		
		$wrap = "";
		
		if (isset(self::$valoriLista))
		{
			$wrap = "<div class='callout callout-info'>".gtext("È possibile utilizzare i seguenti SEGNAPOSTO nell'oggetto e nel corpo della mail, che  verranno poi riempiti con i valori corretti nella preparazione e invio della mail").":";
			
			foreach (self::$valoriLista as $pl => $titolo)
			{
				$wrap .= "<div><b>[$pl]</b>: ".gtext($titolo)."</div>";
			}
			
			$wrap .= "</div>";
		}
		
		return $wrap;
	}
    
}
