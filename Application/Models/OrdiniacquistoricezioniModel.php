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

class OrdiniacquistoricezioniModel extends GenericModel
{
	public $campoTitolo = "id_ordine_acquisto_ricezione";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public $urlOrdineAcquisto = "ordiniacquisto";
	
	public static $stati = array(
		"chiuso"	=>	array(
			"text"	=>	"Chiuso",
			"label"	=>	"success",
			"azione"=>	"Apri",
		),
		"aperto"	=>	array(
			"text"	=>	"Aperto",
			"label"	=>	"warning",
			"azione"=>	"Chiudi",
		),
	);
	
	public function __construct() {
		$this->_tables = 'ordini_acquisto_ricezioni';
		$this->_idFields = 'id_ordine_acquisto_ricezione';
		
		$this->addStrongCondition("both",'checkNotEmpty',"data_ricezione_merce");
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"admin/media/Ricezioni",
				"allowedExtensions"	=>	'pdf,png,jpg,jpeg,pdf',
				"maxFileSize"	=>	v("dimensioni_upload_ricezioni"),
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe' => array("HAS_MANY", 'OrdiniacquistoricezionirigheModel', 'id_ordine_acquisto_ricezione', null, "RESTRICT", "L'elemento ha delle righe collegate e non può essere eliminato"),
		);
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
    
    public function deletable($idRicezione)
    {
		if (!$this->editabile($idRicezione))
			return false;
		
		$numeroRighe = OrdiniacquistoricezionirigheModel::g(false)->where(array(
			"id_ordine_acquisto_ricezione"	=>	(int)$idRicezione
		))->rowNumber();
		
		if ($numeroRighe > 0)
			return false;
		
		return true;
	}
	
    public function editabile($idRicezione)
	{
		if ($this->chiuso($idRicezione))
			return false;
		
		if ($this->movimentato($idRicezione))
			return false;
		
		return true;
	}
    
    public function movimentato($idRicezione)
	{
		return false;
	}
    
    public function chiuso($idRicezione)
	{
		return (int)$this->clear()->whereId((int)$idRicezione)->field("chiuso");
	}
	
	public static function haRighe($idRicezione)
	{
		return OrdiniacquistoricezionirigheModel::g()->clear()->where(array(
			"id_ordine_acquisto_ricezione"	=>	(int)$idRicezione,
		))->rowNumber();
	}
	
	public function ordiniCollegati($idRicezione)
	{
		$oarrModel = new OrdiniacquistoricezionirigheModel();
		
		return $oarrModel->clear()
			->select("distinct ordini_acquisto.id_ordine_acquisto,ordini_acquisto.numero_ordine,ordini_acquisto.data_ordine")
			->inner(array("riga"))
			->inner("ordini_acquisto")->on("ordini_acquisto.id_ordine_acquisto = ordini_acquisto_righe.id_ordine_acquisto")
			->where(array(
				"ordini_acquisto_ricezioni_righe.id_ordine_acquisto_ricezione"	=>	(int)$idRicezione,
			))
			->orderBy("ordini_acquisto.numero_ordine")
			->send();
	}
	
	public function ordiniAcquistoCrud($record)
	{
		$ordiniAcquisto = $this->ordiniCollegati($record["ordini_acquisto_ricezioni"]["id_ordine_acquisto_ricezione"]);
		
		$htmlArray = array();
		
		foreach ($ordiniAcquisto as $r)
		{
			$idOrdine = (int)$r["ordini_acquisto"]["id_ordine_acquisto"];
			
			$htmlArray[] = "<a target='_blank' href='".Url::getRoot().$this->urlOrdineAcquisto."/form/update/$idOrdine'><b>".$idOrdine."</b></a> ".gtext("del")." <b>".smartDate($r["ordini_acquisto"]["data_ordine"], v("default_date_format"))."</b>";
		}
		
		return implode("<br />", $htmlArray);
	}
	
	// Imposta lo stato parzialmente ricevuto o ricevuto per gli ordini di acquisto collegati alla ricezione $idRicezione
	public function settaStatoRicevutoOrdiniCollegati($idRicezione)
	{
		$idStatoPazialmenteRicevuto = OrdiniacquistostatiModel::getIdStatoCondizione("parzialmente_ricevuto");
		$idStatoRicevuto = OrdiniacquistostatiModel::getIdStatoCondizione("ricevuto");
		
		$ordiniCollegati = $this->ordiniCollegati((int)$idRicezione);
		
		$oaModel = new OrdiniacquistoModel();
		
		OrdiniacquistostatistoricoModel::$segnaIdAdmin = false;
		
		foreach ($ordiniCollegati as $ordine)
		{
			$idOrdineAcquisto = (int)$ordine["ordini_acquisto"]["id_ordine_acquisto"];
			
			$haRicezioni = $oaModel->haRicezioni($idOrdineAcquisto);
			$haRigheDaRicevere = $oaModel->haRigheDaRicevere($idOrdineAcquisto);
			
			$idStatoFinale = 0;
			
			if ($haRicezioni && !$haRigheDaRicevere)
			{
				if ($idStatoRicevuto)
					$idStatoFinale = $idStatoRicevuto;
			}
			else if ($haRicezioni)
			{
				if ($idStatoPazialmenteRicevuto)
					$idStatoFinale = $idStatoPazialmenteRicevuto;
			}
			
			if ($idStatoFinale)
			{
				$oaModel->sValues(array(
					"id_ordine_acquisto_stato"	=>	(int)$idStatoFinale,
				));
				
				$oaModel->update($idOrdineAcquisto);
			}
		}
		
		OrdiniacquistostatistoricoModel::$segnaIdAdmin = true;
	}
	
	public function statoLabelCrud($record)
	{
		if ($record["ordini_acquisto_ricezioni"]["chiuso"])
			return "<span class='label label-".self::$stati["chiuso"]["label"]."'>".gtext(self::$stati["chiuso"]["text"])."</span>";
		else
			return "<span class='label label-".self::$stati["aperto"]["label"]."'>".gtext(self::$stati["aperto"]["text"])."</span>";
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
		
		return false;
	}
}
