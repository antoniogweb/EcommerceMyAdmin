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

class TraduttoriModel extends GenericModel
{
	use DIModel;
	
	public static $modulo = null;
	
	public $cartellaModulo = "Traduttori";
	public $classeModuloPadre = "Traduttore";
	
	public function __construct() {
		$this->_tables='traduttori';
		$this->_idFields='id_traduttore';
		
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
						"<div class='form_notice'>".gtext("Attivando questo traduttore verranno disattivati gli altri")."</div>"
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
		if (isset($this->values["attivo"]) && $this->values["attivo"])
			$this->db->query("update traduttori set attivo = 0 where 1");
		
		return parent::update($id, $where);
	}
	
	public static function traduciTabellaTraduzioni($lingua, $idRecord = 0, $limit = 10, $log = null)
	{
		if (LingueModel::checkLinguaAttiva($lingua))
		{
			$tModel = new TraduzioniModel();
			
			$contesto = array("front");
			
			if (LingueModel::permettiCambioLinguaBackend())
				$contesto[] = "back";
			
			// Estraggo gli elementi da tradurre
			$elementiDaTradurre = $tModel->clear()
				->select("*")
				->inner("traduzioni as principale")
				->on(array(
					"traduzioni.chiave = principale.chiave and traduzioni.contesto = principale.contesto and principale.lingua = ?",
					array(sanitizeAll(v("lingua_default_frontend")))
				))
				->where(array(
					"lingua"	=>	sanitizeAll($lingua),
					"in"		=>	array(
						"contesto"	=>	sanitizeAllDeep($contesto),
					),
					"tradotta"	=>	0,
				));
			
			if ($idRecord)
				$tModel->aWhere(array(
					"principale.id_t"	=>	(int)$idRecord,
				));
			
			if ($limit)
				$tModel->limit($limit);
			
			$elementiDaTradurre = $tModel->send();
			
			if (count($elementiDaTradurre) > 0)
			{
				foreach ($elementiDaTradurre as $riga)
				{
					if (!trim($riga["principale"]["valore"]))
						continue;
					
					$traduzione = TraduttoriModel::getModulo()->traduci($riga["principale"]["valore"], v("lingua_default_frontend"), $lingua);
					
					if ($traduzione !== false)
					{
						$traduzione = trim($traduzione);
						
						$tModel->sValues(array(
							"valore"	=>	$traduzione,
						));
						
						$tModel->update((int)$riga["traduzioni"]["id_t"]);
						
						$testoLog = "TRADOTTO RECORD ".$riga["principale"]["id_t"]."\n".v("lingua_default_frontend").":\n".$riga["principale"]["valore"]."\n$lingua:\n".$traduzione;
						
						if ($log)
							$log->writeString($testoLog);
						
						echo $testoLog."\n";
					}
				}
				// Estraggo le righe da tradurre
// 				$testiDaTradurre = $tModel->clear()->where(array(
// 					"lingua"	=>	sanitizeAll(v("lingua_default_frontend")),
// 					"in"		=>	array(
// 						"contesto"	=>	sanitizeAllDeep($contesto),
// 					),
// 				));
// 				
// 				$testiDaTradurre = $tModel->send();
				
// 				print_r($elementiDaTradurre);
			}
		}
	}
}
