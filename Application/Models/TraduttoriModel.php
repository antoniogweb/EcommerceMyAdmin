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
	
	public static $campoTabella = array(
		"id_page"		=>	"pages",
		"id_c"			=>	"categories",
// 		"id_marchio"	=>	"marchi",
// 		"id_tag"		=>	"tag",
// 		"id_car"		=>	"caratteristiche",
// 		"id_cv"			=>	"caratteristiche_valori",
// 		"id_fascia_prezzo"	=>	"fasce_prezzo",
// 		"id_av"			=>	"attributi_valori",
	);
	
	public static $campiDaTradurreContenuti = array(
		"title",
		"description",
		"keywords",
		"meta_description",
		"sottotitolo",
		"titolo",
		"descrizione",
		"testo_link",
		"descrizione_2",
		"descrizione_3",
		"descrizione_4",
	);
	
	public static $campiDaTradurreTesti = array(
		"valore",
		"testo_link",
	);
	
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
	
	public static function traduciTabellaContenuti($campo, $lingua, $idRecord, $limit = 10, $log = null)
	{
		if (LingueModel::checkLinguaAttiva($lingua))
		{
			$ctModel = new ContenutitradottiModel();
			
			$ctModel->clear()->select("*")->where(array(
				"ne"	=>	array(
					$campo	=>	0,
				),
			));
			
			if ($campo == "id_page")
				$ctModel->inner(array("page"))->sWhere("(contenuti_tradotti.salvato = 0 OR (contenuti_tradotti.data_traduzione IS NOT NULL AND pages.data_ultima_modifica IS NOT NULL AND contenuti_tradotti.data_traduzione < pages.data_ultima_modifica))");
			if ($campo == "id_c")
				$ctModel->inner(array("category"))->sWhere("(contenuti_tradotti.salvato = 0 OR (contenuti_tradotti.data_traduzione IS NOT NULL AND categories.data_ultima_modifica IS NOT NULL AND contenuti_tradotti.data_traduzione < categories.data_ultima_modifica))");
			else
				$ctModel->sWhere("contenuti_tradotti.salvato = 0");
			
			if ($idRecord)
				$ctModel->aWhere(array(
					"contenuti_tradotti.$campo"	=>	(int)$idRecord,
				));
			
			if ($limit)
				$ctModel->limit($limit);
			
			$elementiDaTradurre = $ctModel->send();
			
// 			print_r($elementiDaTradurre);die();
			
			foreach ($elementiDaTradurre as $riga)
			{
				$traduzioni = array();
				
				foreach (self::$campiDaTradurreContenuti as $campoDaTradurre)
				{
					// Cerco il testo da tradurre
					if (isset(self::$campoTabella[$campo]))
						$testoDaTradurre = isset($riga[self::$campoTabella[$campo]][$campoDaTradurre]) ? htmlentitydecode($riga[self::$campoTabella[$campo]][$campoDaTradurre]) : "";
					else
						$testoDaTradurre = htmlentitydecode($riga["contenuti_tradotti"][$campoDaTradurre]);
					
					// Traduco
					if (!trim($testoDaTradurre))
						$traduzioni[$campoDaTradurre] = "";
					else
					{
						$traduzioni[$campoDaTradurre] = TraduttoriModel::getModulo()->traduci($testoDaTradurre, v("lingua_default_frontend"), $lingua);
						
						if ($traduzioni[$campoDaTradurre] !== false)
							$traduzioni[$campoDaTradurre] = trim($traduzioni[$campoDaTradurre]);
						else
							$traduzioni[$campoDaTradurre] = "";
					}
				}
				
				$ctModel->sValues($traduzioni);
				
				if (!$riga["contenuti_tradotti"]["salvato"])
					$ctModel->setValue("alias", "");
				else
					$ctModel->setValue("alias", $riga["contenuti_tradotti"]["alias"], "sanitizeDb");
				
				$ctModel->setSalvatoEDataTraduzione();
				
				$ctModel->update($riga["contenuti_tradotti"]["id_ct"]);
				
				$testoLog = "TRADOTTO RECORD ".$riga["contenuti_tradotti"]["id_ct"].":\n";
				
				foreach ($traduzioni as $campo => $traduzione)
				{
					if (trim($traduzione))
						$testoLog .= "$campo: $traduzione\n";
				}
				
				if ($log)
					$log->writeString($testoLog);
				
				echo $testoLog."\n";
			}
		}
	}
	
	public static function traduciTabellaTesti($lingua, $idRecord = 0, $limit = 10, $log = null)
	{
		if (LingueModel::checkLinguaAttiva($lingua))
		{
			$tModel = new TestiModel();
			
			// Estraggo gli elementi da tradurre
			$elementiDaTradurre = $tModel->clear()
				->select("*")
				->inner("testi as principale")
				->on(array(
					"testi.chiave = principale.chiave and principale.lingua = ?",
					array(sanitizeAll(v("lingua_default_frontend")))
				))
				->where(array(
					"lingua"	=>	sanitizeAll($lingua),
				))
				->sWhere("(testi.data_ultima_modifica IS NULL OR (principale.data_ultima_modifica IS NOT NULL AND testi.data_ultima_modifica < principale.data_ultima_modifica))");
			
			if ($idRecord)
				$tModel->aWhere(array(
					"principale.id_t"	=>	(int)$idRecord,
				));
			
			if ($limit)
				$tModel->limit($limit);
			
			$elementiDaTradurre = $tModel->send();
			
// 			echo $tModel->getQuery();
			
			foreach ($elementiDaTradurre as $riga)
			{
				$traduzioni = array();
				
				foreach (self::$campiDaTradurreTesti as $campoDaTradurre)
				{
					$testoDaTradurre = htmlentitydecode($riga["principale"][$campoDaTradurre]);
					
					if (!trim($testoDaTradurre))
						$traduzioni[$campoDaTradurre] = "";
					else
					{
						$traduzioni[$campoDaTradurre] = TraduttoriModel::getModulo()->traduci($testoDaTradurre, v("lingua_default_frontend"), $lingua);
						
						if ($traduzioni[$campoDaTradurre] !== false)
							$traduzioni[$campoDaTradurre] = trim($traduzioni[$campoDaTradurre]);
						else
							$traduzioni[$campoDaTradurre] = "";
					}
				}
				
				$tModel->sValues($traduzioni);
				
				$tModel->update((int)$riga["testi"]["id_t"]);
				
				$testoLog = "TRADOTTO RECORD ".$riga["testi"]["id_t"].":\n";
				
				foreach ($traduzioni as $campo => $traduzione)
				{
					if (trim($traduzione))
						$testoLog .= "$campo: $traduzione\n";
				}
				
				if ($log)
					$log->writeString($testoLog);
				
				echo $testoLog."\n";
			}
		}
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
		}
	}
}
