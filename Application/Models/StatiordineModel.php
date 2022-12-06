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

class StatiordineModel extends GenericModel {
	
	public static $labelStati = array(
		"default"	=>	"Grigio",
		"primary"	=>	"Blu",
		"success"	=>	"Verde",
		"danger"	=>	"Danger",
		"warning"	=>	"Warning",
		"purple"	=>	"Porpora",
		"info"		=>	"Azzurro",
		"maroon"	=>	"Marrone",
		"olive"		=>	"Oliva",
		"teal"		=>	"Verde acqua",
		"gallo"		=>	"Giallo",
		"fuchsia"	=>	"Fucsia",
		"azzurrino"	=>	"Azzurrino",
		"ciano"		=>	"Ciano",
		"nero"		=>	"Nero",
	);
	
	public function __construct() {
		$this->_tables='stati_ordine';
		$this->_idFields='id_stato_ordine';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_stato_ordine', null, "CASCADE"),
        );
    }
    
    public function edit($record)
	{
		return "<span class='text-bold label label-".$record[$this->_tables]["classe"]." data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attivo",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'pagato'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine pagato?",
					"options"	=>	self::$attivoSiNo + array("-1"=>gtext("Neutro")),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'classe'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Colore della label dello stato",
					"options"	=>	self::$labelStati,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'manda_mail_al_cambio_stato'	=>	array(
					"type"	=>	"Select",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'descrizione'	=>	array(
					"labelString"	=>	"Testo della mail al cambio stato",
					'wrap'		=>	array(
						null,null,SegnapostoModel::getLegenda($this),
					),
				),
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public function insert()
	{
		$this->values["tipo"] == "U";
		
		return parent::insert();
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			if ($record["tipo"] == "S")
				return false;
			
			$numeroOrdini = OrdiniModel::g()->where(array(
				"stato"	=>	$record["codice"]
			))->count();
			
			if ($numeroOrdini > 0)
				return false;
		}
		
		return true;
	}
	
	public static function getLabel($valore)
	{
		if ($valore > 0)
			return "success";
		else if ($valore < 0)
			return "default";
		else
			return "danger";
	}
	
	public function pagatoCrud($record)
	{
		$label = self::getLabel($record["stati_ordine"]["pagato"]);
		
		$text = self::$attivoSiNo[$record["stati_ordine"]["pagato"]] ?? "Neutro";
		
		return "<span class='text-bold text text-$label'>".$text."</span>";
	}
	
	public function pagato($codiceStato)
	{
		if (!isset(self::$recordTabella))
			self::setRecordTabella("codice");
		
		return self::$recordTabella[$codiceStato]["pagato"] > 0 ? true : false;
	}
	
	public function neutro($codiceStato)
	{
		if (!isset(self::$recordTabella))
			self::setRecordTabella("codice");
		
		return self::$recordTabella[$codiceStato]["pagato"] < 0 ? true : false;
	}
	
	public static function getCampo($codiceStato, $campo)
	{
		if (!isset(self::$recordTabella))
			self::setRecordTabella("codice");
		
		return isset(self::$recordTabella[$codiceStato][$campo]) ? self::$recordTabella[$codiceStato][$campo] : null;
	}
}
