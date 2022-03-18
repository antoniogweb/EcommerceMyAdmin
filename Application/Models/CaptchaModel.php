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

class CaptchaModel extends GenericModel {
	
	public static $moduloCaptcha = null;
	
	public function __construct() {
		$this->_tables='captcha';
		$this->_idFields='id_captcha';
		
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
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Attivando questo sistema antispam verranno disattivati gli altri")."</div>"
					),
				),
				'campo_nascosto'		=>	array(
					"labelString"	=>	"Campo nascosto (FORM CONTATTI e ISCRIZIONE NEWSLETTER)",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'><b>".gtext("Usato nel FORM CONTATTI e nel FORM DI ISCRIZIONE ALLA NEWSLETTER").":</b><br />".gtext("Campo nascosto invisibile ai clienti che, se riempito, bloccherà l'invio del form.")."<br />".gtext("Essendo nascosto, solo i bot riempiranno il campo, mentre gli utenti reali no.")."</div>"
					),
				),
				'campo_nascosto_registrazione'		=>	array(
					"labelString"	=>	"Campo nascosto (FORM REGISTRAZIONE e FORM CHECKOUT)",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'><b>".gtext("Usato nel FORM DI REGISTRAZIONE e nel FORM AL CHECKOUT").":</b><br />".gtext("Campo nascosto invisibile ai clienti che, se riempito, bloccherà l'invio del form.")."<br />".gtext("Essendo nascosto, solo i bot riempiranno il campo, mentre gli utenti reali no.")."</div>"
					),
				),
				'secret_client'		=>	array(
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'secret_server'		=>	array(
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? "<i class='fa fa-check text text-success'></i>" : "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["attivo"]) && $this->values["attivo"])
			$this->db->query("update captcha set attivo = 0 where 1");
		
		return parent::update($id, $where);
	}
	
	public static function getModulo($codice = null)
	{
		$c = new CaptchaModel();
		
		if (!isset(self::$moduloCaptcha))
		{
			if ($codice)
				$where = array(
					"codice"	=>	sanitizeDb($codice),
				);
			else
				$where = array(
					"attivo"	=>	1,
				);
			
			$attivo = $c->clear()->where($where)->record();
			
			if (empty($attivo))
				$attivo = array(
					"modulo"	=>	"NessunFiltro",
				);
			
			if (file_exists(LIBRARY."/Application/Modules/Captcha/".$attivo["modulo"].".php"))
			{
				require_once(LIBRARY."/Application/Modules/Captcha.php");
				require_once(LIBRARY."/Application/Modules/Captcha/".$attivo["modulo"].".php");
				
				$objectReflection = new ReflectionClass($attivo["modulo"]);
				$object = $objectReflection->newInstanceArgs(array($attivo));
				
				self::$moduloCaptcha = $object;
			}
		}
		
		return $c;
	}
	
	public function __call($metodo, $argomenti)
	{
		if (isset(self::$moduloCaptcha) && method_exists(self::$moduloCaptcha, $metodo))
			return call_user_func_array(array(self::$moduloCaptcha, $metodo), $argomenti);

		return false;
	}
}
