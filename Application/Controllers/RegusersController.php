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

Helper_List::$filtersFormLayout["filters"]["codice_fiscale"]["attributes"]["placeholder"] = "CF ..";

class RegusersController extends BaseController {

// 	protected $_posizioni = array(
// 		"main"		=>	null,
// 		"gruppi"	=>	null,
// 	);
	
	public $argKeys = array(
		'page:forceInt'=>1,
		'username:sanitizeAll'=>'tutti',
		'tipo_cliente:sanitizeAll'=>'tutti',
		'codice_fiscale:sanitizeAll'=>'tutti',
		'has_confirmed:sanitizeAll'=>'tutti',
		'token:sanitizeAll'=>'token',
		'page_fgl:forceInt'=>1,
// 		'partial:sanitizeAll'=>'tutti',
		'p_iva:sanitizeAll'=>'tutti',
		'nazione_utente:sanitizeAll'=>'tutti',
		'id_nazione:sanitizeAll'=>'tutti',
		'codice_app:sanitizeAll'=>'tutti',
	);
	
	public $tabella = "clienti";
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();
		$this->model("RegusersgroupsModel");
		$this->model("SpedizioniModel");
		$this->model("OrdiniModel");
		$this->model("IntegrazioniloginModel");
		
// 		$data["sezionePannello"] = "ecommerce";

		$this->_topMenuClasses['clienti'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$this->append($data);
		
		$this->s['admin']->check();
	}
	
	protected function pmain()
	{
		parent::main();
	}
	
	public function main()
	{
		$this->shift();
		
		$mainFields = array('[[ledit]];regusers.username;','nome','regusers.tipo_cliente','regusers.codice_fiscale','regusers.p_iva','getYesNoUtenti|regusers:has_confirmed');
		$headLabels = 'Email,Nome/r.soc,Tipo cliente,C.F., P.IVA,Attivo?';
		
		$filtri = array('username','codice_fiscale','p_iva');
		
		if (v("attiva_gruppi_utenti"))
		{
			$mainFields[] = 'RegusersModel.listaGruppi|regusers.id_user';
			$headLabels .= ',Gruppi';
		}
		
		if (v("attiva_ip_location"))
		{
			$mainFields[] = 'nazionenavigazione';
			$headLabels .= ',Nazione';
			
			$filtri[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new RegusersModel()));
		}
		
		if (v("abilita_login_tramite_app"))
		{
			$mainFields[] = 'appCrud';
			$headLabels .= ',APP';
			
			$filtri[] = array("codice_app",null,array("tutti"=>"Fonte account","sito"=>"Sito") + $this->m["IntegrazioniloginModel"]->toList("codice", "titolo")->send());
		}
		
		$this->mainFields = $mainFields;
		$this->mainHead = $headLabels;
		
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->where(array(
			'has_confirmed'	=>	$this->viewArgs['has_confirmed'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			"lk" => array('n!regusers.codice_fiscale' => $this->viewArgs['codice_fiscale']),
			" lk" => array('n!regusers.p_iva' => $this->viewArgs['p_iva']),
			"  lk" => array('n!regusers.username' => $this->viewArgs['username']),
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
		))->convert();
		
		if ($this->viewArgs["id_nazione"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungianazione";
			
			$this->bulkActions = array(
				"checkbox_regusers_id_user"	=>	array("aggiungianazione","Aggiungi alla nazione"),
			);
			
			$this->m[$this->modelName]->sWhere("regusers.id_user not in (select id_user from regusers_nazioni where id_nazione = ".(int)$this->viewArgs["id_nazione"].")");
		}
		
		if ($this->viewArgs["codice_app"] != "tutti")
		{
			$appWhere = $this->viewArgs["codice_app"];
			
			if ($this->viewArgs["codice_app"] == "sito")
				$appWhere = "";
			
			$this->m[$this->modelName]->aWhere(array(
				"codice_app"	=>	$appWhere,
			));
		}
		
		$this->getTabViewFields("main");
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->shift(2);
		
		$fields = 'username,has_confirmed,password,tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,nazione,pec,codice_destinatario,lingua,telefono_2';
		
		$formFields = 'username,has_confirmed,password,confirmation,tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,nazione,pec,codice_destinatario,lingua,telefono_2';
		
		if (v("attiva_ruoli"))
		{
			$fields .= ",id_ruolo";
			$formFields .= ",id_ruolo";
		}
		
		if (v("attiva_tipi_azienda"))
		{
			$fields .= ",id_tipo_azienda";
			$formFields .= ",id_tipo_azienda";
		}
		
		if (v("attiva_classi_sconto"))
		{
			$fields .= ",id_classe";
			$formFields .= ",id_classe";
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$this->formFields = $formFields;
		
		parent::form($queryType, $id);
		
		if (strcmp($queryType,'update') === 0)
		{
			$data['numeroElementi'] = $this->m["RegusersgroupsModel"]->where(array("id_user"=>(int)$id))->rowNumber();
			
			$cliente = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($cliente) && $cliente["codice_app"])
				$data["appLogin"] = $this->m["IntegrazioniloginModel"]->where(array(
					"codice"	=>	sanitizeDb($cliente["codice_app"]),
				))->record();
			
			$this->append($data);
		}
	}
	
	public function spedizioni($id = 0)
	{
		$this->_posizioni['spedizioni'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_user";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizioniModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("indirizzo_spedizione","spedizioni.cap_spedizione","spedizioni.citta_spedizione","nazione", "provincia","spedizioni.ultimo_usato");
		$this->mainHead = "Indirizzo,Cap,Città,Nazione,Provincia,Ultimo usato";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"spedizioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("spedizioni.*")->orderBy("spedizioni.indirizzo_spedizione")->where(array("id_user"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["RegusersModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function ordini($id = 0)
	{
		$this->_posizioni['ordini'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_user";
		
		$this->mainButtons = "";
		
		$this->modelName = "OrdiniModel";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("vedi","smartDate|orders.data_creazione","orders.nome_promozione","statoOrdineBreve|orders.stato","totaleCrud");
		$this->mainHead = "Ordine,Data,Promoz.,Stato,Totale";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"ordini/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("orders.*")->orderBy("orders.id_o desc")->where(array("id_user"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["RegusersModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
// 	public function ordina()
// 	{
// 		$this->modelName = "RegusersgroupsModel";
// 		$this->orderBy = "id_order";
// 		
// 		parent::ordina();
// 	}
	
	public function gruppi($id = 0)
	{
		$this->_posizioni['gruppi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_user";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "RegusersgroupsModel";
		
		$this->m[$this->modelName]->setFields('id_group','sanitizeAll');
		$this->m[$this->modelName]->values['id_user'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("reggroups.name");
		$this->mainHead = "GRUPPO";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"gruppi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("regusers_groups.*,reggroups.*")->inner("reggroups")->using("id_group")->orderBy("reggroups.name")->where(array("id_user"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["listaGruppi"] = $this->m[$this->modelName]->clear()->from("reggroups")->select("reggroups.name,reggroups.id_group")->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$data['tabella'] = "utente sito web";
		
		$data["titoloRecord"] = $this->m["RegusersModel"]->where(array("id_user"=>$clean['id']))->field("username");
		
		$this->append($data);
	}

}
