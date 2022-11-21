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

class ProdottiModel extends PagesModel {
	
	public $hModelName = "CategorieModel";
	
	public function __construct() {
		
		parent::__construct();

	}
	
	public function overrideFormStruct()
	{
		$this->formStruct["entries"]["gift_card"] = array(
			"type"	=>	"Select",
			"options"	=>	self::$attivoSiNo,
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	'È un prodotto Gift Card',
		);
		
		$this->formStruct["entries"]["prezzo_promozione_ass_ivato"] = array(
			'labelString'	=>	'Prezzo scontato IVA inclusa (€)',
			'entryClass'	=>	'class_promozione form_input_text',
		);
		
		$this->formStruct["entries"]["tipo_sconto"] = array(
			'entryClass'	=>	'class_promozione form_input_text',
		);
	}
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
// 			'id_c'	=>	'id_c',
			'in_evidenza'	=>	'in_evidenza',
			'in_promozione'	=>	'in_promozione',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
// 			'id_c'	=>	'CATEGORIA',
			'in_evidenza'	=>	'IN EVIDENZA?',
			'in_promozione'	=>	'IN PROMOZIONE?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
// 			'id_c'	=>	'getCatNameForFilters',
			'in_evidenza'	=>	'getYesNo',
			'in_promozione'	=>	'getYesNo',
		);
		
		$this->_popupOrderBy = array(
// 			'id_c'	=>	'lft asc',
		);
		
		$this->_popupWhere[] = array();
		
// 		if (isset($this->hModel->section))
// 			$this->_popupWhere["id_c"] = $this->hModel->getChildrenFilterWhere();
	}
	
	public function insert()
	{
// 		if (strcmp($this->values["prezzo_promozione"],"") === 0)
// 		{
// 			$this->values["prezzo_promozione"] = $this->values["price"];
// 		}
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
// 		if (strcmp($this->values["in_promozione"],"N") === 0)
// 		{
// 			$this->values["prezzo_promozione"] = $this->values["price"];
// 		}
		
		return parent::update($id, $where);
	}
	
	// restituisce true se la riga del carrello non è una gift card
	public static function isGiftCart($idPage)
	{
		if (!v("attiva_gift_card"))
			return false;
		
		$p = new ProdottiModel();
		
		return $p->clear()->where(array(
			"id_page"	=>	(int)$idPage,
			"gift_card"	=>	1,
		))->rowNumber();
	}
	
	public static function immagineCarrello($idPage, $idC, $immagineCombinazione = null)
	{
		$clean["id_page"] = (int)$idPage;
		
		$elencoImmagini = ImmaginiModel::immaginiPaginaFull($clean["id_page"]);
		$elencoImmagini[] = "";
		
		$immagine = $elencoImmagini[0];
		
		if (v("immagine_in_varianti") && !v("immagini_separate_per_variante"))
		{
			if (!isset($immagineCombinazione))
				$immagineCombinazione = CombinazioniModel::g()->where(array("id_c"=>(int)$idC))->field("immagine");
			
			if (isset($immagineCombinazione) && $immagineCombinazione && in_array($immagineCombinazione,$elencoImmagini))
				$immagine = $immagineCombinazione;
		}
		else if (v("immagini_separate_per_variante"))
		{
			$immagini = ImmaginiModel::immaginiCombinazione((int)$idC);
			
			if (count($immagini) > 0)
				$immagine = $immagini[0]["immagine"];
		}
		
		return $immagine;
	}
}
