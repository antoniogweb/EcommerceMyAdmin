<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class OrdiniperiodiresoModel extends GenericModel
{
	public $campoTitolo = "data_inizio";
	
	public function __construct()
	{
		$this->_tables = 'orders_periodi_reso';
		$this->_idFields = 'id_o_periodo_reso';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'data_inizio'	=>	array(
					'labelString'=>	'Data consegna della merce',
					'className'	=>	'for_print form-control date_input',
				),
				'data_fine'	=>	array(
					'labelString'=>	'Data fine periodo di reso',
					'className'	=>	'for_print form-control date_input',
				),
				'data_richiesta'	=>	array(
					'labelString'=>	'Data / ora richiesta reso',
					'className'	=>	'for_print form-control',
				),
			),
		);
	}
	
	public function getUrlRichiediReso($id, $urlCompleto = true)
	{
		$pr = $this->selectId((int)$id);
		
		if (!empty($pr))
		{
			$ordine = OrdiniModel::g(false)->clear()->select("id_o,cart_uid,admin_token")->whereId((int)$pr["id_o"])->record();
			
			if (!empty($ordine))
			{
				$url = "reso-ordine/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]."/".((int)$id);;
				
				if ($urlCompleto)
					return Url::getRoot().$url;
				else
					return $url;
			}
		}
		
		return "";
	}
	
	public function getUrlRichiediResoLista($id, $urlCompleto = true)
	{
		$pr = $this->selectId((int)$id);
		
		if (!empty($pr))
		{
			$lista = ListeregaloModel::listeUtenteModel(User::$id, (int)$pr["id_lista_regalo"])->record();
			
			if (!empty($lista))
			{
				$url = "reso-lista/".$pr["id_lista_regalo"]."/".((int)$id);;
				
				if ($urlCompleto)
					return Url::getRoot().$url;
				else
					return $url;
			}
		}
		
		return "";
	}
	
	public function inPeriodoReso($id)
	{
		$pr = $this->selectId((int)$id);
		
		if (!empty($pr))
		{
			$inizio = new DateTime($pr["data_inizio"]);
			$fine = new DateTime($pr["data_fine"]);
			$oggi = new DateTime(date("Y-m-d"));
			
			if ($oggi >= $inizio && $oggi <= $fine)
				return true;
		}
		
		return false;
	}
	
	public function isResoInStatoPermesso($id)
	{
		$record = $this->clear()->select("orders_periodi_reso.*,orders.stato")->inner(array("ordine"))->whereId((int)$id)->first();
		
		if (!empty($record))
		{
			$statoOrdine = $record["orders"]["stato"];
			
			if (StatiordineModel::g(false)->permettiReso($statoOrdine))
				return true;
		}
		
		return false;
	}
	
	public function richiedi($id)
	{
		$record = $this->selectId((int)$id);
		
		if (empty($record))
			return false;
		
		$oModel = new OrdiniModel();
		$listaModel = new ListeregaloModel();
		
		$ordine = $oModel->selectId((int)$record["id_o"]);
		$lista = $listaModel->selectId((int)$record["id_lista_regalo"]);
		
		if (empty($ordine) && empty($lista))
			return false;
		
		$tipo = !empty($ordine) ? "O" : "L";
		
		$dataOraRichiesta = date("Y-m-d H:i:s");
		$dataOraRichiestaFormattata = smartDate($dataOraRichiesta, v("default_date_format")." H:i");
		
		$this->sValues(array(
			"richiesta"	=>	1,
			"ip"		=>	getIp(),
			"data_richiesta"	=>	$dataOraRichiesta,
		));
		
		$suffissoTemplate = $tipo == "O" ? "" : "_lista";
		
		if ($this->update($id))
		{
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	$tipo == "O" ? array($ordine["email"]) : array($lista["email"]),
				"oggetto"	=>	$tipo == "O" ? v("oggetto_mail_richiesta_reso") : v("oggetto_mail_richiesta_reso_lista"),
				"tipologia"	=>	"RESO",
				"id_o"	=>	$tipo == "O" ? (int)$ordine["id_o"] : 0,
				"oggetto_placeholder"	=>	$tipo == "O" ? "" : $lista["titolo"],
				"lingua"	=>	$tipo == "O" ? $ordine["lingua"] : $lista["lingua"],
				"testo_path"	=>	"Elementi/Mail/Resi/mail_al_cliente$suffissoTemplate.php",
				"tabella"		=>	"orders_periodi_reso",
				"id_elemento"	=>	(int)$id,
				"array_variabili_tema"	=>	array(
					"CLIENTE"	=>	$tipo == "O" ? OrdiniModel::getNominativo($ordine) : $lista["genitore_1"],
					"NUMERO_ORDINE"	=>	$ordine["id_o"] ?? 0,
					"NOME_NEGOZIO"	=>	Parametri::$nomeNegozio,
					"ID_RICHIESTA"	=>	(int)$id,
					"DATA_ORA_RICHIESTA"	=>	$dataOraRichiestaFormattata,
					"CODICE_LISTA"	=>	$lista["codice"] ?? "",
					"TITOLO_LISTA"	=>	$lista["titolo"] ?? "",
					"GENITORE_2"	=>	$lista["genitore_2"] ?? "",
				),
			));
			
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array(Parametri::$mailReso),
				"oggetto"	=>	$tipo == "O" ? v("oggetto_mail_richiesta_reso") : v("oggetto_mail_richiesta_reso_lista"),
				"tipologia"	=>	"RESO_NEGOZIO",
				"id_o"	=>	$tipo == "O" ? (int)$ordine["id_o"] : 0,
				"oggetto_placeholder"	=>	$tipo == "O" ? "" : $lista["titolo"],
				"lingua"	=>	v("default_backend_language"),
				"testo_path"	=>	"Elementi/Mail/Resi/mail_al_negozio$suffissoTemplate.php",
				"tabella"		=>	"orders_periodi_reso",
				"id_elemento"	=>	(int)$id,
				"array_variabili_tema"	=>	array(
					"CLIENTE"	=>	$tipo == "O" ? OrdiniModel::getNominativo($ordine) : $lista["genitore_1"],
					"NUMERO_ORDINE"	=>	$ordine["id_o"] ?? 0,
					"NOME_NEGOZIO"	=>	Parametri::$nomeNegozio,
					"ID_RICHIESTA"	=>	(int)$id,
					"DATA_ORA_RICHIESTA"	=>	$dataOraRichiestaFormattata,
					"CODICE_LISTA"	=>	$lista["codice"] ?? "",
					"TITOLO_LISTA"	=>	$lista["titolo"] ?? "",
					"ID_LISTA"		=>	$lista["id_lista_regalo"] ?? "",
					"GENITORE_2"	=>	$lista["genitore_2"] ?? "",
				),
			));
			
			return true;
		}
		
		return false;
	}
	
	public function setDataFine()
	{
		if (isset($this->values["data_inizio"]) && checkIsoDate(getIsoDate($this->values["data_inizio"])))
		{
			$dateTime = new DateTime(getIsoDate($this->values["data_inizio"]));
			$dateTime->modify("+".v("giorni_periodo_reso")." days");
			$this->values["data_fine"] = $dateTime->format("Y-m-d");
		}
	}
	
	public function update($id = null, $where = null)
	{
		$this->setDataFine();
		
		return parent::update($id, $where);
	}
	
	public function insert()
	{
		$this->setDataFine();
		
		return parent::insert();
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !$record["richiesta"])
			return true;
		
		return false;
	}
	
	public function dataInizioCrud($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."ordiniperiodireso/form/update/".$record["orders_periodi_reso"]["id_o_periodo_reso"]."?partial=Y&nobuttons=Y'>".$record["orders_periodi_reso"]["data_inizio"]."</a>";
	}
	
	public function richiestaCrud($record)
	{
		if ($record["orders_periodi_reso"]["richiesta"])
			return "<i class='text-danger fa fa-check'></i>";
		else
			return "<i class='fa fa-ban'></i>";
	}
}
