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

class AimodelliModel extends GenericModel
{
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "ModelliAI";
	public $classeModuloPadre = "ModelloAI";
	
	public function __construct() {
		$this->_tables='ai_modelli';
		$this->_idFields='id_ai_modello';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
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
				'predefinito'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Predefinito",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Impostandolo come predefinito, gli altri modelli dello stesso tipo non saranno più predefiniti.")."</div>"
					),
				),
			),
		);
		
		$this->moduleFormStruct($id);
	}
	
	public function checkModulo($codice, $token = "")
	{
		return $this->clear()->where(array(
			"codice"	=>	sanitizeDb((string)$codice),
			"attivo"	=>	1,
		))->rowNumber();
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["predefinito"]) && $this->values["predefinito"])
		{
			$record = $this->selectId((int)$id);

			if (!empty($record))
				$this->query(array(
					"update ai_modelli set predefinito = 0 where tipo = ?",
					array(sanitizeAll($record["tipo"]))
				));
		}

		return parent::update($id, $where);
	}

	public function predefinito($record)
	{
		return $record[$this->_tables]["predefinito"] ? "<i class='fa fa-check text text-success'></i>" : "";
	}
	
	public function getIdPredefinito()
	{
		return (int)$this->clear()->where(array(
			"predefinito"	=>	1,
		))->field("id_ai_modello");
	}
}
