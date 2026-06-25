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

Helper_List::$filtersFormLayout["filters"]["id_ordine_acquisto_filtro"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"N° Ordine",
	),
);

Helper_List::$filtersFormLayout["filters"]["ragione_sociale"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca..",
	),
);

Helper_Menu::$htmlLinks["save_righe_ordini_acquisto"]["attributes"] .= " url-salva='ordiniacquistorighe/salva' ";

class OrdiniacquistoController extends BaseController
{
	public $orderBy = "ragione_sociale";
	public $pulsantiMenuRighe = "";
	public $modelNameRighe = "OrdiniacquistorigheModel";
	
	public $argKeys = array(
		'id_ordine_acquisto_filtro:sanitizeAll'=>'tutti',
		'id_form_fornitore:sanitizeAll'=>'tutti',
		'ragione_sociale:sanitizeAll'=>'tutti',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "ordini di acquisto";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$_GET["id_form_fornitore"] = "tutti";
		
		$this->shift();
		
		$this->mainFields = array("[[ledit]];ordini_acquisto.numero_ordine;", "ordini_acquisto.ragione_sociale", "aggregate.anno_ordine", "ordini_acquisto.data_ordine", "ordini_acquisto.telefono", "ordini_acquisto.email", "statoordinelabel", "numeroDaCollegareCrud");
		$this->mainHead = "N° Ordine,Ragione sociale,Anno,Data,Telefono,Email,Stato,Da collegare";
		
		$this->m[$this->modelName]->select("ordini_acquisto.*,DATE_FORMAT(data_ordine, '%Y') as anno_ordine")
			->aWhere(array(
				"numero_ordine"	=>	$this->viewArgs["id_ordine_acquisto_filtro"],
			))
			->orderBy("anno_ordine desc,numero_ordine desc")->convert();
		
		if ($this->viewArgs["ragione_sociale"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"  AND"	=>	FornitoriModel::getWhereClauseRicercaLibera($this->viewArgs['ragione_sociale']),
			));
		}
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], 'data_ordine');
		
		$this->m[$this->modelName]->save();
		
		$this->filters = array("id_ordine_acquisto_filtro","ragione_sociale","dal","al");
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->addStrongCondition("both",'checkIsNotStrings|0',"numero_ordine");
		
		$formFields = $fields =  'id_fornitore,data_ordine,numero_ordine,ragione_sociale,email,email_amministrativa,pec,codice_fiscale,p_iva,telefono,telefono_2,indirizzo,numero_civico,nazione,provincia,comune,cap,localita,referente,telefono_referente,cellulare_referente,email_referente,id_ordine_acquisto_stato';
		
		if ($queryType == "update")
			$fields = str_replace("id_fornitore,", "", $fields);
			
		$this->m[$this->modelName]->setValuesFromPost($fields);
		$this->m[$this->modelName]->fields = $formFields;
		
		if ($this->viewArgs["id_form_fornitore"] != "tutti")
			$this->formDefaultValues = htmlentitydecodeDeep($this->m("FornitoriModel")->selectId((int)$this->viewArgs["id_form_fornitore"]));
		
		$this->formDefaultValues["numero_ordine"] = $this->m($this->modelName)->getNumero();
		
		if ($queryType == "update")
		{
			$this->disabledFields .= ",id_fornitore";
		}
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
		$this->mainShift = 1;
		
		$this->_posizioni['righe'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_ordine_acquisto";
		
		$this->mainButtons = "ldel";
		
		$mainModelName = $this->modelName;
		
		$this->modelName = $this->modelNameRighe;
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'80px',
			),
		);
		
		if (!OrdiniacquistoModel::g()->isBozza((int)$id))
		{
			$this->addBulkActions = false;
			$this->colProperties = array();
		}
		
		$this->rowAttributes = array(
			"class"	=>	"listRow id_tipo_riga_acquisto_;ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia; id_articolo_;ordini_acquisto_righe.id_articolo;",
		);
		
		$this->mainFields = array("primaImmagineCarrelloCrud", "titoloCrud", "attributiCrud", "riferimentoRigaCrud", "codiceCrud", "prezzoInteroCrud", "quantitaCrud", "sconto1Crud", "sconto2Crud", "omaggioCrud", "aliquitaIvaCrud", "acquistabileCrud");
		$this->mainHead = "Immagine,Articolo,Variante,Riferimento riga ordine,Codice,Prezzo,Quantità,Sconto 1, Sconto 2,Om.,Aliquota,Acq";
		
		if (!$this->pulsantiMenuRighe)
		{
			$this->pulsantiMenuRighe = "back";
			
			if (OrdiniacquistoModel::g()->isBozza((int)$id))
				$this->pulsantiMenuRighe .= ",save_righe_ordini_acquisto";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$this->pulsantiMenuRighe,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->clear()->select("ordini_acquisto_righe.*")
			->left(array("articolo"))
			->left("ordini_acquisto_righe_tipologie")->on("ordini_acquisto_righe_tipologie.id_ordine_acquisto_riga_tipologia = ordini_acquisto_righe.id_ordine_acquisto_riga_tipologia")
			->where(array(
				"ordini_acquisto_righe.id_ordine_acquisto"	=>	$clean['id']
			))
			->orderBy("ordini_acquisto_righe_tipologie.id_order,ordini_acquisto_righe.id_order")
			->convert()->save();
		
		$this->getTabViewFields("righe");
		
		Helper_Menu::$htmlLinks["save_righe_ordini_acquisto"]["attributes"] .= " id-ordine='".(int)$id."'";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m($mainModelName)->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		$data["tipologie"] = $this->m("OrdiniacquistorighetipologieModel")->clear()->orderBy("id_order")->send(false);
		$this->append($data);
	}
	
	public function inviipdf($id)
	{
		$this->mainShift = 1;
		
		$this->_posizioni['inviipdf'] = 'class="active"';
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_ordine_acquisto";
		
		$mainModelName = $this->modelName;
		
		$this->modelName = "OrdiniacquistopdfModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("cleanDateTime", "linkPdfCrud", "inviatoCrud", "adminusers.username");
		$this->mainHead = "Data PDF,File PDF,Inviato,Generato da";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"inviipdf/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->select("ordini_acquisto_pdf.*,adminusers.username")->left("adminusers")->on("adminusers.id_user = ordini_acquisto_pdf.id_admin")->where(array("ordini_acquisto_pdf.id_ordine_acquisto"=>$clean['id']))->orderBy("ordini_acquisto_pdf.data_creazione desc")->convert()->save();
		
		$this->getTabViewFields("inviipdf");
		
		$this->mainButtons = "";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m[$mainModelName]->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		
		$this->append($data);
	}
	
	public function storicostati($idO)
	{
		$this->model("OrdinistatiModel");
		
		$data["stati"] = $this->m("OrdiniacquistostatistoricoModel")->clear()
			->select("ordini_acquisto_stati_storico.data_creazione,ordini_acquisto_stati.titolo,adminusers.username,ordini_acquisto_stati_storico.id_ordine_acquisto_stato,ordini_acquisto_stati.titolo")
			->left("ordini_acquisto_stati")->on("ordini_acquisto_stati.id_ordine_acquisto_stato = ordini_acquisto_stati_storico.id_ordine_acquisto_stato")
			->left("adminusers")->on("ordini_acquisto_stati_storico.id_admin = adminusers.id_user")
			->where(array(
				"ordini_acquisto_stati_storico.id_ordine_acquisto"	=>	(int)$idO,
			))->orderBy("ordini_acquisto_stati_storico.id_ordine_acquisto_stato_storico")->send();
		
		$this->append($data);
		$this->load("vedi_storico");
	}
	
	public function stampapdf($id = 0, $idPdf = 0)
	{
		$this->clean();
		
		$values = $this->m("OrdiniacquistopdfModel")->generaORestituisciPdfOrdine($id, $idPdf);
		
		$folder = LIBRARY . "/".OrdiniacquistopdfModel::getMediaPath();
		
		if (is_array($values) && !empty($values) && file_exists($folder."/".$values["filename"]))
		{
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename='.$values["titolo"]);
			readfile($folder."/".$values["filename"]);
			
			$this->m("OrdiniacquistopdfModel")->eliminaPdfNonInviati();
		}
		else
			$this->responseCode(403);
	}
	
	public function inviapdf($id)
	{
		$this->shift(1);
		
		$this->clean();
		
		if ($this->m("OrdiniacquistopdfModel")->inviaPdf($id))
			flash("notice", "<div class='alert alert-success'>".gtext("Email inviata correttamente")."</div>");
		
		$this->redirect($this->applicationUrl.$this->controller."/form/update/".(int)$id.$this->viewStatus);
	}
}
