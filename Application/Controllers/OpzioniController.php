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

class OpzioniController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $sezionePannello = "utenti";
	
	public $argKeys = array('codice:sanitizeAll'=>'tutti','q:sanitizeAll'=>'tutti');
	
	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array("opzioni.titolo", "opzioni.valore", "opzioni.codice");
		$this->mainHead = "Titolo,Valore,Codice";
		
		$this->m[$this->modelName]->clear()->where(array(
				"codice" => $this->viewArgs["codice"],
				"lk"	=>	array("titolo" => $this->viewArgs["q"]),
			))->orderBy("id_order")->convert()->save();
		
		parent::main();
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
