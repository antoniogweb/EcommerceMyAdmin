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

Helper_List::$filtersFormLayout["filters"]["cerca"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca ..",
	),
);

Helper_List::$filtersFormLayout["filters"]["id_fornitore"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class MagazzinoarticolilistiniController extends BaseController
{
	public $argKeys = array(
		'cerca:sanitizeAll'=>'tutti',
		'id_fornitore:sanitizeAll'=>'tutti',
	);
	
	// public $mainButtons = 'ldel';
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "listino fornitori";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("magazzino_articoli_listini.titolo", "magazzino_articoli_listini.codice", "magazzino_articoli_listini.gtin", "magazzino_articoli_listini.mpn", "magazzino_articoli_listini.prezzo", "fornitori.ragione_sociale", "inAcquistiCrud");
		$this->mainHead = "Descrizione,Codice,GTIN/EAN,MPN/Barcode,Prezzo,Fornitore,In Acquisti";
		
		$this->mainButtons = "ldel";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->m[$this->modelName]->clear()->select("*")->inner(array("fornitore"))->where(array(
			"id_fornitore"	=>	$this->viewArgs['id_fornitore'],
		))->orderBy("magazzino_articoli_listini.titolo")->convert();
		
		if ($this->viewArgs["cerca"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"  AND"	=>	MagazzinoarticolilistiniModel::getWhereClauseRicercaLibera($this->viewArgs['cerca']),
			));
		}
		
		$this->m[$this->modelName]->save();
		
		$this->filters = array("cerca");
		
		$this->filters[] = array("id_fornitore",null,array(
			"tutti"		=>	gtext("Fornitore"),
		) + $this->m($this->modelName)->clear()->inner(array("fornitore"))->select("distinct fornitori.id_fornitore,fornitori.ragione_sociale")->toList("fornitori.id_fornitore", "fornitori.ragione_sociale")->orderBy("fornitori.ragione_sociale")->send());
		
		parent::main();
	}
}
