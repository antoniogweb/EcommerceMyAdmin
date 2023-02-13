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

Helper_List::$filtersFormLayout["filters"]["id_lista_reg_filt"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["cerca"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca ..",
	),
);

class CombinazioniController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $arrayAttributi = array();
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "magazzino";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		$this->argKeys = array(
			'prodotto:sanitizeAll'=>'tutti',
			'categoria:sanitizeAll'=>'tutti',
			'codice:sanitizeAll'=>'tutti',
			'id_page:sanitizeAll'=>'tutti',
			'listino:sanitizeAll'=>'tutti',
			'st_giac:sanitizeAll'=>'tutti',
			'id_lista_regalo:sanitizeAll'=>'tutti',
			'id_ordine:sanitizeAll'=>'tutti',
			'id_lista_regalo_ordine:sanitizeAll'=>'tutti',
			'id_lista_reg_filt:sanitizeAll'=>'tutti',
			'cerca:sanitizeAll'=>'tutti',
			'attivo:sanitizeAll'=>'tutti',
		);
		
		$this->model("PagesattributiModel");
		
		$this->arrayAttributi = $this->m["PagesattributiModel"]->clear()->select("distinct pages_attributi.id_a, concat(attributi.titolo,' (', attributi.nota_interna,')') as t")->inner(array("attributo"))->toList("pages_attributi.id_a","aggregate.t");
		
		if (isset($_GET["id_page"]) && $_GET["id_page"] != "tutti")
		{
			$this->m["PagesattributiModel"]->where(array(
				"id_page"	=>	(int)$_GET["id_page"],
			))->orderBy("pages_attributi.id_order");
			
			$this->arrayAttributi = $this->m["PagesattributiModel"]->send();
			
			foreach ($this->arrayAttributi as $idA => $titoloA)
			{
				$this->argKeys["id_".$idA.":sanitizeAll"] = "tutti";
			}
		}
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->model("AttributivaloriModel");
		$this->model("CombinazionilistiniModel");
	}

	public function main()
	{
		if (VariabiliModel::checkToken("token_aggiorna_alias_combinazioni"))
			$this->m[$this->modelName]->aggiornaAlias();
		
		// Ricontrollo i prezzi scontati delle combinazioni
		PagesModel::g()->aggiornaStatoProdottiInPromozione();
		
		$this->addBulkActions = false;
		
		$this->shift();
		
		$prezzoLabel = "Prezzo";
		$prezzoScontatoLabel = "Prezzo scontato";
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			$prezzoLabel .= " IVA inclusa";
			$prezzoScontatoLabel .= " IVA inclusa";
		}
		else
		{
			$prezzoLabel .= " IVA esclusa";
			$prezzoScontatoLabel .= " IVA esclusa";
		}
		
		if ($this->viewArgs["listino"] == "tutti")
		{
			$prezzoLabel .= " (Italia)";
			$prezzoScontatoLabel .= " (Italia)";
		}
		else if ($this->viewArgs["listino"] == "W")
		{
			$prezzoLabel .= " (Mondo)";
			$prezzoScontatoLabel .= " (Mondo)";
		}
		else
		{
			$prezzoLabel .= " (".findTitoloDaCodice($this->viewArgs["listino"]).")";
			$prezzoScontatoLabel .= " (".findTitoloDaCodice($this->viewArgs["listino"]).")";
		}
		
		$mainFields = array();
		$mainHeadArray = array();
		
		if (v("immagini_separate_per_variante"))
		{
			$mainFields[] = "primaImmagineCarrelloCrud";
			$mainHeadArray[] = "Immagine";
		}
		
		if (!partial())
		{
			$mainFields[] = "c1.title";
			$mainFields[] = "prodotto";
			$mainHeadArray[] = "Categoria";
			$mainHeadArray[] = "Prodotto";
		}
		else
		{
			$mainFields[] = "prodotto";
			$mainHeadArray[] = "Prodotto";
		}
		
		$mainHeadArray[] = "Prodotto attivo";
		$mainFields[] = "attivoCrud";
		
		if (v("immagine_in_varianti"))
		{
			$mainFields[] = "immagine";
			$mainHeadArray[] = "Immagine";
		}
		
		$mainHead = implode(",", $mainHeadArray);
		
		$mainFields[] = "varianti";
		$mainFields[] = "codice";
		$mainFields[] = "prezzo";
		
		$mainHead .= ",Variante,Codici,$prezzoLabel";
		
		if (v("gestisci_sconti_combinazioni_separatamente"))
		{
			$mainFields[] = "prezzoScontato";
			$mainHead .= ",$prezzoScontatoLabel";
		}
		
		$mainFields[] = "peso";
		$mainHead .= ",Peso";
		
		$this->mainFields = $mainFields;
		$this->mainHead = $mainHead;
		
		if (v("attiva_campo_giacenza") || v("attiva_giacenza"))
		{
			$this->mainFields[] = "giacenza";
			$this->mainHead .= ",Giac.";
		}
		
		$this->mainFields[] = "visibileCrud";
		$this->mainHead .= ",Acq.";
		
		if (!partial())
		{
			$this->mainFields[] = "ordini";
			$this->mainHead .= ",Acquisti";
		}
		
		if ($this->viewArgs["id_lista_regalo"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungialistaregalo";
			$this->mainHead .= ",Aggiungi";
		}
		
		if ($this->viewArgs["id_ordine"] != "tutti")
		{
			if ($this->viewArgs["id_lista_reg_filt"] != "tutti")
			{
				$this->mainFields[] = "numeroRegalati";
				$this->mainFields[] = "numeroRimastiDaRegalare";
				$this->mainHead .= ",Regalati,Rimasti da regalare";
			}
			
			$this->mainFields[] = "bulkaggiungiaordine";
			$this->mainHead .= ",Aggiungi";
		}
		
		if (v("attiva_liste_regalo"))
		{
			$this->mainFields[] = "linkListeRegaloCrud";
			$this->mainHead .= ",Liste.";
		}
		
		if (VariabiliModel::movimenta() && !partial() && v("mostra_link_storico_movimentazioni"))
		{
			$this->mainFields[] = "linkMovimentiCrud";
			$this->mainHead .= ",Mov.";
		}
		
// 		if (v("attiva_campo_giacenza") || v("attiva_giacenza"))
// 			$this->colProperties = array(
// 				array(
// 					'width'	=>	'30px',
// 				),
// 				null,null,null,
// 				array(
// 					'width'	=>	'160px',
// 				),
// 			);
// 		
// 		if (v("gestisci_sconti_combinazioni_separatamente"))
// 			$this->colProperties[] = array(
// 				'width'	=>	'160px',
// 			);
		
		if ($this->viewArgs['id_page'] == "tutti")
		{
			$this->filters = array();
			
			if (v("mostra_filtro_ricerca_libera_in_magazzino"))
				$this->filters[] = "cerca";
			
			$this->filters[] = "categoria";
			$this->filters[] = "prodotto";
			$this->filters[] = "codice";
		}
		
		if (v("mostra_filtri_varianti_in_magazzino") || $this->viewArgs['id_page'] != "tutti")
		{
			foreach ($this->arrayAttributi as $idA => $titoloA)
			{
				Helper_List::$filtersFormLayout["filters"]["id_".$idA] = array(
					"type"	=>	"select",
					"attributes"	=>	array(
						"class"	=>	"form-control",
					),
				);
				
				$filtriIdA = array("tutti" => $titoloA) + $this->m["AttributivaloriModel"]->selectPerFiltro($idA);
				
				$this->filters[] = array("id_".$idA,null,$filtriIdA);
			}
		}
		
		$this->filters[] = array("st_giac",null,array(
			"tutti"		=>	"Stato giacenza",
			"0"	=>	"Esaurito",
			"1"	=>	"Non esaurito",
		));
		
		if (!partial())
			$this->filters[] = array("attivo",null,array(
				"tutti"		=>	"Attivo / Non attivo",
				"Y"	=>	"Attivo",
				"N"	=>	"Non attivo",
			));
		
		if (v("attiva_liste_regalo") && $this->viewArgs['id_page'] == "tutti")
			$this->filters[] = array("id_lista_reg_filt",null,array(
				"tutti"		=>	"Lista regalo",
			) + ListeregaloModel::g()->filtroListe());
		
		$menuButtons = 'save_combinazioni,esporta';
		
		if ($this->viewArgs["id_lista_regalo_ordine"] != "tutti" && $this->viewArgs["id_ordine"] != "tutti")
		{
			$menuButtons = 'torna_ordine';
			Helper_Menu::$htmlLinks["torna_ordine"]["absolute_url"] = $this->baseUrl.'/ordini/righe/'.(int)$this->viewArgs["id_ordine"]."?partial=Y";
			
			Helper_Menu::$htmlLinks["torna_ordine"]["text"] = "Torna";
			Helper_Menu::$htmlLinks["torna_ordine"]["attributes"] = 'role="button" class="make_spinner btn btn-info"';
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>v("numero_per_pagina_magazzino"), 'mainMenu'=>$menuButtons);
		
		$this->mainButtons = 'ldel';
		
		$this->m[$this->modelName]->clear()->select("c2.title,c1.title,pages.*,combinazioni.*")
				->inner(array("pagina"))
				->left("categories as c1")->on("c1.id_c = pages.id_c")
				->left("categories as c2")->on("c2.id_c = c1.id_p")
				->where(array(
					"lk" => array('pages.title' => $this->viewArgs['prodotto']),
					" lk" => array('c1.title' => $this->viewArgs['categoria']),
					"  lk" => array('combinazioni.codice' => $this->viewArgs['codice']),
					"id_page"	=>	$this->viewArgs['id_page'],
					"pages.attivo"	=>	$this->viewArgs['attivo'],
				))
				->addWhereCategoria(CategoriesModel::getIdCategoriaDaSezione("prodotti"), true, "pages.id_c")
				->orderBy("c1.title,pages.title,combinazioni.id_order");
		
		if ($this->viewArgs["st_giac"] != "tutti")
		{
			if ($this->viewArgs["st_giac"])
				$this->m[$this->modelName]->aWhere(array(
					"gt"	=>	array(
						"combinazioni.giacenza"	=>	0,
					)
				));
			else
				$this->m[$this->modelName]->aWhere(array(
					"combinazioni.giacenza"	=>	0,
				));
		}
		
		if ($this->viewArgs["cerca"] != "tutti")
			$this->m[$this->modelName]->addWhereSearch($this->viewArgs["cerca"]);
		
		if ($this->viewArgs["id_lista_reg_filt"] != "tutti")
		{
			$this->m[$this->modelName]->where(array(
				"liste_regalo_pages.id_lista_regalo"	=>	$this->viewArgs["id_lista_reg_filt"],
			))
			->inner("liste_regalo_pages")->on("combinazioni.id_c = liste_regalo_pages.id_c");
		}
		
		$indice = 0;
		
		if (isset($_GET["id_page"]) && $_GET["id_page"] != "tutti")
		{
			foreach ($this->arrayAttributi as $idA => $titoloA)
			{
				if ($this->viewArgs["id_".$idA] != "tutti")
				{
					$strOr = str_repeat(" ", $indice);
					
					$this->m[$this->modelName]->aWhere(array(
						$strOr."OR"	=>	array(
							"col_1"	=>	$this->viewArgs["id_".$idA],
							"col_2"	=>	$this->viewArgs["id_".$idA],
							"col_3"	=>	$this->viewArgs["id_".$idA],
							"col_4"	=>	$this->viewArgs["id_".$idA],
							"col_5"	=>	$this->viewArgs["id_".$idA],
							"col_6"	=>	$this->viewArgs["id_".$idA],
							"col_7"	=>	$this->viewArgs["id_".$idA],
							"col_8"	=>	$this->viewArgs["id_".$idA],
						),
					));
					
					$indice++;
				}
			}
		}
		
		if ($this->viewArgs["id_lista_regalo"] != "tutti")
		{
			$this->addBulkActions = true;
			
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungialistaregalo";
			
			$this->bulkActions = array(
				"checkbox_combinazioni_id_c"	=>	array("aggiungialistaregalo","Aggiungi alla lista regalo"),
			);
			
			$this->m[$this->modelName]->sWhere(array("combinazioni.id_c not in (select id_c from liste_regalo_pages where id_c is not null and id_lista_regalo = ?)",array((int)$this->viewArgs["id_lista_regalo"])));
		}
		
		if ($this->viewArgs["id_ordine"] != "tutti")
		{
			$this->addBulkActions = true;
			
			$this->tabella = "articoli";
			
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiaordine";
			
			$this->bulkActions = array(
				"checkbox_combinazioni_id_c"	=>	array("aggiungiaordine","Aggiungi ad ordine"),
			);
			
			$this->m[$this->modelName]->sWhere(array("combinazioni.acquistabile = ? and pages.attivo = ? and combinazioni.id_c not in (select id_c from righe where id_c is not null and id_o = ?)",array(1, 'Y', (int)$this->viewArgs["id_ordine"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,valore');
		
		parent::form($queryType, $id);
	}
	
	public function modificaattributicombinazioni()
	{
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayMd5 = [];
		$arrayIdsErrore = [];
		
		foreach ($valori as $v)
		{
			if (isset($v["id_c"]) && isset($v["valori"]) && is_array($v["valori"]))
			{
				$md5 = md5(implode("-",$v["valori"]));
				
				if (!in_array($md5, $arrayMd5))
					$arrayMd5[] = $md5;
				else
					$arrayIdsErrore[] = $v["id_c"];
			}
		}
		
		if ((int)count($arrayIdsErrore) === 0)
		{
			foreach ($valori as $v)
			{
				if (isset($v["id_c"]) && isset($v["valori"]) && is_array($v["valori"]))
				{
					if (v("usa_transactions"))
						$record = $this->m[$this->modelName]->whereId((int)$v["id_c"])->forUpdate()->record();
					else
						$record = $this->m[$this->modelName]->selectId((int)$v["id_c"]);
					
					if (!empty($record))
					{
						$this->m[$this->modelName]->sValues(array());
						
						$col = 1;
						foreach ($v["valori"] as $idAv)
						{
							$this->m[$this->modelName]->setValue("col_$col", $idAv);
							
							$col++;
						}
						
						$this->m[$this->modelName]->pUpdate($record["id_c"]);
					}
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		echo json_encode($arrayIdsErrore);
	}
	
	public function salva()
	{
		$arrayIdsErrore = [];
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		CombinazioniModel::$aggiornaAliasAdInserimento = false;
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		list($campoPrice, $campoPriceScontato) = CombinazioniModel::campiPrezzo();
		
		$arrayIdPage = array();
		
		foreach ($valori as $v)
		{
			if (v("usa_transactions"))
				$record = $this->m[$this->modelName]->whereId((int)$v["id_c"])->forUpdate()->record();
			else
				$record = $this->m[$this->modelName]->selectId((int)$v["id_c"]);
			
			$this->m[$this->modelName]->setValues(array(
				"codice"	=>	$v["codice"],
				"peso"		=>	$v["peso"],
			));
			
			if (!$v["id_cl"])
			{
				$this->m[$this->modelName]->setValue($campoPrice, $v["prezzo"]);
				
				if (isset($v["price_scontato"]) && v("gestisci_sconti_combinazioni_separatamente"))
					$this->m[$this->modelName]->setValue($campoPriceScontato, $v["price_scontato"]);
			}
			
			if (isset($v["giacenza"]))
				$this->m[$this->modelName]->setValue("giacenza", $v["giacenza"]);
			
			if (isset($v["immagine"]))
				$this->m[$this->modelName]->setValue("immagine", $v["immagine"]);
			
			if (isset($v["acquistabile"]))
				$this->m[$this->modelName]->setValue("acquistabile", $v["acquistabile"]);
			
			if (isset($v["gtin"]))
				$this->m[$this->modelName]->setValue("gtin", $v["gtin"]);
			
			if (isset($v["mpn"]))
				$this->m[$this->modelName]->setValue("mpn", $v["mpn"]);
			
			$idPage = isset($record["id_page"]) ? $record["id_page"] : 0;
			
			if (CombinazioniModel::checkCodiceUnivoco($v["codice"], $idPage))
			{
				if ($this->m[$this->modelName]->update($v["id_c"]) && isset($v["giacenza"]) && (int)$record["giacenza"] !== (int)$v["giacenza"] && VariabiliModel::movimenta())
					$this->m[$this->modelName]->movimenta($v["id_c"], ((int)$record["giacenza"] - (int)$v["giacenza"]), 0, 1);
				
				if ($v["id_cl"])
				{
					$this->m["CombinazionilistiniModel"]->setValues(array(
						$campoPrice	=>	$v["prezzo"],
					));
					
					if (isset($v["price_scontato"]) && v("gestisci_sconti_combinazioni_separatamente"))
						$this->m["CombinazionilistiniModel"]->setValue($campoPriceScontato, $v["price_scontato"]);
					
					$this->m["CombinazionilistiniModel"]->update($v["id_cl"]);
				}
			}
			else
			{
				$arrayIdsErrore[] = $v["id_c"];
			}
			
			if (isset($v["id_page"]) && !in_array($v["id_page"],$arrayIdPage))
				$arrayIdPage[] = (int)$v["id_page"];
		}
		
		PagesModel::$aggiornaPrezziCombinazioniQuandoSalvi = false;
		
		// Aggiorno i prezzi delle combinazioni
		foreach ($arrayIdPage as $idPage)
		{
			PagesModel::g()->aggiornaPrezziCombinazioni((int)$idPage);
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		echo json_encode($arrayIdsErrore);
	}
	
	public function rendicanonical($idC)
	{
		$this->m[$this->modelName]->rendicanonical($idC);
	}
	
	public function modificaacquistabile($idC, $valore = 1)
	{
		$clean["valore"] = (int)$valore;
		
		$combinazione =$this->m[$this->modelName]->selectId((int)$idC);
		
		if (empty($combinazione))
			return;
		
		if ($clean["valore"] === 0 || $clean["valore"] === 1)
		{
			$this->m[$this->modelName]->sValues(array(
				"acquistabile"	=>	$clean["valore"],
			));
			
			$this->m[$this->modelName]->pUpdate((int)$idC);
		}
	}
}
