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

Helper_Menu::$htmlLinks["prenotabordero"] = array(
	'title'	=>	"Prenota borderò",
	'text'	=>	"Prenota borderò",
	'url'	=>	'prenota',
	"htmlBefore" => '',
	"htmlAfter" => '',
	"attributes" => 'role="button" class="btn btn-success menu_btn make_spinner"',
	"classIconBefore"	=>	'<i class="fa fa-book"></i>',
);

class SpedizioninegozioinviiController extends BaseController {
	
	public $argKeys = array(
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'id_spedizioniere:sanitizeAll'=>'tutti',
		'stato:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = gtext("borderò spedizioni",true);
	}
	
	public function main()
	{
		$this->queryActions = "del";
		$this->bulkQueryActions = "";
		$this->mainButtons = "ldel";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'');
		
		$this->shift();
		
		$this->mainFields = array("spedizioni_negozio_invii.id_spedizione_negozio_invio", "smartDate|spedizioni_negozio_invii.data_spedizione", "spedizionieri.titolo", "statoCrud", "spedizioniCrud","reportCrud");
		$this->mainHead = "ID,Data invio,Spedizioniere,Stato,Spedizioni collegate,Borderò";
		
		$this->m[$this->modelName]->select("*")->inner(array("spedizioniere"))->orderBy("spedizioni_negozio_invii.data_spedizione desc,spedizioni_negozio_invii.id_spedizione_negozio_invio desc")->convert()->save();
		
		parent::main();
		
		$data["spedizionieri_attivi"] = SpedizionieriModel::g()->where(array(
			"attivo"	=>	1,
		))->send(false);
		
		$this->append($data);
	}
	
	// Prenota il borderò
	public function prenota($idspedizioniere = 0)
	{
		$this->shift();
		
		$this->clean();
		
		$this->m[$this->modelName]->prenota($idspedizioniere);
		
		$this->redirect("spedizioninegozioinvii/main".$this->viewStatus);
	}
	
	// Conferma le spedizioni prenotate
	// $id dell'invio (borderò)
	public function confermaspedizioni($id = 0)
	{
		$this->shift(1);
		
		$this->clean();
		
		$this->m("SpedizioninegozioinviiModel")->inviaAlCorriere((int)$id);
	}
	
	// Stampa il pdf del borderò dell'invio $id
	public function reportpdf($id = 0)
	{
		$this->clean();
		
		$this->m("SpedizioninegozioinviiModel")->reportPdf((int)$id);
	}
}
