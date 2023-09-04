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

class SpedizioninegozioController extends BaseController {
	
	public $argKeys = array(
		'id_o:sanitizeAll'=>'tutti', // -> usato durante l'inserimento
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'id_ordine:sanitizeAll'=>'tutti', // -> usato per il filtro
		'id_spedizioniere:sanitizeAll'=>'tutti',
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
		
		Params::$exitAtFirstFailedValidation = false;
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>"");
		
		$this->mainFields = array("spedizioni_negozio.id_spedizione_negozio", "ordiniCrud", "cleanDateTimeSpedizione", "statoCrud", "spedizionieri.titolo", "indirizzoCrud", "nazioneCrud");
		$this->mainHead = "ID,Ordine,Data spedizione,Stato,Spedizioniere,Indirizzo,Nazione";
		
		$filtroSpedizioniere = array(
			"tutti"		=>	"Spedizioniere",
		) + $this->m("SpedizionieriModel")->selectTendina(false);
		
		$this->filters = array("dal","al",'id_ordine',array("id_spedizioniere",null,$filtroSpedizioniere));
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("spedizioniere"))
				->where(array(
					"spedizioni_negozio.id_spedizioniere"	=>	$this->viewArgs['id_spedizioniere'],
				))
				->orderBy("spedizioni_negozio.data_spedizione desc,spedizioni_negozio.id_spedizione_negozio desc")->convert();
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(data_spedizione, '%Y-%m-%d') >= ?",array(getIsoDate($this->viewArgs['dal']))));
		
		if ($this->viewArgs['al'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(data_spedizione, '%Y-%m-%d') <= ?",array(getIsoDate($this->viewArgs['al']))));
		
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
			if ($this->viewArgs["id_o"] == "tutti" || !OrdiniModel::g(false)->whereId((int)$this->viewArgs["id_o"])->rowNumber())
				$this->responseCode(403);
			
			$fields = "data_spedizione,id_spedizioniere";
			
			$this->menuLinksInsert = "";
		}
		else
		{
			$fields = $this->m[$this->modelName]->getCampiFormUpdate();
			
			if ($this->viewArgs["partial"] == "Y")
				$this->menuLinks = "";
		}
		
		if ($queryType == "update" && SpedizioninegozioModel::aperto((int)$id))
			$this->m[$this->modelName]->setUpdateConditions((int)$id);
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$campiDaDisabilitare = "note,contrassegno,tipologia";
		
		if ($queryType == "update" && !SpedizioninegozioModel::aperto((int)$id))
			$campiDaDisabilitare = $this->m[$this->modelName]->getCampiFormUpdate(true);
		
		$this->disabledFields = $campiDaDisabilitare;
		$this->m[$this->modelName]->delFields($campiDaDisabilitare);
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$this->_posizioni['righe'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizione_negozio";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizioninegoziorigheModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
// 		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/;righe.id_page;/;righe.immagine;' />", "#;righe.id_o;", "righe.title", "righe.attributi", "righe.codice", "quantitaCrud");
		$this->mainHead = "Immagine,Ordine,Articolo,Variante,Codice,Quantità";
		
		$pulsantiMenu = "back";
		
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
		
		$this->mainFields = array("cleanDateTime", "spedizioni_negozio_eventi.titolo");
		$this->mainHead = "Data / ora,Titolo";
		
		$pulsantiMenu = "back";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"eventi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->where(array("id_spedizione_negozio"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	// Invia la spedizione al corriere
	public function invia($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		if (!$this->m[$this->modelName]->whereId((int)$id)->rowNumber())
			$this->responseCode(403);
		
		$record = $this->m[$this->modelName]->clear()->selectId((int)$id);
		
		if (!empty($record) && $record["id_spedizioniere"])
		{
			if (SpedizioninegozioModel::aperto((int)$id))
			{
				$_POST["updateAction"] = 1;
				Params::$arrayToValidate = htmlentitydecodeDeep($record);
				$_POST["nazione"] = Params::$arrayToValidate["nazione"];
				
				$this->m[$this->modelName]->setUpdateConditions((int)$id);
				
				$stato = "I";
				
				if ($this->m($this->modelName)->checkConditions('update'))
				{
					$this->m($this->modelName)->sValues(array(
						"stato"			=>	$stato,
						"data_invio"	=>	date("Y-m-d H:i:s"),
					));
					
					if ($this->m($this->modelName)->update((int)$id))
						$this->m("SpedizioninegozioeventiModel")->inserisci((int)$id, $stato);
				}
				else
					flash("notice",$this->m($this->modelName)->notice);
			}
		}
		
		$this->redirect("spedizioninegozio/form/update/".(int)$id.$this->viewStatus);
	}
}
