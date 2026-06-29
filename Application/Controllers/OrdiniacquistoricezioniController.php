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

class OrdiniacquistoricezioniController extends BaseController
{
	public $argKeys = array(
	
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "ricezioni di ordini di acquisto";
	
	public $pulsantiMenuRighe = "";
	public $modelNameRighe = "OrdiniacquistoricezionirigheModel";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];ordini_acquisto_ricezioni.id_ordine_acquisto_ricezione;", 'ordini_acquisto_ricezioni.data_ricezione_merce', 'ordini_acquisto_ricezioni.numero_documento_trasporto');
		$this->mainHead = "N° Ricezione,Data ricezione,Numero DDT";
		
		$this->m[$this->modelName]->select("ordini_acquisto_ricezioni.*")
			->aWhere(array(
				// "numero_ordine"	=>	$this->viewArgs["id_ordine_acquisto_filtro"],
			))
			->orderBy("id_ordine_acquisto_ricezione desc")->convert();
		
		// $this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], 'data_ordine');
		
		$this->m[$this->modelName]->save();
		
		// $this->filters = array("id_ordine_acquisto_filtro","ragione_sociale","dal","al");
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('data_ricezione_merce,numero_documento_trasporto');
		
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
		
		// if (!OrdiniacquistoModel::g()->isBozza((int)$id))
		// {
		// 	$this->addBulkActions = false;
		// 	$this->colProperties = array();
		// }
		
		$this->rowAttributes = array(
			"class"	=>	"listRow id_tipo_riga_acquisto_;ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia; id_articolo_;ordini_acquisto_righe.id_articolo;",
		);
		
		$this->mainFields = array("primaImmagineCarrelloCrud", "ordini_acquisto_righe.titolo", "ordini_acquisto_righe.attributi", "ordini_acquisto_righe.codice", "ordineCrud", "ordini_acquisto_righe.id_ordine_acquisto_riga", "quantitaCrud");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,N° Ordine acquisto,ID Riga,Quantità ricevuta";
		
		if (!$this->pulsantiMenuRighe)
		{
			$this->pulsantiMenuRighe = "back";
// 			
// 			if (OrdiniacquistoModel::g()->isBozza((int)$id))
				$this->pulsantiMenuRighe .= ",save_righe_ordini_acquisto_ricezione";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$this->pulsantiMenuRighe,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->clear()->select("ordini_acquisto_ricezioni_righe.*,ordini_acquisto_righe.*,ordini_acquisto.numero_ordine,ordini_acquisto.data_ordine")
			->left(array("riga"))
			->left("ordini_acquisto")->on("ordini_acquisto_righe.id_ordine_acquisto = ordini_acquisto.id_ordine_acquisto")
			->left("ordini_acquisto_righe_tipologie")->on("ordini_acquisto_righe_tipologie.id_ordine_acquisto_riga_tipologia = ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia")
			->where(array(
				"ordini_acquisto_ricezioni_righe.id_ordine_acquisto_ricezione"	=>	$clean['id']
			))
			->orderBy("ordini_acquisto_righe_tipologie.id_order,ordini_acquisto.numero_ordine,ordini_acquisto_righe.id_order")
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
}
