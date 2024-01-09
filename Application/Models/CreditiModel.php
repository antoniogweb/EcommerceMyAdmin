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
	public function __construct() {
		$this->_tables = 'crediti';
		$this->_idFields = 'id_crediti';
		
		parent::__construct();
	}
	
	public static function gNumeroEuroRimasti($id_user)
	{
		if (!$id_user)
			return 0;
		
		$c = new CreditiModel();
		
		$res = $c->clear()->where(array(
			"id_user"	=>	$id_user,
			"attivo"	=>	1,
			"gte"		=>	array(
				"data_scadenza"	=>	date("Y-m-d")
			)
		))->getSum("numero_crediti");
		
		if ($res !== false)
			return number_format((int)$res * (float)v("moltiplicatore_credito"),2,".","");
		
		return 0;
	}
	
// 	public static function getSconto($id_user)
// 	{
// 		$euroCrediti = self::gNumeroEuroRimasti($id_user);
// 		$totaleProdottiSpedizionePagamento = 
// 		
// 		if ($euroCrediti >)
// 	}
	
	// Crea la promo dalla riga ordine
	public function aggiungiDaRigaOrdine($idR)
	{
		$rModel = new RigheModel();
		
		$riga = $rModel->selectId((int)$idR);
		$oModel = new OrdiniModel();
		
		if (empty($riga))
			return;
		
		$attivo = OrdiniModel::isPagato($riga["id_o"]) ? 1 : 0;
		
		$crediti = $this->clear()->where(array(
			"id_r"	=>	(int)$idR,
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
				$ora->modify("+12 months");
				$data_scadenza = $ora->format("Y-m-d");
				$ora->modify("-30 days");
				$data_invio_avviso = $ora->format("Y-m-d");
				
				$this->sValues(array(
					"id_user"				=>	$ordine["id_user"],
					"attivo"				=>	$attivo,
					"azione"				=>	"C", // Carica
					"numero_crediti"		=>	($riga["numero_crediti"] * $riga["quantity"]),
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
}
