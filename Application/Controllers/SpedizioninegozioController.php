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

Helper_List::$filtersFormLayout["filters"]["id_spedizioniere"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["id_sped"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"ID spedizione ..",
	),
);

Helper_List::$filtersFormLayout["filters"]["numero_spedizione"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Numero spedizione ..",
	),
);

class SpedizioninegozioController extends BaseController {
	
	public $argKeys = array(
		'id_o:sanitizeAll'=>'tutti', // -> usato durante l'inserimento
		'id_lista_regalo:sanitizeAll'=>'tutti', // -> usato durante l'inserimento
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'id_ordine:sanitizeAll'=>'tutti', // -> usato per il filtro
		'id_spedizioniere:sanitizeAll'=>'tutti',
		'stato:sanitizeAll'=>'tutti',
		'numero_spedizione:sanitizeAll'=>'tutti',
		'id_spedizione_negozio_invio:sanitizeAll'=>'tutti',
		'id_sped:sanitizeAll'=>'tutti', // -> usato durante l'inserimento
	);
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = gtext("spedizioni negozio",true);
		
		$this->model("SpedizioninegoziorigheModel");
		$this->model("SpedizioninegozioeventiModel");
		$this->model("SpedizioninegoziocolliModel");
		$this->model("SpedizioninegozioserviziModel");
		$this->model("SpedizioninegozioinfoModel");
		
