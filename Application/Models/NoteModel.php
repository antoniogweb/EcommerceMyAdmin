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

class NoteModel extends GenericModel
{
	public static $elencoTabellePermesse = array(
		"liste_regalo_pages"	=>	array(
			"model"	=>	"ListeregalopagesModel",
		),
		"righe" => array(
			"model"	=>	"RigheModel",
		),
		"promozioni" => array(
			"model"	=>	"PromozioniModel",
			"email"	=>	true,
			"valore"	=>	true,
		),
	);
	
	public function __construct() {
		$this->_tables = 'note';
		$this->_idFields = 'id_nota';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'utente' => array("BELONGS_TO", 'UsersModel', 'id_admin',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'testo'	=>	array(
					"labelString"	=>	"Testo della nota",
					'attributes'		=>	'rows = "8"',
				),
			),
		);
	}
    
    public function checkTabella($tabella, $idRif)
	{
		if (isset(self::$elencoTabellePermesse[$tabella]))
		{
			$model = $this->getModel($tabella);
			
			$numero = $model->clear()->whereId((int)$idRif)->rowNumber();
			
			if ($numero)
				return true;
		}
		
		return false;
	}
    
    public function checkInsertTabella()
	{
		if (!isset($this->values["tabella_rif"]) || !isset($this->values["id_rif"]))
			return false;
		
		if (!$this->checkTabella($this->values["tabella_rif"], $this->values["id_rif"]))
			return false;
		
		return true;
	}
    
    public function getModel($tabella)
	{
		if (self::$elencoTabellePermesse[$tabella]["model"])
			return new self::$elencoTabellePermesse[$tabella]["model"];
		
		return null;
	}
    
    public function withEmail($tabella)
	{
		if (isset(self::$elencoTabellePermesse[$tabella]["email"]) && self::$elencoTabellePermesse[$tabella]["email"])
			return true;
		
		return false;
	}
	
	public function withValore($tabella)
	{
		if (isset(self::$elencoTabellePermesse[$tabella]["valore"]) && self::$elencoTabellePermesse[$tabella]["valore"])
			return true;
		
		return false;
	}
	
    public function hasEmail($tabella, $idRif)
	{
		$email = $this->getEmail($tabella, $idRif);
		
		return $email ? true : false;
	}
    
    public function getEmail($tabella, $idRif)
	{
		$model = $this->getModel($tabella);
		
		if (isset($model) && $idRif)
		{
			$record = $model->selectId((int)$idRif);
			
			if (!empty($record) && isset($record["email"]) && checkMail(trim($record["email"])))
				return trim($record["email"]);
		}
		
		return "";
	}
    
    public function numeroEuroUsatiInNote($tabella, $idRif)
	{
		$res = $this->clear()->select("sum(valore) as SOMMA")->where(array(
			"tabella_rif"	=>	sanitizeAll($tabella),
			"id_rif"		=>	(int)$idRif,
		))->send();
		
		if (count($res) > 0)
			return (float)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
	}
    
    public function numeroEuroUsati($tabella, $idRif)
	{
		$model = $this->getModel($tabella);
		
		if (isset($model) && method_exists($model, "numeroEuroUsati"))
		{
			return $model->numeroEuroUsati($idRif);
		}
		
		return 0;
	}
	
	public function numeroEuroRimasti($tabella, $idRif)
	{
		$model = $this->getModel($tabella);
		
		if (isset($model) && method_exists($model, "numeroEuroRimasti"))
		{
			return $model->numeroEuroRimasti($idRif);
		}
		
		return 0;
	}
    
    public function getTotalePromoAssoluta($tabella, $idRif)
	{
		$model = $this->getModel($tabella);
		
		if (isset($model) && method_exists($model, "getTotalePromoAssoluta"))
		{
			return $model->getTotalePromoAssoluta($idRif);
		}
		
		return 0;
	}
    
    public function getTestoDefault($tabella, $idRif, $campo = "testo")
	{
		$model = $this->getModel($tabella);
		
		if (isset($model) && method_exists($model, "getTestoDefaultNota"))
		{
			return $model->getTestoDefaultNota($idRif, $campo);
		}
		
		return "";
	}
    
    public function mandaEmail($id)
	{
		if ($this->withEmail($this->values["tabella_rif"]))
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record) && $record["email"] && checkMail(trim($record["email"])))
			{
				$res = MailordiniModel::inviaMail(array(
					"emails"	=>	array(trim($record["email"])),
					"oggetto"	=>	strip_tags(htmlentitydecode($record["oggetto"])),
					"tipologia"	=>	"MAIL NOTA",
					"tabella"	=>	$record["tabella_rif"],
					"id_elemento"	=>	(int)$record["id_rif"],
					"testo"	=>	nl2br(strip_tags(htmlentitydecode($record["testo"]))),
				));
			}
		}
	}
    
    public function insert()
    {
		$this->values["id_admin"] = (int)User::$id;
		
		if (!$this->checkInsertTabella())
			return false;
		
		$res = parent::insert();
		
		$this->mandaEmail($this->lId);
		
		return $res;
    }
    
    public function manageable($id)
	{
		return $this->deletable($id);
	}
    
    public function deletable($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && (int)$record["id_admin"] === User::$id)
			return true;
		
		return false;
    }
	
    public function noteCrudHtml($tabellaRif, $idRif)
    {
		$html = "";
		
		$ultimaNota = $this->clear()->select("note.testo,note.data_creazione,adminusers.username")->inner(array("utente"))->where(array(
			"tabella_rif"	=>	sanitizeAll($tabellaRif),
			"id_rif"		=>	(int)$idRif,
		))->orderBy("id_nota desc")->limit(1)->first();
		
		if (!empty($ultimaNota))
			$html .= "<div><small>".gtext("Ultima nota di")." <b>".$ultimaNota["adminusers"]["username"]."</b> ".gtext("del")." <b>".date("d/m/y H:i",strtotime($ultimaNota["note"]["data_creazione"]))."</b><br /><i>".$ultimaNota["note"]["testo"]."</i></small></div>";
		
		$html .= "<small><a class='iframe label label-info' title='".gtext("Aggiungi nota")."' href='".Url::getRoot()."note/form/insert/0?cl_on_sv=Y&partial=Y&nobuttons=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-plus-square-o'></i> ".gtext("Aggiungi nota")."</a></small>";
		
		if (!empty($ultimaNota))
			$html .= "<small style='margin-left:10px;'><a class='iframe label label-default' title='".gtext("Aggiungi nota")."' href='".Url::getRoot()."note/main?partial=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-list'></i> ".gtext("Tutte le note")."</a></small>";
		
		return $html;
    }
    
    public function emailCrud($record)
	{
		$moModel = new MailordiniModel();
		
		$emailInviate = $moModel->estraiRigaTabellaIdRef("promozioni", (int)$record["note"]["id_rif"]);
		
		$htmlArray = array();
		
		foreach ($emailInviate as $email)
		{
			$htmlArray[] = gtext("Email inviata a")." ".$email["email"]." ".gtext("in data")." ".F::getDateAndTimeInCorrectFormat(strtotime($email["data_creazione"]));
		}
		
		return implode("<br />", $htmlArray);
	}
}
