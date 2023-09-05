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

class EventiretargetingModel extends GenericModel {
	
	public static $debug = false;
	public static $debugResult= array();
	
	public static $scattaDopoOre = array();
	
	public function __construct() {
		$this->_tables='eventi_retargeting';
		$this->_idFields='id_evento';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'elementi' => array("HAS_MANY", 'EventiretargetingelementiModel', 'id_evento', null, "RESTRICT", "Attenzione, l'elemento ha delle restrizioni e non può essere eliminato. <br />Disattivarlo per fare in modo che non sia più attivo."),
			'email' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'gruppo' => array("BELONGS_TO", 'EventiretargetinggruppiModel', 'id_gruppo_retargeting',null,"CASCADE"),
		);
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Dai un titolo al tuo evento',
					'entryClass'	=>	'form_input_text help_titolo',
				),
				'id_gruppo_retargeting'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Quale evento vuoi scatenare?",
					"options"	=>	$this->gruppiEventi(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'scatta_dopo_ore'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Dopo quante ore vuoi inviare la mail?",
					"options"	=>	OpzioniModel::codice("TIME_RETARGETING"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_page'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Quale mail vuoi inviare?",
					"options"	=>	$this->selectMail(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attiva l'evento",
					"options"	=>	$this->selectAttivo(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public static function setDebug($valore = true)
	{
		self::$debug = $valore;
	}
	
	public function insert()
	{
		$this->values["creation_time"] = time();
		
		return parent::insert();
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? "<i class='fa fa-check text text-success'></i>" : "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function selectAttivo()
	{
		return gtextDeep(self::$attivoSiNo);
	}
	
	public function dopoquanto($record)
	{
		if (empty(self::$scattaDopoOre))
			self::$scattaDopoOre = OpzioniModel::codice("TIME_RETARGETING");
		
		if (isset(self::$scattaDopoOre[$record["eventi_retargeting"]["scatta_dopo_ore"]]))
			return gtext(self::$scattaDopoOre[$record["eventi_retargeting"]["scatta_dopo_ore"]]);
		
		return "";
	}
	
	public function selectMail()
	{
		return PagesModel::g(false)->selectPagineSezione("email", false, false);
	}
	
	public function gruppiEventi()
	{
		$erg = new EventiretargetinggruppiModel();
		
// 		return $erg->clear()->where(array(
// 			"attivo"	=>	1,
// 		))->orderBy("id_order")->toList("id_gruppo_retargeting", "titolo")->send();
		
		$res = $erg->clear()->where(array(
			"attivo"	=>	1,
		))->orderBy("id_order")->send(false);
		
		$arrayEventi = array();
		
		foreach ($res as $r)
		{
			if (VariabiliModel::verificaCondizioni($r["condizioni"]))
				$arrayEventi[$r["id_gruppo_retargeting"]] = $r["titolo"];
		}
		
		return $arrayEventi;
	}
	
	public function scattaDopoOre()
	{
		return gtextDeep(self::$scattaDopoOre);
	}
	
	public static function processaContatto($idElemento)
	{
		self::processa($idElemento, "ContattiModel", true);
	}
	
	public static function processaPromo($idElemento)
	{
		self::processa($idElemento, "PromozioniModel", true);
	}
	
	public static function processaLista($idElemento)
	{
		self::processa($idElemento, "ListeregaloemailModel", true);
	}
	
	public static function processaSpedizione($idElemento)
	{
		self::processa($idElemento, "SpedizioninegozioeventiModel", true);
	}
	
	public static function processa($idElemento = 0, $limitaAModel = null, $immediati = false)
	{
		if (!v("attiva_eventi_retargeting"))
			return;
		
		$arrayEmailIdLingua = array();
		
		$evModel = new EventiretargetingModel();
		$evElModel = new EventiretargetingelementiModel();
		$evGrModel = new EventiretargetinggruppiModel();
		EventiretargetinggruppiModel::getIdGruppiModel();
		
		$evModel->clear()->where(array(
			"attivo"	=>	1,
		))->inner(array("email"))->orderBy("eventi_retargeting.id_order");
		
		if ($immediati)
			$evModel->sWhere("scatta_dopo_ore = 0");
		else
			$evModel->sWhere("scatta_dopo_ore > 0");
		
		$eventiAttivi = $evModel->send();
		
		if (!empty($eventiAttivi))
		{
// 			print_r(self::$eventiAttivi);
			foreach ($eventiAttivi as $evento)
			{
				$idEvento = (int)$evento["eventi_retargeting"]["id_evento"];
				
				$idGruppoRetargeting = $evento["eventi_retargeting"]["id_gruppo_retargeting"];
				
				$dettagliGruppoRetargeting = $evGrModel->selectId($idGruppoRetargeting);
				
				$idPagina = $evento["eventi_retargeting"]["id_page"];
				$scattaDopoOre = $evento["eventi_retargeting"]["scatta_dopo_ore"];
				$timeCreazioneEvento = $evento["eventi_retargeting"]["creation_time"];
				
				$modelName = EventiretargetinggruppiModel::$arrayIdModel[$idGruppoRetargeting];
				
				if ($limitaAModel && $modelName != $limitaAModel)
					continue;
				
				$arrayFonti = $evGrModel->getArrayFonti($idGruppoRetargeting);
				
				$cModel = new $modelName;
				$cModel->clear();
				$primaryKey = $cModel->getPrimaryKey();
				$tableName = $cModel->table();
				
				if ($idElemento)
					$cModel->aWhere(array(
						"$primaryKey"	=>	(int)$idElemento,
					));
				
				$cModel->aWhere(array(
					"gt"	=>	array(
						"creation_time"	=>	sanitizeDb($timeCreazioneEvento),
					),
					"ne"	=>	array(
						"email"	=>	"",
					),
				));
				
				if (count($arrayFonti) > 0)
					$cModel->aWhere(array(
						"in"	=>	array(
							"fonte"	=>	sanitizeDbDeep($arrayFonti),
						),
					));
				
				if (!empty($dettagliGruppoRetargeting) && $dettagliGruppoRetargeting["clausola_where"])
					$cModel->sWhere($dettagliGruppoRetargeting["clausola_where"]);
				
				if ($scattaDopoOre > 0)
				{
					$tempoEvento = time() - ($scattaDopoOre * 3600);
// 					$cModel->sWhere("creation_time <= $tempoEvento");
					$cModel->sWhere(array($cModel->campoTimeEventoRemarketing." <= ?",array((int)$tempoEvento)));
				}
				
				$tipoControllo = (!empty($dettagliGruppoRetargeting)) ? $dettagliGruppoRetargeting["blocca_reinvio_mail_stesso"] : "EVENTO";
				
				if ($tipoControllo == "EVENTO")
					$cModel->sWhere(array("email not in (select email from eventi_retargeting_elemento where id_evento = ?)",array($idEvento)));
				else
					$cModel->sWhere(array("(email,$primaryKey) not in (select email,id_elemento from eventi_retargeting_elemento where id_evento = ?)",array($idEvento)));
				
				$elementi = $cModel->send(false);
				
				$queryElementi = $cModel->getQuery();
				
				$elementiProcessati = array();
// 				$elementiProcessatiElemento = array();
				
				foreach ($elementi as $e)
				{
					if (!isset($arrayEmailIdLingua[$idPagina][$e["lingua"]]))
						$arrayEmailIdLingua[$idPagina][$e["lingua"]] = PagesModel::getPageDetails($idPagina, $e["lingua"]);
					
					$email = $arrayEmailIdLingua[$idPagina][$e["lingua"]];
					
					$giaProcessato = false;
					$emailElemento = strtolower(trim($e["email"]));
					
					$usaTemplate = ((int)$email["pages"]["id_mail_template"]) ? true : false;
					
					if ($emailElemento && checkMail($emailElemento))
					{
						$oggetto = htmlentitydecode(field($email, "title"));
						$testo = htmlentitydecode(field($email, "description"));
						
						TraduzioniModel::sLingua($e["lingua"], "front");
						$oggetto = SegnapostoModel::sostituisci($oggetto, $e, $cModel);
						$testo = SegnapostoModel::sostituisci($testo, $e, $cModel);
						TraduzioniModel::rLingua();
						
						if ($tipoControllo == "EVENTO" && in_array($emailElemento, $elementiProcessati))
							$giaProcessato = true;
						
// 						if ($tipoControllo == "EVENTO_ELEMENTO" && isset($elementiProcessatiElemento[$e[$primaryKey]]) && in_array($emailElemento, $elementiProcessatiElemento[$e[$primaryKey]]))
// 							$giaProcessato = true;
						
						$elementiProcessati[] = $emailElemento;
						
// 						if (isset($elementiProcessatiElemento[$e[$primaryKey]]))
// 							$elementiProcessatiElemento[$e[$primaryKey]][] = $emailElemento;
// 						else
// 							$elementiProcessatiElemento[$e[$primaryKey]] = array($emailElemento);
						
						$mailInviata = 0;
						
						MailordiniModel::$idMailInviate = array();
						
						if (!self::$debug && !$giaProcessato)
						{
							$valoriMail = array(
								"emails"	=>	array($emailElemento),
								"oggetto"	=>	$oggetto,
								"testo"		=>	$testo,
								"tipologia"	=>	"RETARGETING",
								"id_evento"	=>	$idEvento,
								"lingua"	=>	$e["lingua"],
								"usa_template"	=>	$usaTemplate,
							);
							
							if (MailordiniModel::inviaMail($valoriMail))
								$mailInviata = 1;
						}
						
						$valoriElemento = array(
							"id_evento"		=>	$idEvento,
							"id_elemento"	=>	$e[$primaryKey],
							"id_page"		=>	$email["pages"]["id_page"],
							"duplicato"		=>	$giaProcessato ? 1 : 0,
							"mail_inviata"	=>	$mailInviata,
							"email"			=>	$emailElemento,
							"id_mail"		=>	count(MailordiniModel::$idMailInviate) > 0 ? MailordiniModel::$idMailInviate[0] : 0,
							"tabella_elemento"	=>	$tableName,
							"model"			=>	$modelName,
						);
						
						if (!self::$debug)
						{
							$evElModel->setValues($valoriElemento);
							
							$evElModel->insert();
						}
						else
						{
							if (!$giaProcessato)
							{
								$valoriElemento += array(
									"data_ora_elemento"	=>	date("Y-m-d H:i", $e["creation_time"]),
									"numero_ore_evento"	=>	$scattaDopoOre,
									"nome_evento"		=>	$evento["eventi_retargeting"]["titolo"],
									"oggetto"			=>	sanitizeAll($oggetto),
									"testo"				=>	sanitizeAll($testo),
									"queryElementi"		=>	$queryElementi,
								);
							
								self::$debugResult[] = $valoriElemento;
							}
						}
					}
				}
			}
		}
	}
	
	public static function printDebugResult()
	{
		$html = "Niente da schedulare";
		
		if (count(self::$debugResult) > 0)
		{
			$html = "<table border='1'><tr><th>".implode("</th><th>",array_keys(self::$debugResult[0]))."</th></tr>";
			
			foreach (self::$debugResult as $r)
			{
				$r = htmlentitydecodeDeep($r);
				
				$html .= "<tr><td>".implode("</td><td>",array_values($r))."</td></tr>";
			}
			
			$html .= "</table>";
			
			$html .= "<br /><a href='".Url::getRoot()."contenuti/processaschedulazione/".v("token_schedulazione")."'>Conferma e processa</a>";
		}
		
		return $html;
	}
}
