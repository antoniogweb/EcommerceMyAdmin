<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class RegusersController extends BaseController {

// 	protected $_posizioni = array(
// 		"main"		=>	null,
// 		"gruppi"	=>	null,
// 	);
	
	public $tabella = "clienti";
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();
		$this->model("RegusersgroupsModel");
		$this->model("SpedizioniModel");
		$this->model("OrdiniModel");
		
// 		$data["sezionePannello"] = "ecommerce";
		
		$this->setArgKeys(array('page:forceInt'=>1,'username:sanitizeAll'=>'tutti','tipo_cliente:sanitizeAll'=>'tutti','codice_fiscale:sanitizeAll'=>'tutti','has_confirmed:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token','page_fgl:forceInt'=>1, 'partial:sanitizeAll'=>'tutti', 'p_iva:sanitizeAll'=>'tutti','nazione_utente:sanitizeAll'=>'tutti'));

		$this->_topMenuClasses['clienti'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$this->append($data);
		
		$this->s['admin']->check();
	}

	public function main() { //view all the users

		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'add'));
		
		$mainFields = '[[checkbox]];regusers.id_user;,[[ledit]];regusers.username;,nome,regusers.tipo_cliente,regusers.codice_fiscale,regusers.p_iva,getYesNoUtenti|regusers:has_confirmed';
		$headLabels = '[[bulkselect:checkbox_regusers_id_user]],Email,Nome/r.soc,Tipo cliente,C.F., P.IVA,Attivo?';
		
		$filtri = array(null,'username',null,null,'codice_fiscale','p_iva',null);
		
		if (v("attiva_gruppi_utenti"))
		{
			$mainFields .= ',RegusersModel.listaGruppi|regusers.id_user';
			$headLabels .= ',Gruppi';
			$filtri[] = null;
		}
		
		if (v("attiva_ip_location"))
		{
			$mainFields .= ',nazionenavigazione';
			$headLabels .= ',Nazione';
			
			$filtri[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new RegusersModel()));
		}
		
		$this->scaffold->itemList->setFilters($filtri);
		
		$this->scaffold->itemList->setBulkActions(array(
			"checkbox_regusers_id_user"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$this->scaffold->loadMain($mainFields,'regusers:id_user','ldel,ledit');

		$this->scaffold->update('del');
		
		$this->m[$this->modelName]->bulkAction("del");
		
		$this->scaffold->setHead($headLabels);
		
		$whereClauseArray = array(
			'has_confirmed'	=>	$this->viewArgs['has_confirmed'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			"lk" => array('n!regusers.codice_fiscale' => $this->viewArgs['codice_fiscale']),
			" lk" => array('n!regusers.p_iva' => $this->viewArgs['p_iva']),
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
		);
		$this->scaffold->model->where($whereClauseArray);
		
// 		print_r($this->scaffold->model->foreignKeys);
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		
		if (strcmp($this->viewArgs['username'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('n!regusers.username' => $this->viewArgs['username']),
			);

			$this->scaffold->model->aWhere($where);
		}

		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
		
		$data['tabella'] = "utente sito web";
		
		$this->append($data);
		$this->load('main');
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->shift(2);
		
		$fields = 'username,has_confirmed,password:sha1,tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,id_classe,nazione,pec,codice_destinatario,lingua';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$formFields = 'username,has_confirmed,password,confirmation,tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,id_classe,nazione,pec,codice_destinatario,lingua';
		
		if (v("attiva_ruoli"))
		{
			$fields .= ",id_ruolo";
			$formFields .= ",id_ruolo";
		}
		
		$this->formFields = $formFields;
		
		parent::form($queryType, $id);
		
		if (strcmp($queryType,'update') === 0)
		{
			$data['numeroElementi'] = $this->m["RegusersgroupsModel"]->where(array("id_user"=>(int)$id))->rowNumber();
			
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
