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

class FeedModel extends GenericModel
{
	public static $modulo = null;
	
	public function __construct() {
		$this->_tables='feed';
		$this->_idFields='id_feed';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$attributesVisibilita = array(
			"visible-f"	=>	"usa_token_sicurezza",
			"visible-v"	=>	1,
		);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attivo",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Attivando questo sistema antispam verranno disattivati gli altri")."</div>"
					),
				),
				'usa_token_sicurezza'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Accedi al feed solo se conosci il token di sicurezza?",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"entryAttributes"	=> self::$onChanggeCheckVisibilityAttributes,
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Permetti lo scarico del feed solo a chi posside il token di sicurezza")."</div>"
					),
				),
				'token_sicurezza'	=>	array(
					"entryAttributes"	=>	$attributesVisibilita,
				),
				'query_string'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà aggiunta ad ogni link del feed (inserire anche il ? iniziale)")."</div>"
					),
				),
			),
		);
	}
	
	public function setTokenSicurezza($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !trim($record["token_sicurezza"]))
		{
			$this->sValues(array(
				"token_sicurezza"	=>	randomToken(),
			));
			
			$this->update((int)$id);
		}
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? "<i class='fa fa-check text text-success'></i>" : "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public function checkModulo($codice, $token = "")
	{
		return $this->clear()->where(array(
			"codice"	=>	sanitizeDb((string)$codice),
			"attivo"	=>	1,
			"OR"	=>	array(
				"usa_token_sicurezza"	=>	0,
				"AND"	=>	array(
					"token_sicurezza"	=>	sanitizeDb((string)$token),
					"ne"	=>	array(
						"token_sicurezza"	=>	"",
					)
				)
			)
		))->rowNumber();
	}
	
	public static function getModulo($codice)
	{
		$c = new FeedModel();
		
		if (!isset(self::$modulo))
		{
			$where = array(
				"codice"	=>	sanitizeDb($codice),
			);
			
			$attivo = $c->clear()->where($where)->record();
			
			if (!empty($attivo) && file_exists(LIBRARY."/Application/Modules/Feed/".$attivo["modulo"].".php"))
			{
				require_once(LIBRARY."/Application/Modules/Feed.php");
				require_once(LIBRARY."/Application/Modules/Feed/".$attivo["modulo"].".php");
				
				$objectReflection = new ReflectionClass($attivo["modulo"]);
				$object = $objectReflection->newInstanceArgs(array($attivo));
				
				self::$modulo = $object;
			}
		}
		
		return $c;
	}
	
	public function __call($metodo, $argomenti)
	{
		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
			return call_user_func_array(array(self::$modulo, $metodo), $argomenti);

		return false;
	}
}
