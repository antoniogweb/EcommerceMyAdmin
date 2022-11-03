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

Helper_List::$filtersFormLayout["filters"]["lista_regalo"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Lista regalo ..",
	),
);

class OrdiniController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $addIntegrazioniInMain = false;
	
	public $tabella = "ordini";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->session('admin');
		$this->model();

		$this->setArgKeys(array(
			'page:forceInt'=>1,
			'id_o:sanitizeAll'=>'tutti',
			'pagamento:sanitizeAll'=>'tutti',
			'stato:sanitizeAll'=>'tutti',
			'tipo_cliente:sanitizeAll'=>'tutti',
			'email:sanitizeAll'=>'tutti',
			'codice_fiscale:sanitizeAll'=>'tutti',
			'registrato:sanitizeAll'=>'tutti',
			'token:sanitizeAll'=>'token',
			'partial:sanitizeAll'=>'tutti',
			'id_comb:sanitizeAll'=>'tutti',
			'dal:sanitizeAll'=>'tutti',
			'al:sanitizeAll'=>'tutti',
			'nazione_utente:sanitizeAll'=>'tutti',
			'lista_regalo:sanitizeAll'=>'tutti',
			'id_lista_regalo:sanitizeAll'=>'tutti',
		));

		$this->model("OrdiniModel");
		$this->model("RigheModel");
		$this->model("RegusersModel");
		$this->model("CorrieriModel");
		$this->model("MailordiniModel");
		
		$this->s['admin']->check();
		
		$this->m["FattureModel"]->checkFiles();
		
		$this->_topMenuClasses['ordini'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
		
		$this->generaPosizioni();
	}

	public function main()
	{
		$this->shift();
		$this->m[$this->modelName]->cViewStatus = $this->viewStatus;
		
		$this->addBulkActions = false;
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array(
			'linkcrud',
			'smartDate|orders.data_creazione',
			'OrdiniModel.getNome|orders.id_o',
			'orders.email',
			'orders.tipo_cliente',
			'OrdiniModel.getCFoPIva|orders.id_o',
			'orders.nome_promozione',
			'statoordinelabel',
			'totaleCrud',
		);
		
		$this->mainButtons = "";
		
		$this->mainHead = 'N°,Data,Nome/Rag.Soc,Email,Tipo,C.F./P.IVA,Promoz.,Stato,Totale';
		
		if (v("attiva_ip_location"))
		{
			$this->mainFields[] = 'nazionenavigazione';
			$this->mainHead .= ',Nazione';
		}
		
		if (v("attiva_liste_regalo"))
		{
			$this->mainFields[] = 'listaregalo';
			$this->mainHead .= ',Lista regalo';
		}
		
		$this->aggiungiintegrazioni();
		
		$this->mainHead .= ",";
		$this->mainFields[] = "<a class='text_16 action_edit' title='vedi ordine ;orders.id_o;' href='".$this->baseUrl."/".$this->applicationUrl.$this->controller."/vedi/;orders.id_o;".$this->viewStatus."'><i class='verde fa fa-arrow-right'></i></a>";
		
		$this->m[$this->modelName]->clear()->orderBy("orders.id_o desc");
		
		$where = array(
			'id_o'	=>	$this->viewArgs['id_o'],
			'stato'	=>	$this->viewArgs['stato'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			'pagamento'	=>	$this->viewArgs['pagamento'],
			'registrato'	=>	$this->viewArgs['registrato'],
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
			'id_lista_regalo'	=>	$this->viewArgs['id_lista_regalo'],
		);
		
		$this->m[$this->modelName]->where($where);
		
		if (strcmp($this->viewArgs['email'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('orders.email' => $this->viewArgs['email']),
			);

			$this->m[$this->modelName]->aWhere($where);
		}
		
		if (strcmp($this->viewArgs['codice_fiscale'],'tutti') !== 0)
		{
			$where = array(
				"OR"	=> array(
					"lk" => array('orders.codice_fiscale' => $this->viewArgs['codice_fiscale']),
					" lk" => array('orders.p_iva' => $this->viewArgs['codice_fiscale']),
					)
			);

			$this->m[$this->modelName]->aWhere($where);
		}
		
		if ($this->viewArgs['id_comb'] != "tutti")
		{
			$this->m[$this->modelName]->groupBy("orders.id_o")->inner("righe")->on("righe.id_o = orders.id_o")->aWhere(array(
				"righe.id_c"	=>	$this->viewArgs['id_comb'],
			));
		}
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->m[$this->modelName]->sWhere("DATE_FORMAT(data_creazione, '%Y-%m-%d') >= '".getIsoDate($this->viewArgs['dal'])."'");
		
		if ($this->viewArgs['al'] != "tutti")
			$this->m[$this->modelName]->sWhere("DATE_FORMAT(data_creazione, '%Y-%m-%d') <= '".getIsoDate($this->viewArgs['al'])."'");
		
		if (strcmp($this->viewArgs['lista_regalo'],'tutti') !== 0)
			$this->m[$this->modelName]->inner(array("lista"))->where(array(
				" OR"	=>	array(
					"lk"	=>	array(
						"liste_regalo.titolo"	=>	$this->viewArgs['lista_regalo'],
					),
					" lk"	=>	array(
						"liste_regalo.codice"	=>	$this->viewArgs['lista_regalo'],
					),
					
				),
			));
		
		$this->m[$this->modelName]->save();
		
		$filtroTipo = array(
			"tutti"		=>	"Tipo cliente",
			"privato"	=>	"Privato",
			"libero_professionista"	=>	"Professionista",
			"azienda"	=>	"Azienda",
		);
		
		$filtroStato = array(
			"tutti"		=>	"Stato ordine",
		) + OrdiniModel::$stati;
		
		$this->filters = array("dal","al",'id_o','email','codice_fiscale',array("tipo_cliente",null,$filtroTipo),array("stato",null,$filtroStato));
		
		if (v("attiva_ip_location"))
			$this->filters[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new OrdiniModel()));
		
		if (v("attiva_liste_regalo"))
			$this->filters[] = "lista_regalo";
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = "torna_ordine,save";
		
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost('tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,indirizzo_spedizione,cap_spedizione,provincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,stato,nazione,pec,codice_destinatario');
		
		parent::form($queryType, $id);
	}
	
	public function integrazioni($id = 0)
	{
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		parent::integrazioni($id);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["OrdiniModel"]->titolo((int)$id);
		
		$this->append($data);
	}
	
	public function righe($id = 0)
	{
		$this->_posizioni['righe'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_o";
		
		$this->mainButtons = "";
		
		$this->modelName = "RigheModel";
		
// 		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("righe.title");
		$this->mainHead = "Articolo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("id_order")->where(array("id_o"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["OrdiniModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function setstato($id_o, $stato)
	{
		$this->shift(2);
		
		$this->clean();
		
		$res = $this->m["OrdiniModel"]->clear()->where(array("id_o"=>(int)$id_o))->send();
		
		if (isset(OrdiniModel::$stati[$stato]) && count($res) > 0)
		{
			$this->m["OrdiniModel"]->setValues(array(
				"stato"	=>	$stato,
			));
			
			if ($this->m["OrdiniModel"]->update((int)$id_o))
			{
				switch ($stato)
				{
					case "pending":
						$this->m["OrdiniModel"]->mandaMail($id_o);
						break;
					case "completed":
						$this->m["OrdiniModel"]->mandaMailCompleted($id_o);
						break;
					case "closed":
						$this->m["OrdiniModel"]->mandaMailClosed($id_o);
						break;
					case "deleted":
						$this->m["OrdiniModel"]->mandaMailDeleted($id_o);
						break;
				}
			}
		}
		
		$this->redirect($this->applicationUrl.$this->controller."/vedi/".(int)$id_o.$this->viewStatus);
	}
	
	public function vedi($id_o)
	{
		@session_start();
		
		$this->shift(1);
		
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$data['notice'] = null;
		
		$clean["id_o"] = $data["id"] = (int)$id_o;
// 		$clean["admin_token"] = $data["admin_token"] = sanitizeAll($admin_token);
		
		$data["notice_send"] = null;
		
		if (isset($_GET["action"]))
		{
			$this->m["OrdiniModel"]->mandaMail($clean["id_o"]);
// 			$data["notice_send"] = $this->m["OrdiniModel"]->notice;
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		if (isset($_GET["invia_fattura"]))
		{
			$this->m["OrdiniModel"]->mandaMailFattura($clean["id_o"]);
// 			$data["notice_send"] = $this->m["OrdiniModel"]->notice;
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		$res = $this->m["OrdiniModel"]->clear()
							->where(array("id_o" => $clean["id_o"]))
							->send();
		
		$data["righeOrdine"] = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		
		$this->helper("Menu",$this->applicationUrl.$this->controller,"panel");
		
		$this->h["Menu"]->links['edit']['url'] = 'form/update/'.$clean["id_o"];
		$this->h["Menu"]->links['edit']['title'] = "Modifica ordine";
		
		$this->h["Menu"]->links['manda_mail']['url'] = 'vedi/'.$clean["id_o"];
		$this->h["Menu"]->links['manda_mail']['class'] = 'mainMenuItem';
// 		$this->h["Menu"]->links['manda_mail']['text'] = 'Invia mail';
		$this->h["Menu"]->links['manda_mail']['queryString'] = '?n=y&action=send';
		$this->h["Menu"]->links['manda_mail']['icon'] = $this->baseUrl.'/Public/Img/Icons/mail_small.png';
		$this->h["Menu"]->links['manda_mail']['title'] = "Invia nuovamente la mail dell'ordine al cliente";
		
		$data["menu"] = $this->h["Menu"]->render("back,edit,manda_mail");
		
		if (count($res) > 0)
		{
			$data["integrazioni"] = IntegrazioniModel::getElencoPulsantiIntegrazione($res[0]["orders"]["id_o"], $this->controller);
			
			$this->_posizioni['main'] = 'class="active"';
			$data['posizioni'] = $this->_posizioni;
			
			$data["cliente"] = null;
			
			if ($res[0]["orders"]["id_user"] > 0)
			{
				$data["cliente"] = $this->m["RegusersModel"]->selectId($res[0]["orders"]["id_user"]);
			}
			
			$data["ordine"] = $res[0]["orders"];
			
			$data["tipoOutput"] = "web";
			
			$data["corriere"] = $this->m["CorrieriModel"]->selectId($data["ordine"]["id_corriere"]);
			
			$data["fatture"] = $this->m["FattureModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
			
			$data["mail_fatture"] =  $this->m["MailordiniModel"]->clear()->where(array(
				"id_o"	=>	$clean["id_o"],
				"tipo"=>	"F",
			))->orderBy("data_creazione desc")->send(false);
			
			$data["mail_altre"] =  $this->m["MailordiniModel"]->clear()->where(array(
				"id_o"	=>	$clean["id_o"],
				"ne"	=>	array("tipo"=>	"F"),
				"tipologia"	=>	"ORDINE",
			))->orderBy("data_creazione desc")->send(false);
			
			$this->append($data);
			$this->load('vedi');
		}
	}
}
