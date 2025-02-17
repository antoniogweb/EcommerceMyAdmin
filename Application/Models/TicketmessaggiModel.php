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

class TicketmessaggiModel extends GenericModel
{
	use TickettraitModel;
	
	public function __construct() {
		$this->_tables = 'ticket_messaggi';
		$this->_idFields = 'id_ticket_messaggio';
		
		$this->_idOrder = 'id_order';
		
		list($allowedExtensions, $allowedMimeTypes) = $this->getAllowedExtensionMimeTypes();
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/ticket_immagini",
				"allowedExtensions"	=>	$allowedExtensions,
				'allowedMimeTypes'	=>	$allowedMimeTypes,
				"createImage"	=>	false,
				"createImageParams"	=>	array(
					"imgWidth"	=>	800,
					"imgHeight"	=>	800,
					"jpegImgQuality"	=>	60,
				),
				"maxFileSize"	=>	v("dimensioni_upload_video_ticket"),
				"clean_field"	=>	"clean_filename",
				"disallow"		=>	true,
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ticket' => array("BELONGS_TO", 'TicketModel', 'id_ticket',null,"RESTRICT","Si prega di selezionare un ticket di assistenza"),
			'admin' => array("BELONGS_TO", 'UsersModel', 'id_admin',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'descrizione'	=>	array(
					'type'		=>	'Textarea',
					'className'		=>	'form-control testo_feedback',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
    
    public function setConditions()
	{
		$this->addStrongCondition("insert",'checkNotEmpty',"descrizione,accetto");
	}
	
	public function insert()
	{
		if (App::$isFrontend)
			$this->values["id_user"] = User::$id ? (int)User::$id : (int)TicketModel::$userId;
		else
			$this->values["id_admin"] = (int)User::$id;
		
		if ($this->upload("insert"))
		{
			$this->setEstensioneEMimeType();
			
			$this->setTipo();
			
			$res = parent::insert();
			
			if ($res && !App::$isFrontend && isset($this->values["id_admin"]) && $this->values["id_admin"])
			{
				$idTicketMessaggio = $this->lId;
				
				$ticket = $this->clear()->select("ticket.stato,ticket.id_ticket")->inner(array("ticket"))->where(array(
					"id_ticket_messaggio"	=>	(int)$idTicketMessaggio,
				))->first();
				
				if (!empty($ticket) && $ticket["ticket"]["stato"] == "A")
				{
					$tModel = new TicketModel();
					
					$tModel->sValues(array(
						"stato"	=>	"L",
					));
					
					$tModel->pUpdate($ticket["ticket"]["id_ticket"]);
				}
			}
			
			if ($res)
			{
				$this->mandaMail($this->lId);
				
				$this->aggiungiNotifica($this->lId); // Aggiungi la notifica nel pannello admin
			}
			
			return $res;
		}
		
		return false;
	}
	
	public function aggiungiNotifica($idMessaggio)
	{
		if (App::$isFrontend)
		{
			$record = $this->clear()->select("*")
				->inner(array("ticket"))->whereId((int)$idMessaggio)
				->first();
			
			if (!empty($record) && $record["ticket"]["id_user"])
			{
				$idTicket = (int)$record["ticket"]["id_ticket"];
				
				$n = new NotificheModel();
				
				$n->setValues(array(
					"titolo"	=>	"Nuovo messaggio nel ticket ID ".(int)$idTicket,
					"contesto"	=>	"TICKET",
					"url"		=>	"ticket/form/update/".(int)$idTicket,
					"classe"	=>	"text-yellow",
					"icona"		=>	"fa-ticket",
					"condizioni"=>	"attiva_gestiobe_ticket=1",
				));
				
				$n->insert();
			}
		}
	}
	
	public function mandaMail($idMessaggio)
    {
		$ticket = $this->clear()->select("*")
			->inner(array("ticket"))->whereId((int)$idMessaggio)
			->inner("regusers")->on("regusers.id_user = ticket.id_user")
			->first();
		
		if (!empty($ticket))
		{
			$idTicket = (int)$ticket["ticket"]["id_ticket"];
			
			$lingua = $ticket["regusers"]["lingua"] ? $ticket["regusers"]["lingua"] : v("lingua_default_frontend");
			
			$oggetto = "Nuovo messaggio al ticket ID ".(int)$idTicket;
			
			if ($ticket["ticket_messaggi"]["id_admin"])
			{
				$email = $ticket["regusers"]["username"];
				$template = "Elementi/Mail/Ticket/mail_nuovo_messaggio_cliente.php";
				$messaggioTicket = htmlentitydecode($ticket["ticket_messaggi"]["descrizione"]);
			}
			else
			{
				$email = (v("email_ticket_negozio") && checkMail(v("email_ticket_negozio"))) ? v("email_ticket_negozio") : Parametri::$mailInvioOrdine;
				$template = "Elementi/Mail/Ticket/mail_nuovo_messaggio_negozio.php";
				$messaggioTicket = $ticket["ticket_messaggi"]["descrizione"];
			}
			
			if (checkMail($email))
			{
				$nazione = $ticket["regusers"]["nazione_navigazione"] ? strtolower($ticket["regusers"]["nazione_navigazione"]) : strtolower(v("nazione_default"));
				$linguaUrl = v("attiva_nazione_nell_url") ? $lingua."_".$nazione : $lingua;
				
				$valoriMail = array(
					"emails"	=>	array($email),
					"oggetto"	=>	$oggetto,
					"tipologia"	=>	"NUOVO_MESSAGGIO_TICKET",
					"lingua"	=>	$lingua,
					"testo_path"=>	$template,
					"tabella"	=>	"ticket",
					"id_elemento"	=>	(int)$idTicket,
					"array_variabili_tema"	=>	array(
						"ID_TICKET"			=>	$ticket["ticket"]["id_ticket"],
						"EMAIL_CLIENTE"		=>	$ticket["regusers"]["username"],
						"OGGETTO_TICKET"	=>	$ticket["ticket"]["oggetto"],
						"MESSAGGIO_TICKET"	=>	$messaggioTicket,
						"URL_TICKET"		=>	Domain::$publicUrl."/".$linguaUrl."/ticket/view/$idTicket/".$ticket["ticket"]["ticket_uid"],
						"NOMINATIVO_CLIENTE"=>	self::getNominativo($ticket["regusers"]),
					),
				);
				
				MailordiniModel::inviaMail($valoriMail);
			}
		}
    }
    
    // Controlla se ha superato il numero di messaggi consecutivi prima che risponda il negozio
    public function okInvioNuovoMessaggio($idTicket)
    {
		$res = $this->clear()->select("id_admin")->where(array(
			"id_ticket"	=>	(int)$idTicket,
		))->orderBy("id_ticket_messaggio desc")->limit(v("numero_massimo_messaggi_consecutivi_per_ticket"))->toList("id_admin")->send();
		
		if (count($res) <  (int)v("numero_massimo_messaggi_consecutivi_per_ticket"))
			return true;
		
		foreach ($res as $r)
		{
			if ((int)$r)
				return true;
		}
		
		return false;
    }
}
