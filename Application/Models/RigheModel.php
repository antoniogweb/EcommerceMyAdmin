<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class RigheModel extends GenericModel {

	public function __construct() {
		$this->_tables='righe';
		$this->_idFields='id_r';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'righe.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'elementi' => array("HAS_MANY", 'RigheelementiModel', 'id_r', null, "CASCADE"),
// 			'spedizioni' => array("HAS_MANY", 'SpedizioninegoziorigheModel', 'id_r', null, "CASCADE"),
        );
    }
    
	public function insert()
	{
		$res = parent::insert();
		
		if ($res && VariabiliModel::movimenta() && isset($this->values["quantity"]) && isset($this->values["id_c"]))
			CombinazioniModel::g()->movimenta($this->values["id_c"], $this->values["quantity"], $this->lId);
		
		return $res;
	}
	
	// imposta il campo "movimentato"
	public function setMovimentato($idR, $valore = 1)
	{
		$this->sValues(array(
			"movimentato"	=>	$valore,
		));
		
		$this->pUpdate((int)$idR);
	}
	
	public function setPriceNonIvato($id = 0)
	{
		if (v("prezzi_ivati_in_prodotti") && (isset($this->values["price_ivato"]) || isset($this->values["prezzo_intero_ivato"]) || isset($this->values["prezzo_finale_ivato"])))
		{
			$valore = (float)RigheModel::g()->whereId((int)$id)->field("iva");
			
			if (isset($this->values["price_ivato"]))
				$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["prezzo_intero_ivato"]))
				$this->values["prezzo_intero"] = number_format(setPrice($this->values["prezzo_intero_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["prezzo_finale_ivato"]))
				$this->values["prezzo_finale"] = number_format(setPrice($this->values["prezzo_finale_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function update($id = null, $where = null)
	{
		$old = $this->selectId($id);
		
		$this->setPriceNonIvato($id);
		
		if (parent::update($id, $where))
		{
			$new = $this->selectId($id);
			
			if (VariabiliModel::movimenta() && $new["quantity"] != $old["quantity"] && $old["movimentato"])
				CombinazioniModel::g()->movimenta($new["id_c"], ($new["quantity"] - $old["quantity"]), (int)$id);
			
			return true;
		}
		
		return false;
	}
	
	public function del($id = null, $where = null)
	{
		$old = $this->selectId($id);
		
		if (parent::del($id, $where))
		{
			if (VariabiliModel::movimenta() && $old["movimentato"])
				CombinazioniModel::g()->movimenta($old["id_c"], (-1)*$old["quantity"], (int)$id);
			
			$_SESSION["aggiorna_totali_ordine"] = true;
			
			return true;
		}
		
		return false;
	}
	
	public function aggiornaTotaliOrdine($idRiga)
	{
		if (!App::$isFrontend)
		{
			$idOrdine = (int)RigheModel::g()->whereId((int)$idRiga)->field("id_o");
			
			if ($idOrdine && OrdiniModel::tipoOrdine($idOrdine) != "W")
				OrdiniModel::g()->aggiornaTotali($idOrdine);
		}
	}
	
	public function immagineCrud($record)
	{
		if (!$record["righe"]["prodotto_generico"])
			return "<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/".$record["righe"]["id_page"]."/".$record["righe"]["immagine"]."' />";
		
		return "";
	}
	
	public function titolocompleto($record)
	{
		$titolo = $record["pages"]["title"] ? $record["pages"]["title"] : $record[$this->_tables]["title"];
		
// 		if ($record[$this->_tables]["attributi"])
// 			$titolo .= "<br />".$record[$this->_tables]["attributi"];
		
		return $titolo;
	}
	
	public static function regalati($idLista, $idC = 0)
	{
		$r = RigheModel::g()->inner("orders")->on("orders.id_o = righe.id_o")->where(array(
			"orders.id_lista_regalo"	=>	(int)$idLista,
			"ne"	=>	array(
				"orders.stato"	=>	"deleted",
			),
		));
		
		if ($idC)
			$r->aWhere(array(
				"righe.id_c"	=>	(int)$idC
			));
		
		return $r;
	}
	
	private function getPrezzoCampo($record, $field)
	{
		return v("prezzi_ivati_in_prodotti") ? setPriceReverse(abs((float)setPrice($record["righe"][$field."_ivato"]))) : number_format(abs((float)setPrice($record["righe"][$field])),v("cifre_decimali"),",","");
	}
	
	public static function prodottoCustom($record)
	{
		return $record["righe"]["prodotto_generico"] ? true : false;
	}
	
	public function titoloCrud($record)
	{
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
		{
			if (self::prodottoCustom($record))
				return "<input id-riga='".$record["righe"]["id_r"]."' style='min-width:500px;' class='form-control' name='title' value='".$record["righe"]["title"]."' />";
			else
				return $record["righe"]["title"]."<input type='hidden' id-riga='".$record["righe"]["id_r"]."' name='title' value='".$record["righe"]["title"]."' />";
		}
		else
			return $record["righe"]["title"];
	}
	
	public function acquistabileCrud($record)
	{
		if ($record["righe"]["prodotto_generico"])
			return "";
		
		$cModel = new CombinazioniModel();
		
		if ($record["pages"]["attivo"] == "N" || !$cModel->clear()->whereId((int)$record["righe"]["id_c"])->field("acquistabile"))
			return "<i class='text-danger fa fa-ban'></i>";
	}
	
	public function codiceCrud($record)
	{
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
		{
			if (self::prodottoCustom($record))
				return "<input id-riga='".$record["righe"]["id_r"]."' style='min-width:100px;' class='form-control' name='codice' value='".$record["righe"]["codice"]."' />";
			else
				return $record["righe"]["codice"]."<input type='hidden' id-riga='".$record["righe"]["id_r"]."' name='codice' value='".$record["righe"]["codice"]."' />";
		}
		else
			return $record["righe"]["codice"];
	}
	
	public function prezzoInteroCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "prezzo_intero");
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='form-control prezzo_pieno_riga_ordine' name='prezzo_intero' value='".$prezzo."' />";
		else
			return $prezzo;
	}
	
	public function scontoCrud($record)
	{
		if (setPrice($record["righe"]["sconto"]) > 0)
			$sconto = number_format(setPrice($record["righe"]["sconto"]),2,",","");
		else
		{
			$sconto = CombinazioniModel::calcolaSconto(setPrice($record["righe"]["prezzo_intero"]), setPrice($record["righe"]["price"]));
			$sconto = number_format($sconto,2,",","");
		}
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
		{
			$prezzo = $this->getPrezzoCampo($record, "prezzo_intero");
			$disabled = "";
			
			if (setPrice($prezzo) <= 0 || $record["righe"]["id_riga_tipologia"])
				$disabled = "disabled";
			
			return "<input id-riga='".$record["righe"]["percentuale_promozione"]."' style='max-width:90px;' class='form-control sconto_riga_ordine' name='' value='".$sconto."' $disabled/>";
		}
		else
			return $sconto;
	}
	
	public function prezzoScontatoCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "price");
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
		{
			$disabled = $record["righe"]["id_riga_tipologia"] ? "disabled" : "";
			
			return '<div style="position:relative;">'."<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='prezzo_scontato_riga_ordine form-control' name='price' value='".$prezzo."' $disabled/><i style='display:none;position:absolute;top:10px;left:5px;' class='text-primary fa fa-spinner fa-spin'></i></div>";
		}
		else
			return $prezzo;
	}
	
	public function prezzoFinaleCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "prezzo_finale");
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='form-control' name='prezzo_finale' value='".$prezzo."' />";
		else
			return $prezzo;
	}
	
	public function quantitaCrud($record)
	{
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return '<div style="position:relative;">'."<input id-riga='".$record["righe"]["id_r"]."' style='max-width:60px;' class='quantita_riga_ordine form-control' name='quantity' value='".$record["righe"]["quantity"]."' /><i style='display:none;position:absolute;top:10px;left:5px;' class='text-primary fa fa-spinner fa-spin'></i></div>";
		else
			return $record["righe"]["quantity"];
	}
	
	public function evasaCrud($record)
	{
		$checked = $record["righe"]["evasa"] ? "checked" : "";
		
		if ($record["righe"]["id_riga_tipologia"])
			return "";
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return "<input $checked type='checkbox' id-riga='".$record["righe"]["id_r"]."' id-c='".$record["righe"]["id_c"]."' style='max-width:120px;' name='evasa' value='".$record["righe"]["evasa"]."' />";
		else
		{
			if ($record["righe"]["evasa"])
				return "<a class='ajlink' href='".Url::getRoot()."righe/modificaevaso/".$record["righe"]["id_r"]."/0'><i class='fa fa-check text-success'></i></a>";
			else
				return "<a class='ajlink' href='".Url::getRoot()."righe/modificaevaso/".$record["righe"]["id_r"]."/1'><i class='fa fa-ban text-danger'></i></a>";
		}
	}
	
	public function attributiCrud($record)
	{
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
		{
			$selectVarianti = ProdottiModel::selectCombinazioni((int)$record["righe"]["id_page"]);
			
			if (count($selectVarianti) === 1 && !reset($selectVarianti))
				return Html_Form::hidden("id_c", array_key_first($selectVarianti));
			else
				return Html_Form::select("id_c",$record["righe"]["id_c"], ProdottiModel::selectCombinazioni((int)$record["righe"]["id_page"]), "select_attributo_ordine_offline form-control", null, "yes");
		}
		else if ($record["righe"]["attributi"])
			return $record["righe"]["attributi"];
		
		return "--";
	}
	
	public function deletable($id)
	{
		$idOrdine = (int)$this->whereId($id)->field("id_o");
		
		return OrdiniModel::g()->isDeletable($idOrdine);
	}
	
	public function ordiniCrud($record)
	{
		if ($record["aggregate"]["numero_totale"] > 0)
		{
			if (!isset($_GET["esporta"]))
				return $record["aggregate"]["numero_totale"]." <a title='Elenco ordini dove è stato acquistato' class='iframe' href='".Url::getRoot()."ordini/main?partial=Y&id_page=".$record["righe"]["id_page"]."'><i class='fa fa-list'></i></a>";
			else
				return $record["aggregate"]["numero_totale"];
		}
		
		return "";
	}
	
	public function thumbCrud($record)
	{
		if ($record["righe"]["immagine"])
			return '<img src="'.Url::getRoot()."thumb/immagineinlistaprodotti/0/".$record["righe"]["immagine"].'" />';
		
		return "";
	}
	
	public function variante($record)
	{
		return $record["righe"]["attributi"];
	}
	
	public function statoordinelabel($records)
	{
		return OrdiniModel::g()->statoordinelabel($records);
// 		if (isset(OrdiniModel::$stati[$records["orders"]["stato"]]))
// 			return "<span class='text-bold label label-".OrdiniModel::$labelStati[$records["orders"]["stato"]]."'>".OrdiniModel::$stati[$records["orders"]["stato"]]."<span>";
// 		
// 		return $records["orders"]["stato"];
	}
	
	// Restituisce la quantità da spedire
	public static function qtaDaSpedire($idR)
	{
		$rModel = new RigheModel();
		$snrModel = new SpedizioninegoziorigheModel();
		
		// Quantità nella riga
		$qtaOrdine = $rModel->clear()->where(array(
			"id_r"		=>	(int)$idR,
			"gift_card"	=>	0,
			"prodotto_digitale"	=>	0,
			"prodotto_crediti"	=>	0,
		))->field("quantity");
		
		// Quantità in spedizione o spedita
		$qtaSpedita = $snrModel->clear()->inner(array("spedizione"))->where(array(
			"id_r"	=>	(int)$idR,
			"ne"	=>	array(
				"spedizioni_negozio.stato"	=>	"E",
			),
		))->getSum("quantity");
		
		if ($qtaOrdine > $qtaSpedita)
			return ($qtaOrdine - $qtaSpedita);
		
		return 0;
	}
	
	public static function getWhereClauseRicercaLibera($search)
	{
		$tokens = explode(" ", $search);
		$andArray = array();
		$iCerca = 10;
		
		foreach ($tokens as $token)
		{
			$andArray[str_repeat(" ", $iCerca)."lk"] = array(
				"righe.title"	=>	sanitizeAll(htmlentitydecode($token)),
			);
			
			$iCerca++;
		}
		
		return $andArray;
	}
}
