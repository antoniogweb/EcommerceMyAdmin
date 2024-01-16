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

class CreditiModel extends GenericModel
{
	public $campoTimeEventoRemarketing = "time_invio_avviso";
	
	public function __construct() {
		$this->_tables = 'crediti';
		$this->_idFields = 'id_crediti';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
    
    public function insert()
    {
		$this->setValue("creation_time", time());
		
		return parent::insert();
    }
    
    // Restituisce la categoria di un prodotto crediti
    public static function gIdCategory()
    {
		$p = new PagesModel();
		
		return (int)$p->clear()->addWhereAttivo()->aWhere(array(
			"prodotto_crediti"	=>	1
		))->limit(1)->field("id_c");
    }
    
	public static function gNumeroEuroRimasti($id_user, $forza = false)
	{
		if (!$id_user)
			return 0;
		
		if (CartModel::numeroProdottiCreditiInCarrello() > 0 && !$forza)
			return 0;
		
		$c = new CreditiModel();
		
		$res = $c->clear()->select("SUM(numero_crediti * moltiplicatore_credito) as CREDITI")->where(array(
			"id_user"	=>	(int)$id_user,
			"attivo"	=>	1,
			"gte"		=>	array(
				"data_scadenza"	=>	date("Y-m-d")
			)
		))->send();
		
		if (isset($res[0]["aggregate"]["CREDITI"]) && $res[0]["aggregate"]["CREDITI"] && $res[0]["aggregate"]["CREDITI"] > 0)
			return $res[0]["aggregate"]["CREDITI"];
		
		return 0;
	}
	
	public function getStoricoCrediti($idUser)
	{
		if (!$idUser)
			return 0;
		
		return $this->clear()->select("crediti.*,orders.id_o,orders.cart_uid,orders.stato")->left(array("ordine"))->where(array(
			"id_user"	=>	(int)$idUser,
		))->orderBy("data_creazione desc")->send();
	}
	
	// Restituisce l'ultimo pacchetto attivo di crediti
	public function ultimoPacchettoAttivoCrediti($idUser)
	{
		if (!$idUser)
			return 0;
		
		return $this->clear()->where(array(
			"id_user"	=>	(int)$idUser,
			"attivo"	=>	1,
			"azione"	=>	"C",
		))->orderBy("data_scadenza desc")->limit(1)->record();
	}
	
	public static function dataScadenzaCrediti($idUser)
	{
		$c = new CreditiModel();
		
		$record = $c->ultimoPacchettoAttivoCrediti($idUser);
		
		if (!empty($record))
			return $record["data_scadenza"];
		
		return date("Y-m-d");
	}
	
	// Aggiungi scarico crediti da ordine
	public function aggiungiScaricoDaOrdine($idO)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$oModel = new OrdiniModel();
		
		$ordine = $oModel->selectId((int)$idO);
		
		if (empty($ordine))
			return;
		
		$crediti = $this->clear()->where(array(
			"id_o"		=>	(int)$idO,
			"azione"	=>	"S",
		))->send(false);
		
		if (count($crediti) > 0)
		{
			$attivo = ($ordine["stato"] != "deleted") ? 1 : 0;
			
			foreach ($crediti as $c)
			{
				if ((int)$c["attivo"] !== (int)$attivo)
				{
					$this->sValues(array(
						"attivo"	=>	$attivo,
					));
					
					$this->update((int)$c["id_crediti"]);
				}
			}
		}
		else
		{
			$recordAttivo = $this->ultimoPacchettoAttivoCrediti($ordine["id_user"]);
			
			$moltiplicatore = $ordine["moltiplicatore_credito"] > 0 ? $ordine["moltiplicatore_credito"] : 1;
			
			$this->sValues(array(
				"id_user"				=>	$ordine["id_user"],
				"attivo"				=>	1,
				"azione"				=>	"S", // Carica
				"numero_crediti"		=>	number_format($ordine["euro_crediti"] / $moltiplicatore, 2, ".", ""),
				"moltiplicatore_credito"=>	(-1) * $moltiplicatore,
				"data_scadenza"			=>	!empty($recordAttivo) ? $recordAttivo["data_scadenza"] : date("Y-m-d"),
				"in_scadenza"			=>	0,
				"data_invio_avviso"		=>	!empty($recordAttivo) ? $recordAttivo["data_invio_avviso"] : date("Y-m-d"),
				"time_invio_avviso"		=>	!empty($recordAttivo) ? $recordAttivo["time_invio_avviso"] : strtotime(date("Y-m-d")),
				"email"					=>	$ordine["email"],
				"lingua"				=>	$ordine["lingua"],
				"nazione"				=>	$ordine["nazione_navigazione"],
				"id_r"					=>	0,
				"id_o"					=>	$ordine["id_o"],
				"fonte"					=>	"ACQUISTO",
			), "sanitizeDb");
			
			$this->insert();
		}
	}
	
