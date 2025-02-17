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

class SpedizioniModel extends GenericModel {
	
	public static $spedizioneImportata = false;
	
	public $campoValore = "id_spedizione";
	public $metodoPerTitolo = "titoloJson";
	
	public function __construct() {
		$this->_tables='spedizioni';
		$this->_idFields='id_spedizione';
		$this->_idOrder = 'id_order';
		
		if (!self::$spedizioneImportata)
			$this->addStrongCondition("both",'checkNotEmpty',"indirizzo_spedizione");
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nazione_spedizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'provincia_spedizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectProvince(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryClass'	=>	'provincia_spedizione form_input_text',
				),
				'dprovincia_spedizione'	=>	array(
					"labelString"	=>	"Provincia spedizione",
					'entryClass'	=>	'dprovincia_spedizione form_input_text',
				),
			),
		);
	}
	
	public function setDaUsarePerApp($id)
	{
		if (self::$spedizioneImportata)
			return;
		
		$this->query(array("update spedizioni set da_usare = 0 where id_user = ? AND da_usare != 0", array((int)User::$id)));
		$this->query(array("update spedizioni set da_usare = 1 where id_user = ? AND id_spedizione = ?", array((int)User::$id, (int)$id)));
	}
	
	public function update($id = null, $where = null)
	{
		$this->setProvinciaSpedizione();
		
		$res = parent::update($id, $where);
		
		if ($res)
			$this->setDaUsarePerApp($id);
		
		return $res;
	}
	
	public function insert()
	{
		$this->setProvinciaSpedizione();
		
		$res = parent::insert();
		
		if ($res)
			$this->setDaUsarePerApp($this->lId);
		
		return $res;
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record["username"]))
		{
			return $record["indirizzo_spedizione"] . " - " . $record["citta_spedizione"];
		}
		
		return "";
	}
	
	public function nazione($record)
	{
		return nomeNazione($record["spedizioni"]["nazione_spedizione"]);
	}
	
	public function provincia($record)
	{
		return ($record["spedizioni"]["nazione_spedizione"] == "IT") ? $record["spedizioni"]["provincia_spedizione"] : $record["spedizioni"]["dprovincia_spedizione"];
	}
	
	public function indirizzo_spedizione($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."spedizioni/form/update/".$record["spedizioni"]["id_spedizione"]."?partial=Y&nobuttons=Y'>".$record["spedizioni"]["indirizzo_spedizione"]."</a>";
	}
	
	public function titoloJson($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (!empty($record))
			return $record["indirizzo_spedizione"]." ".$record["cap_spedizione"]." ".$record["citta_spedizione"];;
		
		return "";
	}
	
	public static function numeroIndirizziDiSpedizioneUtente($idUser)
	{
		$spModel = new SpedizioniModel();
		
		return $spModel->clear()->where(array(
			"id_user"	=>	(int)$idUser,
		))->rowNumber();
	}
	
	public static function getCodiceGestionale($idSpedizione)
	{
		return self::g()->clear()->select("codice_gestionale")->whereId((int)$idSpedizione)->field("codice_gestionale");
	}
}
