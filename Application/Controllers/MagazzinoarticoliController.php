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
		'id_ordine_acquisto:sanitizeAll'=>'tutti',
		'q:sanitizeAll'=>'tutti',
		'id_page:sanitizeAll'=>'tutti',
		'id_articolo_comb:sanitizeAll'=>'tutti',
		'id_ordine_acquisto_ricezione:sanitizeAll'=>'tutti',
	);
	
	// public $mainButtons = 'ldel';
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "articoli di magazzino";
	
	public $orderBy = "categories.title,pages.title,combinazioni.acquistabile desc,combinazioni.id_order";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$_GET["id_form_fornitore"] = "tutti";
		
		$this->shift();
		
		if ($this->viewArgs["q"] != "tutti")
		{
			$this->viewArgs["cerca"] = $this->viewArgs["q"];
			
			$this->orderBy = "pages.attivo desc,pages.title";
		}
		
		$this->mainFields = array("primaImmagineCarrelloCrud","categories.title","titoloCrud","varianteCrud","marchi.titolo","codiceCrud","combinazioni.codice","attivoCrud","acquistabileCrud","prezzoCrud","sconto1Crud","sconto2Crud","quantitaCrud","magazzino_articoli.aliquota_iva");
		$this->mainHead = "Immagine,Categoria ecommerce,Articolo,Variante,Marchio,Codice,Codice Web,Vis. Web,Acq. Web,Prezzo,Sconto 1,Sconto 2,Ultima Qta.,Iva";
		
		if ($this->viewArgs["id_ordine_acquisto"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiaordine";
			$this->mainHead .= ",Aggiungi";
		}
		
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
		
		$this->inverseColProperties = array(
			null,null,
			array(
				'class'	=>	'sfondo_colonna_prezzo',
			),
			array(
				'class'	=>	'sfondo_colonna_prezzo',
			),
			array(
				'class'	=>	'sfondo_colonna_prezzo',
			),
			array(
				'class'	=>	'sfondo_colonna_prezzo',
			),
			array(
				'class'	=>	'sfondo_colonna_prezzo',
			),
		);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'save_articoli');
		
		$this->m[$this->modelName]->select("magazzino_articoli.*,categories.title,marchi.titolo,pages.id_page,pages.attivo,combinazioni.acquistabile,combinazioni.id_c,combinazioni.codice,pages.title")
			->left(array("combinazioni"))
			->left("combinazioni")->on("combinazioni.id_c = magazzino_articoli_combinazioni.id_c")
			->left("pages")->on("pages.id_page = combinazioni.id_page")
			->left("categories")->on("pages.id_c = categories.id_c")
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array(
					"OR"	=>	array(
						"lk" => array('magazzino_articoli.titolo' => $this->viewArgs['prodotto']),
						" lk" => array('pages.title' => $this->viewArgs['prodotto']),
					),
					" lk" => array('categories.title' => $this->viewArgs['categoria']),
					" OR"	=>	array(
						"lk" => array('magazzino_articoli.codice' => $this->viewArgs['codice']),
						" lk" => array('magazzino_articoli.gtin' => $this->viewArgs['codice']),
						"  lk" => array('magazzino_articoli.mpn' => $this->viewArgs['codice']),
					),
					"pages.attivo"	=>	$this->viewArgs['attivo'],
					"pages.id_marchio"	=>	$this->viewArgs['id_marchio'],
					"combinazioni.acquistabile"	=>	$this->viewArgs['acquistabile'],
				))
			->orderBy($this->orderBy)->convert();
		
		if ($this->viewArgs["q"] != "tutti")
		{
			$this->m[$this->modelName]->groupBy("coalesce(magazzino_articoli_combinazioni.id_page,magazzino_articoli.id_articolo)");
		}
		
		if ($this->viewArgs["cerca"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"  AND"	=>	MagazzinoarticoliModel::getWhereClauseRicercaLibera($this->viewArgs['cerca']),
			));
		}
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"pages.id_page"	=>	(int)$this->viewArgs["id_page"],
			));
		}
		
		if ($this->viewArgs["id_articolo_comb"] != "tutti")
		{
			$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb((int)$this->viewArgs["id_articolo_comb"]);
			
			if (!empty($recordWeb))
			{
				$this->m[$this->modelName]->aWhere(array(
					"pages.id_page"	=>	(int)$recordWeb["id_page"],
				));
				
				$this->m[$this->modelName]->metodoPerTitolo = "titoloCombinazioneJson";
			}
			else
				$this->m[$this->modelName]->aWhere(array(
					"magazzino_articoli.id_articolo"	=>	(int)$this->viewArgs["id_articolo_comb"],
				));
		}
		
		if ($this->viewArgs["id_ordine_acquisto"] != "tutti" || $this->viewArgs["id_ordine_acquisto_ricezione"] != "tutti")
		{
			$this->mainButtons = '';
			$this->queryActions = '';
			
			if ($this->viewArgs["id_ordine_acquisto"] != "tutti")
			{
				$metodo = "aggiungiaordine";
				$testoBulk = "Aggiungi all'ordine";
			}
			else
			{
				$metodo = "aggiungiaricezione";
				$testoBulk = "Aggiungi alla ricezione";
			}
			
			$this->bulkActions = array(
				"++checkbox_magazzino_articoli_id_articolo"	=>	array($metodo, $testoBulk),
			);
			
			$this->bulkQueryActions = $metodo;
		}
		else
			$this->addBulkActions = false;
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		if ($queryType != "update")
			$this->responseCode(403);
		
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields =  'titolo,codice,gtin,id_iva';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function salva()
	{
		$arrayIdsErrore = [];
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		CombinazioniModel::$aggiornaAliasAdInserimento = false;
		
		$this->checkCsrf(true, "POST");
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayIdPage = array();
		
		foreach ($valori as $v)
		{
			if (v("usa_transactions"))
				$record = $this->m[$this->modelName]->clear()->whereId((int)$v["id_articolo"])->forUpdate()->record();
			else
				$record = $this->m[$this->modelName]->selectId((int)$v["id_articolo"]);
			
			$this->m[$this->modelName]->setValues(array(
				"codice"	=>	$v["codice"] ?? '',
				"gtin"		=>	$v["gtin"] ?? '',
				"mpn"		=>	$v["mpn"] ?? '',
			));

			if (!empty($record))
			{
				if (!$this->m[$this->modelName]->update((int)$record["id_articolo"]))
					$arrayIdsErrore[] = $v["id_articolo"];
			}
			else
			{
				$arrayIdsErrore[] = $v["id_articolo"];
			}
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		echo json_encode($arrayIdsErrore);
	}
}
