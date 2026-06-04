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

class MagazzinoarticoliController extends BaseController
{
	public $argKeys = array(
		// 'id_ordine_acquisto_filtro:sanitizeAll'=>'tutti',
		// 'id_form_fornitore:sanitizeAll'=>'tutti',
		// 'ragione_sociale:sanitizeAll'=>'tutti',
		// 'dal:sanitizeAll'=>'tutti',
		// 'al:sanitizeAll'=>'tutti',
	);
	
	public $mainButtons = 'ldel';
	
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
		
		$this->mainFields = array("magazzino_articoli.titolo","magazzino_articoli.codice");
		$this->mainHead = "Articolo,Codice";
		
		$this->m[$this->modelName]->select("magazzino_articoli.*")
			// ->aWhere(array(
			// 	"id_ordine_acquisto"	=>	$this->viewArgs["id_ordine_acquisto_filtro"],
			// ))
			->orderBy("titolo")->convert();
		
		// if ($this->viewArgs["ragione_sociale"] != "tutti")
		// {
		// 	$this->m[$this->modelName]->aWhere(array(
		// 		"  AND"	=>	FornitoriModel::getWhereClauseRicercaLibera($this->viewArgs['ragione_sociale']),
		// 	));
		// }
		
		$this->m[$this->modelName]->save();
		
		// $this->filters = array("id_ordine_acquisto_filtro","ragione_sociale","dal","al");
		
		parent::main();
	}
	
// 	public function form($queryType = 'insert', $id = 0)
// 	{
// 		$this->shift(2);
// 		
// 		$this->_posizioni['main'] = 'class="active"';
// 		
// 		$formFields = $fields =  'id_fornitore,data_ordine,numero_ordine,ragione_sociale,email,email_amministrativa,pec,codice_fiscale,p_iva,telefono,telefono_2,indirizzo,numero_civico,nazione,provincia,comune,cap,localita,referente,telefono_referente,cellulare_referente,email_referente,id_ordine_acquisto_stato';
// 		
// 		if ($queryType == "update")
// 			$fields = str_replace("id_fornitore,", "", $fields);
// 			
// 		$this->m[$this->modelName]->setValuesFromPost($fields);
// 		$this->m[$this->modelName]->fields = $formFields;
// 		
// 		if ($this->viewArgs["id_form_fornitore"] != "tutti")
// 			$this->formDefaultValues = htmlentitydecodeDeep($this->m("FornitoriModel")->selectId((int)$this->viewArgs["id_form_fornitore"]));
// 		
// 		$this->formDefaultValues["numero_ordine"] = $this->m($this->modelName)->getNumero();
// 		
// 		if ($queryType == "update")
// 		{
// 			$this->disabledFields .= ",id_fornitore";
// 		}
// 		
// 		parent::form($queryType, $id);
// 	}
}
