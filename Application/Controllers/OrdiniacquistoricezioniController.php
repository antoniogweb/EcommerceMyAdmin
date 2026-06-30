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

Helper_Menu::$htmlLinks["save_righe_ordini_acquisto_ricezione"]["attributes"] .= " url-salva='ordiniacquistoricezionirighe/salva' ";

Helper_List::$filtersFormLayout["filters"]["id_ricezione_filtro"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"N° Ricezione",
	),
);

class OrdiniacquistoricezioniController extends BaseController
{
	public $argKeys = array(
		'id_ricezione_filtro:sanitizeAll'=>'tutti',
		'numero_ordine_acquisto:sanitizeAll'=>'tutti',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "ricezioni di prodotti";
	
	public $pulsantiMenuRighe = "";
	public $modelNameRighe = "OrdiniacquistoricezionirigheModel";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
		
		$data["urlOrdineAcquisto"] = $this->m[$this->modelName]->urlOrdineAcquisto;
		$this->append($data);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];ordini_acquisto_ricezioni.id_ordine_acquisto_ricezione;", 'ordini_acquisto_ricezioni.data_ricezione_merce', 'ordini_acquisto_ricezioni.numero_documento_trasporto', "statoLabelCrud", "ordiniAcquistoCrud");
		$this->mainHead = "N° Ricezione,Data ricezione,Numero DDT,Stato,Ordini acquisto";
		
		$this->m[$this->modelName]->select("ordini_acquisto_ricezioni.*")
			->aWhere(array(
				"id_ordine_acquisto_ricezione"	=>	$this->viewArgs["id_ricezione_filtro"],
			))
			->orderBy("id_ordine_acquisto_ricezione desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], 'data_ricezione_merce');
		
		if ($this->viewArgs["numero_ordine_acquisto"] != "tutti")
		{
			$this->m[$this->modelName]->inner(array("righe"))
				->inner("ordini_acquisto_righe")->on("ordini_acquisto_ricezioni_righe.id_ordine_acquisto_riga = ordini_acquisto_righe.id_ordine_acquisto_riga")->inner("ordini_acquisto")->on("ordini_acquisto.id_ordine_acquisto = ordini_acquisto_righe.id_ordine_acquisto")
				->aWhere(array(
					"ordini_acquisto.numero_ordine"	=>	$this->viewArgs["numero_ordine_acquisto"],
				))
				->groupBy("ordini_acquisto_ricezioni.id_ordine_acquisto_ricezione");
		}
		
		$this->m[$this->modelName]->save();
		
		$this->filters = array("id_ricezione_filtro","numero_ordine_acquisto","dal","al");
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('data_ricezione_merce,numero_documento_trasporto,filename');
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
		$this->mainShift = 1;
		
		$this->_posizioni['righe'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_ordine_acquisto_ricezione";
		
		$this->mainButtons = "ldel";
		
		$mainModelName = $this->modelName;
		
		$this->modelName = $this->modelNameRighe;
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		if (!OrdiniacquistoricezioniModel::g()->editabile((int)$id))
		{
			$this->addBulkActions = false;
		}
		
		$this->rowAttributes = array(
			"class"	=>	"listRow id_tipo_riga_acquisto_;ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia; id_articolo_;ordini_acquisto_righe.id_articolo;",
		);
		
		$this->mainFields = array("primaImmagineCarrelloCrud", "titoloCrud", "attributiCrud", "codiceCrud", "ordineCrud", "ordini_acquisto_righe.id_ordine_acquisto_riga", "riferimentoRigaCrud", "ordini_acquisto_righe.quantita", "quantitaCrud");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,N° Ordine acquisto,ID Riga,Riferimento riga ordine di vendita,Quantità ordinata,Quantità ricevuta";
		
		if (!$this->pulsantiMenuRighe)
		{
			$this->pulsantiMenuRighe = "back";
			
			if (OrdiniacquistoricezioniModel::g()->editabile((int)$id))
				$this->pulsantiMenuRighe .= ",save_righe_ordini_acquisto_ricezione";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$this->pulsantiMenuRighe,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->clear()->select("ordini_acquisto_ricezioni_righe.*,ordini_acquisto_righe.*,ordini_acquisto.numero_ordine,ordini_acquisto.data_ordine,magazzino_articoli_combinazioni.id_page,magazzino_articoli_combinazioni.id_c,magazzino_articoli.codice,pages.title,pages.id_page,combinazioni.id_c")
			->left(array("riga"))
			->left("ordini_acquisto")->on("ordini_acquisto_righe.id_ordine_acquisto = ordini_acquisto.id_ordine_acquisto")
			->left("ordini_acquisto_righe_tipologie")->on("ordini_acquisto_righe_tipologie.id_ordine_acquisto_riga_tipologia = ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia")
			->left(array("articolo"))
			->left("magazzino_articoli_combinazioni")->on("magazzino_articoli_combinazioni.id_articolo = ordini_acquisto_ricezioni_righe.id_articolo")
			->left("combinazioni")->on("combinazioni.id_c = magazzino_articoli_combinazioni.id_c")
			->left("pages")->on("pages.id_page = combinazioni.id_page")
			->where(array(
				"ordini_acquisto_ricezioni_righe.id_ordine_acquisto_ricezione"	=>	$clean['id']
			))
			->orderBy("(ordini_acquisto.numero_ordine = 0) DESC,ordini_acquisto.numero_ordine,ordini_acquisto_righe_tipologie.id_order,ordini_acquisto_righe.id_order")
			->convert()->save();
		
		$this->getTabViewFields("righe");
		
		Helper_Menu::$htmlLinks["save_righe_ordini_acquisto_ricezione"]["attributes"] .= " id-ordine='".(int)$id."'";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m($mainModelName)->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		$data["ordiniDaRicevere"] = array(0 => gtext("Seleziona ordine acquisto")) + OrdiniacquistoModel::ordiniDaRicevereSelect();
		
		// print_r($data["ordiniDaRicevere"]);
		$this->append($data);
	}
	
	public function chiudi($idRicezione, $chiuso = 1)
	{
		$this->shift(2);
		$this->clean();
		
		$ricezione = $this->m($this->modelName)->selectId((int)$idRicezione);
		
		if (!empty($ricezione))
		{
			$chiusuraRicezione = false;
			
			if ($ricezione["chiuso"] && (int)$chiuso === 0)
				$this->m($this->modelName)->sValues(array(
					"chiuso"	=>	0,
				));
			else if (!$ricezione["chiuso"] && (int)$chiuso === 1)
			{
				$this->m($this->modelName)->sValues(array(
					"chiuso"	=>	1,
				));
				
				$chiusuraRicezione = true;
			}
			else
				$this->responseCode(403);
			
			if ($this->m($this->modelName)->update((int)$idRicezione) && $chiusuraRicezione)
				$this->m($this->modelName)->settaStatoRicevutoOrdiniCollegati((int)$idRicezione);
			
			$this->redirect($this->applicationUrl.$this->controller."/form/update/".(int)$idRicezione.$this->viewStatus);
		}
		else
			$this->responseCode(403);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}
}
