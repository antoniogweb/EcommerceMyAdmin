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

class RicerchesinonimiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ricerche_sinonimi';
		$this->_idFields = 'id_ricerca_sinonimo';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'	=>	array(
					'labelString'=>	'Termine di ricerca',
				),
				'sinonimi'	=>	array(
					'labelString'=>	'Sinonimi',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserirli uno dopo l'altro divisi da una virgola.")."</div>"
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	private function sistemaSinonimi()
	{
		if (isset($this->values["sinonimi"]) && $this->values["sinonimi"])
		{
			$sinonimiArray = explode(",", $this->values["sinonimi"]);
			
			$sinonimiArray = array_map('trim', $sinonimiArray);
			
			$this->values["sinonimi"] = sanitizeAll(implode(",", $sinonimiArray));
		}
	}
	
	public function insert()
	{
		$this->sistemaSinonimi();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->sistemaSinonimi();
		
		return parent::update($id, $where);
	}
	
	// La funzione elabora la stringa, cercando tra i sinonimi
	public function estraiTerminiDaStringaDiRicerca($stringa)
	{
		$stringa = (string)$stringa;
		
		if (!trim($stringa))
			return $stringa;
		
		$stringa = trim($stringa);
		
		$stringaArray = explode(" ", $stringa);
		
		$arrayTermini = [];
		$arrayTerminiDaComporre = [];
		
		foreach ($stringaArray as $termine)
		{
			if ((int)strlen($termine) === 1)
			{
				$arrayTerminiDaComporre[] = $termine;
				continue;
			}
			
			if (count($arrayTerminiDaComporre) > 0)
			{
				$termine = implode(" ", $arrayTerminiDaComporre)." ".$termine;
				
				$arrayTerminiDaComporre = [];
			}
			
			$nuovoTermine = $this->clear()->where(array(
				"lk"	=>	array(
					"n!concat(',',sinonimi,',')"	=>	sanitizeAll(",".$termine.","),
				),
			))->field("titolo");
			
			$arrayTermini[] = $nuovoTermine ? htmlentitydecode($nuovoTermine) : $termine;
		}
		
		$arrayTermini = array_unique($arrayTermini);
		
		return implode(" ", $arrayTermini);
	}
}
