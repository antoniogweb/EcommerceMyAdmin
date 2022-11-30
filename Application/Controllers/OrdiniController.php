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

Helper_List::$filtersFormLayout["filters"]["tipo_ordine"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class OrdiniController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $addIntegrazioniInMain = false;
	
	public $tabella = "ordini";
	
	public $argKeys = array(
		'page:forceInt'=>1,
		'id_ordine:sanitizeAll'=>'tutti',
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
		'tipo_ordine:sanitizeAll'=>'tutti',
		'id_lista_insert:sanitizeAll'=>'tutti',
		'from:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->session('admin');
		$this->model();

		$this->model("OrdiniModel");
		$this->model("RigheModel");
		$this->model("RegusersModel");
		$this->model("CorrieriModel");
		$this->model("MailordiniModel");
		$this->model("SpedizioniModel");
		
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
		
		$mainMenu = (v("permetti_ordini_offline") && !partial()) ? "add" : "";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>$mainMenu, 'modifyAction'=>'vedi');
		
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
		
		$this->mainButtons = "ldel,ledit";
		
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
		
		if (v("permetti_ordini_offline"))
		{
			$this->mainFields[] = 'tipoOrdineCrud';
			$this->mainHead .= ',Tipo';
		}
		
		$this->aggiungiintegrazioni();
		
		$this->m[$this->modelName]->clear()->orderBy("orders.id_o desc");
		
		$where = array(
			'id_o'	=>	$this->viewArgs['id_ordine'],
			'stato'	=>	$this->viewArgs['stato'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			'pagamento'	=>	$this->viewArgs['pagamento'],
			'registrato'	=>	$this->viewArgs['registrato'],
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
			'id_lista_regalo'	=>	$this->viewArgs['id_lista_regalo'],
			'tipo_ordine'	=>	$this->viewArgs['tipo_ordine'],
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
		
		$this->filters = array("dal","al",'id_ordine','email','codice_fiscale',array("tipo_cliente",null,$filtroTipo),array("stato",null,$filtroStato));
		
		if (v("attiva_ip_location"))
			$this->filters[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new OrdiniModel()));
		
		if (v("attiva_liste_regalo"))
			$this->filters[] = "lista_regalo";
		
		if (v("permetti_ordini_offline"))
			$this->filters[] = array("tipo_ordine",null,array(
				"tutti"	=>	gtext("Tipo ordine"),
				"W"		=>	gtext("Ordini WEB"),
				"B"		=>	gtext("Ordini Backend"),
			));
			
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		if( !session_id() )
			session_start();
		
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		if (!isset($_POST["id_user"]))
			$_SESSION["id_user"] = !empty($record) ? (int)$record["id_user"] : 0;
		
		if (!isset($_POST["id_spedizione"]))
			$_SESSION["id_spedizione"] = !empty($record) ? (int)$record["id_spedizione"] : 0;
		
		if (isset($_POST["id_user"]))
		{
			$cliente = $this->m["RegusersModel"]->selectId((int)$_POST["id_user"]);
			
			if (!empty($cliente))
			{
				if ((int)$_POST["id_user"] && (int)$_POST["id_user"] !== (int)$_SESSION["id_user"])
				{
					$_SESSION["id_user"] = (int)$_POST["id_user"];
					
					$campiDaCopiare = OpzioniModel::arrayValori("CAMPI_DA_COPIARE_DA_ORDINE_A_CLIENTE");
					
					foreach ($campiDaCopiare as $cdc)
					{
						if (isset($cliente[$cdc]))
							$_POST[$cdc] = $cliente[$cdc];
					}
					
					$_POST["email"] = $cliente["username"];
				}
				
				if ((int)$_POST["id_spedizione"] && (int)$_POST["id_spedizione"] !== (int)$_SESSION["id_spedizione"])
				{
					$spedizione = $this->m["SpedizioniModel"]->selectId((int)$_POST["id_spedizione"]);
					
					if (!empty($spedizione))
					{
						$campiDaCopiare = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
						
						foreach ($campiDaCopiare as $cdc)
						{
							if (isset($spedizione[$cdc]))
								$_POST[$cdc] = $spedizione[$cdc];
						}
					}
				}
			}
		}
		
		$idUser = isset($_POST["id_user"]) ? $_POST["id_user"] : $_SESSION["id_user"];
		
		$lingua = $this->m["RegusersModel"]->getLingua((int)$idUser);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinksInsert = partial() ? "save" : "back,save";
		$this->menuLinks = "torna_ordine,save";
		
		$this->shift(2);
		
		$fields = 'tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,indirizzo_spedizione,cap_spedizione,provincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,stato,nazione,pec,codice_destinatario,pagamento,dprovincia,dprovincia_spedizione,note';
		
		if (v("permetti_modifica_cliente_in_ordine"))
			$fields .= ",id_user,id_spedizione";
		
		if (v("attiva_liste_regalo"))
			$fields .= ",dedica,firma";
		
		if (v("permetti_ordini_offline") && (!$id || OrdiniModel::g()->isDeletable((int)$id)))
		{
			$fields .= ",id_corriere,id_p";
			
			$fields .= ",id_iva";
			$this->disabledFields = "id_iva";
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$this->m[$this->modelName]->setValue("lingua", $lingua);
		
		if ($queryType == "insert" && $this->viewArgs['id_lista_insert'] != "tutti")
			$this->m[$this->modelName]->setValue("id_lista_regalo", $this->viewArgs['id_lista_insert']);
		
		if ($this->disabledFields)
			$this->m[$this->modelName]->delFields($this->disabledFields);
		
		parent::form($queryType, $id);
		
		$data["tipoSteps"] = "modifica";
		$this->append($data);
	}
	
	public function integrazioni($id = 0)
	{
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		parent::integrazioni($id);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["OrdiniModel"]->titolo((int)$id);
		
		$data["tipoSteps"] = "vedi";
		$this->append($data);
	}
	
	public function righe($id = 0)
	{
		if (!v("permetti_ordini_offline") || OrdiniModel::tipoOrdine((int)$id) == "W")
			$this->redirect("ordini/vedi/".(int)$id);
		
		$this->m["OrdiniModel"]->checkAggiorna((int)$id);
		
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		$this->_posizioni['righe'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_o";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "RigheModel";
		
		if (!OrdiniModel::g()->isDeletable($id))
		{
			$this->addBulkActions = false;
			$this->colProperties = array();
		}
		
// 		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/;righe.id_page;/;righe.immagine;' />", "righe.title", "attributiCrud", "righe.codice", "prezzoInteroCrud", "prezzoScontatoCrud", "quantitaCrud", ";righe.iva;%");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,Prezzo pieno,Prezzo scontato,Quantità,Aliquota";
		
		$pulsantiMenu = "torna_ordine";
		
		if (OrdiniModel::g()->isDeletable($id))
			$pulsantiMenu .= ",save_righe";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("id_order")->where(array("id_o"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["id_lista_regalo"] = $this->m["OrdiniModel"]->whereId($clean['id'])->field("id_lista_regalo");
		$data["titoloRecord"] = $this->m["OrdiniModel"]->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		
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
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		if (isset($_GET["invia_fattura"]))
		{
			$this->m["OrdiniModel"]->mandaMailFattura($clean["id_o"]);
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		$res = $this->m["OrdiniModel"]->clear()
							->where(array("id_o" => $clean["id_o"]))
							->send();
		
		$data["righeOrdine"] = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->orderBy("id_order")->send();
		
		$this->helper("Menu",$this->applicationUrl.$this->controller,"panel");
		
		$this->h["Menu"]->links['edit']['url'] = 'form/update/'.$clean["id_o"];
		$this->h["Menu"]->links['edit']['title'] = "Modifica ordine";
		
		$this->h["Menu"]->links['manda_mail']['url'] = 'vedi/'.$clean["id_o"];
		$this->h["Menu"]->links['manda_mail']['class'] = 'mainMenuItem';
// 		$this->h["Menu"]->links['manda_mail']['text'] = 'Invia mail';
		$this->h["Menu"]->links['manda_mail']['queryString'] = '?n=y&action=send';
		$this->h["Menu"]->links['manda_mail']['icon'] = $this->baseUrl.'/Public/Img/Icons/mail_small.png';
		$this->h["Menu"]->links['manda_mail']['title'] = "Invia nuovamente la mail dell'ordine al cliente";
		
		$menuButtons = $this->viewArgs["from"] == "liste" ? "edit,manda_mail" : "back,edit,manda_mail";
		
		$data["menu"] = $this->h["Menu"]->render($menuButtons);
		
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
// 				"ne"	=>	array("tipo"=>	"F"),
				"tipologia"	=>	"ORDINE",
			))->orderBy("data_creazione desc")->send(false);
			
			$data["tipoSteps"] = "vedi";
			$this->append($data);
			
			$this->append($data);
			$this->load('vedi');
		}
	}
}
