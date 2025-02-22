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
		
		$this->formStruct["entries"]["stampa_gtin_nel_feed"] = array(
			"type"	=>	"Select",
			"options"	=>	self::$attivoSiNo,
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	'Permetti la stampa del codice GTIN nel feed',
			'wrap'		=>	array(
				null,
				null,
				"<div class='form_notice'>".gtext("Se impostato su No il GTIN non verrà stampato nel feed Google o Facebook, anche se presente nel prodotto.")."</div>"
			),
		);
		
		$this->formStruct["entries"]["prodotto_digitale"] = array(
			"type"	=>	"Select",
			"options"	=>	self::$attivoSiNo,
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	'È un prodotto digitale?',
			'wrap'		=>	array(
				null,
				null,
				"<div class='form_notice'>".gtext("Se impostato su Sì, non sarà prevista la spedizione per tale prodotto.")."</div>"
			),
		);
		
		$this->formStruct["entries"]["prodotto_crediti"] = array(
			"type"	=>	"Select",
			"options"	=>	self::$attivoSiNo,
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
			'labelString'=>	'È un prodotto per acquistare CREDITI?',
			'wrap'		=>	array(
				null,
				null,
				"<div class='form_notice'>".gtext("Se impostato su Sì, alla conclusione dell'ordine verro assegnati al clienti tanti CREDITI quanti indicati nel campo inferiore Numero Crediti.")."</div>"
			),
		);
		
		$this->formStruct["entries"]["numero_crediti"] = array(
			'labelString'	=>	'Numero Crediti',
			'wrap'		=>	array(
				null,
				null,
				"<div class='form_notice'>".gtext("Il numero di crediti che verranno assegnati al cliente dopo aver concluso l'ordine.")."</div>"
			),
		);
	}
	
	public function setFilters()
	{
// 		$this->_popupItemNames = array(
// 			'attivo'	=>	'attivo',
// 			'in_evidenza'	=>	'in_evidenza',
// 			'in_promozione'	=>	'in_promozione',
// 		);
// 
// 		$this->_popupLabels = array(
// 			'attivo'	=>	'PUBBLICATO?',
// 			'in_evidenza'	=>	'IN EVIDENZA?',
// 			'in_promozione'	=>	'IN PROMOZIONE?',
// 		);
// 
// 		$this->_popupFunctions = array(
// 			'attivo'=>	'getYesNo',
// 			'in_evidenza'	=>	'getYesNo',
// 			'in_promozione'	=>	'getYesNo',
// 		);
// 		
// 		$this->_popupOrderBy = array(
// 		);
// 		
// 		$this->_popupWhere[] = array();
	}
	
	// Controlla che il codice non sia già stato usato
	public function checkCodiceUnivoco($id = 0)
	{
		if (isset($this->values["codice"]) && $this->values["codice"] && v("controlla_che_il_codice_prodotti_sia_unico"))
		{
			$c = new CombinazioniModel();
			
			$c->clear()->where(array(
				"codice"	=>	sanitizeDb($this->values["codice"]),
			));
			
			if ($id)
				$c->aWhere(array(
					"ne"	=>	array(
						"id_page"	=>	(int)$id,
					),
				));
			
			$numero = $c->rowNumber();
			
			if ($numero)
			{
				$this->result = false;
				$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione, il codice inserito è già usato da un altro prodotto")."</div><div style='display:none;' rel='hidden_alert_notice'>codice</div>";
				
				return false;
			}
		}
		
		return true;
	}
	
	public function insert()
	{
		if ($this->checkCodiceUnivoco())
			return parent::insert();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		if ($this->checkCodiceUnivoco($id))
			return parent::update($id, $where);
		
		return false;
	}
	
	// restituisce true se la riga del carrello è una gift card
	public static function isGiftCart($idPage)
	{
		if (!v("attiva_gift_card"))
			return false;
		
		return self::isTipo($idPage, "gift_card");
		
// 		$p = new ProdottiModel();
// 		
// 		return $p->clear()->where(array(
// 			"id_page"	=>	(int)$idPage,
// 			"gift_card"	=>	1,
// 		))->rowNumber();
	}
	
	// restituisce true se la riga del carrello è un prodotto digitale
	public static function isProdottoDigitale($idPage)
	{
		if (!v("attiva_prodotti_digitali"))
			return false;
		
		return self::isTipo($idPage, "prodotto_digitale");
	}
	
	// restituisce true se la riga del carrello è un prodotto CREDITI
	public static function isProdottoCrediti($idPage)
	{
		if (!v("attiva_crediti"))
			return false;
		
		return self::isTipo($idPage, "prodotto_crediti");
	}
	
	
	public static function isTipo($idPage, $tipo)
	{
		$p = new ProdottiModel();
		
		return $p->clear()->where(array(
			"id_page"	=>	(int)$idPage,
			"$tipo"	=>	1,
		))->rowNumber();
	}
	
	public static function selectCombinazioni($idPage)
	{
		$c = new CombinazioniModel();
		
		$res = $c->clear()->select("id_c,acquistabile")->where(array(
			'id_page'	=>	(int)$idPage,
		))->orderBy("id_order")->toList("id_c", "acquistabile")->send();
		
		$resultArray = [];
		
		foreach ($res as $id_c => $acquistabile)
		{
			$stringa = strip_tags($c->getStringa($id_c, ","));
// 			$stringa = $stringa ? $stringa : "--";
// 			
// 			if (!$acquistabile)
// 				$stringa .= "(NON ACQUISTABILE)";
			
			$resultArray[$id_c] = $stringa;
		}
		
		return $resultArray;
	}
	
	// Restituisce l'ID della combinazione del primo prodotto generico trovato
	public static function getIdProdottoGenerico()
	{
		$c = new CombinazioniModel();
		
		$record = $c->clear()->select("combinazioni.id_c")->inner(array("pagina"))->where(array(
			"pages.prodotto_generico"	=>	1,
		))->first();
		
		if (!empty($record))
			return $record["combinazioni"]["id_c"];
		
		return 0;
	}
	
	public static function ricalcolaPrezziSuNuovaIva($record, $vecchiaIva, $nuovaIva)
	{
		list ($nuvoPrezzoIvato, $nuvoPrezzoNonIvato) = IvaModel::ricalcolaPrezzo($record["price_ivato"], $record["price"], $vecchiaIva, $nuovaIva);
		
		$record["price_ivato"] = $nuvoPrezzoIvato;
		$record["price"] = $nuvoPrezzoNonIvato;
		
		list ($nuvoPrezzoIvato, $nuvoPrezzoNonIvato) = IvaModel::ricalcolaPrezzo($record["price_scontato_ivato"], $record["price_scontato"], $vecchiaIva, $nuovaIva);
		
		$record["price_scontato_ivato"] = $nuvoPrezzoIvato;
		$record["price_scontato"] = $nuvoPrezzoNonIvato;
		
		return $record;
	}

	// Restituisce i prodotti più venduti, di una certa categoria e/o di un certo marchio
	public static function prodottiPiuVenduti($id_c = 0, $id_marchio = 0, $limit = 0)
	{
		$p = new PagesModel;

		$p->clear()->select("pages.id_page")
			->left(array("righe"))
			->addWhereAttivo()
			->groupBy("pages.id_page")
			->orderBy("sum(righe.quantity) desc,pages.id_page desc")
			->toList("pages.id_page");

		if ($limit)
			$p->limit($limit);

		if ($id_c)
			$p->addWhereCategoria((int)$id_c);

		if ($id_marchio)
			$p->aWhere(array(
				"id_marchio"	=>	(int)$id_marchio,
			));

		return $p->send();
	}
}
