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

class FeedbackModel extends GenericModel {
	
	public $campoTitolo = "autore";
	
	public static $tendinaPunteggi = array(
		"0_0"	=>	"0",
		"0_5"	=>	"0,5",
		"1_0"	=>	"1",
		"1_5"	=>	"1,5",
		"2_0"	=>	"2",
		"2_5"	=>	"2,5",
		"3_0"	=>	"3",
		"3_5"	=>	"3,5",
		"4_0"	=>	"4",
		"4_5"	=>	"4,5",
		"5_0"	=>	"5",
	);
	
	public function __construct() {
		$this->_tables='feedback';
		$this->_idFields='id_feedback';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicato',
					'options'	=>	array('1' => 'sì','0' => 'no'),
					"reverse"	=>	"yes",
				),
				'voto'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Punteggio',
					'options'	=>	self::$tendinaPunteggi,
					"reverse"	=>	"yes",
				),
			),
		);
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
	
	public function sistemaVoto()
	{
		if (isset($this->values["voto"]))
			$this->values["voto"] = str_replace("_",".",$this->values["voto"]);
	}
	
	public function sistemaVotoNumero($valore)
	{
		return str_replace(",","_",$valore);
	}
	
	public function insert()
	{
		$this->sistemaVoto();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->sistemaVoto();
		
		return parent::update($id, $where);
	}
	
	public function dataora($record)
	{
		return date("d/m/Y H:i", strtotime($record["feedback"]["data_creazione"]));
	}
	
	public function attivo($record)
	{
		return $record["feedback"]["attivo"] ? "Sì" : "No";
	}
	
	public function edit($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."feedback/form/update/".$record["feedback"]["id_feedback"]."?partial=Y&nobuttons=Y'>".$record["feedback"]["autore"]."</a>";
	}
	
	public function punteggio($record)
	{
		$punteggio = str_replace(",",".",$record["feedback"]["voto"]);
		
		$stellePiene = floor($punteggio);
		$mezzaStella = ($punteggio > $stellePiene) ? true : false;
		
		$arrayIcone = array();
		
		if ($punteggio <= 2)
			$color = "danger";
		else if ($punteggio <= 3)
			$color = "warning";
		else if ($punteggio <= 5)
			$color = "success";
			
		for ($i = 0; $i < $stellePiene; $i++)
		{
			$arrayIcone[] = "<i class='text text-$color fa fa-star'></i>";
		}
		
		if ($mezzaStella)
			$arrayIcone[] = "<i class='text text-$color fa fa-star-half'></i>";
		
		return implode(" ",$arrayIcone);
	}
	
}
