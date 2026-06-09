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

class OrdiniacquistorigheModel extends GenericModel
{
	public $campoTitolo = "titolo";

	public function __construct() {
		$this->_tables = 'ordini_acquisto_righe';
		$this->_idFields = 'id_ordine_acquisto_riga';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'ordine' => array("BELONGS_TO", 'OrdiniacquistoModel', 'id_ordine_acquisto',null,"CASCADE"),
			'articolo' => array("BELONGS_TO", 'MagazzinoarticoliModel', 'id_articolo',null,"CASCADE"),
			'tipologia' => array("BELONGS_TO", 'OrdiniacquistorighetipologieModel', 'id_ordine_acquisto_riga_tipologia',null,"CASCADE"),
		);
    }
	
	public function insert()
	{
		if (!isset($this->values["id_iva"]))
		{
			$ivaModel = new IvaModel();
			
			$this->values["id_iva"] = $ivaModel->clear()->select("id_iva")->orderBy("id_order")->limit(1)->field("id_iva");
			$this->values["aliquota_iva"] = sanitizeAll($ivaModel->getValore((int)$this->values["id_iva"]));
		}
		
		if (!isset($this->values["quantita"]))
			$this->values["quantita"] = 1;
		
		if (parent::insert())
		{
			$idOrdine = $this->clear()->whereId((int)$this->lId)->field("id_ordine_acquisto");
			
			if ($idOrdine)
				OrdiniacquistoModel::g(false)->aggiornaTotali((int)$idOrdine);
		}
	}
	
	public function update($id = null, $where = null)
	{
		return parent::update($id, $where);
	}
	
	public function primaImmagineCarrelloCrud($record)
    {
		if (!$record["ordini_acquisto_righe"]["id_page"])
			return "";
		
		$immagine = ProdottiModel::immagineCarrello($record["ordini_acquisto_righe"]["id_page"], $record["ordini_acquisto_righe"]["id_c"]);
		
		if ($immagine)
			return "<img src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$immagine."' />";
		
		return "";
    }
    
	public function titoloCrud($record)
	{
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
		{
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='min-width:500px;' class='form-control' name='titolo' value='".$record["ordini_acquisto_righe"]["titolo"]."' />";
		}
		else
			return $record["ordini_acquisto_righe"]["titolo"];
	}
    
    public function attributiCrud($record)
	{
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
		{
			if ($record["ordini_acquisto_righe"]["id_page"])
			{
				$selectVarianti = MagazzinoarticolicombinazioniModel::selectCombinazioni((int)$record["ordini_acquisto_righe"]["id_page"]);
				
				if (count($selectVarianti) === 1 && !reset($selectVarianti))
					return Html_Form::hidden("id_articolo", array_key_first($selectVarianti));
				else
					return Html_Form::select("id_articolo",$record["ordini_acquisto_righe"]["id_articolo"], $selectVarianti, "select_attributo_ordine_acquisto_offline form-control", null, "yes");
			}
			else
				return Html_Form::hidden("id_articolo", $record["ordini_acquisto_righe"]["id_articolo"]);
		}
		else
			return $record["ordini_acquisto_righe"]["attributi"];
	}
	
	public function codiceCrud($record)
	{
		$codice = $record["ordini_acquisto_righe"]["codice"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
		{
			if ($record["ordini_acquisto_righe"]["id_page"])
				return $record["ordini_acquisto_righe"]["codice"]."<input type='hidden' id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' name='codice' value='".$record["ordini_acquisto_righe"]["codice"]."' />";
			else
				return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:90px;' class='form-control codice_riga_ordine' name='codice' value='".$codice."' />";
		}
		else
			return $codice;
	}
	
	public function prezzoInteroCrud($record)
	{
		$prezzo = $record["ordini_acquisto_righe"]["prezzo"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:90px;' class='form-control prezzo_pieno_riga_ordine' name='prezzo' value='".$prezzo."' />";
		else
			return $prezzo;
	}
	
	public function sconto1Crud($record)
	{
		$sconto = $record["ordini_acquisto_righe"]["sconto_1"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:65px;' class='form-control sconto_1_riga_ordine' name='sconto_1' value='".$sconto."' />";
		else
			return $sconto;
	}
	
	public function sconto2Crud($record)
	{
		$sconto = $record["ordini_acquisto_righe"]["sconto_2"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:65px;' class='form-control sconto_2_riga_ordine' name='sconto_2' value='".$sconto."' />";
		else
			return $sconto;
	}
	
	public function quantitaCrud($record)
	{
		$quantita = $record["ordini_acquisto_righe"]["quantita"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:60px;' class='form-control quantita_riga_ordine' name='quantita' value='".$quantita."' />";
		else
			return $quantita;
	}
	
	public function deletable($id)
	{
		$idOrdine = (int)$this->whereId($id)->field("id_ordine_acquisto");
		
		return OrdiniacquistoModel::g()->isBozza($idOrdine);
	}
	
	public function acquistabileCrud($record)
	{
		if (!$record["ordini_acquisto_righe"]["id_page"])
			return "";
		
		$cModel = new CombinazioniModel();
		$pModel = new PagesModel();
		
		if ($pModel->whereId((int)$record["ordini_acquisto_righe"]["id_page"])->field("attivo") == "N" || !$cModel->clear()->whereId((int)$record["ordini_acquisto_righe"]["id_c"])->field("acquistabile"))
			return "<i class='text-danger fa fa-ban'></i>";
		
		return "";
	}
	
	public static function subtotale($riga)
	{
		if ($riga["id_ordine_acquisto_riga_tipologia"])
			return 0;
		
		$scontato1 = number_format($riga["prezzo"] * (1 - ($riga["sconto_1"] / 100)),2,".","");
		$scontato2 = number_format($scontato1* (1 - ($riga["sconto_2"] / 100)),2,".","");
		
		$subtotale = number_format($scontato2 * $riga["quantita"],2,".","");
		
		return $subtotale;
	}
	
	public function del($id = null, $where = null)
	{
		$idOrdine = $this->clear()->whereId((int)$id)->field("id_ordine_acquisto");
		
		if (parent::del($id, $where) && $idOrdine)
		{
			OrdiniacquistoModel::g(false)->aggiornaTotali((int)$idOrdine);
		}
	}
}
