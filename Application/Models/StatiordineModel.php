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

class StatiordineModel extends GenericModel {
	
	public static $labelStati = array(
		"default"	=>	"Grigio",
		"primary"	=>	"Blu",
		"success"	=>	"Verde",
		"danger"	=>	"Danger",
		"warning"	=>	"Warning",
		"purple"	=>	"Porpora",
		"info"		=>	"Azzurro",
		"maroon"	=>	"Marrone",
		"olive"		=>	"Oliva",
		"teal"		=>	"Verde acqua",
		"gallo"		=>	"Giallo",
		"fuchsia"	=>	"Fucsia",
		"azzurrino"	=>	"Azzurrino",
		"ciano"		=>	"Ciano",
		"nero"		=>	"Nero",
	);
	
	public function __construct() {
		$this->_tables='stati_ordine';
		$this->_idFields='id_stato_ordine';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_stato_ordine', null, "CASCADE"),
        );
    }
    
    public function edit($record)
	{
		return "<span class='text-bold label label-".$record[$this->_tables]["classe"]." data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attivo",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'pagato'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine pagato",
					"options"	=>	self::$attivoSiNo + array("-1"=>gtext("Neutro")),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'da_spedire'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine pronto per la spedizione",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se settato su sì, gli ordini che avranno questo stato potranno essere spediti.")."</div>"
					),
				),
				'in_spedizione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine in spedizione",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se settato su sì, alla prenotazione di una spedizone, gli ordini collegati verranno impostati a questo stato. Può esserci un solo stato ordine con il campo In spedizione impostato su sì.")."</div>"
					),
				),
				'spedito'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato corrispondente ad un ordine spedito",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se settato su sì, alla conferma di una spedizone, gli ordini collegati verranno impostati a questo stato. Può esserci un solo stato ordine con il campo Spedito impostato su sì.")."</div>"
					),
				),
				'classe'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Colore della label dello stato",
					"options"	=>	self::$labelStati,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'manda_mail_al_cambio_stato'	=>	array(
					"type"	=>	"Select",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'descrizione'	=>	array(
					"labelString"	=>	"Testo della mail al cambio stato",
					'wrap'		=>	array(
						null,null,SegnapostoModel::getLegenda($this),
					),
				),
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public function insert()
	{
		$this->values["tipo"] == "U";
		
		$this->impostaStatiSpedizione();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->impostaStatiSpedizione();
		
		return parent::update($id, $where);
	}
	
	public function impostaStatiSpedizione()
	{
		if (isset($this->values["in_spedizione"]) && $this->values["in_spedizione"])
			$this->db->query("update stati_ordine set in_spedizione = 0 where 1");
		
		if (isset($this->values["spedito"]) && $this->values["spedito"])
			$this->db->query("update stati_ordine set spedito = 0 where 1");
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			if ($record["tipo"] == "S")
				return false;
			
			$numeroOrdini = OrdiniModel::g()->where(array(
				"stato"	=>	$record["codice"]
			))->count();
			
			if ($numeroOrdini > 0)
				return false;
		}
		
		return true;
	}
	
	public static function getLabel($valore)
	{
		if ($valore > 0)
			return "success";
		else if ($valore < 0)
			return "default";
		else
			return "danger";
	}
	
	public function pagatoCrud($record)
	{
		$label = self::getLabel($record["stati_ordine"]["pagato"]);
		
		$text = self::$attivoSiNo[$record["stati_ordine"]["pagato"]] ?? "Neutro";
		
		return "<span class='text-bold text text-$label'>".$text."</span>";
	}
	
	public function daSpedireCrud($record)
	{
		if ($record["stati_ordine"]["da_spedire"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "";
	}
	
	public function inSpedizioneCrud($record)
	{
		if ($record["stati_ordine"]["in_spedizione"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "";
	}
	
	public function speditoCrud($record)
	{
		if ($record["stati_ordine"]["spedito"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "";
	}
	
	public function pagato($codiceStato)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("codice");
		
		return self::$recordTabella[$codiceStato]["pagato"] > 0 ? true : false;
	}
	
	public function neutro($codiceStato)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("codice");
		
		return self::$recordTabella[$codiceStato]["pagato"] < 0 ? true : false;
	}
	
	public static function getCampo($codiceStato, $campo)
	{
		if (!isset(self::$recordTabella))
			self::g(false)->setRecordTabella("codice");
		
		return isset(self::$recordTabella[$codiceStato][$campo]) ? self::$recordTabella[$codiceStato][$campo] : null;
	}
	
	// Restituisce un array con tutti i codici degli stati da spedire
	public static function getStatiDaSpedire()
	{
		return self::g(false)->clear()->select("codice")->where(array(
			"da_spedire"	=>	1,
		))->toList("codice")->send();
	}
	
	// Controlla se lo stato dell'ordine è editabile ed eliminabile
	public static function statoEditabileEdEliminabile($stato)
	{
		$statiArray = explode(",", v("stati_ordine_editabile_ed_eliminabile"));
		
		if (in_array($stato, $statiArray))
			return true;
		
		return false;
	}
	
	public static function getTitoliStati($stati, $char = ", ")
	{
		$titoliArray = [];
		
		$statiArray = explode(",", v("stati_ordine_editabile_ed_eliminabile"));
		
		foreach ($statiArray as $stato)
		{
			$titoliArray[] = self::getCampo($stato, "titolo");
		}
		
		return implode($char, $titoliArray);
	}
}
