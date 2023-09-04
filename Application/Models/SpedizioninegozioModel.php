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

class SpedizioninegozioModel extends FormModel {
	
	const TIPOLOGIA_PORTO_FRANCO = 'PORTO_FRANCO';
	const TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO = 'PORTO_FRANCO_CONTRASSEGNO';
	
	public function __construct() {
		$this->_tables='spedizioni_negozio';
		$this->_idFields='id_spedizione_negozio';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe' => array("HAS_MANY", 'SpedizioninegoziorigheModel', 'id_spedizione_negozio', null, "CASCADE"),
			'spedizioniere' => array("BELONGS_TO", 'SpedizionieriModel', 'id_spedizioniere',null,"RESTRICT","Si prega di selezionare lo spedizioniere".'<div style="display:none;" rel="hidden_alert_notice">id_spedizioniere</div>'),
		);
    }
	
	public function update($id = null, $where = null)
	{
		$this->setProvinciaFatturazione();
		
		$res = parent::update($id, $where);
		
		return $res;
	}
	
	public function insert()
	{
		if (isset($_GET["id_o"]))
		{
			$ordine = OrdiniModel::g(false)->whereId((int)$_GET["id_o"])->record();
			
			if (!empty($ordine))
			{
				$this->setValue("id_user", $ordine["id_user"]);
				$this->setValue("id_spedizione", $ordine["id_spedizione"]);
				$this->setValue("ragione_sociale", OrdiniModel::getNominativo($ordine), "sanitizeDb");
				$this->setValue("ragione_sociale_2", $ordine["destinatario_spedizione"], "sanitizeDb");
				$this->setValue("indirizzo", $ordine["indirizzo"], "sanitizeDb");
				$this->setValue("cap", $ordine["cap"], "sanitizeDb");
				$this->setValue("citta", $ordine["citta"], "sanitizeDb");
				$this->setValue("provincia", $ordine["provincia"], "sanitizeDb");
				$this->setValue("dprovincia", $ordine["dprovincia"], "sanitizeDb");
				$this->setValue("nazione", $ordine["nazione"], "sanitizeDb");
				$this->setValue("telefono", $ordine["telefono"], "sanitizeDb");
				$this->setValue("email", $ordine["email"], "sanitizeDb");
				$this->setValue("note", $ordine["note"], "sanitizeDb");
				$this->setValue("note_interne", $ordine["note_interne"], "sanitizeDb");
				
				$tipologia = ($ordine["pagamento"] == "contrassegno") ? self::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO : self::TIPOLOGIA_PORTO_FRANCO;
				
				$this->setValue("tipologia", $tipologia);
			}
		}
		
		$this->setProvinciaFatturazione();
		
		$res = parent::insert();
		
		if ($res && isset($ordine))
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
		
		return $res;
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
		$idsO = $this->getOrdini($record["spedizioni_negozio"]["id_spedizione_negozio"]);
		
		return "#".implode(", #", $idsO);
	}
	
	// Restituisce gli ordini legati ad una spedizione
	public function getOrdini($idS)
	{
		return $this->clear()
			->select("righe.id_o")
			->left(array("righe"))
			->left("righe")->on("righe.id_r = spedizioni_negozio_righe.id_r")
			->where(array(
				"id_spedizione_negozio"	=>	(int)$idS,
			))
			->groupBy("righe.id_o")
			->toList("righe.id_o")
			->send();
	}
	
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
		
		foreach ($idOs as $idO)
		{
			if (!$idO)
				continue;
			
			$arrayRighe = $this->getSelectFromIdO($arrayRighe, (int)$idO);
		}
		
		// Cerco gli ordini con lo stesso id_spedizione
		$idSpedizione = $this->clear()->whereId((int)$idS)->field("id_spedizione");
		
		if ($idSpedizione)
		{
			$idOsSped = OrdiniModel::g(false)->where(array(
				"nin"	=>	array(
					"id_o"	=>	forceIntDeep($idOs),
				),
				"id_spedizione"	=>	(int)$idSpedizione
			))->toList("id_o")->send();
			
			foreach ($idOsSped as $idO)
			{
				$arrayRighe = $this->getSelectFromIdO($arrayRighe, (int)$idO);
			}
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
		
		return $this->clear()->select("*")->inner(array("spedizioniere"))->sWhere(array("id_spedizione_negozio in (select id_spedizione_negozio from spedizioni_negozio_righe inner join righe on righe.id_r = spedizioni_negozio_righe.id_r where righe.id_o = ? $sWhereIdR)",$sWhereArray))->send();
	}
	
	// Restituisce la label della spedizione con il link
	public function badgeSpedizione($idO = 0, $idR = 0, $full = true, $divisorio = '<hr style="margin-bottom:10px !important; margin-top:10px !important; "/>')
	{
		$spedizioni = $this->getSpedizioniOrdine($idO, $idR);
		
		$arrayBadge = [];
		
		$checkAccesso = ControllersModel::checkAccessoAlController(array("spedizioninegozio"));
		
		foreach ($spedizioni as $sp)
		{
			$html = "<p>";
			
			if ($checkAccesso && $full)
				$html .= '<a href="'.Url::getRoot()."spedizioninegozio/form/update/".$sp["spedizioni_negozio"]["id_spedizione_negozio"].'" target="_blank" class="pull-right label label-primary text-bold">'.gtext("dettagli").' <i class="fa fa-arrow-right"></i></a>';
			
			$html .= '<a href="'.Url::getRoot()."spedizioninegozio/form/update/".$sp["spedizioni_negozio"]["id_spedizione_negozio"].'" target="_blank"><b style="'.$this->getStile($sp["spedizioni_negozio"]["stato"]).'" class="label label-default"><i class="fa fa-truck"></i> '.$sp["spedizioni_negozio"]["id_spedizione_negozio"].'</b></a> del <b>'.smartDate($sp["spedizioni_negozio"]["data_spedizione"]).'</b>';
			
			if ($full)
				$html .= '<br />'.gtext("Spedizioniere").': <b>'.$sp["spedizionieri"]["titolo"].'</b>';
			
			$html .= "</p>";
			
			$arrayBadge[] = $html;
		}
		
		return implode($divisorio, $arrayBadge);
	}
	
	public function deletable($id)
	{
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
    
    public function statoCrud($record)
    {
		return "<span style='".$this->getStile($record["spedizioni_negozio"]["stato"])."' class='label label-default'>".$this->getTitoloStato($record["spedizioni_negozio"]["stato"])."</span>";
    }
    
    // Setta le condizioni totali sia per il salvataggio che per l'invio
    public function setUpdateConditions($idSpedizione = 0)
    {
		$campiObbligatori = "data_spedizione,id_spedizioniere,nazione,provincia,dprovincia,indirizzo,cap,citta,ragione_sociale";
		
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
}