	// Aggiungi i crediti da riga ordine
	public function aggiungiDaRigaOrdine($idR)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$rModel = new RigheModel();
		
		$riga = $rModel->selectId((int)$idR);
		$oModel = new OrdiniModel();
		
		if (empty($riga))
			return;
		
		$attivo = OrdiniModel::isPagato($riga["id_o"]) ? 1 : 0;
		
		$crediti = $this->clear()->where(array(
			"id_r"		=>	(int)$idR,
			"azione"	=>	"C",
		))->send(false);
		
		if (count($crediti) > 0)
		{
			foreach ($crediti as $c)
			{
				if ((int)$c["attivo"] !== (int)$attivo)
				{
					$this->sValues(array(
						"attivo"	=>	$attivo,
					));
					
					$this->update((int)$c["id_crediti"]);
				}
			}
		}
		else if ($attivo)
		{
			$ordine = $oModel->selectId((int)$riga["id_o"]);
			
			if (!empty($ordine))
			{
				$ora = new DateTime();
				$ora->modify("+".v("mesi_durata_crediti")." months");
				$data_scadenza = $ora->format("Y-m-d");
				$ora->modify("-30 days");
				$data_invio_avviso = $ora->format("Y-m-d");
				
				$this->sValues(array(
					"id_user"				=>	$ordine["id_user"],
					"attivo"				=>	$attivo,
					"azione"				=>	"C", // Carica
					"numero_crediti"		=>	($riga["numero_crediti"] * $riga["quantity"]),
					"moltiplicatore_credito"=>	v("moltiplicatore_credito"),
					"data_scadenza"			=>	$data_scadenza,
					"in_scadenza"			=>	1,
					"data_invio_avviso"		=>	$data_invio_avviso,
					"time_invio_avviso"		=>	strtotime($data_invio_avviso),
					"email"					=>	$ordine["email"],
					"lingua"				=>	$ordine["lingua"],
					"nazione"				=>	$ordine["nazione_navigazione"],
					"id_r"					=>	$idR,
					"id_o"					=>	$riga["id_o"],
					"fonte"					=>	"ORDINE",
				), "sanitizeDb");
				
				if ($this->insert())
					$this->sincronizzaDateScadenza($this->lId);
			}
		}
	}
	
	public function sincronizzaDateScadenza($id_crediti)
	{
		$record = $this->selectId((int)$id_crediti);
		
		if (!empty($record) && $record["id_user"])
		{
			$this->sValues(array(
				"data_scadenza"		=>	$record["data_scadenza"],
				"in_scadenza"		=>	0,
				"data_invio_avviso"	=>	$record["data_invio_avviso"],
				"time_invio_avviso"	=>	$record["time_invio_avviso"],
			));
			
			$this->pUpdate(null, array(
				"id_crediti != ? and data_scadenza >= ? and id_user = ?",
				array(
					(int)$id_crediti,
					sanitizeDb(date("Y-m-d")),
					(int)$record["id_user"]
				)
			));
		}
	}
	
	// Controlla se devo eliminare la riga con "in_scadenza = 1" per il cliente dell'ordine
	public function controllaInScadenza($idUser)
	{
		if (!$idUser)
			return 0;
		
		$numeroCrediti = self::gNumeroEuroRimasti($idUser, true);
		
		if ($numeroCrediti <= 0)
			$this->query(array(
				"update crediti set in_scadenza = 0 where id_user = ?",
				array(
					(int)$idUser
				)
			));
	}
	
	// Metodo per segnaposto
	public function gDataScadenza($lingua, $record)
	{
		if (!isset($record["data_scadenza"]))
			return "";
		
		return date("d-m-Y", strtotime($record["data_scadenza"]));
	}
	
	// Metodo per segnaposto
	public function getNominativoInOrdineOCliente($lingua, $record)
	{
		if (!isset($record["id_user"]))
			return "";
		
		$rModel = new RegusersModel();
		
		$user = $rModel->selectId((int)$record["id_user"]);
		
		if (!empty($user))
			return self::getNominativo($user);
		
		return "";
	}
}
