<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class OrdiniController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
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
			'nazione_utente:sanitizeAll'=>'tutti'
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
	}

	public function main()
	{
		$this->shift();

		Params::$nullQueryValue = 'tutti';

		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>20, 'mainMenu'=>'panel'));
		
		$mainFields = array(
			'<a href="'.$this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/vedi/;orders.id_o;'.$this->viewStatus.'">#;orders.id_o;</a>',
			'smartDate|orders.data_creazione',
			'OrdiniModel.getNome|orders.id_o',
			'orders.email',
			'orders.tipo_cliente',
			'OrdiniModel.getCFoPIva|orders.id_o',
			'orders.nome_promozione',
			'statoordinelabel',
			'totaleCrud',
		);
		
		$headLabels = 'N°,Data,Nome/Rag.Soc,Email,Tipo,C.F./P.IVA,Promoz.,Stato,Totale';
		
		if (v("attiva_ip_location"))
		{
			$mainFields[] = 'nazionenavigazione';
			$headLabels .= ',Nazione';
		}
		
		$this->scaffold->loadMain($mainFields,'orders.id_o','');
		
		$vediOrdineLink = "<a class='text_16 action_edit' title='vedi ordine ;orders.id_o;' href='".$this->baseUrl."/".$this->applicationUrl.$this->controller."/vedi/;orders.id_o;".$this->viewStatus."'><i class='verde fa fa-arrow-right'></i></a>";
		
// 		$this->scaffold->addItem('text',";OrdiniModel.pulsanteFattura|orders.id_o;");
		$this->scaffold->addItem('text',$vediOrdineLink);
		
		$this->scaffold->setHead($headLabels);
		
		$this->scaffold->model->clear()->orderBy("orders.id_o desc");
		
		$where = array(
			'id_o'	=>	$this->viewArgs['id_o'],
			'stato'	=>	$this->viewArgs['stato'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			'pagamento'	=>	$this->viewArgs['pagamento'],
			'registrato'	=>	$this->viewArgs['registrato'],
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
		);
		
		$this->scaffold->model->where($where);
		
		if (strcmp($this->viewArgs['email'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('orders.email' => $this->viewArgs['email']),
			);

			$this->scaffold->model->aWhere($where);
		}
		
		if (strcmp($this->viewArgs['codice_fiscale'],'tutti') !== 0)
		{
			$where = array(
				"OR"	=> array(
					"lk" => array('orders.codice_fiscale' => $this->viewArgs['codice_fiscale']),
					" lk" => array('orders.p_iva' => $this->viewArgs['codice_fiscale']),
					)
			);

			$this->scaffold->model->aWhere($where);
		}
		
		if ($this->viewArgs['id_comb'] != "tutti")
		{
			$this->scaffold->model->groupBy("orders.id_o")->inner("righe")->on("righe.id_o = orders.id_o")->aWhere(array(
				"righe.id_c"	=>	$this->viewArgs['id_comb'],
			));
		}
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->scaffold->model->sWhere("DATE_FORMAT(data_creazione, '%Y-%m-%d') >= '".getIsoDate($this->viewArgs['dal'])."'");
		
		if ($this->viewArgs['al'] != "tutti")
			$this->scaffold->model->sWhere("DATE_FORMAT(data_creazione, '%Y-%m-%d') <= '".getIsoDate($this->viewArgs['al'])."'");
		
		$this->scaffold->itemList->aggregateFilters();
		$this->scaffold->itemList->showFilters = false;
		$filtroTipo = array(
			"tutti"		=>	"Tipo cliente",
			"privato"	=>	"Privato",
			"libero_professionista"	=>	"Professionista",
			"azienda"	=>	"Azienda",
		);
		
		$filtroStato = array(
			"tutti"		=>	"Stato ordine",
		) + OrdiniModel::$stati;
		
		$filtri = array("dal","al",'id_o','email','codice_fiscale',array("tipo_cliente",null,$filtroTipo),array("stato",null,$filtroStato));
		
		if (v("attiva_ip_location"))
		{
			$filtri[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new OrdiniModel()));
		}
		
		$this->scaffold->itemList->setFilters($filtri);
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
		$data["filtri"] = $this->scaffold->itemList->createFilters();
		
		$data["tabella"] = "ordini";
		
		$this->append($data);
		$this->load('main');
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = "torna_ordine,save";
		
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost('tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,indirizzo_spedizione,cap_spedizione,provincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,stato,nazione,pec,codice_destinatario');
		
		parent::form($queryType, $id);
	}
	
// 	public function form($queryType = 'update', $id = 0)
// 	{
// 		$this->shift(3);
// 		
// 		$qAllowed = array("update");
// 		
// 		if (in_array($queryType,$qAllowed))
// 		{
// 			$clean['id'] = (int)$id;
// 			$clean["admin_token"] = $this->request->get("admin_token","","sanitizeAll");
// 			
// // 			if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
// 			
// 			$this->m['OrdiniModel']->setFields('tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,indirizzo_spedizione','sanitizeAll');
// 			
// 			$this->m['OrdiniModel']->updateTable('update',$clean['id']);
// 
// 			$params = array(
// 				'formMenu'=>'torna_ordine',
// 			);
// 		
// 			$this->loadScaffold('form',$params);
// 			$this->scaffold->loadForm($queryType,$this->applicationUrl.$this->controller."/form/$queryType/".$clean['id']."/".$clean["admin_token"]);
// 			
// 			$this->scaffold->mainMenu->links['torna_ordine']['url'] = 'vedi/'.$clean['id']."/".$clean["admin_token"];
// // 			$this->scaffold->mainMenu->links['torna_ordine']['queryString'] = '?n=y';
// 			$this->scaffold->mainMenu->links['torna_ordine']['title'] = "Torna alla pagina di dettaglio dell'ordine";
// 			
// 			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);
// 
// 			$data['scaffold'] = $this->scaffold->render();
// 			
// 			$this->append($data);
// 			$this->load('form');
// 		}
// 	}
	
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
		
		$clean["id_o"] = (int)$id_o;
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
			$data["integrazioni"] = IntegrazioniModel::getElencoPulsantiIntegrazione($res[0]["orders"]["id_user"], "ORDINE");
// 			if ()
// 			if (isset($_POST["modifica_stato_ordine"]) and strcmp($clean["admin_token"],$res[0]["orders"]["admin_token"]) === 0)
// 			{
// 				$clean['stato'] = $this->request->post("stato","pending","sanitizeAll");
// 				$statiPermessi = array("pending","deleted","completed");
// 				
// 				if (in_array($clean['stato'],$statiPermessi))
// 				{
// 					$this->m["OrdiniModel"]->values = array("stato" => $clean['stato']);
// 					$this->m["OrdiniModel"]->update($clean["id_o"]);
// 					$data['notice'] = $this->m["OrdiniModel"]->notice;
// 				}
// 			}
			
// 			$res = $this->m["OrdiniModel"]->clear()
// 								->where(array("id_o" => $clean["id_o"]))
// 								->send();
			
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
