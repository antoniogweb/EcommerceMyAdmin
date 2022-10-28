<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class ListeregalolinkModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'liste_regalo_link';
		$this->_idFields = 'id_lista_regalo_link';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
        );
    }
    
    public function checkDuplicati()
    {
		if (isset($this->values["email"]) && isset($this->values["id_lista_regalo"]))
		{
			return !$this->clear()->where(array(
				"email"	=>	sanitizeAll($this->values["email"]),
				"id_lista_regalo"	=>	sanitizeAll($this->values["id_lista_regalo"]),
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
			$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, il link è già stato inviato all'indirizzo email indicato.")."</div><div class='evidenzia'>class_email</div>";
			
			$this->result = false;
			$this->queryResult = false;
		}
    }
    
    public function checkAccesso($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && ListeregaloModel::numeroListeUtente(User::$id, $record["id_lista_regalo"]))
			return true;
		
		return false;
    }
    
	public function inviaMail($id)
    {
		$record = $this->clear()->select("*")
			->inner(array("lista"))
			->inner("regusers")->on("regusers.id_user = liste_regalo.id_user")
			->where(array(
				"id_lista_regalo_link"	=>	(int)$id,
			))->first();
		
		if (!empty($record) && $record["liste_regalo_link"]["numero_tentativi"] < v("numero_massimo_tentativi_invio_link"))
		{
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($record["liste_regalo_link"]["email"]),
				"oggetto"	=>	"Nuova lista regalo condivisa!",
				"tipologia"	=>	"LINK LISTA REGALO",
				"tabella"	=>	"liste_regalo_link",
				"id_elemento"	=>	(int)$id,
				"testo_path"	=>	"Elementi/Mail/mail_link_lista_regalo.php",
				"array_variabili_tema"	=>	array(
					"NOME_CREATORE_LISTA"	=>	self::getNominativo($record["regusers"]),
					"EMAIL_CREATORE_LISTA"	=>	$record["regusers"]["username"],
					"LINK_LISTA"	=>	Domain::$publicUrl."/".$record["regusers"]["lingua"].F::getNazioneUrl($record["regusers"]["nazione_navigazione"])."/".ListeregaloModel::getUrlAlias($record["liste_regalo"]["id_lista_regalo"]),
				),
			));
			
			$inviato = 0;
			
			if ($res)
				$inviato = 1;
			
			$this->sValues(array(
				"inviato"	=>	$inviato,
				"numero_tentativi"	=>	($record["liste_regalo_link"]["numero_tentativi"] + 1),
			));
			
			$this->update((int)$id);
			
			return true;
		}
		
		return false;
    }
    
    public function inviata($record)
    {
		if ($record["liste_regalo_link"]["inviato"])
			return '<i class="fa fa-check text text-success" aria-hidden="true"></i>';
		else
			return '<i class="fa fa-ban text text-danger" aria-hidden="true"></i>';
    }
    
    public function invia($record)
    {
		if ($record["liste_regalo_link"]["numero_tentativi"] < 10)
			return '<a title="'.gtext("Invia nuovamente il link all'indirizzo e-mail indicato nella riga.").'" class="ajlink" href="'.Url::getRoot().'listeregalolink/invia/'.$record["liste_regalo_link"]["id_lista_regalo_link"].'"><i class="fa fa-refresh" aria-hidden="true"></i></a>';
		
		return "";
    }
}
