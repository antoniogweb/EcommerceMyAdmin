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
			return parent::insert();
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
			return parent::update($id, $where);
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
		return "<span class='text-bold label label-".OrdiniacquistostatiModel::getCampo($record["ordini_acquisto"]["id_ordine_acquisto_stato"], "classe")."'>".OrdiniacquistostatiModel::getCampo($record["ordini_acquisto"]["id_ordine_acquisto_stato"], "titolo")."<span>";
	}
	
	public function isBozza($idOrdineAcquisto)
	{
		$record = $this->clear()->select("id_ordine_acquisto,id_ordine_acquisto_stato")->whereId((int)$idOrdineAcquisto)->record();
		
		if (!empty($record) && OrdiniacquistostatiModel::g()->bozza((int)$record["id_ordine_acquisto_stato"]))
			return true;
		
		return false;
	}
	
	public function imponibile($idOrdine)
	{
		$oarModel = new OrdiniacquistorigheModel();
		
		$righe = $oarModel->clear()->where(array(
			"id_ordine_acquisto"	=>	(int)$idOrdine,
		))->send(false);
		
		$imponibile = 0;
		
		foreach ($righe as $riga)
		{
			$imponibile += OrdiniacquistorigheModel::subtotale($riga);
		}
		
		return $imponibile;
	}
	
	public function iva($idOrdine)
	{
		$oarModel = new OrdiniacquistorigheModel();
		
		$righe = $oarModel->clear()->where(array(
			"id_ordine_acquisto"	=>	(int)$idOrdine,
		))->send(false);
		
		$arrayAliquote = array();
		
		foreach ($righe as $riga)
		{
			$imponibile = OrdiniacquistorigheModel::subtotale($riga);
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
		$imponibile = $this->imponibile($idOrdine);
		$iva = $this->iva($idOrdine);
		$totale = $imponibile + $iva;
		
		$this->sValues(array(
			"imponibile"	=>	$imponibile,
			"iva"			=>	$iva,
			"totale"		=>	$totale,
		));
		
		$this->update((int)$idOrdine);
	}
}
