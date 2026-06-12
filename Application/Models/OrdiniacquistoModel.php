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
			'fornitore' => array("BELONGS_TO", 'FornitoriModel', 'id_fornitore',null,"RESTRICT","Si prega di selezionare un fornitore".'<div style="display:none;" rel="hidden_alert_notice">id_fornitore</div>'),
			'stato' => array("BELONGS_TO", 'OrdiniacquistostatiModel', 'id_ordine_acquisto_stato',null,"RESTRICT","Si prega di selezionare uno stato".'<div style="display:none;" rel="hidden_alert_notice">id_ordine_acquisto_stato</div>'),
			'pdf' => array("BELONGS_TO", 'OrdiniacquistopdfModel', 'id_ordine_acquisto',null,"CASCADE"),
			'righe' => array("HAS_MANY", 'OrdiniacquistorigheModel', 'id_ordine_acquisto', null, "CASCADE"),
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
		
		$nRighe = $this->rowNumber();
		
		if ($nRighe > 0)
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
}
