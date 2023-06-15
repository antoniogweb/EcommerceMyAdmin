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

class PromozioniinviiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'promozioni_invii';
		$this->_idFields = 'id_promozione_invio';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'promozione' => array("BELONGS_TO", 'PromozioniModel', 'id_p',null,"CASCADE"),
        );
    }
    
    public function checkDuplicati()
    {
		if (isset($this->values["email"]) && isset($this->values["id_p"]))
		{
			return !$this->clear()->where(array(
				"email"	=>	sanitizeAll($this->values["email"]),
				"id_p"	=>	sanitizeAll($this->values["id_p"]),
			))->rowNumber();
		}
		
		return true;
    }
    
    public function insert()
    {
		if ($this->checkDuplicati())
		{
			$this->values["time_creazione"] = time();
			
			$res = parent::insert();
			
			if ($res && !App::$isFrontend)
				$this->inviaMail($this->lId);
			
			return $res;
		}
		else
		{
			$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, il codice è già stato inviato all'indirizzo email indicato.")."</div><div class='evidenzia'>class_email</div>";
			
			$this->result = false;
			$this->queryResult = false;
		}
    }
    
    public function checkAccesso($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && PromozioniModel::numeroPromoUtente(User::$id, $record["id_p"]))
			return true;
		
		return false;
    }
    
	public function inviaMail($id)
    {
		$record = $this->clear()->select("*")
			->inner(array("promozione"))
			->inner("regusers")->on("regusers.id_user = promozioni.id_user")
			->where(array(
				"id_promozione_invio"	=>	(int)$id,
			))->first();
		
		if (!empty($record) && $record["promozioni_invii"]["numero_tentativi"] < v("numero_massimo_tentativi_invio_codice_coupon"))
		{
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($record["promozioni_invii"]["email"]),
				"oggetto"	=>	"Il tuo codice coupon ".$record["promozioni"]["codice"],
				"tipologia"	=>	"CODICE COUPON A CLIENTE",
				"tabella"	=>	"promozioni_invii",
				"id_elemento"	=>	(int)$id,
				"testo_path"	=>	"Elementi/Mail/mail_link_codice_coupon_cliente.php",
				"array_variabili_tema"	=>	array(
					"CODICE_COUPON"	=>	$record["promozioni"]["codice"],
					"TITOLO_PROMO"	=>	$record["promozioni"]["titolo"],
					"NOME_UTENTE"	=>	self::getNominativo($record["regusers"]),
					"EMAIL_UTENTE"	=>	$record["regusers"]["username"],
					"LINK_AREA_RISERVATA"	=>	Domain::$publicUrl."/".$record["regusers"]["lingua"].F::getNazioneUrl($record["regusers"]["nazione_navigazione"])."/area-riservata",
				),
			));
			
			$inviato = 0;
			
			if ($res)
				$inviato = 1;
			
			$this->sValues(array(
				"inviato"	=>	$inviato,
				"numero_tentativi"	=>	($record["promozioni_invii"]["numero_tentativi"] + 1),
				"time_ultimo_invio"	=>	time(),
			));
			
			$this->update((int)$id);
			
			return true;
		}
		
		return false;
    }
    
    public function inviata($record)
    {
		if ($record["promozioni_invii"]["inviato"])
			return '<i class="fa fa-check text text-success" aria-hidden="true"></i>';
		else
			return '<i class="fa fa-ban text text-danger" aria-hidden="true"></i>';
    }
    
    public function ultimoinvito($record)
    {
		if ($record["promozioni_invii"]["time_ultimo_invio"])
			return date("d-m-Y H:i", $record["promozioni_invii"]["time_ultimo_invio"]);
		
		return "";
    }
    
    public function getInvii($idP)
    {
		return $this->clear()->aWhere(array(
				"id_p"	=>	(int)$idP,
			))->orderBy("id_promozione_invio desc")->send();
    }
}
