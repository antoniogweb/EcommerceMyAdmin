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

class IntegrazioniModel extends GenericModel {
	
	public static $modulo = null;
	
	public static $elencoSezioni = null;
	
	public function __construct() {
		$this->_tables='integrazioni';
		$this->_idFields='id_integrazione';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'sezioni' => array("HAS_MANY", 'IntegrazionisezioniModel', 'id_integrazione', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
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
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public static function getElencoIntegrazioni($sezione)
	{
		if (!isset(self::$elencoSezioni))
		{
			$i = new IntegrazioniModel();
			
			$integrazioni = $i->clear()->select("distinct integrazioni.id_integrazione,integrazioni.*")->inner(array("sezioni"))->where(array(
				"integrazioni_sezioni.sezione"	=>	$sezione,
				"integrazioni.attivo"			=>	1,
			))->orderBy("integrazioni_sezioni.id_order")->findAll();
			
			self::$elencoSezioni = array();
			
			foreach ($integrazioni as $i)
			{
				self::$elencoSezioni[$i["integrazioni"]["codice"]] = $i;
			}
		}
		
		return self::$elencoSezioni;
	}
	
	public static function getElencoPulsantiIntegrazione($idElemento, $sezione)
	{
		$res = self::getElencoIntegrazioni($sezione);
		
		$arrayPulsanti = array();
		
		foreach ($res as $i)
		{
			$arrayPulsanti[] = "<a class='btn btn-info' href='".Url::getRoot()."integrazioni/invia/".strtolower($sezione)."/$idElemento'><i class='fa fa-paper-plane-o'></i> ".gtext("Invia a")." ".$i["integrazioni"]["titolo"]."</a>";
		}
		
		return $arrayPulsanti;
	}
	
	public static function getModulo($id)
	{
		$i = new IntegrazioniModel();
		
		if (!isset(self::$modulo))
		{
			$record = $i->selectId($id);
			
			if (!empty($record))
			{
				require_once(LIBRARY."/Application/Modules/Integrazioni/".$record["classe"].".php");
				
				$objectReflection = new ReflectionClass($record["classe"]);
				self::$modulo = $objectReflection->newInstanceArgs(array($record));
			}
		}
		
		return $i;
	}
	
	public function __call($metodo, $argomenti)
	{
		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
			return call_user_func_array(array(self::$modulo, $metodo), $argomenti);

		return false;
	}
}
