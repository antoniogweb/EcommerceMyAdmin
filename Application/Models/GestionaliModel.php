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

class GestionaliModel extends GenericModel {
	
	use CrudModel;
	
	public static $modulo = null;
	
	public static $elencoSezioni = null;
	
	public function __construct() {
		$this->_tables='gestionali';
		$this->_idFields='id_gestionale';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
    
	public function setFormStruct($id = 0)
	{
		$record = $this->selectId($id);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	self::$entryAttivo,
				'param_1'		=>	array(
					'labelString'	=>	self::getModulo($record["codice"])->gParam1Label(),
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'param_2'		=>	array(
					'labelString'	=>	self::getModulo($record["codice"])->gParam2Label(),
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
			),
		);
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["attivo"]) && $this->values["attivo"])
			$this->db->query("update gestionali set attivo = 0 where 1");
		
		return parent::update($id, $where);
	}
	
	public static function getModulo($codice = null)
	{
		$i = new GestionaliModel();
		
		if (!isset(self::$modulo))
		{
			if ($codice)
				$attivo = $i->clear()->where(array(
					"codice"	=>	sanitizeDb($codice),
				))->record();
			else
				$attivo = $i->clear()->where(array(
					"attivo"	=>	1,
				))->record();
			
			if (!empty($attivo) && file_exists(LIBRARY."/Application/Modules/Gestionali/".$attivo["classe"].".php"))
			{
				require_once(LIBRARY."/Application/Modules/Gestionale.php");
				require_once(LIBRARY."/Application/Modules/Gestionali/".$attivo["classe"].".php");
				
				$objectReflection = new ReflectionClass($attivo["classe"]);
				$object = $objectReflection->newInstanceArgs(array($attivo));
				
				self::$modulo = $object;
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
	
	public static function integrazioneAttiva()
	{
		return self::getModulo()->isAttiva();
	}
	
	public function metodo($metodo)
	{
		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
			return true;

		return false;
	}
	
	public static function invia($elemento, $id_elemento, $azione = "metodo")
	{
		if (array_key_exists($elemento, Gestionale::$tabellaElementi))
		{
			$metodo = Gestionale::$tabellaElementi[$elemento][$azione];
			$backRoute = Gestionale::$tabellaElementi[$elemento]["bak_route"];
			
			self::getModulo();
			
			if (method_exists(self::$modulo, $metodo))
			{
				call_user_func_array(array(self::$modulo, $metodo), array($id_elemento));
			}
			
			// restituisce l'URL per tornare all'ordine
			return Url::routeToUrl($backRoute, array($id_elemento));
		}
		
		return "";
	}
}
