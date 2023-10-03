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

class SpedizioninegozioinviiModel extends GenericModel {
	
	public static $dateTime = null;
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_invii';
		$this->_idFields='id_spedizione_negozio_invio';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'spedizioniere' => array("BELONGS_TO", 'SpedizionieriModel', 'id_spedizioniere',null,"CASCADE"),
        );
    }
    
    public function spedizioniCrud($record)
    {
		return "<a class='iframe' href='".Url::getRoot()."spedizioninegozio/main?id_spedizione_negozio_invio=".(int)$record["spedizioni_negozio_invii"]["id_spedizione_negozio_invio"]."&partial=Y&nobuttons=Y'><i class='fa fa-list'></i></a>";
    }
    
    public function reportCrud($record)
    {	
		if ($record["spedizioni_negozio_invii"]["stato"] == "C" && SpedizionieriModel::getModulo((int)$record["spedizioni_negozio_invii"]["id_spedizioniere"], true)->metodo("reportPdf"))
			return "<a target='_blank' href='".Url::getRoot()."spedizioninegozioinvii/reportpdf/".(int)$record["spedizioni_negozio_invii"]["id_spedizione_negozio_invio"]."'><i class='fa fa-download'></i></a>";
		
		return "";
    }
    
    // Restituisce l'ID dell'ultimo invio dello spedizioniere
    // se non lo trova lo crea se $crea == true altrimenti restituisce 0
    // $dataInvio, la data con la quale cercarlo o crearlo, se vuota usa la data corrente 
    public function getIdUltimoInvioSpedizioniere($idSpedizioniere, $crea = false, $dataInvio = null)
    {
		if (!isset($dataInvio))
			$dataInvio = date("Y-m-d");
		
		$idInvio = $this->clear()->select("id_spedizione_negozio_invio")->where(array(
			"data_spedizione"	=>	sanitizeAll($dataInvio),
			"stato"				=>	"A",
			"id_spedizioniere"	=>	(int)$idSpedizioniere,
		))->field("id_spedizione_negozio_invio");
		
		if (!$idInvio && $crea)
		{
			$this->sValues(array(
				"data_spedizione"	=>	$dataInvio,
				"id_spedizioniere"	=>	(int)$idSpedizioniere,
			));
			
			if ($this->insert())
				return $this->lId;
		}
		
		return (int)$idInvio;
    }
	
	// collega le spedizioni all'invio $idInvio
	// restituisce un array con gli ID delle spedizioni da inviare
	public function collegaSpedizioni($idInvio)
	{
		$record = $this->selectId((int)$idInvio);
		
		if (empty($record))
			return array();
		
		$spedizioniDaInviare = SpedizioninegozioModel::g(false)->getSpedizioniDaInviare($record["id_spedizioniere"], true);
		
		if (count($spedizioniDaInviare) > 0)
		{
			$spnModel = new SpedizioninegozioModel();
			
			foreach ($spedizioniDaInviare as $id)
			{
				$spnModel->sValues(array(
					"id_spedizione_negozio_invio"	=>	(int)$idInvio
				));
				
				$spnModel->pUpdate((int)$id);
			}
		}
		
		return $spedizioniDaInviare;
	}
	
	// Prenota l'invio e collega tutte le spedizioni pronte
    public function prenota()
    {
		$arrayIdSpedizionieri = SpedizionieriModel::g()->where(array(
			"attivo"	=>	1,
		))->toList("id_spedizioniere")->send();
		
		foreach ($arrayIdSpedizionieri as $idSpedizioniere)
		{
			$spedizioniDaInviare = SpedizioninegozioModel::g(false)->getSpedizioniDaInviare($idSpedizioniere, true);
			
			if (count($spedizioniDaInviare) > 0 && SpedizionieriModel::getModulo((int)$idSpedizioniere, true)->spedizioniConfermabili($spedizioniDaInviare))
			{
				$idInvio = $this->getIdUltimoInvioSpedizioniere($idSpedizioniere, true);
				
				if ($idInvio)
					$this->collegaSpedizioni($idInvio);
			}
		}
    }
    
    public function statoCrud($record)
    {
		if ($record["spedizioni_negozio_invii"]["stato"] == "C")
			return '<span class="label label-success"><i class="fa fa-thumbs-up"></i> '.gtext("Elaborato").'</span>';
		else if ($record["spedizioni_negozio_invii"]["stato"] == "A")
			return '<span class="label label-default"><i class="fa fa-clock-o"></i> '.gtext("In coda di elaborazione").'</span>';
		
		return "";
    }
    
    public function deletable($id)
    {
		if (self::g(false)->whereId((int)$id)->field("stato") != "A")
			return false;
		
		return true;
    }
    
    public function del($id = null, $where = null)
    {
		// Scollega le spedizioni
		if ($id && !$where)
			SpedizioninegozioModel::g(false)->query(array(
				"update spedizioni_negozio set id_spedizione_negozio_invio = 0 where id_spedizione_negozio_invio = ?",
				array(
					(int)$id,
				)
			));
		
		return parent::del($id, $where);
	}
	
	// Restituisce un array con tutti gli ID degli invii in coda
	// Se $id != 0, cerca quell'invio specifico
	public function getInviiInCoda($id = 0)
	{
		$this->clear()->where(array(
			"data_spedizione"	=>	date("Y-m-d"),
			"stato"				=>	"A",
		));
		
		if ($id)
			$this->aWhere(array(
				"id_spedizione_negozio_invio"	=>	(int)$id,
			));
		
		return $this->send(false);
	}
	
	// Conferma con il corriere le spedizioni legate ad un invio
	public function inviaAlCorriere($idInvio = 0)
	{
		$invii = $this->getInviiInCoda($idInvio);
		
		foreach ($invii as $invio)
		{
			$idInvio = (int)$invio["id_spedizione_negozio_invio"];
			$idSpedizioniere = (int)$invio["id_spedizioniere"];
			
			$idsSpedizioniDaConfermare = $this->collegaSpedizioni($idInvio);
			
			$modulo = SpedizionieriModel::getModulo((int)$idSpedizioniere, true);
			
			if ($modulo && $modulo->isAttivo() && $modulo->metodo("confermaSpedizioni") && count($idsSpedizioniDaConfermare) > 0)
			{
				$risultati = $modulo->confermaSpedizioni($idsSpedizioniDaConfermare, $idInvio);
				
				foreach ($idsSpedizioniDaConfermare as $idSpedizione)
				{
					if (!$risultati[$idSpedizione]->getErrore())
						SpedizioninegozioModel::g(false)->settaStato($idSpedizione, "II", "data_invio", $risultati[$idSpedizione]->toArray(false));
					else
						SpedizioninegozioModel::g(false)->settaStato($idSpedizione, "I", "data_pronta_invio", $risultati[$idSpedizione]->toArray(false));
				}
				
				$this->sValues(array(
					"stato"	=>	"C",
					"data_elaborazione"	=>	date("Y-m-d H:i:s"),
				));
				
				$this->update((int)$idInvio);
			}
		}
	}
	
	// Stampa il pdf del borderò dell'invio $id
	public function reportPdf($id = 0)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			SpedizionieriModel::getModulo((int)$record["id_spedizioniere"], true)->reportPdf((int)$id);
		
		return;
	}
	
	public function getSpedizioniInvio($id = 0)
	{
		$spnModel = new SpedizioninegozioModel();
		
		return $spnModel->where(array(
			"id_spedizione_negozio_invio"	=>	(int)$id,
		))->orderBy("id_spedizione_negozio desc")->send();
	}
}