		Params::$exitAtFirstFailedValidation = false;
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>"add");
		
		$this->mainFields = array("spedizioni_negozio.id_spedizione_negozio", "ordiniCrud", "spedizioni_negozio.numero_spedizione", "cleanDateTimeSpedizione", "spedizioni_negozio.contrassegno" , "brderoCrud", "statoCrud", "trackingCrud", "spedizionieri.titolo", "spedizioni_negozio.ragione_sociale", "spedizioni_negozio.email", "indirizzoCrud", "nazioneCrud");
		$this->mainHead = "ID,Ordine,Numero Spedizione,Data spedizione,Contrassegno,Borderò,Stato,Tracking,Spedizioniere,Ragione sociale,Email,Indirizzo,Nazione";
		
		if (v("attiva_liste_regalo"))
		{
			$this->mainFields[] = 'listaregalo';
			$this->mainHead .= ',Lista regalo';
		}
		
		$filtroSpedizioniere = array(
			"tutti"		=>	"Spedizioniere",
		) + $this->m("SpedizionieriModel")->selectTendina(false);
		
		$filtroStato = array(
			"tutti"		=>	"Stato spedizione",
		) + $this->m("SpedizioninegoziostatiModel")->selectTendina(false);
		
		$this->filters = array("dal","al",'id_ordine','id_sped','numero_spedizione',array("stato",null,$filtroStato),array("id_spedizioniere",null,$filtroSpedizioniere));
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("spedizioniere","invio"))
				->where(array(
					"spedizioni_negozio.id_spedizione_negozio"	=>	$this->viewArgs['id_sped'],
					"spedizioni_negozio.id_spedizioniere"	=>	$this->viewArgs['id_spedizioniere'],
					"spedizioni_negozio.stato"	=>	$this->viewArgs['stato'],
					"spedizioni_negozio.numero_spedizione"	=>	$this->viewArgs['numero_spedizione'],
					"spedizioni_negozio.id_spedizione_negozio_invio"	=>	$this->viewArgs['id_spedizione_negozio_invio'],
				))
				->orderBy("spedizioni_negozio.data_spedizione desc,spedizioni_negozio.id_spedizione_negozio desc")->convert();
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(spedizioni_negozio.data_spedizione, '%Y-%m-%d') >= ?",array(getIsoDate($this->viewArgs['dal']))));
		
		if ($this->viewArgs['al'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(spedizioni_negozio.data_spedizione, '%Y-%m-%d') <= ?",array(getIsoDate($this->viewArgs['al']))));
		
		if ($this->viewArgs['id_ordine'] != "tutti")
		{
			$this->m[$this->modelName]->inner(array("righe"))->inner("righe")->on("righe.id_r = spedizioni_negozio_righe.id_r")->aWhere(array(
				"righe.id_o"	=>	(int)$this->viewArgs['id_ordine'],
			))->groupBy("spedizioni_negozio.id_spedizione_negozio");
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		if ($queryType == "insert")
		{
			$fields = "id_spedizioniere";
			
			$this->menuLinksInsert = "";
		}
		else
		{
			$fields = $this->m[$this->modelName]->getCampiFormUpdate(false, (int)$id);
			
			if ($this->viewArgs["partial"] == "Y")
				$this->menuLinks = "save";
			
			if (!SpedizioninegozioModel::aperto((int)$id))
			{
				$this->menuLinks = partial() ? "" : "back";
				
				if ($this->m[$this->modelName]->idLetteraDiVettura((int)$id))
					$fields .= ",numero_spedizione";
			}
		}
		
		if ($queryType == "update" && SpedizioninegozioModel::aperto((int)$id))
			$this->m[$this->modelName]->setUpdateConditions((int)$id);
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$campiDaDisabilitare = $queryType == "update" ? "id_spedizioniere,note" : "note";
		
		if ($queryType == "update" && !SpedizioninegozioModel::aperto((int)$id))
			$campiDaDisabilitare = $this->m[$this->modelName]->getCampiFormUpdate(true, (int)$id);
		
		$this->disabledFields = $campiDaDisabilitare;
		$this->m[$this->modelName]->delFields($campiDaDisabilitare);
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['righe'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizioninegoziorigheModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/;righe.id_page;/;righe.immagine;' />", "#;righe.id_o;", "righe.title", "righe.attributi", "righe.codice", "quantitaCrud");
		$this->mainHead = "Immagine,Ordine,Articolo,Variante,Codice,Quantità";
		
		$pulsantiMenu = partial() ? "" : "back";
		
		if (SpedizioninegozioModel::g()->deletable($id))
			$pulsantiMenu .= ",save_righe_spedizione";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("riga"))->orderBy("righe.id_o,id_spedizione_negozio_riga")->where(array("id_spedizione_negozio"=>$clean['id']))->convert()->save();
		
		$data["righeDaSpedireSelect"] = $this->m["SpedizioninegozioModel"]->getSelectRigheDaSpedire($id); 
		
		$this->m[$this->modelName]->setFields('id_r','sanitizeAll');
		
		$this->m[$this->modelName]->values['id_spedizione_negozio'] = $clean['id'];
		
		$this->m[$this->modelName]->updateTable('insert');
		
		if ($this->m[$this->modelName]->queryResult)
		{
			$this->m["SpedizioninegozioModel"]->ricalcolaContrassegno($clean['id']);
		}
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function colli($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['colli'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizioninegoziocolliModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("pesoCrud");
		$this->mainHead = "Peso (kg)";
		
		$pulsantiMenu = partial() ? "" : "back";
		
		if (SpedizioninegozioModel::g()->deletable($id))
			$pulsantiMenu .= ",save_colli_spedizione";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"colli/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->orderBy("id_spedizione_negozio_collo")->where(array("id_spedizione_negozio"=>$clean['id']))->convert()->save();
		
		$this->m[$this->modelName]->setFields('peso','sanitizeAll');
		
		$this->m[$this->modelName]->values['id_spedizione_negozio'] = $clean['id'];
		
		$this->m[$this->modelName]->updateTable('insert');
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function servizi($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['servizi'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizioninegozioserviziModel";
		
		$this->mainFields = array("spedizioni_negozio_servizi.codice", "spedizioni_negozio_servizi.titolo");
		$this->mainHead = "Codice,Titolo";
		
		$pulsantiMenu = partial() ? "" : "back";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"servizi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->orderBy("id_order")->where(array("id_spedizione_negozio"=>$clean['id']))->convert()->save();
		
		$this->m[$this->modelName]->setFields('codice','sanitizeAll');
		
		$this->m[$this->modelName]->values['id_spedizione_negozio'] = $clean['id'];
		
		$this->m[$this->modelName]->updateTable('insert');
		
		parent::main();
		
		$data["selectServizi"] = SpedizioninegozioModel::getElencoServizi($clean['id']);
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function eventi($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['eventi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "";
		
		$this->modelName = "SpedizioninegozioeventiModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		$this->queryActions = "";
		$this->bulkQueryActions = "";
		
// 		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("cleanDateTime", "titoloCrud", "emailCrud");
		$this->mainHead = "Data / ora,Titolo,Notifica";
		
		$pulsantiMenu = partial() ? "" : "back";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"eventi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->left("eventi_retargeting_elemento")->on("tabella_elemento = 'spedizioni_negozio_eventi' and eventi_retargeting_elemento.id_elemento = spedizioni_negozio_eventi.id_spedizione_negozio_evento")->where(array("id_spedizione_negozio"=>$clean['id']))->orderBy("spedizioni_negozio_eventi.id_spedizione_negozio_evento")->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function info($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$idSpedizioneInvio = (int)$this->m["SpedizioninegozioModel"]->whereId((int)$id)->field("id_spedizione_negozio_invio");
		
		$this->_posizioni['info'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "";
		
		$this->modelName = "SpedizioninegozioinfoModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		$this->queryActions = "";
		$this->bulkQueryActions = "";
		
// 		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("cleanDateTime", "spedizioni_negozio_info.codice_info", "vediCrud");
		$this->mainHead = "Data / ora,Tipologia,Vedi";
		
		$pulsantiMenu = partial() ? "" : "back";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"info/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->where(array(
			"OR"	=>	array(
				"id_spedizione_negozio"			=>	$clean['id'],
				"AND"	=>	array(
					"id_spedizione_negozio_invio"	=>	(int)$idSpedizioneInvio,
					"ne"	=>	array(
						"id_spedizione_negozio_invio"	=>	0,
					),
				),
			),
		))->orderBy("spedizioni_negozio_info.id_spedizione_negozio_info desc")->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	// Setta la spedizione come pronta da inviare (stato = I)
	public function prontadainviare($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		if (!$this->m($this->modelName)->prontaDaInviare($id))
			flash("notice",$this->m($this->modelName)->notice);
		
		$this->redirect("spedizioninegozio/form/update/".(int)$id.$this->viewStatus);
	}
	
	// Setta la spedizione come aperta (stato = A) la spedizione $id
	public function apri($id = 0, $forza = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		if (!$this->m($this->modelName)->apri($id, $forza))
			flash("notice",$this->m($this->modelName)->notice);
		
		$this->redirect("spedizioninegozio/form/update/".(int)$id.$this->viewStatus);
	}
	
	// Imposta allo stato II la spedizione $id
	public function conferma($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		$spedizione = $this->m($this->modelName)->selectId((int)$id);
		
		if (!empty($spedizione) && SpedizioninegozioModel::pronta((int)$id) && $this->m($this->modelName)->idLetteraDiVettura((int)$id))
			SpedizioninegozioModel::g(false)->settaStato((int)$id, "II", "data_invio");
		
		$this->redirect("spedizioninegozio/form/update/".(int)$id.$this->viewStatus);
	}
	
	// Conferma le spedizioni prenotate
	// $id dell'invio (borderò)
	public function confermaspedizioni($id = 0, $idInvio = 0)
	{
		$this->shift(2);
		
		$this->clean();
		
		$this->m("SpedizioninegozioModel")->inviaAlCorriere((int)$id, (int)$idInvio);
	}
	
	public function controllaspedizioni($id = 0, $forza = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		if ($forza)
			Spedizioniere::$forzaRichiestaInfo = true;
		
		$this->m($this->modelName)->controllaStatoSpedizioniInviate((int)$id);
	}
	
	// Stampa il segnacollo della spedizione avente ID = $id
	public function segnacollo($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		$this->m($this->modelName)->segnacollo((int)$id);
	}
	
	// Stampa il segnacollo della spedizione avente ID = $id
	public function letteradivettura($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		$path = $this->m($this->modelName)->letteradivettura((int)$id);
		
		if (is_array($path))
			parent::scaricaFile($path[0], $path[1]);
	}
	
	public function vediinfo($idInfo)
	{
		$this->model("SpedizioninegozioinfoModel");
		
		$data["record_evento"] = $this->m("SpedizioninegozioinfoModel")->clear()->select("*")->whereId((int)$idInfo)->first();
		
		$modulo = SpedizionieriModel::getModulo((int)$data["record_evento"]["spedizioni_negozio_info"]["id_spedizioniere"], true);
		
		$data["output"] = $modulo ? $modulo->decodeOutput($data["record_evento"]["spedizioni_negozio_info"]["descrizione"]) : $data["record_evento"]["spedizioni_negozio_info"]["descrizione"];
		
		$this->append($data);
		$this->load("vedi_info");
	}
}
