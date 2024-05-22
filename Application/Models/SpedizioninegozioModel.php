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

require_once(LIBRARY . "/Application/Modules/Data/Spedizioni/Result.php");

class SpedizioninegozioModel extends FormModel {
	
	const TIPOLOGIA_PORTO_FRANCO = 'PORTO_FRANCO';
	const TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO = 'PORTO_FRANCO_CONTRASSEGNO';
	
	const LABEL_CONSEGNATA = "CONSEGNATA";
	
	public $applySoftConditionsOnPost = true;
	public $applySoftConditionsOnValues = false;
	
	public function __construct() {
		$this->_tables='spedizioni_negozio';
		$this->_idFields='id_spedizione_negozio';
		$this->_idOrder = 'id_order';
		
		OrdiniModel::setStatiOrdine();
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe' => array("HAS_MANY", 'SpedizioninegoziorigheModel', 'id_spedizione_negozio', null, "CASCADE"),
			'eventi' => array("HAS_MANY", 'SpedizioninegozioeventiModel', 'id_spedizione_negozio', null, "CASCADE"),
			'colli' => array("HAS_MANY", 'SpedizioninegoziocolliModel', 'id_spedizione_negozio', null, "CASCADE"),
			'info' => array("HAS_MANY", 'SpedizioninegozioinfoModel', 'id_spedizione_negozio', null, "CASCADE"),
			'servizi' => array("HAS_MANY", 'SpedizioninegozioserviziModel', 'id_spedizione_negozio', null, "CASCADE"),
			'spedizioniere' => array("BELONGS_TO", 'SpedizionieriModel', 'id_spedizioniere',null,"RESTRICT","Si prega di selezionare lo spedizioniere".'<div style="display:none;" rel="hidden_alert_notice">id_spedizioniere</div>'),
			'invio' => array("BELONGS_TO", 'SpedizioninegozioinviiModel', 'id_spedizione_negozio_invio',null,"CASCADE"),
			'lettera' => array("BELONGS_TO", 'SpedizionieriletterevetturaModel', 'id_spedizioniere_lettera_vettura',null,"CASCADE"),
		);
    }
	
	public function setFormStruct($id = 0)
	{
		parent::setFormStruct($id);
		
		$modulo = self::getModulo((int)$id);
		
		$this->formStruct["entries"]["ragione_sociale_2"]["labelString"] = "Destinatario spedizione";
		
		$this->formStruct["entries"]["note_interne"]["labelString"] = "Note";
		$this->formStruct["entries"]["note_interne"]["wrap"] = array();
		
		if ($modulo)
			$this->formStruct["entries"]["numero_spedizione"]["labelString"] = $modulo->getLabelNumeroSpedizione();
		
		$this->formStruct["entries"]["codice_pagamento_contrassegno"] = array(
			"type"	=>	"Select",
			"options"	=>	$modulo ? $modulo->gCodiciPagamentoContrassegno() : [],
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	'Pagamento accettato (solo per contrassegno)',
		);
		
		$this->formStruct["entries"]["formato_etichetta_pdf"] = array(
			"type"	=>	"Select",
			"options"	=>	$modulo ? implode(",",$modulo->gFormatiEtichetta()) : [],
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	"Formato dell'etichetta in PDF",
		);
		
		$this->formStruct["entries"]["tipo_servizio"] = array(
			"type"	=>	"Select",
			"options"	=>	$modulo ? $modulo->gTipoServizio() : [],
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	"Tipo servizio",
		);
		
		$this->formStruct["entries"]["codice_tariffa"] = array(
			"type"	=>	"Select",
			"options"	=>	$modulo ? $modulo->gCodiceTariffa() : [],
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	"Codice tariffa",
		);
		
		$this->formStruct["entries"]["assicurazione_integrativa"] = array(
			"type"	=>	"Select",
			"options"	=>	$modulo ? $modulo->gAssicurazioneIntegrativa() : [],
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
		);
		
		$this->formStruct["entries"]["id_spedizioniere_lettera_vettura"] = array(
			"type"	=>	"Select",
			"options"	=>	$this->gSelectTemplate((int)$id),
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	"Template lettera di vettura (per dropshipping)",
		);
	}
	
	public function selectNazione($empty = false)
	{
		$n = new NazioniModel();
		
		if (!isset(NazioniModel::$elenco))
		{
			$default = $empty ? array("W"	=>	"Tutte le nazioni") : array(""	=>	"Seleziona");
			NazioniModel::$elenco = $default + $n->select("iso_country_code,titolo")->orderBy("titolo")->toList("iso_country_code","titolo")->send();
		}
		
		return NazioniModel::$elenco;
	}
	
	public function gSelectTemplate($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			return array(0 => "--") + SpedizionieriletterevetturaModel::g()->clear()->select("id_spedizioniere_lettera_vettura,titolo")->where(array(
				"id_spedizioniere"	=>	(int)$record["id_spedizioniere"],
				"attivo"			=>	1,
			))->toList("id_spedizioniere_lettera_vettura","titolo")->send();
		}
		
		return array();
	}
	
	public static function statiSpedizioniNonInviate()
	{
		return array("A","I");
	}
	
	public static function statiSpedizioniInviate()
	{
		return array("II");
	}
	
	public static function statiSpedizioniApribili()
	{
		return array("I","II");
	}
	
	public static function statiSpedizioniAnnullabili()
	{
		return array("II","C");
	}
	
	public function update($id = null, $where = null)
	{
		$this->setProvinciaFatturazione();
		$this->setTipologia(); // porto franco o porto franco con contrassegno
		
		$res = parent::update($id, $where);
		
		return $res;
	}
	
	// Salva nella spedizione se porto franco o porto assegnato
	public function setTipologia()
	{
		$this->values["tipologia"] = self::TIPOLOGIA_PORTO_FRANCO;
		
		if (isset($this->values["contrassegno"]) && setPrice($this->values["contrassegno"]) > 0)
			$this->values["tipologia"] = self::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO;
	}
	
	private function recuperaAnagraficaDaStruttura($struttura, $suffisso = "")
	{
		$this->setValue("indirizzo", $struttura["indirizzo$suffisso"], "sanitizeDb");
		$this->setValue("cap", $struttura["cap$suffisso"], "sanitizeDb");
		$this->setValue("citta", $struttura["citta$suffisso"], "sanitizeDb");
		$this->setValue("provincia", $struttura["provincia$suffisso"], "sanitizeDb");
		$this->setValue("dprovincia", $struttura["dprovincia$suffisso"], "sanitizeDb");
		$this->setValue("nazione", $struttura["nazione$suffisso"], "sanitizeDb");
		$this->setValue("telefono", $struttura["telefono$suffisso"], "sanitizeDb");
	}
	
	public function recuperaDatiDaOrdine($idO)
	{
		$ordine = OrdiniModel::g(false)->whereId((int)$idO)->record();
		
		if (!empty($ordine))
		{
			$this->setValue("id_user", $ordine["id_user"]);
			$this->setValue("id_spedizione", $ordine["id_spedizione"]);
			
			$ragSoc = $ordine["destinatario_spedizione"] ? $ordine["destinatario_spedizione"] : OrdiniModel::getNominativo($ordine);
			
			$this->setValue("ragione_sociale_2", $ragSoc, "sanitizeDb");
			$this->setValue("ragione_sociale", OrdiniModel::getNominativo($ordine), "sanitizeDb");
			
			$this->recuperaAnagraficaDaStruttura($ordine, "_spedizione");
			
			$this->setValue("email", $ordine["email"], "sanitizeDb");
			$this->setValue("lingua", (string)$ordine["lingua"], "sanitizeDb");
// 			$this->setValue("nazione", (string)$ordine["nazione"], "sanitizeDb");
			$this->setValue("note", $ordine["note"], "sanitizeDb");
			$this->setValue("note_interne", $ordine["telefono_spedizione"], "sanitizeDb");
			
			$tipologia = ($ordine["pagamento"] == "contrassegno") ? self::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO : self::TIPOLOGIA_PORTO_FRANCO;
			
			$this->setValue("tipologia", $tipologia);
			
			if ($ordine["pagamento"] == "contrassegno")
				$this->setValue("contrassegno", $ordine["total"]);
		}
	}
	
	public function recuperaDatiDaListaRegalo($idLista)
	{
		$lista = ListeregaloModel::g(false)->select("*")
			->inner(array("cliente"))
			->whereId((int)$idLista)
			->first();
		
		if (!empty($lista))
		{
			$cliente = $lista["regusers"];
			
			$spedizione = RegusersModel::g(false)->getSpedizionePrincipale($cliente["id_user"]);
			
			$struttura = !empty($spedizione) ? $spedizione : $cliente;
			
			$this->setValue("id_user", $cliente["id_user"]);
			
			$ragSoc = (!empty($spedizione) && $spedizione["destinatario_spedizione"]) ? $spedizione["destinatario_spedizione"] : OrdiniModel::getNominativo($cliente);
			
			$this->setValue("ragione_sociale_2", $ragSoc, "sanitizeDb");
			$this->setValue("ragione_sociale", OrdiniModel::getNominativo($cliente), "sanitizeDb");
			
			$telefono = !empty($spedizione) ? $spedizione["telefono_spedizione"] : $cliente["telefono"];
			
			$this->setValue("note_interne", $telefono, "sanitizeDb");
			
			$suffisso = "";
			
			if (!empty($spedizione))
			{
				$this->setValue("id_spedizione", $spedizione["id_spedizione"]);
				
				$suffisso = "_spedizione";
			}
			
			$this->recuperaAnagraficaDaStruttura($struttura, $suffisso);
			
			$this->setValue("tipologia", self::TIPOLOGIA_PORTO_FRANCO);
			$this->setValue("id_lista_regalo", (int)$idLista);
			
			$this->setValue("email", $cliente["username"], "sanitizeDb");
			$this->setValue("lingua", (string)$lista["liste_regalo"]["lingua"], "sanitizeDb");
// 			$this->setValue("nazione", (string)$lista["liste_regalo"]["nazione"], "sanitizeDb");
		}
	}
	
	public function insert()
	{
		if (isset($_GET["id_lista_regalo"]) && is_numeric($_GET["id_lista_regalo"]))
			$this->recuperaDatiDaListaRegalo((int)$_GET["id_lista_regalo"]);
		else if (isset($_GET["id_o"]) && is_numeric($_GET["id_o"]))
			$this->recuperaDatiDaOrdine((int)$_GET["id_o"]);
		
		// Recupero comunque i dati dell'ordine se presente nell'URL
		if (isset($_GET["id_o"]) && is_numeric($_GET["id_o"]))
			$ordine = OrdiniModel::g(false)->whereId((int)$_GET["id_o"])->record();
		
		if (isset($ordine) && !empty($ordine))
		{
			$this->setValue("id_ordine_di_partenza", $ordine["id_o"]);
			$this->setValue("codice_bda", $ordine["id_o"]);
			$this->setValue("riferimento_mittente_numerico", $ordine["id_o"]);
			$this->setValue("riferimento_mittente_alfa", $ordine["cognome"], "sanitizeDb");
		}
		
		$this->setValue("data_spedizione", date("Y-m-d"));
		
		$this->setProvinciaFatturazione();
		$this->setTipologia(); // porto franco o porto franco con contrassegno
		
		$res = parent::insert();
		
		if ($res && isset($ordine) && !empty($ordine))
		{
			$rModel = new RigheModel();
			$snr = new SpedizioninegoziorigheModel();
			
			$righeOrdine = $rModel->clear()->where(array("id_o"=>(int)$ordine["id_o"]))->send(false);
			
			foreach ($righeOrdine as $r)
			{
				$snr->sValues(array(
					"peso"		=>	$r["peso"],
					"id_r"		=>	(int)$r["id_r"],
					"id_spedizione_negozio"	=>	$this->lId,
				));
				
				$snr->insert();
			}
		}
		
		if ($res)
		{
			// Aggiungo un collo
			$sncModel = new SpedizioninegoziocolliModel();
			
			$sncModel->setValues(array(
				"id_spedizione_negozio"	=>	(int)$this->lId,
				"peso"					=>	number_format($this->pesoRighe(array((int)$this->lId)),2,".",""),
			));
			
			$sncModel->insert();
			
			// Aggiungo l'evento di apertura spedizione
			SpedizioninegozioeventiModel::g()->inserisci($this->lId, "A");
			
			// Aggiungo i valori di default del corriere
			$this->inserisciValoriDefaultCorriere($this->lId);
		}
		
		return $res;
	}
	
	// Inserisci i valori di default del corriere
	public function inserisciValoriDefaultCorriere($idS)
	{
		$modulo = self::getModulo($idS);
		
		if ($modulo->metodo("inserisciValoriDefaultCorriere"))
		{
			$modulo->inserisciValoriDefaultCorriere($this);
			
			$this->pUpdate((int)$idS);
		}
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record["id_spedizione_negozio"]))
		{
			return "N°".$record["id_spedizione_negozio"];
		}
		
		return "";
	}
	
	public function nazione($record)
	{
		return nomeNazione($record["spedizioni"]["nazione_spedizione"]);
	}
	
	public function provincia($record)
	{
		return ($record["spedizioni"]["nazione_spedizione"] == "IT") ? $record["spedizioni"]["provincia_spedizione"] : $record["spedizioni"]["dprovincia_spedizione"];
	}
	
	public function indirizzoCrud($record)
	{
		$record = $record["spedizioni_negozio"];
		
		return $record["indirizzo"]."<br />".$record["cap"]." ".$record["citta"]." (".$record["provincia"].") - ".nomeNazione($record["nazione"]);
	}
	
	public function nazioneCrud($record)
	{
		$record = $record["spedizioni_negozio"];
		
		return nomeNazione($record["nazione"]);
	}
	
	public function ordiniCrud($record)
	{
		$ordini = $this->getOrdini($record["spedizioni_negozio"]["id_spedizione_negozio"], false);
		
		$html = "";
		
		if (count($ordini) > 0 && $ordini[0]["orders"]["id_o"])
		{
			foreach ($ordini as $ordine)
			{
				$html .= "<span style='margin-right:2px;' class='label label-".OrdiniModel::getLabelStato($ordine["orders"]["stato"])."'>#".$ordine["orders"]["id_o"]."</span>";
			}
		}
		
		return $html;
	}
	
	public function brderoCrud($record)
	{
		if ($record["spedizioni_negozio_invii"]["id_spedizione_negozio_invio"])
			return "<b>".$record["spedizioni_negozio_invii"]["id_spedizione_negozio_invio"]."</b> ".gtext("del")." ".smartDate($record["spedizioni_negozio_invii"]["data_spedizione"]);
		
		return "";
	}
	
	public function trackingCrud($record)
	{
		if ($record["spedizioni_negozio"]["stato"] == "C")
			return gtext(self::LABEL_CONSEGNATA);
		
		return "<i>".$record["spedizioni_negozio"]["label_spedizioniere"]."</i>";
	}
	
	// Restituisce gli ordini legati ad una spedizione
	public function getOrdini($idS, $soloIds = true)
	{
		$this->clear()
			->left(array("righe"))
			->left("righe")->on("righe.id_r = spedizioni_negozio_righe.id_r")
			->where(array(
				"id_spedizione_negozio"	=>	(int)$idS,
			))
			->groupBy("righe.id_o");
		
		if ($soloIds)
			$this->select("righe.id_o")->toList("righe.id_o");
		else
			$this->left("orders")->on("orders.id_o = righe.id_o")->select("orders.*");
		
		return $this->send();
	}
	
	// Ricalcola il totale del contrassegno per la spedizione
	// array idS
	public function ricalcolaContrassegno($idS)
	{
		$oModel = new OrdiniModel();
		
		$idoS = $this->getOrdini($idS);
		
		$totaleContrassegno = (float)$oModel->clear()->where(array(
			"in"	=>	array(
				"id_o"	=>	forceIntDeep($idoS),
			),
			"pagamento"	=>	"contrassegno",
		))->getSum("total");
		
		$this->sValues(array(
			"contrassegno"	=>	$totaleContrassegno,
			"tipologia"	=>	($totaleContrassegno > 0) ? self::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO : self::TIPOLOGIA_PORTO_FRANCO,
		));
		
		$this->update((int)$idS);
	}
	
	// Restituisce un array per la select delle righe da spedire dell'ordine
	protected function getSelectFromIdO($arrayRighe, $idO)
	{
		$righe = OrdiniModel::righeDaSpedire((int)$idO);
		
		foreach ($righe as $r)
		{
			$arrayRighe[$r["id_r"]] = gtext("Ordine")." #".(int)$idO." - ".$r["title"]." ".strip_tags($r["attributi"]);
		}
		
		return $arrayRighe;
	}
	
	// Restituisce un array del tipo array("id_r" => "Titolo riga", ...) di tutte le righe ancora da spedire legate alla spedizione in questione
	public function getSelectRigheDaSpedire($idS)
	{
		$idOs = $this->getOrdini((int)$idS);
		
		$arrayRighe = [];
		
		$spedizioneNegozio = $this->selectId((int)$idS);
		
		if (empty($spedizioneNegozio))
			return $arrayRighe;
		
		foreach ($idOs as $idO)
		{
			if (!$idO)
				continue;
			
			$arrayRighe = $this->getSelectFromIdO($arrayRighe, (int)$idO);
		}
		
		$ninArray = array(
			"id_o"	=>	forceIntDeep($idOs),
		);
		
		$idOsAltri = [];
		
		if ($spedizioneNegozio["id_lista_regalo"])
		{
			$idOsAltri = OrdiniModel::g(false)->where(array(
				"nin"	=>	$ninArray,
				"id_lista_regalo"	=>	(int)$spedizioneNegozio["id_lista_regalo"]
			))->toList("id_o")->send();
		}
		else
		{
			// Cerco gli ordini con lo stesso id_spedizione
			$idSpedizione = $this->clear()->whereId((int)$idS)->field("id_spedizione");
			
			if ($idSpedizione)
			{
				$idOsAltri = OrdiniModel::g(false)->where(array(
					"nin"	=>	$ninArray,
					"id_spedizione"	=>	(int)$idSpedizione
				))->toList("id_o")->send();
			}
		}
		
		foreach ($idOsAltri as $idO)
		{
			$arrayRighe = $this->getSelectFromIdO($arrayRighe, (int)$idO);
		}
		
		return $arrayRighe;
	}
	
	// Restituisci tutte le spedizioni dell'ordine
	public function getSpedizioniOrdine($idO, $idR = 0)
	{
		$sWhereIdR = "";
		$sWhereArray = array((int)$idO);
		
		if ($idR)
		{
			$sWhereIdR .= " and righe.id_r = ?";
			$sWhereArray[] = (int)$idR;
		}
		
		if (App::$isFrontend)
		{
			$sWhereIdR .= " and spedizioni_negozio.stato != ?";
			$sWhereArray[] = "A";
		}
		
		return $this->clear()->select("*")->inner(array("spedizioniere"))->sWhere(array("id_spedizione_negozio in (select id_spedizione_negozio from spedizioni_negozio_righe inner join righe on righe.id_r = spedizioni_negozio_righe.id_r where righe.id_o = ? $sWhereIdR)",$sWhereArray))->send();
	}
	
	// Restituisce la label della spedizione con il link
	public function badgeSpedizione($idO = 0, $idR = 0, $full = true, $divisorio = '<hr style="margin-bottom:10px !important; margin-top:10px !important; "/>', $badgeClass = "label label-default")
	{
		$spedizioni = $this->getSpedizioniOrdine($idO, $idR);
		
		$arrayBadge = [];
		
		$checkAccesso = ControllersModel::checkAccessoAlController(array("spedizioninegozio"));
		
		foreach ($spedizioni as $sp)
		{
			$html = "<p>";
			
			if ($checkAccesso && $full && !App::$isFrontend)
				$html .= '<a href="'.Url::getRoot()."spedizioninegozio/form/update/".$sp["spedizioni_negozio"]["id_spedizione_negozio"].'" target="_blank" class="pull-right label label-primary text-bold">'.gtext("dettagli").' <i class="fa fa-arrow-right"></i></a>';
			
			if (!App::$isFrontend)
				$html .= '<a href="'.Url::getRoot()."spedizioninegozio/form/update/".$sp["spedizioni_negozio"]["id_spedizione_negozio"].'" target="_blank">';
			
			$idLabel = !App::$isFrontend ? $sp["spedizioni_negozio"]["id_spedizione_negozio"].' - ' : "";
			$titoloStato = !App::$isFrontend ? $this->getTitoloStato($sp["spedizioni_negozio"]["stato"]) : $this->getTitoloStatoFrontend($sp["spedizioni_negozio"]["stato"]);
			$labelData = !App::$isFrontend ? 'Data' : 'Data spedizione';
			
			$html .= '<b title="'.gtext("Spedizione allo stato: ".$titoloStato).'" style="'.$this->getStile($sp["spedizioni_negozio"]["stato"]).'" class="'.$badgeClass.'"><i class="fa fa-truck"></i> '.$idLabel.$titoloStato.'</b>';
			
			if (!App::$isFrontend)
				$html .= '</a>';
			
			if (!App::$isFrontend && $sp["spedizioni_negozio"]["errore_invio"])
				$html .= '<br /><span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> '.gtext("Errore invio").'</span>';
			
			$html .= '<br />'.gtext($labelData).': <b>'.smartDate($sp["spedizioni_negozio"]["data_spedizione"]).'</b>';
			
			if ($full)
				$html .= '<br />'.gtext("Spedizioniere").': <b>'.$sp["spedizionieri"]["titolo"].'</b>';
			
			$labelSpedizioniere = App::$isFrontend ? $sp["spedizioni_negozio"]["label_spedizioniere_frontend"] : $sp["spedizioni_negozio"]["label_spedizioniere"];
			
			if ($labelSpedizioniere)
				$html .= '<br /><i style="font-size:12px;">'.$labelSpedizioniere.'</i>';
			
			if (App::$isFrontend && !in_array($sp["spedizioni_negozio"]["stato"], self::statiSpedizioniNonInviate()))
			{
				$modulo = SpedizioninegozioModel::getModulo((int)$sp["spedizioni_negozio"]["id_spedizione_negozio"]);
				
				if ($modulo)
				{
					$html .= '<br /><a target="_blank" href="'.$modulo->getUrlTracking((int)$sp["spedizioni_negozio"]["id_spedizione_negozio"]).'">'.gtext("Vai al tracking").'</a>';
					
					if (date("Y-m-d", strtotime($sp["spedizioni_negozio"]["data_invio"])) == date("Y-m-d"))
						$html .= '<br />(<i style="font-size:12px;">'.gtext("Il tracking della spedizione potrebbe essere disponibile da domani")."</i>)";
				}
			}
				
			
			$html .= "</p>";
			
			$arrayBadge[] = $html;
		}
		
		return implode($divisorio, $arrayBadge);
	}
	
	public function deletable($id)
	{
		if (self::getStato($id) != "A")
			return false;
		
		return true;
	}
	
	public function cleanDateTimeSpedizione($record)
    {
		$formato = "d-m-Y";
		
		if (isset($record[$this->_tables]["data_spedizione"]) && $record[$this->_tables]["data_spedizione"])
			return date($formato,strtotime($record[$this->_tables]["data_spedizione"]));
		
		return "";
    }
    
    public function getStile($stato)
    {
		return SpedizioninegoziostatiModel::getCampoG($stato, "style");
    }
    
    public function getTitoloStato($stato)
    {
		return SpedizioninegoziostatiModel::getCampoG($stato, "titolo");
    }
    
    public function getTitoloStatoFrontend($stato)
    {
		return SpedizioninegoziostatiModel::getCampoG($stato, "titolo_frontend");
    }
    
    public function statoCrud($record)
    {
		$html = "<span style='".$this->getStile($record["spedizioni_negozio"]["stato"])."' class='label label-default'>".$this->getTitoloStato($record["spedizioni_negozio"]["stato"])."</span>";
		
		if ($record["spedizioni_negozio"]["errore_invio"])
			$html .= '<br /><span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> '.gtext("Errore invio").'</span>';
		
		return $html;
    }
    
    // Setta le condizioni totali sia per il salvataggio che per l'invio
    public function setUpdateConditions($idSpedizione = 0)
    {
		$campoObbligatorioProvincia = "dprovincia";
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
			$campoObbligatorioProvincia = "provincia";
		
		$campiObbligatori = "nazione,$campoObbligatorioProvincia,indirizzo,cap,citta,ragione_sociale";
		
		$this->addStrongCondition("update",'checkNotEmpty',$campiObbligatori);
		
		$this->addSoftCondition("update",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>").'<div style="display:none;" rel="hidden_alert_notice">email</div>');
		
		if ($idSpedizione)
		{
			$record = $this->clear()->selectId((int)$idSpedizione);
			
			if (!empty($record) && $record["id_spedizioniere"])
			{
				SpedizionieriModel::getModulo((int)$record["id_spedizioniere"])->setConditions($this);
			}
		}
    }
    
    // Restituisce il modulo spedizioniere
    public static function getModulo($idSpedizione)
    {
		$record = self::g(false)->clear()->select("id_spedizioniere")->whereId((int)$idSpedizione)->record();
		
		// Aggiungo i campi dello spedizioniere
		if (!empty($record) && $record["id_spedizioniere"])
			return SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true);
		
		return null;
    }
    
    // Restituisce i campi del form legati al modulo
    public static function getCampiModulo($idSpedizione)
    {
		$modulo = self::getModulo($idSpedizione);
		
		if ($modulo && $modulo->isAttivo())
			return $modulo->gCampiSpedizione();
		
		return [];
    }
    
     // Restituisce i campi indirizzo del form legati al modulo
    public static function getCampiIndirizzoModulo($idSpedizione)
    {
		$modulo = self::getModulo($idSpedizione);
		
		if ($modulo && $modulo->isAttivo())
			return $modulo->gCampiIndirizzo();
		
		return [];
    }
    
    public function getCampiFormUpdate($daDisabilitare = false, $idSpedizione = 0)
    {
		$fields =  "id_spedizioniere,id_spedizioniere_lettera_vettura,nazione,provincia,dprovincia,indirizzo,cap,citta,telefono,email,ragione_sociale,contrassegno";
		
		if (self::legataAdOrdineOLista($idSpedizione))
			$fields .= ",note";
		
// 		if (!$daDisabilitare)
			$fields .= ",note_interne";
		
		$campiSpedizione = self::getCampiModulo($idSpedizione);
		
		if (!empty($campiSpedizione))
			$fields .= ",".implode(",",$campiSpedizione);
		
		$campiIndirizzoSpedizione = self::getCampiIndirizzoModulo($idSpedizione);
		
		if (!empty($campiIndirizzoSpedizione))
			$fields .= ",".implode(",",$campiIndirizzoSpedizione);
		
		return $fields;
    }
    
    public static function getStato($idS)
    {
		return SpedizioninegozioModel::g(false)->whereId((int)$idS)->field("stato");
    }
    
    public static function aperto($idS)
	{
		$stato = self::getStato($idS);
		
		return $stato == "A" ? true : false;
	}
	
	public static function pronta($idS)
	{
		$stato = self::getStato($idS);
		
		return $stato == "I" ? true : false;
	}
	
	public function apri($id, $forza = false)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record) && (SpedizioninegozioModel::pronta((int)$id) || $forza))
		{
			if (SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true)->eliminaSpedizione((int)$id, $this) || $forza)
			{
				$data = new Data_Spedizioni_Result();
				$values = $data->toArray();
				$values["id_spedizione_negozio_invio"] = 0;
				
				$this->settaStato($id, "A", "", $values);
				
				return true;
			}
		}
		
		return false;
	}
	
	// Annulla la spedizione (stato = E)
	public function annulla($id)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record) && in_array($record["stato"], self::statiSpedizioniAnnullabili()))
		{
			$this->settaStato($id, "E");
			
			return true;
		}
		
		return false;
	}
	
	// Invia la spedizione $id al corriere
	public function prontaDaInviare($id)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record) && $record["id_spedizioniere"])
		{
			if (SpedizioninegozioModel::aperto((int)$id))
			{
				$_POST["updateAction"] = 1;
				Params::$arrayToValidate = htmlentitydecodeDeep($record);
				$_POST["nazione"] = Params::$arrayToValidate["nazione"];
				
				$this->setUpdateConditions((int)$id);
				
				$stato = "I";
				
				$this->setValues(htmlentitydecodeDeep($record));
				
				if ($this->checkConditions('update', (int)$id))
				{
					$this->setValues(array());
					
					if ($this->checkColli([$id]))
					{
						if ($record["id_spedizioniere_lettera_vettura"])
						{
							$this->settaStato($id, "I", "data_pronta_invio");
						}
						else
						{
							// Modulo spedizioniere
							$output = SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true)->prenotaSpedizione($id, $this);
							
							if ($output !== false)
							{
								if ($output->instradato())
									$this->settaStato($id, "I", "data_pronta_invio", $output->toArray());
								else
									$this->settaStato($id, "A", "", $output->toArray());
								
								return true;
							}
						}
					}
					else
						$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione, inserire almeno un collo di peso maggiore di 0 kg. Controllare inoltre che nessun collo abbia peso 0kg")."</div>";
				}
			}
		}
		
		return false;
	}
	
	// Invia al corriere la singola spedizione
	public function inviaAlCorriere($id, $idInvio)
	{
		$record = $this->clear()->selectId((int)$id);
		
		if (!empty($record) && $record["id_spedizioniere"])
		{
			if (SpedizioninegozioModel::pronta((int)$id))
			{
				$idsSpedizioniDaConfermare = array($id);
				
				// Modulo spedizioniere
				$risultati =  SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true)->confermaSpedizioni($idsSpedizioniDaConfermare, $idInvio);
				
				$modulo = SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true);
				
				foreach ($idsSpedizioniDaConfermare as $idSpedizione)
				{
					if (!$risultati[$idSpedizione]->getErrore() || $modulo->impostaConfermatoAncheSeErrore())
						SpedizioninegozioModel::g(false)->settaStato($idSpedizione, "II", "data_invio", $risultati[$idSpedizione]->toArray(false));
					else
						SpedizioninegozioModel::g(false)->settaStato($idSpedizione, "I", "data_pronta_invio", $risultati[$idSpedizione]->toArray(false));
				}
			}
		}
	}
	
	public function getSpedizioniInviate($idS = 0, $giorni = 20)
	{
		$ora = new DateTime();
		$ora->modify("-$giorni days");
		
		$this->clear()->where(array(
			"in"	=>	array(
				"stato"	=>	self::statiSpedizioniInviate()
			),
			"gte"	=>	array(
				"data_invio"	=>	sanitizeAll($ora->format("Y-m-d H:i:s")),
			),
		));
		
		if ($idS)
			$this->aWhere(array(
				"id_spedizione_negozio"	=>	(int)$idS
			));
		
		return $this->send(false);
	}
	
	// Imposta lo stato della spedizione
	// $values: valori da salvare
	public function settaStato($id, $stato, $campoData = "", $values = array())
	{
		$this->sValues(array(
			"stato"			=>	$stato,
		));
		
		if ($campoData)
			$this->setValue($campoData, date("Y-m-d H:i:s"));
		
		if ($stato == "I")
			$this->setValue("data_spedizione", date("Y-m-d"));
		
		foreach ($values as $k => $v)
		{
			$this->setValue($k, $v);
		}
		
		if ($this->update((int)$id))
		{
			SpedizioninegozioeventiModel::g()->inserisci((int)$id, $stato);
			
			// Setta lo stato dell'ordine
			$this->settaStatoOrdini($id, $stato);
		}
	}
	
	// Setta lo stato degli ordini legati alla spedizione
	public function settaStatoOrdini($idSpedizione, $statoSpedizione = "I")
	{
		if (!in_array($statoSpedizione, array("I","II")))
			return;
		
		$idsOrdini = $this->getOrdini($idSpedizione, true);
		
		$condizione = ($statoSpedizione == "I") ? "in_spedizione" : "spedito";
		
		$statoOrdine = $this->getStatoInSpedizione($condizione);
		
		if (!$statoOrdine)
			return;
		
		$oModel = new OrdiniModel();
		
		foreach ($idsOrdini as $idOrdine)
		{
			$ordine = $oModel->selectId((int)$idOrdine);
			
			if (empty($ordine))
				continue;
			
			$procedi = true;
			
			if ($statoSpedizione == "I")
			{
				$statiDaSpedire = StatiordineModel::getStatiDaSpedire();
				
				if (!in_array($ordine["stato"], $statiDaSpedire))
					$procedi = false;
			}
			else if ($statoSpedizione == "II")
			{
				$statoOrdineInSpedizione = $this->getStatoInSpedizione("in_spedizione");
				
				if (
					!$statoOrdineInSpedizione || 
					$ordine["stato"] != $statoOrdineInSpedizione || 
					count(OrdiniModel::righeDaSpedire((int)$idOrdine)) > 0 || 
					count(OrdiniModel::righeInSpedizione((int)$idOrdine)) > 0
				)
					$procedi = false;
			}
			
			if ($procedi && $ordine["stato"] != $statoOrdine)
			{
				$oModel->sValues(array(
					"stato"	=>	$statoOrdine,
				));
				
				$oModel->update((int)$idOrdine);
			}
		}
	}
	
	public function getStatoInSpedizione($condizione = "in_spedizione")
	{
		$soModel = new StatiordineModel();
		
		return $soModel->clear()->where(array(
			"$condizione"	=>	1,
		))->field("codice");
	}
	
	// Imposta la spedizione come consegnata
	public function settaConsegnata($id)
	{
		$modulo = self::getModulo((int)$id);
		
		$dataConsegna = "";
		
		if (isset($modulo))
			$dataConsegna = $modulo->getDataConsegna($id);
		
		$values = array();
		
		if ($dataConsegna)
			$values = array(
				"data_consegna"	=>	$dataConsegna,
			);
		
		$this->settaStato($id, "C", "", $values);
	}
	
	// Imposta la spedizione come in errore
	public function settaInErrore($id)
	{
// 		$this->settaStato($id, "E");
	}
	
	// Restituisce tutte le spedizioni da inviare, con data corrente o precedente
	// se $soloIds == true restituisce solo gli ID delle spedizioni
	// se $idS != 0, restituisce solo la spedizione avente id = $idS
	public function getSpedizioniDaInviare($idSpedizioniere, $soloIds = false, $idS = 0)
	{
		$this->clear()->where(array(
			"in"	=>	array(
				"stato"	=>	array("I"),
			),
			"id_spedizioniere"	=>	(int)$idSpedizioniere,
			"id_spedizioniere_lettera_vettura"	=>	0,
		))->sWhere("(id_spedizione_negozio_invio = 0 OR id_spedizione_negozio_invio in (select id_spedizione_negozio_invio from spedizioni_negozio_invii where spedizioni_negozio_invii.stato = 'A'))");
		
		if ($idS)
			$this->aWhere(array(
				"id_spedizione_negozio"	=>	(int)$idS
			));
		
		$conTabella = false;
		
		if ($soloIds)
		{
			$this->select($this->_idFields)->toList($this->_idFields);
			
			$conTabella = true;
		}
		
		return $this->send($conTabella);
	}
	
	// Controlla le spedizioni incviate negli ultimi $giorni
	// $idS indica una spedizione specifica da controllare
	// $elaboraSpedizione = 0 -> controllo solo lo stao interrogando il corriere. $elaboraSpedizione = 1, imposta consegnata o in errore se il corriere dice che è stata, rispettivamente, consegnata o messa in errore
	public function controllaStatoSpedizioniInviate($idS = 0, $giorni = 20, $elaboraSpedizione = 1)
	{
		$inviate = $this->getSpedizioniInviate($idS, $giorni);
		
		foreach ($inviate as $sp)
		{
			if ($sp["id_spedizioniere"])
			{
				// Modulo spedizioniere
				$modulo = SpedizionieriModel::getModulo((int)$sp["id_spedizioniere"], true);
				
				// Recupero le informazioni dal server del corriere
				$modulo->getInfo($sp["id_spedizione_negozio"]);
				
				// Attendi 200 millisecondi
				usleep(200000);
				
				if ($elaboraSpedizione)
				{
					if ($modulo->consegnata($sp["id_spedizione_negozio"])) // Se consegnata
						$this->settaConsegnata($sp["id_spedizione_negozio"]);
					else if ($modulo->inErrore($sp["id_spedizione_negozio"])) // Se in errore
						$this->settaInErrore($sp["id_spedizione_negozio"]);
				}
			}
		}
	}
	
	// Se la spedizione è legata ad un ordine o ad una lista
	public static function legataAdOrdineOLista($idSpedizione)
	{
		$record = self::g(false)->selectId((int)$idSpedizione);
		
		if (!empty($record) && ($record["id_ordine_di_partenza"] || $record["id_lista_regalo"]))
			return true;
		
		return false;
	}
	
	// Usata in main, stessa usata dell'ordine
	public function listaregalo($record)
	{
		return OrdiniModel::g(false)->listaregalo($record, "spedizioni_negozio");
	}
	
	// Restituisce il peso totale della spedizione guardando le righe inserite
	// array $idS
	public function pesoRighe($idS)
	{
		$snrModel = new SpedizioninegoziorigheModel();
		
		$res = $snrModel->clear()->select("sum(peso * quantity) as PESO_TOTALE")->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($idS),
			),
		))->send();
		
		if (count($res) > 0 && $res[0]["aggregate"]["PESO_TOTALE"])
			return $res[0]["aggregate"]["PESO_TOTALE"];
		
		return 0;
	}
	
	// Restituisce il peso totale della spedizione
	// array $idS
	public function peso($idS)
	{
		$sncModel = new SpedizioninegoziocolliModel();
		
		$res = $sncModel->clear()->select("sum(peso) as PESO_TOTALE")->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($idS),
			),
		))->send();
		
		if (count($res) > 0 && $res[0]["aggregate"]["PESO_TOTALE"])
			return $res[0]["aggregate"]["PESO_TOTALE"];
		
		return 0;
	}
	
	// Restituisce i colli legati alla spedizione
	// array $idS
	public function getColli($idS, $soloNumero = false)
	{
		$sncModel = new SpedizioninegoziocolliModel();
		
		$sncModel->clear()->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($idS),
			),
		));
		
		return $soloNumero ? $sncModel->rowNumber() : $sncModel->send(false);
	}
	
	// Controlla che ci sia almeno un collo e che ogni collo abbia peso maggiore di zero
	public function checkColli($idS)
	{
		$colli = $this->getColli($idS);
		
		if (count($colli) <= 0)
			return false;
		
		foreach ($colli as $collo)
		{
			if ($collo["peso"] <= 0)
				return false;
		}
		
		return true;
	}
	
	// Stampa o genera il segnacollo della spedizione
	// $returnPath se impostato su 1 restituisce il PDF del path del PDF
	public function segnacollo($idS, $returnPath = false)
	{
		$record = $this->clear()->selectId((int)$idS);
		
		if (!empty($record) && !SpedizioninegozioModel::aperto((int)$idS))
			return SpedizioninegozioModel::getModulo($idS)->segnacollo($idS, $returnPath);
		
		return "";
	}
	
	// Stampa il segnacollo in formato Zpl della spedizione
	public function zpl($idS)
	{
		$record = $this->clear()->selectId((int)$idS);
		
		if (!empty($record) && !SpedizioninegozioModel::aperto((int)$idS))
			return SpedizioninegozioModel::getModulo($idS)->zpl($idS);
		
		return "";
	}
	
	// Stampa la lettera di vettura
	public function letteradivettura($idS)
	{
		$res = $this->clear()->select("*")->inner(array("lettera", "spedizioniere"))->whereId((int)$idS)->first();
		
		if (!empty($res) && !SpedizioninegozioModel::aperto((int)$idS) && $res["spedizioni_negozio"]["id_spedizioniere_lettera_vettura"])
		{
			$spedizione = htmlentitydecodeDeep($res["spedizioni_negozio"]);
			$lettera = $res["spedizionieri_lettere_vettura"];
			$spedizioniere = htmlentitydecodeDeep($res["spedizionieri"]);
			
			$pathLettera = Domain::$parentRoot . "/images/letterevettura/" . $lettera["filename"];
			
			if (file_exists($pathLettera))
			{
				$spnrModel = new SpedizioninegoziorigheModel();
				
				$estensione = Files_Upload::sFileExtension($lettera["filename"]);
				
				createFolderFull("Logs/tmp",LIBRARY);
				
				$fileName = md5(randString(22).microtime().uniqid(mt_rand(),true));
				
				if (!@is_dir(LIBRARY."/Logs/tmp/"))
					return;
				
				$outputFile = LIBRARY."/Logs/tmp/$fileName.$estensione";
				
				$codici = $spnrModel->getCodiciProdottiSpedizione([(int)$idS]);
				
				$naturaArray = [];
				
				foreach ($codici as $codice => $qta)
				{
					$naturaArray[] =  $codice."x".$qta;
				}
				
				$placeholders = array(
					"ragione_sociale"	=>	$spedizione["ragione_sociale"],
					"ragione_sociale_2"	=>	$spedizione["ragione_sociale_2"],
					"indirizzo"			=>	$spedizione["indirizzo"],
					"cap"			=>	$spedizione["cap"]."     ",
					"citta"			=>	$spedizione["citta"],
					"provincia"		=>	$spedizione["provincia"],
					"nazione"		=>	NazioniModel::g(false)->findTitoloDaCodice($spedizione["nazione"]),
					"cod_naz"		=>	$spedizione["nazione"],
					"telefono"			=>	$spedizione["telefono"],
					"peso"			=>	number_format($this->peso([(int)$idS]),1,",",""),
					"colli"			=>	(string)$this->getColli([(int)$idS], true),
					"natura_merce"	=>	count($naturaArray) > 0 ? implode(" + ", $naturaArray) : "",
					"contrassegno"	=>	$spedizione["contrassegno"] > 0 ? number_format($spedizione["contrassegno"],2,",","") : "",
					"modalita_incasso"	=>	$spedizione["contrassegno"] > 0 ? SpedizionieriModel::getModulo((int)$spedizione["id_spedizioniere"], true)->gLabelCodicePagamento($spedizione["codice_pagamento_contrassegno"]) : "",
					"importo_assicurazione"	=>	$spedizione["importo_assicurazione"] > 0 ? number_format($spedizione["importo_assicurazione"],2,",","") : "",
					"note"			=>	$spedizione["note_interne"],
					"data"			=>	date("d/m/Y", strtotime($spedizione["data_spedizione"])),
					"p"				=>	$spedizione["tipo_servizio"] == "E" ? "1" : "",
					"s"				=>	$spedizione["tipo_servizio"] == "H" ? "1" : "",
				);
				
// 				print_r($placeholders);die();
				
				if (SpedizionieriletterevetturaModel::getModulo((int)$spedizione["id_spedizioniere_lettera_vettura"])->salva($pathLettera, $outputFile, $placeholders))
					return array($outputFile, "lettera_di_vettura_".$spedizioniere["titolo"]."_spedizione_".(int)$idS."_del_".date("d_m_Y",strtotime($spedizione["data_spedizione"])).".$estensione");
			}
		}
		
		return null;
	}
	
	public static function getNumeroSpedizione($idS)
	{
		return SpedizioninegozioModel::g(false)->whereId((int)$idS)->field("numero_spedizione");
	}
	
	public static function getElencoServizi($id)
	{
		$modulo = self::getModulo((int)$id);
		
		if ($modulo)
			return $modulo->gSelectServizi();
		
		return array();
	}
	
	public function idLetteraDiVettura($id)
	{
		return (int)$this->clear()->inner(array("lettera"))->whereId((int)$id)->field("spedizioni_negozio.id_spedizioniere_lettera_vettura");
	}
	
	// Restituisxce il campo struttura_info_tracking della spedizione $idSpedizione
	public function getInfoTracking($idSpedizione)
	{
		$spedizione = $this->selectId((int)$idSpedizione);
		
		if (!empty($spedizione) && $spedizione["struttura_info_tracking"])
			return $spedizione["struttura_info_tracking"];
		
		return "";
	}
}
