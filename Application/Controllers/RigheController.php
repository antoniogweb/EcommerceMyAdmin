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

Helper_List::$filtersFormLayout["filters"]["da_ordinare"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class RigheController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'titolo:sanitizeAll'=>'tutti',
		'titolo_riga:sanitizeAll'=>'tutti',
		'id_marchio:sanitizeAll'=>'tutti',
		'id_o_da_ordinare:sanitizeAll'=>'tutti',
		'da_ordinare:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "marketing";
	
	public $tabella = "prodotti più venduti";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if ($action == "elenco" || $action == "daordinare")
			$this->sezionePannello = "ecommerce";
		
		parent::__construct($model, $controller, $queryString, $application, $action);
	}
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array(
			array(
				'width'	=>	'80px',
			),
		);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta');
		
		$this->shift();
		
		$this->mainFields = array("thumb", "titolocompleto", "categories.title", "ordiniCrud");
		$this->mainHead = "Immagine,Prodotto,Categoria,Ordini";
		
		$filtri = array("dal","al");
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
				->select("sum(quantity) as numero_totale,righe.id_page,righe.id_r,righe.title,righe.attributi,righe.immagine,categories.title,righe.id_c,pages.title")
				->inner("orders")->on("righe.id_o = orders.id_o")
				->left("pages")->on("pages.id_page = righe.id_page")
				->left("categories")->on("pages.id_c = categories.id_c")
				->where(array(
					"ne" => array(
						"orders.stato"	=>	"deleted"
					),
					" ne" => array(
						"righe.id_page"	=>	0,
					),
					"righe.prodotto_generico"	=>	0,
				))
				->groupBy("righe.id_page")
				->orderBy("sum(quantity) desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function daordinare()
	{
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
		
		$this->tabella = "segna come da ordinare";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array(
			array(
				'width'	=>	'90px',
			),
		);
		
		$this->shift();
		$this->mainShift = 0;
		
		$recordsPerPage = 30;
		$daOrdinareCrud = "righe.qta_da_ordinare";
		$mainMenu = "";
		
		if ($this->viewArgs["id_o_da_ordinare"] != "tutti")
		{
			$recordsPerPage = 999999;
			$daOrdinareCrud = "daOrdinareCrud";
			$mainMenu = "save_da_ordinare";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>$recordsPerPage, 'mainMenu'=>$mainMenu, 'mainAction'=>'daordinare');
		
		$this->mainFields = array("immagineCrud", "righe.title", "righe.attributi", "righe.codice", "righe.price_ivato", "righe.prezzo_finale_ivato", "righe.quantity", $daOrdinareCrud, "ordinataCrud", "ricevutaCrud");
		$this->mainHead = "Immagine,Prodotto,Variante,Codice,Prezzo,Prezzo scontato,Quantità acquistata,Quantità da ordinare,Quantità ordinata,Quantità ricevuta";
		
		$this->m[$this->modelName]->clear()
				->select("orders.id_o,orders.stato,orders.sezionale,orders.numero_documento,orders.data_creazione,righe.*")
				->inner("orders")->on("righe.id_o = orders.id_o")
				->where(array(
					"orders.id_o"	=>	$this->viewArgs["id_o_da_ordinare"],
					"id_riga_tipologia"	=>	0,
				))
				->orderBy("righe.id_order")->convert();
		
		if ($this->viewArgs["id_o_da_ordinare"] == "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"gt"	=>	array(
					"qta_da_ordinare"	=>	0,
				),
			));
			
			$this->clean();
			$this->sezionePannello = "acquisti";
			$this->loadHeaderAndFooter();
			$this->tabella = "righe ordini di vendita da ordinare";
			
			$this->mainFields[] = "linkcruddaordinare";
			$this->mainFields[] = "cleanDateTimeOrdine";
			$this->mainFields[] = "statoordinelabel";
			
			$this->mainHead .= ",N°Ordine acquisto,Data Ora,Stato ordine";
			
			$filtroDaOrdinare = array(
				"tutti"		=>	gtext("Filtro da ordinare"),
			) + array(
				"D"	=>	gtext("Da ordinare"),
				"O"		=>	gtext("Ordinati"),
			);
			
			$this->filters = array(array("da_ordinare",null,$filtroDaOrdinare));
			
			if ($this->viewArgs["da_ordinare"] != "tutti")
			{
				$idRs = OrdiniModel::idRigheDaOrdinare();
				
				$inNin = $this->viewArgs["da_ordinare"] == "D" ? "in" : "nin";
				
				$this->m[$this->modelName]->aWhere(array(
						"$inNin"	=>	array(
							"righe.id_r"	=>	forceIntDeep($idRs),
						),
					));
			}
		}
		
		$this->m[$this->modelName]->save();
		
		$this->baseMain();
	}
	
	public function elenco()
	{
		if (!v("mostra_sezione_righe_ordine"))
			$this->responseCode(403);
		
		$this->tabella = "righe ordine";
		
		Helper_Menu::$htmlLinks["esporta"]["url"] = "elenco";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array(
			array(
				'width'	=>	'90px',
			),
		);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta', 'mainAction'=>'elenco');
		
		$this->shift();
		
		$this->mainFields = array("linkcrud", "statoordinelabel", "cleanDateTime", 'OrdiniModel.getNome|orders.id_o', "righe.title", "righe.quantity", "righe.prezzo_intero_ivato", "righe.price_ivato");
		$this->mainHead = "N°Ordine,Stato ordine,Data Ora,Nome/Rag.Soc,Prodotto,Quantità,Prezzo,Prezzo scontato";
		
		$this->filters = array("titolo", "titolo_riga", "dal", "al");
		
		$this->m[$this->modelName]->clear()
				->select("orders.id_o,orders.stato,righe.*")
				->inner("orders")->on("righe.id_o = orders.id_o")
				->where(array(
					"righe.id_riga_tipologia"	=>	0,
					"orders.sezionale"	=>	"",
				))
				->orderBy("orders.data_creazione desc,righe.id_order")->convert();
		
		if (v("usa_marchi"))
		{
			$this->mainFields[] = "marchiTitoloCrud";
			$this->mainHead .= ",Marchio";
			
			$this->filters[] = array("id_marchio",null,array("tutti"=>gtext("Marchio")) + $this->m("MarchiModel")->filtro());
			
			if ($this->viewArgs["id_marchio"] != "tutti")
				$this->m[$this->modelName]->inner("pages")->on("pages.id_page = righe.id_page")->aWhere(array(
					"pages.id_marchio"	=>	(int)$this->viewArgs["id_marchio"],
				));
		}
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], "data_creazione", "orders");
		
		if ($this->viewArgs["titolo"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"      AND"	=>	OrdiniModel::getWhereClauseRicercaLibera($this->viewArgs['titolo']),
			));
		}
		
		if ($this->viewArgs['titolo_riga'] != "tutti")
			$this->m[$this->modelName]->aWhere(array(
				"    AND"	=>	RigheModel::getWhereClauseRicercaLibera($this->viewArgs['titolo_riga']),
			));
		
		$this->m[$this->modelName]->save();
		
		$this->baseMain();
	}
	
	public function salva()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
