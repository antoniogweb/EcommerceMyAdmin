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
    
    // Restituisce l'ID dell'ultimo invio dello spedizioniere
    // se non lo trova lo crea se $crea == true altrimenti restituisce 0
    // $dataInvio, la data con la quale cercarlo o crearlo, se vuota usa la data corrente 
    public function getIdUltimoInvioSpedizioniere($idSpedizioniere, $crea = false, $dataInvio = null)
    {
		$modulo = SpedizionieriModel::getModulo((int)$idSpedizioniere, true);
		
		if (!$modulo->isAttivo())
			return 0;
		
		if (!isset($dataInvio))
			$dataInvio = date("Y-m-d");
		
		$idInvio = $this->clear()->select("id_spedizione_negozio_invio")->where(array(
			"data_spedizione"	=>	sanitizeAll($dataInvio),
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
	
	// collega le spedizioni [array di int] $idS ad (int) $idInvio
	public function collegaSpedizioni($idInvio, $idS)
	{
		if (!$idInvio)
			return;
		
		$spnModel = new SpedizioninegozioModel();
		
		foreach ($idS as $id)
		{
			$spnModel->sValues(array(
				"id_spedizione_negozio_invio"	=>	(int)$idInvio
			));
			
			$spnModel->pUpdate((int)$id);
		}
	}
	
	// Prenota l'invio e collega tutte le spedizioni pronte
    public function prenota()
    {
		$arrayIdSpedizionieri = SpedizionieriModel::g()->where(array(
			"attivo"	=>	1,
		))->toList("id_spedizioniere")->send();
		
		$spnModel = new SpedizioninegozioModel();
		
		foreach ($arrayIdSpedizionieri as $idSpedizioniere)
		{
			$spedizioniDaInviare = $spnModel->getSpedizioniDaInviare($idSpedizioniere, true);
			
			if (count($spedizioniDaInviare) > 0)
			{
				$idInvio = $this->getIdUltimoInvioSpedizioniere($idSpedizioniere, true);
				
				$this->collegaSpedizioni($idInvio, $spedizioniDaInviare);
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
}
