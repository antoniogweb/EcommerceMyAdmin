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

Helper_List::$filtersFormLayout["filters"]["id_ticket_tipologia"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["id_t"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"ID ticket ..",
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
		'id_t:sanitizeAll'=>'tutti',
	);
	
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
		$this->mainFields = array("ticket.id_ticket", "cleanDateTime", "ticket.oggetto", "nome", "regusers.username", "ticket_tipologie.titolo", "creatoDaCrud", "fonteCrud", "statoCrud", "campanellaCrud");
		$this->mainHead = "ID,Data ora,Oggetto,Cliente,Email,Tipologia,Creato da,Fonte,Stato,";
		
		$filtroStato = array(
			"tutti"		=>	"Stato",
		) + $this->m("TicketstatiModel")->selectTendina(false);
		
		$filtroTipologia = array(
			"tutti"		=>	"Tipologia",
		) + $this->m("TickettipologieModel")->selectTipologie();
		
		$this->filters = array("titolo","id_t","dal","al",array("id_ticket_tipologia",null,$filtroTipologia),array("stato",null,$filtroStato));
		
		$this->inverseColProperties = array(
			null,null,
			array(
				"width"	=>	"1%",
			),
		);
		
		$this->m[$this->modelName]->clear()
			->select("ticket.*,regusers.*,ticket_tipologie.*,ticket_messaggi.id_admin,if (ticket_messaggi.id_admin IS NULL,0,1) as CON_MESSAGGI,adminusers.username")
			->inner(array("tipologia", "cliente"))
			->left(array("admin"))
			->left("(select max(id_ticket_messaggio) as id_messaggio,id_ticket from ticket_messaggi group by id_ticket) as messaggi")->on("messaggi.id_ticket = ticket.id_ticket")
			->left("ticket_messaggi")->on("ticket_messaggi.id_ticket_messaggio = messaggi.id_messaggio")
			->where(array(
				"OR"	=>	array(
					"ne"	=>	array(
						"stato"	=>	"B",
					),
					" ne"	=>	array(
						"id_admin"	=>	"0",
					),
				),
				"id_ticket"	=>	$this->viewArgs["id_t"],
				"stato"		=>	$this->viewArgs["stato"],
				"id_ticket_tipologia"	=>	$this->viewArgs["id_ticket_tipologia"],
			))->orderBy("FIELD(stato, 'B', 'A', 'L', 'C'),CON_MESSAGGI DESC,ticket_messaggi.id_admin,ticket.id_ticket desc");
		
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
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function immagini($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['immagini'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_ticket";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "TicketfileModel";
		
		$this->colProperties = array(
			array(
				"width"	=>	"100px"
			)
		);
		
		$this->addBulkActions = false;
		
		$this->mainFields = array("thumbCrud", "filenameCrud");
		$this->mainHead = "Thumb,Nome file";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>"back",'mainAction'=>"immagini/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->select("*")->orderBy("ticket_file.id_order")->where(array("id_ticket"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["TicketModel"]->titolo($clean['id']);
		
		$this->append($data);
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
	
	public function nuovo()
	{
		if (isset($_GET["nuovoAction"]) && isset($_GET["id_user"]))
		{
			$cliente = $this->m("RegusersModel")->selectId((int)$_GET["id_user"]);
			
			if (!empty($cliente))
			{
				$tiket = $this->m("TicketModel")->add(array(
					"id_admin"	=>	User::$id,
					"id_user"	=>	$cliente["id_user"]
				));
				
				if (!empty($tiket))
					$this->redirect("ticket/form/update/".$tiket["id_ticket"]);
			}
			else
				$this->redirect("regusers/form/insert/0?ticket=1");
		}
		
		$data["clienti"] = array(0	=>	gtext("Nuovo cliente")) + $this->m("TicketModel")->selectUtenti(0, v("utilizza_ricerca_ajax_su_select_2_clienti"));
		
		$this->append($data);
		
		$this->load("nuovo");
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		if (!$id || $queryType == "insert")
			$this->redirect("ticket/nuovo");
		
		$ticket = $data["ticket"] = $this->m('TicketModel')->selectId((int)$id);
		
		if (empty($ticket))
			$this->responseCode(403);
		
		$idTipologia = isset($_POST["id_ticket_tipologia"]) ? (int)$_POST["id_ticket_tipologia"] : (int)$ticket["id_ticket_tipologia"];
		$idO = $idLista = 0;
		
		$tipologia = $data["tipologia"] = $this->m('TickettipologieModel')->selectId((int)$idTipologia);
		
		if (empty($tipologia))
			$this->responseCode(403);
		
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
		
		$this->m[$this->modelName]->addStrongCondition("update",'checkNotEmpty',"oggetto,descrizione");
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
		
		$data["isBozza"] = $this->m("TicketModel")->isBozza((int)$id);
		
		$this->append($data);
	}
	
	public function setstato($id_ticket, $stato)
	{
		$this->shift(2);
		
		$this->clean();
		
		$res = $this->m["TicketModel"]->clear()->whereId((int)$id_ticket)->send();
		$statiTicket = $this->m("TicketstatiModel")->selectTendina(false);
		
		if (isset($statiTicket[$stato]) && count($res) > 0)
		{
			$this->m["TicketModel"]->sValues(array(
				"stato"	=>	$stato,
			));
			
			if ($stato == "C")
				$this->m["TicketModel"]->setValue("data_chiusura", date("Y-m-d H:i:s"));
			
			if ($this->m["TicketModel"]->update((int)$id_ticket) && !isset($_GET["no_mail_stato"]))
			{
				$this->m("TicketstatiModel")->mandaMail($id_ticket, $stato);
			}
		}
		
		$this->redirect($this->applicationUrl.$this->controller."/form/update/".(int)$id_ticket.$this->viewStatus);
	}
	
	public function salvabozza($idTicket = 0)
	{
		$this->clean();
		
		if (!$this->m[$this->modelName]->whereId((int)$idTicket)->rowNumber())
			$this->responseCode(403);
		
		$ttModel = new TickettipologieModel();
		$idTipologiaDefault = $ttModel->getFirstIdTipologiaAttiva();
		
		$values = array();
		$values["id_user"] = $this->request->post("id_user", 0, "forceInt");
		$values["id_ticket_tipologia"] = $this->request->post("id_ticket_tipologia", $idTipologiaDefault, "forceInt");
		$values["oggetto"] = $this->request->post("oggetto", "");
		$values["descrizione"] = $this->request->post("descrizione", "");
		$values["id_o"] = $this->request->post("id_o", 0, "forceInt");
		$values["id_lista_regalo"] = $this->request->post("id_lista_regalo", 0, "forceInt");
		
		$this->m("TicketModel")->sValues($values);
		
		$this->m("TicketModel")->pUpdate((int)$idTicket);
	}
}
