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

Helper_List::$filtersFormLayout["filters"]["id_ticket_tipologia"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class TicketController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>	'tutti',
		'stato:sanitizeAll'	=>	'tutti',
		'id_ticket_tipologia:sanitizeAll'	=>	'tutti',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
	);
	
	public $orderBy = "id_order";
	
	public $tabella = "ticket";
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_gestiobe_ticket"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>100, 'mainMenu'=>'add');
		$this->mainFields = array("cleanDateTime", "ticket.oggetto", "nome", "regusers.username", "ticket_tipologie.titolo", "statoCrud");
		$this->mainHead = "Data ora,Oggetto,Cliente,Email,Tipologia,Stato";
		
		$filtroStato = array(
			"tutti"		=>	"Stato",
		) + $this->m("TicketstatiModel")->selectTendina(false);
		
		$filtroTipologia = array(
			"tutti"		=>	"Tipologia",
		) + $this->m("TickettipologieModel")->selectTipologie();
		
		$this->filters = array("titolo","dal","al",array("id_ticket_tipologia",null,$filtroTipologia),array("stato",null,$filtroStato));
		
		$this->m[$this->modelName]->clear()
			->select("*")
			->inner(array("tipologia", "cliente"))
			->where(array(
				"ne"	=>	array(
					"stato"	=>	"B",
				),
				"stato"	=>	$this->viewArgs["stato"],
				"id_ticket_tipologia"	=>	$this->viewArgs["id_ticket_tipologia"],
			))->orderBy("id_ticket desc");
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(ticket.data_invio, '%Y-%m-%d') >= ?",array(getIsoDate($this->viewArgs['dal']))));
		
		if ($this->viewArgs['al'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(ticket.data_invio, '%Y-%m-%d') <= ?",array(getIsoDate($this->viewArgs['al']))));
		
		if ($this->viewArgs["titolo"] != "tutti")
		{
			$tokens = explode(" ", $this->viewArgs['titolo']);
			$andArray = array();
			$iCerca = 8;
			
			foreach ($tokens as $token)
			{
				$andArray[str_repeat(" ", $iCerca)."lk"] = array(
					"n!concat(regusers.ragione_sociale,' ',regusers.nome,' ',regusers.cognome,' ',regusers.username, ' ',ticket.oggetto)"	=>	sanitizeAll(htmlentitydecode($token)),
				);
				
				$iCerca++;
			}
			
			$this->m[$this->modelName]->aWhere(array(
				"      AND"	=>	$andArray,
			));
		}
		
		$this->m[$this->modelName]->convert()->save();
		
		parent::main();
	}
	
	// Aggiungi un prodotto al ticket
	public function aggiungiprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		$numero_seriale = $this->request->post("numero_seriale","","none");
		
		$this->m('TicketpagesModel')->aggiungiProdotto($clean["id_page"], $idTicket, $ticketUid, $numero_seriale);
	}
	
	// Aggiungi un prodotto al ticket
	public function rimuoviprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		
		$this->m('TicketpagesModel')->rimuoviProdotto($clean["id_page"], $idTicket, $ticketUid);
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$ticket = $data["ticket"] = $this->m('TicketModel')->selectId((int)$id);
		
		$idTipologia = isset($_POST["id_ticket_tipologia"]) ? (int)$_POST["id_ticket_tipologia"] : (int)$ticket["id_ticket_tipologia"];
		$idO = $idLista = 0;
		
		$tipologia = $data["tipologia"] = $this->m('TickettipologieModel')->selectId((int)$idTipologia);
		
		if (empty($tipologia))
		{
			$this->redirect("");
			die();
		}
		
		$fields = "id_user,id_ticket_tipologia,oggetto,descrizione";
		
		if ($tipologia["tipo"] == "ORDINE")
		{
			$fields .= ",id_o";
			
			$idO = isset($_POST["id_o"]) ? (int)$_POST["id_o"] : (int)$ticket["id_o"];
			$idLista = 0;
		}
		else if ($tipologia["tipo"] == "LISTA REGALO")
		{
			$fields .= ",id_lista_regalo";
			
			$idLista = isset($_POST["id_lista_regalo"]) ? (int)$_POST["id_lista_regalo"] : (int)$ticket["id_lista_regalo"];
			$idO = 0;
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
		
		$data['mostra_tendina_prodotti'] = false;
		
		if (
			($tipologia["tipo"] == "ORDINE" && $idO) || 
			($tipologia["tipo"] == "LISTA REGALO" && $idLista) || 
			($tipologia["tipo"] == "PRODOTTO")
		)
		{
			$data['mostra_tendina_prodotti'] = true;
			$data['prodotti'] = array(0 => gtext("Seleziona")) + $this->m('TicketModel')->getTendinaProdotti($ticket["id_user"], $idO, $idLista);
		}
		else
			$data['prodotti'] = array(0 => gtext("Seleziona"));
		
		$data["prodottiInseriti"] = $this->m('TicketpagesModel')->getProdottiInseriti((int)$id);
		
		$data["messaggi"] = $this->m('TicketmessaggiModel')->clear()->select("*")->left(array("admin"))->where(array(
			"id_ticket"	=>	(int)$id,
		))->orderBy("id_ticket_messaggio")->send();
		
		$this->append($data);
	}
}
