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

class IntegrazioninewsletterModel extends GenericModel
{
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "Newsletter";
	public $classeModuloPadre = "Newsletter";
	
	public static $elencoSezioni = null;
	
	public function __construct() {
		$this->_tables='integrazioni_newsletter';
		$this->_idFields='id_integrazione_newsletter';
		
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
				'secret_1'		=>	array(
					'labelString'	=>	self::getModulo($record["codice"])->gSecret1Label(),
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'secret_2'		=>	array(
					'labelString'	=>	self::getModulo($record["codice"])->gSecret2Label(),
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'codice_fonte'		=>	array(
					'labelString'	=>	"Codice della fonte di invio",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Codice libero che servirà per distingue i contatti aggiunti a Sendpulse tramite il sito")." ".Parametri::$nomeNegozio."</div>"
					),
				),
				"riempi_company"	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Riempi il 'company' di SendPulse con Nome e Cognome",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function getNomeCampoClasse()
	{
		return "classe";
	}
	
	public function update($id = null, $where = null)
	{
		if (isset($this->values["attivo"]) && $this->values["attivo"])
			$this->db->query("update integrazioni_newsletter set attivo = 0 where 1");
		
		return parent::update($id, $where);
	}
	
// 	public function attivo($record)
// 	{
// 		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
// 	}
	
// 	public static function getModulo($codice = null)
// 	{
// 		$i = new IntegrazioninewsletterModel();
// 		
// 		if (!isset(self::$modulo))
// 		{
// 			if ($codice)
// 				$attivo = $i->clear()->where(array(
// 					"codice"	=>	sanitizeDb($codice),
// 				))->record();
// 			else
// 				$attivo = $i->clear()->where(array(
// 					"attivo"	=>	1,
// 				))->record();
// 			
// 			if (!empty($attivo) && file_exists(LIBRARY."/Application/Modules/Newsletter/".$attivo["classe"].".php"))
// 			{
// 				require_once(LIBRARY."/Application/Modules/Newsletter.php");
// 				require_once(LIBRARY."/Application/Modules/Newsletter/".$attivo["classe"].".php");
// 				
// 				$objectReflection = new ReflectionClass($attivo["classe"]);
// 				$object = $objectReflection->newInstanceArgs(array($attivo));
// 				
// 				self::$modulo = $object;
// 			}
// 		}
// 		
// 		return $i;
// 	}
	
// 	public function __call($metodo, $argomenti)
// 	{
// 		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
// 			return call_user_func_array(array(self::$modulo, $metodo), $argomenti);
// 
// 		return false;
// 	}
	
	public static function integrazioneAttiva()
	{
		return self::getModulo()->isAttiva();
	}
	
	public static function elaboraDati($valori)
	{
		$valoriFinali = array();
		
		$valoriFinali["email"] = isset($valori["email"]) ? $valori["email"] : $valori["username"];
		
		if (isset($valori["tipo_cliente"]))
		{
			if ($valori["tipo_cliente"] == "azienda")
			{
				$valoriFinali["nome"] = $valori["ragione_sociale"];
				$valoriFinali["cognome"] = "";
			}
			else
			{
				$valoriFinali["nome"] = $valori["nome"];
				$valoriFinali["cognome"] = $valori["cognome"];
			}
		}
		else
		{
			if (isset($valori["nome"]))
				$valoriFinali["nome"] = $valori["nome"];
			
			if (isset($valori["cognome"]))
				$valoriFinali["cognome"] = $valori["cognome"];
			
			if (isset($valori["azienda"]))
				$valoriFinali["azienda"] = $valori["azienda"];
		}
		
		$valoriFinali = IntegrazioninewslettervariabiliModel::mergeCampiAggiuntivi($valoriFinali, $valori);
		
		return $valoriFinali;
	}
	
	public static function sistemaMailchimp()
	{
		if (ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
		{
			$i = new IntegrazioninewsletterModel();
			
			$i->db->query("update integrazioni_newsletter set attivo = 0 where 1");
			
			$i->setValues(array(
				"secret_1"		=>	ImpostazioniModel::$valori["mailchimp_api_key"],
				"codice_lista"	=>	ImpostazioniModel::$valori["mailchimp_list_id"],
				"attivo"		=>	1,
			));
			
			$i->pUpdate(null, "codice = 'MAILCHIMP'");
		}
	}
}
