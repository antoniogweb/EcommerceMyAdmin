<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class OrdiniacquistostatiModel extends GenericModel {
	
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
		$this->_tables='ordini_acquisto_stati';
		$this->_idFields='id_ordine_acquisto_stato';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordini' => array("HAS_MANY", 'OrdiniacquistoModel', 'id_ordine_acquisto_stato', null, "RESTRICT", "L'elemento ha degli ordini di acquisto collegati e non può essere eliminato"),
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
				'chiuso'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine Chiuso",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'inviato'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine inviato al fornitore",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'annullato'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine annullato",
					"options"	=>	self::$attivoSiNo,
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
			),
		);
	}
	
	public function insert()
	{
		$this->impostaStatoInviato();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->impostaStatoInviato();
		
		return parent::update($id, $where);
	}
	
	public function impostaStatoInviato()
	{
		if (isset($this->values["inviato"]) && $this->values["inviato"])
			$this->db->query("update ordini_acquisto_stati set inviato = 0 where 1");
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			$numeroOrdini = OrdiniacquistoModel::g()->where(array(
				"id_ordine_acquisto_stato"	=>	(int)$id
			))->count();
			
			if ($numeroOrdini > 0)
				return false;
		}
		
		return true;
	}
	
	public function chiuso($idStato)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("id_ordine_acquisto_stato");
		
		return self::$recordTabella[$idStato]["chiuso"] > 0 ? true : false;
	}
	
	public function chiusoCrud($record)
	{
		if ($record["ordini_acquisto_stati"]["chiuso"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "";
	}
	
	public function annullatoCrud($record)
	{
		if ($record["ordini_acquisto_stati"]["annullato"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "";
	}
	
	public function bozza($idStato)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("id_ordine_acquisto_stato");
		
		return ((int)self::$recordTabella[$idStato]["chiuso"] <= 0) ? true : false;
	}
	
	public static function getCampo($idStato, $campo)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("id_ordine_acquisto_stato");
		
		return isset(self::$recordTabella[$idStato][$campo]) ? self::$recordTabella[$idStato][$campo] : null;
	}
	
	public function selectStati()
	{
		return $this->clear()->select("id_ordine_acquisto_stato,titolo")->orderBY("id_order")->toList("id_ordine_acquisto_stato", "titolo")->send();
	}
	
	public static function getIdStatoPending()
	{
		$oasModel = new OrdiniacquistostatiModel();
		
		return $oasModel->clear()->where(array(
			"chiuso"	=>	0,
		))->limit(1)->orderBy("id_order")->field("id_ordine_acquisto_stato");
	}
}
