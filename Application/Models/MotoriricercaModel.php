<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class MotoriricercaModel extends GenericModel
{
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "MotoriRicerca";
	public $classeModuloPadre = "MotoreRicerca";
	
	public function __construct() {
		$this->_tables='motori_ricerca';
		$this->_idFields='id_motore_ricerca';
		
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
						"<div class='form_notice'>".gtext("Attivando questo motore di ricerca verranno disattivati gli altri")."</div>"
					),
				),
				'api_key'		=>	array(
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'api_key_public'		=>	array(
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'tempo_cache'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tempo di cache dell'output",
					"options"	=>	OpzioniModel::codice("TEMPO_CACHE_FEED"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'massimo_numero_di_ricerche_in_cache'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext('Il limite massimo di ricerche in cache viene considerato "nel tempo di cache", ad esempio 1 ora.')."<br />".gtext("Da proporzionare al tempo di cache.")."</div>"
					),
				),
			),
		);
		
		$this->moduleFormStruct($id);
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["attivo"]) && $this->values["attivo"])
			$this->db->query("update motori_ricerca set attivo = 0 where 1");
		
		return parent::update($id, $where);
	}
	
	public function checkModulo($codice, $token = "")
	{
		return $this->clear()->where(array(
			"codice"	=>	sanitizeDb((string)$codice),
			"attivo"	=>	1,
		))->rowNumber();
	}
	
	public static function getCodiceAttivo()
	{
		$m = new MotoriricercaModel();
		
		return $m->clear()->where(array(
			"attivo"	=>	1,
		))->field("codice");
	}
}
