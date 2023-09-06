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

class SpedizioninegozioeventiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_eventi';
		$this->_idFields='id_spedizione_negozio_evento';
		
		parent::__construct();
	}
	
	// Insertisci un nuovo evento con uno stato uguale a $stato
	// Controlla che lo stato I non sia già presente nella spedizione avente ID = $idSpedizione
	public function inserisci($idSpedizione, $stato = "I")
	{
		$titolo = SpedizioninegoziostatiModel::getCampoG($stato, "titolo");
		
		$spedizione = SpedizioninegozioModel::g()->selectId((int)$idSpedizione);
		
		if (isset($titolo) && !empty($spedizione))
		{
			// Verifico che non sia già stato inviato
			if ($stato == "I")
			{
				if ($this->clear()->where(array(
					"id_spedizione_negozio"	=>	(int)$idSpedizione,
					"codice"	=>	"I",
				))->rowNumber())
					$stato = "II";
			}
			
			$this->sValues(array(
				"id_spedizione_negozio"	=>	(int)$idSpedizione,
				"titolo"				=>	$titolo,
				"codice"				=>	$stato,
				"email"					=>	htmlentitydecode($spedizione["email"]),
				"lingua"				=>	$spedizione["lingua"],
				"nazione"				=>	$spedizione["nazione"],
				"creation_time"			=>	time(),
			));
			
			$res = $this->insert();
			
			if ($res)
				$this->processaEventiSpedizione($this->lId);
			
			return $res;
		}
		
		return false;
	}
	
	public function processaEventiSpedizione($idSpedizioneEvento)
	{
		$record = $this->selectId((int)$idSpedizioneEvento);
		
		if (!empty($record) && isset($record["email"]) && $record["email"] && checkMail($record["email"]) && $record["codice"] != "II" && $record["codice"] != "A")
			EventiretargetingModel::processaSpedizione($idSpedizioneEvento);
	}
	
	public function titoloCrud($record)
	{
		$stile = SpedizioninegozioModel::g()->getStile($record["spedizioni_negozio_eventi"]["codice"]);
		$titoloStato = SpedizioninegozioModel::g()->getTitoloStato($record["spedizioni_negozio_eventi"]["codice"]);
		
		return '<span style="'.$stile.'" class="label label-default">'.$titoloStato.'</span>';
	}
	
	public function emailCrud($record)
	{
		if ($record["eventi_retargeting_elemento"]["email"])
			return gtext("Notifica inviata all'indirizzo email")." <b>".$record["eventi_retargeting_elemento"]["email"]."</b> ".gtext("in data")." <b>".date("d-m-Y H:i", strtotime($record["eventi_retargeting_elemento"]["data_creazione"]))."</b>";
		
		return "";
	}
	
	// Metodo per segnaposto
	public function getNominativoInOrdineOCliente($lingua, $record)
	{
		if (!isset($record["id_spedizione_negozio"]))
			return "";
		
		$spedizione = SpedizioninegozioModel::g()->selectId((int)$record["id_spedizione_negozio"]);
		
		if (!empty($spedizione))
			return $spedizione["ragione_sociale"]." ".$spedizione["ragione_sociale_2"];
	}
	
	// Metodo per segnaposto
	public function getRiferimentoOrdine($lingua, $record)
	{
		if (!isset($record["id_spedizione_negozio"]))
			return "";
		
		$idsO = SpedizioninegozioModel::g()->getOrdini((int)$record["id_spedizione_negozio"]);
		
		return "#".implode(", #", $idsO);
	}
	
	// Metodo per segnaposto
	public function gLinkOrdine($lingua, $record)
	{
		if (!isset($record["id_spedizione_negozio"]))
			return "";
		
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		$ordini = SpedizioninegozioModel::g()->getOrdini((int)$record["id_spedizione_negozio"], false);
		
		$htmlArray = [];
		
		foreach ($ordini as $ordine)
		{
			$ordine = $ordine["orders"];
			
			$htmlArray[] =	'<a href="'.Domain::$publicUrl.$linguaUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."?n=y".'">#'.$ordine["id_o"].'</a>';
		}
		
		return implode(", ", $htmlArray);
	}
	
	public function gElencoProdottiPerFeedback($lingua, $record)
	{
		if (!isset($record["id_spedizione_negozio"]))
			return "";
		
		$r = new RigheModel();
		
		$righeOrdine = $r->clear()->sWhere(array(
			"id_r in (select id_r from spedizioni_negozio_righe where id_spedizione_negozio = ?)",
			array((int)$record["id_spedizione_negozio"])
		))->send();
		
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		ob_start();
		include tpf("/Elementi/Placeholder/elenco_prodotti_per_feedback.php");
		$output = ob_get_clean();
		
		return $output;
	}
}
