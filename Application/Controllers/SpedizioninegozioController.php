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

class SpedizioninegozioController extends BaseController {
	
	public $argKeys = array('id_o:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = gtext("spedizioni negozio",true);
		
		$this->model("SpedizioninegoziorigheModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>"");
		
		$this->mainFields = array("spedizioni_negozio.id_spedizione_negozio", "ordiniCrud", "cleanDateTime", "spedizionieri.titolo", "indirizzoCrud", "nazioneCrud");
		$this->mainHead = "ID,Ordine,Data spedizione,Spedizioniere,Indirizzo,Nazione";
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("spedizioniere"))
				->where(array(
					
				))
				->orderBy("data_spedizione desc,id_spedizione_negozio desc")->convert()->save();
		
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
		}
		else
			$fields = "data_spedizione,id_spedizioniere,nazione,provincia,dprovincia,indirizzo,cap,citta,telefono,email,note,note_interne,ragione_sociale,ragione_sociale_2";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
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
		
		$this->mainFields = array("<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/;righe.id_page;/;righe.immagine;' />", "righe.title", "righe.attributi", "righe.codice", "quantitaCrud", ";righe.iva;%");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,Quantità,Aliquota";
		
		$pulsantiMenu = "back";
		
		if (SpedizioninegozioModel::g()->deletable($id))
			$pulsantiMenu .= ",save_righe_spedizione";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("riga"))->orderBy("id_spedizione_negozio_riga")->where(array("id_spedizione_negozio"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizioninegozioModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
