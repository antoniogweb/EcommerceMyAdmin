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
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "Feed";
	public $classeModuloPadre = "Feed";
	
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
				),
				'link_a_combinazione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Elenca tutte le varianti",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se deve mostrare solo le pagine o anche tutte le varianti")."</div>"
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
				'tempo_cache'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tempo di cache dell'output",
					"options"	=>	OpzioniModel::codice("TEMPO_CACHE_FEED"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'url_feed'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("URL personalizzato del feed. Se lasciato vuoto, solo l'URL automatico potrà essere utilizzato.")."</div>"
					),
				),
			),
		);
		
		$this->moduleFormStruct($id);
	}
}
