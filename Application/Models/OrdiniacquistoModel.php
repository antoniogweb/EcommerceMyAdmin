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

class OrdiniacquistoModel extends GenericModel
{
	use AcquistoModel;
	
	public $campoTitolo = "ragione_sociale";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public static $idRigheDaRicevere = array();
	
	public $urlOrdineAcquistoRicezioni = "ordiniacquistoricezioni";
	
	public function __construct() {
		$this->_tables = 'ordini_acquisto';
		$this->_idFields = 'id_ordine_acquisto';
		
		$this->_idOrder='id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"ragione_sociale");
		$this->addSoftCondition("both",'checkMail',"email,email_amministrativa,pec,email_referente");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe' => array("HAS_MANY", 'OrdiniacquistorigheModel', 'id_ordine_acquisto', null, "RESTRICT", "L'elemento ha delle righe collegate e non può essere eliminato"),
			'fornitore' => array("BELONGS_TO", 'FornitoriModel', 'id_fornitore',null,"RESTRICT","Si prega di selezionare un fornitore".'<div style="display:none;" rel="hidden_alert_notice">id_fornitore</div>'),
			'stato' => array("BELONGS_TO", 'OrdiniacquistostatiModel', 'id_ordine_acquisto_stato',null,"RESTRICT","Si prega di selezionare uno stato".'<div style="display:none;" rel="hidden_alert_notice">id_ordine_acquisto_stato</div>'),
			'pdf' => array("BELONGS_TO", 'OrdiniacquistopdfModel', 'id_ordine_acquisto',null,"CASCADE"),
		);
    }
    
    public function setFormStruct($id = 0)
	{
		parent::setFormStruct($id);
		
		$this->formStruct["entries"]["id_fornitore"] = array(
			"labelString"	=>	'Fornitore',
			"type"	=>	"Select",
			"options"	=>	array(0	=>	gtext("Seleziona")) + FornitoriModel::g()->filtroFornitore(),
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
		);
		
		$this->formStruct["entries"]["id_ordine_acquisto_stato"] = array(
			"labelString"	=>	'Stato ordine',
			"type"	=>	"Select",
			"options"	=>	OrdiniacquistostatiModel::g()->selectStati(),
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
		);
	}
	
	public function insert()
	{
		if ($this->checkNumero($this->values["numero_ordine"] ?? 0))
		{
			$res =  parent::insert();
			
			OrdiniacquistostatistoricoModel::g()->aggiungi($this->lId, $this->values["id_ordine_acquisto_stato"] ?? OrdiniacquistostatiModel::getIdStatoPending());
			
			return $res;
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione il numero dell'ordine è già esistente nell'anno impostato.")."</div>".'<div style="display:none;" rel="hidden_alert_notice">numero_ordine</div>';
			$this->result = false;
			return false;
		}
	}
	
	public function update($id = null, $where = null)
	{
		if ($this->checkNumero($this->values["numero_ordine"] ?? 0, (int)$id))
		{
			$res = parent::update($id, $where);
			
			if (isset($this->values["id_ordine_acquisto_stato"]))
				OrdiniacquistostatistoricoModel::g()->aggiungi($id, $this->values["id_ordine_acquisto_stato"]);
			
			return $res;
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione il numero dell'ordine è già esistente nell'anno impostato.")."</div>".'<div style="display:none;" rel="hidden_alert_notice">numero_ordine</div>';
			$this->result = false;
			return false;
		}
	}
	
	public function getNumero()
	{
		$documento = $this->clear()->select("max(numero_ordine) as numero")->sWhere(array(
			"DATE_FORMAT(data_ordine, '%Y') = ?",
			array(date("Y"))
		))->send();
		
		if (empty($documento))
			return 1;
		else
			return ((int)$documento[0]["aggregate"]["numero"] + 1);
	}
	
	public function checkNumero($numero, $idOrdine = 0)
	{
		$dataModel = (isset($this->values["data_ordine"]) && checkIsoDate(getIsoDate($this->values["data_ordine"]))) ? new DateTime(getIsoDate($this->values["data_ordine"])) : new DateTime();
		
		$this->clear()->where(array(
			"numero_ordine"	=>	(int)$numero,
		))->sWhere(array(
			"DATE_FORMAT(data_ordine, '%Y') = ?",
			array($dataModel->format("Y"))
		));
		
		if ($idOrdine)
			$this->aWhere(array(
				"ne"	=>	array(
					"id_ordine_acquisto"	=>	(int)$idOrdine,
				)
			));
		
		$nOrdini = $this->rowNumber();
		
		if ($nOrdini > 0)
			return false;
		
		return true;
	}
	
	public function statoordinelabel($record)
	{
		return "<span class='text-bold label label-".OrdiniacquistostatiModel::getCampo($record["ordini_acquisto"]["id_ordine_acquisto_stato"], "classe")."'>".OrdiniacquistostatiModel::getCampo($record["ordini_acquisto"]["id_ordine_acquisto_stato"], "titolo")."</span>";
	}
	
	public function isBozza($idOrdineAcquisto)
	{
		$record = $this->clear()->select("id_ordine_acquisto,id_ordine_acquisto_stato")->whereId((int)$idOrdineAcquisto)->record();
		
		// Controllo le ricezioni
		if ($this->haRicezioni($idOrdineAcquisto))
			return false;
		
		// Controllo lo stato
		if (!empty($record) && OrdiniacquistostatiModel::g()->bozza((int)$record["id_ordine_acquisto_stato"]))
			return true;
		
		return false;
	}
	
	public function getRighe($idOrdine, $diTestata = false, $fields = "ordini_acquisto_righe.*")
	{
		$oarModel = new OrdiniacquistorigheModel();
		
		$oarModel->clear()->select($fields)->left(array("tipologia"))->where(array(
			"id_ordine_acquisto"	=>	(int)$idOrdine,
		));
		
		if ($diTestata)
			$oarModel->sWhere("ordini_acquisto_righe_tipologie.moltiplicatore < 0");
		else
			$oarModel->sWhere("(ordini_acquisto_righe_tipologie.moltiplicatore IS NULL or ordini_acquisto_righe_tipologie.moltiplicatore >= 0)");
		
		return $oarModel->orderBy("ordini_acquisto_righe.id_order")->send(false);
	}
	
	public function infoOrdine($idOrdine)
	{
		$ordine = $this->selectId($idOrdine);
		
		if (empty($ordine))
			return array();
		
		$struttura = array(
			"testata"	=>	$this->selectId($idOrdine),
			"righe"		=>	$this->getRighe($idOrdine, false),
			"sconti"	=>	$this->getRighe($idOrdine, true, "ordini_acquisto_righe.sconto_1 as sconto"),
		);
		
		return $struttura;
	}
	
	public function imponibile($idOrdine, $pieno = false)
	{
		$righe = $this->getRighe($idOrdine, false);
		$righeTestata = $pieno ? array() : $this->getRighe($idOrdine, true);
		
		$imponibile = 0;
		
		foreach ($righe as $riga)
		{
			$imponibile += OrdiniacquistorigheModel::subtotale($riga, $righeTestata, true);
		}
		
		return $imponibile;
	}
	
	public function iva($idOrdine)
	{
		$righe = $this->getRighe($idOrdine, false);
		$righeTestata = $this->getRighe($idOrdine, true);
		
		$arrayAliquote = array();
		
		foreach ($righe as $riga)
		{
			$imponibile = OrdiniacquistorigheModel::subtotale($riga, $righeTestata);
			$aliquota = number_format($riga["aliquota_iva"],2,".","");
			
			if (isset($arrayAliquote[$aliquota]))
				$arrayAliquote[$aliquota] += $imponibile;
			else
				$arrayAliquote[$aliquota] = $imponibile;
		}
		
		$iva = 0;
		
		foreach ($arrayAliquote as $aliquota => $imponibile)
		{
			$iva += number_format($imponibile * ($aliquota / 100), 2, ".", "");
		}
		
		return $iva;
	}
	
	public function aggiornaTotali($idOrdine)
	{
		$imponibilePieno = $this->imponibile($idOrdine, true);
		$imponibile = $this->imponibile($idOrdine);
		$iva = $this->iva($idOrdine);
		$totale = $imponibile + $iva;
		
		$this->sValues(array(
			"imponibile_pieno"	=>	$imponibilePieno,
			"imponibile"	=>	$imponibile,
			"iva"			=>	$iva,
			"totale"		=>	$totale,
		));
		
		$this->update((int)$idOrdine);
	}
	
	public function deletable($idOrdine)
	{
		if (!$this->isBozza($idOrdine))
			return false;
		
		$numeroRighe = OrdiniacquistorigheModel::g(false)->where(array(
			"id_ordine_acquisto"	=>	(int)$idOrdine
		))->rowNumber();
		
		if ($numeroRighe > 0)
			return false;
		
		return true;
	}
	
	public function numeroDaCollegareCrud($record)
	{
		$numero = OrdiniacquistorigheModel::numeroNonCollegate((int)$record["ordini_acquisto"]["id_ordine_acquisto"]);
		
		if ($numero)
			return '<span class="label label-warning">'.$numero.'</span>';
		
		return "";
	}
	
	public static function getChiusiWhereClause()
	{
		return array(
			"ordini_acquisto_stati.chiuso"		=>	1,
			"ordini_acquisto_stati.annullato"	=>	0
		);
	}
	
	public static function righeDaRicevereClause($idO = 0)
	{
		$rModel = new OrdiniacquistorigheModel();
		
		$rModel->clear()
			->inner(array("ordine"))
			->inner("ordini_acquisto_stati")->on("ordini_acquisto_stati.id_ordine_acquisto_stato = ordini_acquisto.id_ordine_acquisto_stato")
			->left("(select id_ordine_acquisto_ricezione_riga,id_ordine_acquisto_riga,quantita from ordini_acquisto_ricezioni_righe) as rr")->on("rr.id_ordine_acquisto_riga = ordini_acquisto_righe.id_ordine_acquisto_riga")
			->aWhere(self::getChiusiWhereClause())
			->sWhere("ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia = 0 and ordini_acquisto_righe.quantita > 0")
			->groupBy("ordini_acquisto_righe.id_ordine_acquisto_riga HAVING (ordini_acquisto_righe.quantita > sum(rr.quantita) or rr.id_ordine_acquisto_ricezione_riga IS NULL)");
		
		if ($idO)
			$rModel->aWhere(array(
				"ordini_acquisto_righe.id_ordine_acquisto"	=>	(int)$idO,
			));
		
		return $rModel;
	}
	
	public static function idRigheDaRicevere($idO = 0)
	{
		if (isset(self::$idRigheDaRicevere[$idO]))
			return self::$idRigheDaRicevere[$idO];
		
		self::$idRigheDaRicevere[$idO] = self::righeDaRicevereClause($idO)->select("ordini_acquisto_righe.id_ordine_acquisto_riga,ordini_acquisto_righe.quantita,rr.quantita,rr.id_ordine_acquisto_ricezione_riga,ordini_acquisto_righe.id_ordine_acquisto")->toList("ordini_acquisto_righe.id_ordine_acquisto_riga")->send();
		
		// echo $righeModel->getQuery();
		
		return self::$idRigheDaRicevere[$idO];
	}
	
	// Restituisce true o false se ha o non ha almeno una riga ricevuta
	public function haRicezioni($idOrdineAcquisto)
	{
		// Controllo le ricezioni
		$oarr = new OrdiniacquistoricezionirigheModel();
		
		$numero = $oarr->clear()->sWhere(array(
			"EXISTS ( select 1 from ordini_acquisto_righe where ordini_acquisto_righe.id_ordine_acquisto_riga = ordini_acquisto_ricezioni_righe.id_ordine_acquisto_riga and ordini_acquisto_righe.id_ordine_acquisto = ? )",
			array((int)$idOrdineAcquisto),
		))->rowNumber();
		
		if ($numero)
			return true;
		
		return false;
	}
	
	// Restituisce true o false se ha o non ha righe da ricevere
	// $idO: ID ordine di acquisto
	public static function haRigheDaRicevere($idO)
	{
		$idRigheDaRicevere = self::idRigheDaRicevere((int)$idO);
		
		return count($idRigheDaRicevere) > 0 ? true : false;
	}
	
	// Restituisce gli ordini con almeno una riga da ricevere
	public static function idOrdiniDaRicevere()
	{
		$idRigheDaRicevere = self::righeDaRicevereClause()->select("ordini_acquisto_righe.id_ordine_acquisto_riga,ordini_acquisto_righe.quantita,rr.quantita,rr.id_ordine_acquisto_ricezione_riga,ordini_acquisto_righe.id_ordine_acquisto")->toList("ordini_acquisto_righe.id_ordine_acquisto")->send();
		
		if (count($idRigheDaRicevere) > 0)
			return array_unique($idRigheDaRicevere);
		
		return array();
	}
	
	// Restituisce la tendina per la selezione degli ordini con almeno una riga da ricevere
	public static function ordiniDaRicevere()
	{
		$idOrdiniDaRicevere = self::idOrdiniDaRicevere();
		
		if (count($idOrdiniDaRicevere) > 0)
		{
			return self::g()->clear()->where(array(
				"in"	=>	array(
					"id_ordine_acquisto"	=>	forceIntDeep($idOrdiniDaRicevere)
				)
			))->send(false);
		}
		
		return array();
	}
	
	public static function ordiniDaRicevereSelect()
	{
		$ordini = self::ordiniDaRicevere();
		
		$tendina = array();
		
		foreach ($ordini as $o)
		{
			$tendina[$o["id_ordine_acquisto"]] = "Ordine N° ".$o["numero_ordine"]." ".gtext("del")." ".smartDate($o["data_ordine"],v("default_date_format"))." - ".gtext("Fornitore").": ".$o["ragione_sociale"];
		}
		
		return $tendina;
	}
	
	// Aggiungi le righe dell'ordine alla ricezione definita in $_GET["id_ordine_acquisto_ricezione"]
	// $idO : ID ordine acquisto
	public function aggiungiaricezione($idO)
	{
		$record = $this->selectId((int)$idO);
		
		if (!empty($record) && isset($_GET["id_ordine_acquisto_ricezione"]))
		{
			$idSRighe = self::idRigheDaRicevere((int)$idO);
			
			foreach ($idSRighe as $idRiga)
			{
				$oarModel = new OrdiniacquistorigheModel();
				
				$oarModel->aggiungiaricezione($idRiga);
			}
		}
    }
    
    public function ricezioniCollegate($idOrdine)
    {
		$oarrModel = new OrdiniacquistoricezionirigheModel();
		
		return $oarrModel->clear()
			->select("distinct ordini_acquisto_ricezioni.id_ordine_acquisto_ricezione,ordini_acquisto_ricezioni.data_ricezione_merce,ordini_acquisto_ricezioni.numero_documento_trasporto")
			->inner(array("riga"))
			->inner(array("ricezione"))->where(array(
				"ordini_acquisto_righe.id_ordine_acquisto"	=>	(int)$idOrdine,
			))->send();
	}
	
    public function ricezioniCrud($record)
	{
		$ricezioni = $this->ricezioniCollegate((int)$record["ordini_acquisto"]["id_ordine_acquisto"]);
			
		$htmlArray = array();
		
		foreach ($ricezioni as $r)
		{
			$idRicezione = (int)$r["ordini_acquisto_ricezioni"]["id_ordine_acquisto_ricezione"];
			
			$htmlArray[] = "<a target='_blank' href='".Url::getRoot().$this->urlOrdineAcquistoRicezioni."/righe/$idRicezione'><b>".$idRicezione."</b></a> ".gtext("del")." <b>".smartDate($r["ordini_acquisto_ricezioni"]["data_ricezione_merce"], v("default_date_format"))."</b>";
		}
		
		return implode("<br />", $htmlArray);
	}
}
