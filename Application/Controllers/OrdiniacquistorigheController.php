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

Helper_List::$filtersFormLayout["filters"]["titolo_riga"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca prodotto..",
	),
);

Helper_List::$filtersFormLayout["filters"]["cerca"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca..",
	),
);

Helper_List::$filtersFormLayout["filters"]["da_ricevere"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class OrdiniacquistorigheController extends BaseController
{
	public $argKeys = array(
		'cerca:sanitizeAll'=>'tutti',
		'titolo_riga:sanitizeAll'=>'tutti',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'da_ricevere:sanitizeAll'=>'tutti',
		'id_oar:sanitizeAll'=>'tutti',
		'id_ordine_acquisto_ricezione:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "righe ordini acquisto";
	
	public $scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta,collega_righe_ordini_acquisto');
	
	public function form($queryType = 'insert', $id = 0)
	{
		if ($queryType == "update")
			$this->responseCode(403);
		
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost("id_ordine_acquisto,id_ordine_acquisto_riga_tipologia");
		
		parent::form($queryType, $id);
	}
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		
		$this->colProperties = array(
			array(
				'width'	=>	'80px',
			),
		);
		
		$this->shift();
		
		$this->mainFields = array("primaImmagineCarrelloCrud", "titoloAssociaCrud", "varianteCrud", "ordini_acquisto_righe.codice", "ordineCrud", "ordini_acquisto_righe.id_ordine_acquisto_riga", ";ordini_acquisto.ragione_sociale;<div>;ordini_acquisto.email_amministrativa;</div><div>;ordini_acquisto.telefono;</div>", "aggregate.anno_ordine","ordini_acquisto.data_ordine", "ordini_acquisto_righe.quantita", "ordini_acquisto_righe.prezzo", "ordini_acquisto_righe.sconto_1", "ordini_acquisto_righe.sconto_2", "omaggioAssociaCrud", "prodottoCrud", "statoordinelabel");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,N° Ordine acquisto,ID Riga,Fornitore,Anno,Data,Quantita,Prezzo,Sconto 1,Sconto 2,Omaggio,Web,Stato";
		
		$filtroDaOrdinare = array(
			"tutti"		=>	gtext("Filtro da ricevere"),
		) + array(
			"D"	=>	gtext("Da ricevere"),
			"R"		=>	gtext("Ricevuti"),
		);
		
		$filtri = array("cerca", "titolo_riga", "dal","al",array("da_ricevere",null,$filtroDaOrdinare));
		$this->filters = $filtri;
		
		$this->rowAttributes = array(
			"class"	=>	"listRow id_tipo_riga_acquisto_;ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia; id_articolo_;ordini_acquisto_righe.id_articolo;",
		);
		
		$this->m[$this->modelName]->clear()
				->select("ordini_acquisto_righe.*,ordini_acquisto.*,DATE_FORMAT(ordini_acquisto.data_ordine, '%Y') as anno_ordine,pages.id_page")
				->inner(array("ordine"))
				->left(array("articolo"))
				->left("combinazioni")->on("combinazioni.id_c = ordini_acquisto_righe.id_c")
				->left("pages")->on("pages.id_page = combinazioni.id_page")
				->left("categories")->on("pages.id_c = categories.id_c")
				->aWhere(array(
					"ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia"	=>	0,
					"ordini_acquisto_righe.id_ordine_acquisto"					=>	$this->viewArgs['id_oar'],
				))
				->orderBy("(ordini_acquisto_righe.id_articolo = 0) DESC,anno_ordine desc,ordini_acquisto.numero_ordine desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], "data_ordine", "ordini_acquisto");
		
		if ($this->viewArgs['cerca'] != "tutti")
			$this->m[$this->modelName]->aWhere(array(
				"    AND"	=>	OrdiniacquistoModel::getWhereClauseRicercaLibera($this->viewArgs['cerca']),
			));
		
		if ($this->viewArgs['titolo_riga'] != "tutti")
			$this->m[$this->modelName]->aWhere(array(
				"    AND"	=>	OrdiniacquistorigheModel::getWhereClauseRicercaLibera($this->viewArgs['titolo_riga']),
			));
		
		if ($this->viewArgs["da_ricevere"] != "tutti")
		{
			$idRs = OrdiniacquistoModel::idRigheDaRicevere();
			
			// print_r($idRs);
			
			$inNin = $this->viewArgs["da_ricevere"] == "D" ? "in" : "nin";
			
			$this->m[$this->modelName]->aWhere(array(
					"$inNin"	=>	array(
						"ordini_acquisto_righe.id_ordine_acquisto_riga"	=>	forceIntDeep($idRs),
					),
				));
			
			if ($this->viewArgs["da_ricevere"] == "R")
				$this->m[$this->modelName]->inner("ordini_acquisto_stati")->on("ordini_acquisto_stati.id_ordine_acquisto_stato = ordini_acquisto.id_ordine_acquisto_stato")->aWhere(OrdiniacquistoModel::getChiusiWhereClause());
		}
		
		if ($this->viewArgs["id_ordine_acquisto_ricezione"] != "tutti")
		{
			$this->bulkActions = array(
				"++checkbox_ordini_acquisto_righe_id_ordine_acquisto_riga"	=>	array("aggiungiaricezione","Aggiungi alla ricezione"),
			);
			
			$this->mainButtons = '';
			$this->queryActions = '';
			$this->bulkQueryActions = "aggiungiaricezione";
		}
		else
			$this->addBulkActions = false;
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function salva()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
// 		Params::$automaticConversionToDbFormat = false;
		
		// check CSRF
		$this->checkCsrf(true, "POST");
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayIdPage = array();
		
		$idOrdine = 0;
		
		if (count($valori) > 0 && isset($valori[0]["id_ordine_acquisto_riga"]))
		{
			$idOrdine = OrdiniacquistorigheModel::g()->whereId((int)$valori[0]["id_ordine_acquisto_riga"])->field("id_ordine_acquisto");
			
			if (!OrdiniacquistoModel::g()->isBozza($idOrdine))
			{
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
				
				return;
			}
		}
		
		if (!$idOrdine)
			return;
		
		$combModel = new CombinazioniModel();
		$maModel = new MagazzinoarticoliModel();
		
		foreach ($valori as $v)
		{
			if ($v["quantita"] > 0)
			{
				$recordRiga = $this->m[$this->modelName]->selectId($v["id_ordine_acquisto_riga"]);
				
				if (!empty($recordRiga))
				{
					$idArticolo = (int)$v["id_articolo"] ?? 0;
					
					$recordArticolo = MagazzinoarticoliModel::g()->selectId($idArticolo);
					$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb($idArticolo);
					
					$moltiplicatore = 1;
					
					$price = abs((float)setPrice($v["prezzo"])) * $moltiplicatore;
					
					$sconto1 = $v["sconto_1"] ?? 0;
					$sconto2 = $v["sconto_2"] ?? 0;
					$codice = $v["codice"] ?? "";
					$omaggio = $v["omaggio"] ?? 0;
					$idR = $v["id_r"] ?? 0;
					
					if ($v["id_articolo"] && (int)$recordRiga["id_articolo"] !== $idArticolo)
					{
						$price = $maModel->getUltimoPrezzo($idArticolo, (int)$recordRiga["id_ordine_acquisto_riga"]);
						$sconto1 = $maModel->getUltimoSconto1($idArticolo, (int)$recordRiga["id_ordine_acquisto_riga"]);
						$sconto2 = $maModel->getUltimoSconto2($idArticolo, (int)$recordRiga["id_ordine_acquisto_riga"]);
						$idR = 0;
					}
					
					$this->m[$this->modelName]->sValues(array(
						"id_articolo"		=>	$idArticolo,
						"quantita"			=>	$v["quantita"] ?? 1,
						"prezzo"			=>	$price,
						"sconto_1"			=>	$sconto1,
						"sconto_2"			=>	$sconto2,
						"omaggio"			=>	(int)$omaggio,
						"titolo"			=>	$v["titolo"] ?? "",
						"id_c"				=>	$recordWeb["id_c"] ?? 0,
						"id_page"			=>	$recordWeb["id_page"] ?? 0,
						"id_r"				=>	(int)$idR,
						"codice"			=>	isset($recordWeb["id_c"]) ? ($recordArticolo["codice"] ?? $codice) : $codice,
						"attributi"			=>	isset($recordWeb["id_c"]) ? strip_tags($combModel->getStringa($recordWeb["id_c"], "<br />")) : "",
					));
					
					$this->m[$this->modelName]->update($v["id_ordine_acquisto_riga"]);
				}
			}
			else
				$this->m[$this->modelName]->del($v["id_ordine_acquisto_riga"]);
		}
		
		$this->m("OrdiniacquistoModel")->aggiornaTotali($idOrdine);
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
	
	public function collega()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		// check CSRF
		$this->checkCsrf(true, "POST");
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayIdPage = array();
		
		$combModel = new CombinazioniModel();
		$pagesModel = new PagesModel();
		
		foreach ($valori as $v)
		{
			$idRiga = $v["id_riga"] ?? 0;
			$idArticolo = $v["id_articolo"] ?? 0;
			
			if ($idRiga && (int)$idArticolo)
			{
				$recordRiga = $this->m[$this->modelName]->selectId((int)$idRiga);
				$recordArticolo = $this->m("MagazzinoarticoliModel")->selectId((int)$idArticolo);
				$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb((int)$idArticolo);
				
				if (!empty($recordRiga) && !empty($recordArticolo) && !empty($recordWeb))
				{
					$recordPage = $pagesModel->clear()->select("id_page,id_marchio")->whereId((int)$recordWeb["id_page"])->record();
					
					$this->m[$this->modelName]->sValues(array(
						"id_articolo"		=>	(int)$idArticolo,
						"id_c"				=>	$recordWeb["id_c"] ?? 0,
						"id_page"			=>	$recordWeb["id_page"] ?? 0,
						"attributi"			=>	isset($recordWeb["id_c"]) ? strip_tags($combModel->getStringa($recordWeb["id_c"], "<br />")) : "",
						"id_marchio"		=>	$recordPage["id_marchio"] ?? 0,
					));
					
					$this->m[$this->modelName]->update((int)$idRiga);
					
					// echo $this->m[$this->modelName]->notice;
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
}
