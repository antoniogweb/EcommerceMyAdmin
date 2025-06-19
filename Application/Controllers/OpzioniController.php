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

Helper_List::$filtersFormLayout["filters"]["q"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Titolo ..",
	),
);

class OpzioniController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $sezionePannello = "utenti";
	
	public $argKeys = array(
		'codice:sanitizeAll'	=>'tutti',
		'q:sanitizeAll'			=>'tutti'
	);
	
	public function __construct($model, $controller, $queryString, $application, $action)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->tabella = OpzioniModel::labelTabella();
	}
	
	private function checkCodice()
	{
		if (!isset($this->viewArgs["codice"]) || !in_array($this->viewArgs["codice"], OpzioniModel::$codiciGestibili))
			die("NON PERMESSO");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->checkCodice();
		
		if ($this->viewArgs["codice"] == "FRASI_DA_NON_TRADURRE")
		{
			$this->mainFields = array("opzioni.titolo");
			$this->mainHead = "Titolo";
			
			$this->filters = array(null, "q");
		}
		else
		{
			$this->mainFields = array("opzioni.titolo", "opzioni.valore");
			$this->mainHead = "Titolo,Valore";
			
			$this->bulkQueryActions = "";
			$this->addBulkActions = false;
			$this->colProperties = array();
		}
		
		$this->m[$this->modelName]->clear()->where(array(
				"codice" => $this->viewArgs["codice"],
				"lk"	=>	array("titolo" => $this->viewArgs["q"]),
			))->orderBy("id_order")->convert()->save();
		
		parent::main();
	}
	
	public function ordina()
	{
		$this->modelName = "OpzioniModel";
		
		parent::ordina();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->checkCodice();
		
		$fields = 'titolo,valore';
		
		if ($this->viewArgs["codice"] == "FRASI_DA_NON_TRADURRE")
			$fields = 'titolo';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["codice"] == "FRASI_DA_NON_TRADURRE")
			$this->m[$this->modelName]->setValue("valore", $this->request->post("titolo",""));
		
		if ($queryType == "insert" && $this->viewArgs["codice"] != "tutti")
			$this->m[$this->modelName]->setValue("codice", $this->viewArgs["codice"]);
		
		$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',$fields);
		
		parent::form($queryType, $id);
	}
	
	public function importacategoriegoogle()
	{
		if (!v("usa_transactions"))
			die("Importazione non permessa");
		
		OpzioniModel::importaCategorieGoogle();
		
		if (count(OpzioniModel::$erroriImportazione) > 0)
		{
			$esito = "<div class='alert alert-danger'>".gtext("Nonè stato possibile importare alcune categorie di Google.")."</div>";
			$esito .= "<div>".gtext("Elenco categorie non importate")."</div>";
			
			$esito .= implode("<br />", OpzioniModel::$erroriImportazione);
			
			$data["esitoMigrazioni"] = $esito;
		}
		else
			$data["esitoMigrazioni"] = "<div class='alert alert-success'>".gtext("Operazione eseguita con successo")."</div>";
		
		$data["titoloPagina"] = gtext("Importazione categorie Google");
		
		$this->append($data);
		$this->load("output");
	}
}
