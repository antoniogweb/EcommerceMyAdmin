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

Helper_Menu::$htmlLinks["elabora_import"] = array(
	"htmlBefore" => '',
	"htmlAfter" => '',
	"attributes" => 'role="button" class="btn btn-primary make_spinner"',
	"class"	=>	"btn btn-info",
	'text'	=>	"Elabora import",
	'queryString'	=>	'',
	"classIconBefore"	=>	'<i class="fa fa-check"></i>',
);

class FornitoriimportController extends BaseController
{
	// public $filters = array("ragione_sociale");
	
	// public $orderBy = "ragione_sociale";
	
	public $argKeys = array(
		'id_fornitore_insert:sanitizeAll'=>'tutti'
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->menuLinks = "elabora_import";
		$this->menuLinksInsert = "";
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'filename';
		
		if ($queryType == "update")
		{
			$fields .= ",colonna_descrizione,colonna_codice_sku,colonna_codice_ean_gtin,colonna_codice_mpn_barcode,colonna_prezzo";
			
			$elaborato = $this->m[$this->modelName]->clear()->whereId((int)$id)->field("elaborato");
			
			if ($elaborato)
				$_GET["report"] = "Y";
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert" && $this->viewArgs["id_fornitore_insert"] != "tutti")
			$this->m[$this->modelName]->setValue("id_fornitore", (int)$this->viewArgs["id_fornitore_insert"]);
		
		parent::form($queryType, $id);
	}
	
	public function elabora($id)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$this->clean();
		
		if ($this->m[$this->modelName]->completo((int)$id))
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			$filePath = FornitoriimportModel::getFolderPath()."/".$record["filename"];
			
			$dati = Xlsx::getData($filePath, null, null, true);
			
			$colonnaDescrizione = $record["colonna_descrizione"];
			$colonnaSKU = $record["colonna_codice_sku"];
			$colonnaGTIN = $record["colonna_codice_ean_gtin"];
			$colonnaMPN = $record["colonna_codice_mpn_barcode"];
			$colonnaPrezzo = $record["colonna_prezzo"];
			
			if (v("usa_transactions"))
				$this->m("MagazzinoarticolilistiniModel")->db->beginTransaction();
			
			foreach ($dati as $indice => $row)
			{
				if ($indice <= 0)
					continue;
				
				$row = array_map_recursive('nullToBlank', $row);
				
				if (!trim($row[$colonnaPrezzo]) || !trim($row[$colonnaSKU]))
					continue;
				
				$recordArticolo = $this->m("MagazzinoarticolilistiniModel")->clear()->select("id_articolo_listino")->where(array(
					"id_fornitore"	=>	(int)$record["id_fornitore"],
					"codice"		=>	$row[$colonnaSKU],
				))->record();
				
				$this->m("MagazzinoarticolilistiniModel")->sValues(array(
					"titolo"		=>	$row[$colonnaDescrizione],
					"prezzo"		=>	$row[$colonnaPrezzo],
					"codice"		=>	$row[$colonnaSKU],
					"gtin"			=>	$row[$colonnaGTIN],
					"mpn"			=>	$row[$colonnaMPN],
					"codice"		=>	$row[$colonnaSKU],
					"id_import"		=>	(int)$record["id_fornitore_import"],
					"id_fornitore"	=>	(int)$record["id_fornitore"],
				));
				
				if (empty($recordArticolo))
					$this->m("MagazzinoarticolilistiniModel")->insert();
				else
					$this->m("MagazzinoarticolilistiniModel")->update($recordArticolo["id_articolo_listino"]);
			}
			
			$this->m[$this->modelName]->sValues(array(
				"elaborato"	=>	1,
			));
			
			$this->m[$this->modelName]->update((int)$id);
			
			if (v("usa_transactions"))
				$this->m("MagazzinoarticolilistiniModel")->db->commit();
			
			$this->redirect($this->applicationUrl.$this->controller."/form/update/".(int)$id."?partial=Y&nobuttons=Y");
		}
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		if ($id && $this->m[$this->modelName]->completo((int)$id))
			$this->scaffold->mainMenu->links['elabora_import']['url'] = 'elabora/'.(int)$id;
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}
}
