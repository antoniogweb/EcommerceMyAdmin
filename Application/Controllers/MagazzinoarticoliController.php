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

Helper_List::$filtersFormLayout["filters"]["acquistabile"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class MagazzinoarticoliController extends BaseController
{
	public $argKeys = array(
		'prodotto:sanitizeAll'=>'tutti',
		'categoria:sanitizeAll'=>'tutti',
		'codice:sanitizeAll'=>'tutti',
		'cerca:sanitizeAll'=>'tutti',
		'attivo:sanitizeAll'=>'tutti',
		'id_marchio:sanitizeAll'=>'tutti',
		'acquistabile:sanitizeAll'=>'tutti',
	);
	
	// public $mainButtons = 'ldel';
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "articoli di magazzino";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$_GET["id_form_fornitore"] = "tutti";
		
		$this->addBulkActions = false;
		
		$this->shift();
		
		$this->mainFields = array("primaImmagineCarrelloCrud","categories.title","magazzino_articoli.titolo","marchi.titolo","prodottoCrud","attivoCrud","acquistabileCrud","magazzino_articoli.codice","magazzino_articoli.prezzo","magazzino_articoli.aliquota_iva");
		$this->mainHead = "Immagine,Categoria ecommerce,Articolo magazzino,Marchio,Prod. Ecomm.,Vis. online,Acq. online,Codice,Prezzo,Iva";
		
		$this->filters = array();
		$this->filters[] = "cerca";
		$this->filters[] = "categoria";
		$this->filters[] = "prodotto";
		$this->filters[] = "codice";
		
		$filtroMarchiSelect = $this->m("MarchiModel")->select("id_marchio,titolo")->orderBy("titolo")->toList("id_marchio", "titolo")->send();
		
		$this->filters[] = array("id_marchio",null,array(
			"tutti"		=>	gtext("Marchio"),
		) + $filtroMarchiSelect);
		
		$this->filters[] = array("attivo",null,array(
				"tutti"		=>	gtext("Attivo / Non attivo"),
				"Y"	=>	gtext("Attivo"),
				"N"	=>	gtext("Non attivo"),
			));
		
		$this->filters[] = array("acquistabile",null,array(
			"tutti"		=>	gtext("Acquistabile / Non acquistabile"),
			"1"	=>	gtext("Acquistabile"),
			"0"	=>	gtext("Non acquistabile"),
		));
		
		$this->m[$this->modelName]->select("magazzino_articoli.*,categories.title,marchi.titolo,pages.id_page,pages.attivo,combinazioni.acquistabile,combinazioni.id_c")
			->left(array("combinazioni"))
			->left("combinazioni")->on("combinazioni.id_c = magazzino_articoli_combinazioni.id_c")
			->left("pages")->on("pages.id_page = combinazioni.id_page")
			->left("categories")->on("pages.id_c = categories.id_c")
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array(
					"lk" => array('magazzino_articoli.titolo' => $this->viewArgs['prodotto']),
					" lk" => array('categories.title' => $this->viewArgs['categoria']),
					"  lk" => array('magazzino_articoli.codice' => $this->viewArgs['codice']),
					"pages.attivo"	=>	$this->viewArgs['attivo'],
					"pages.id_marchio"	=>	$this->viewArgs['id_marchio'],
					"combinazioni.acquistabile"	=>	$this->viewArgs['acquistabile'],
				))
			->orderBy("categories.title,magazzino_articoli.titolo")->convert();
		
		if ($this->viewArgs["cerca"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"  AND"	=>	MagazzinoarticoliModel::getWhereClauseRicercaLibera($this->viewArgs['cerca']),
			));
		}
		
		$this->m[$this->modelName]->save();
		
		// $this->filters = array("id_ordine_acquisto_filtro","ragione_sociale","dal","al");
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields =  'titolo,prezzo,id_iva';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