// 		Params::$automaticConversionToDbFormat = false;
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$campoPrice = "price";
		$campoPriceIntero = "prezzo_intero";
		$campoPriceFinale = "prezzo_finale";
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			$campoPrice = "price_ivato";
			$campoPriceIntero = "prezzo_intero_ivato";
			$campoPriceFinale = "prezzo_finale_ivato";
		}
		
		$arrayIdPage = array();
		
		if (count($valori) > 0 && isset($valori[0]["id_riga"]))
		{
			$idOrdine = RigheModel::g()->whereId((int)$valori[0]["id_riga"])->field("id_o");
			
			if (!OrdiniModel::g()->isDeletable($idOrdine))
			{
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
				
				return;
			}
		}
		
		foreach ($valori as $v)
		{
			if ($v["quantity"] > 0)
			{
				$recordRiga = $this->m[$this->modelName]->selectId($v["id_riga"]);
				
				if (!empty($recordRiga))
				{
					$giacenza = (int)$this->m("CombinazioniModel")->whereId((int)$recordRiga["id_c"])->field("giacenza");
					
					$rigaTipologia = $this->m("RighetipologieModel")->clear()->selectId((int)$recordRiga["id_riga_tipologia"]);
					
					$moltiplicatore = 1;
					
					if (!empty($rigaTipologia))
						$moltiplicatore = (int)$rigaTipologia["moltiplicatore"];
					
					$price = abs((float)setPrice($v["price"])) * $moltiplicatore;
					$prezzo_intero = abs((float)setPrice($v["prezzo_intero"])) * $moltiplicatore;
					
					$sconto = $v["sconto"] ?? 0;
					
					if (!empty($rigaTipologia))
					{
						$price = $prezzo_intero;
						$sconto = 0;
					}
					
					$this->m[$this->modelName]->setValues(array(
						"quantity"			=>	$v["quantity"],
						"disponibile"		=>	($giacenza >= ((int)$v["quantity"] - (int)$recordRiga["quantity"])) ? 1 : 0,
						"$campoPrice"		=>	$price,
						"$campoPriceIntero"	=>	$prezzo_intero,
						"$campoPriceFinale"	=>	$price,
						"in_promozione"		=>	number_format(setPrice($v["price"]),2,".","") != number_format(setPrice($v["prezzo_intero"]),2,".","") ? "Y" : "N",
						"title"				=>	$v["title"],
						"id_c"				=>	$v["id_c"],
						"codice"			=>	$v["codice"],
						"evasa"				=>	$v["evasa"],
						"sconto"			=>	$sconto,
					));
					
					$this->m[$this->modelName]->update($v["id_riga"]);
				}
			}
			else
				$this->m[$this->modelName]->del($v["id_riga"]);
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		if (isset($idOrdine))
		{
			OrdiniModel::g()->aggiornaTotali((int)$idOrdine);
		}
	}
	
	public function modificaevaso($idR, $valore = 1)
	{
		$this->clean();
		
		$clean["valore"] = (int)$valore;
		
		$riga = $this->m($this->modelName)->selectId((int)$idR);
		
		if (empty($riga))
			return;
		
		if ($clean["valore"] === 0 || $clean["valore"] === 1)
		{
			$this->m($this->modelName)->sValues(array(
				"evasa"	=>	$clean["valore"],
			));
			
			$this->m[$this->modelName]->pUpdate((int)$idR);
			
			OrdiniModel::g()->impostaAlloStatoSeTutteLeRigheSonoEvase((int)$riga["id_o"]);
		}
	}
	
	public function salvaqtadaordinare()
	{
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		// check CSRF
		$this->checkCsrf(true, "POST");
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		foreach ($valori as $v)
		{
			$idR = $v["id_r"] ?? 0;
			$qty = $v["qta_da_ordinare"] ?? 0;
			
			if ((int)$qty <= 0)
				$qty = 0;
			
			if ($idR && isset($v["qta_da_ordinare"]))
			{
				$recordRiga = $this->m[$this->modelName]->selectId((int)$idR);
				
				if (!empty($recordRiga))
				{
					$this->m[$this->modelName]->setValues(array(
						"qta_da_ordinare"	=>	(int)$qty,
					));
					
					$this->m[$this->modelName]->update((int)$idR);
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
}
