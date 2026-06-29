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
	public $metodoPerTitolo = "titoloJson";
	public $campoValore = "id_ordine_acquisto_riga";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public static $numeroNonCollegateCache = null;
	
	public function __construct() {
		$this->_tables = 'ordini_acquisto_righe';
		$this->_idFields = 'id_ordine_acquisto_riga';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe_ricezione' => array("HAS_MANY", 'OrdiniacquistoricezionirigheModel', 'id_ordine_acquisto_riga', null, "RESTRICT", "L'elemento ha delle ricezioni collegate e non può essere eliminato"),
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
		
		if (isset($this->values["id_ordine_acquisto"]) && isset($this->values["id_ordine_acquisto_riga_tipologia"]) && $this->values["id_ordine_acquisto_riga_tipologia"])
		{
			if (!OrdiniacquistorighetipologieModel::checkInserimentoTipologiaInOrdine((int)$this->values["id_ordine_acquisto"], (int)$this->values["id_ordine_acquisto_riga_tipologia"]))
				return false;
		}
		
		if (parent::insert())
		{
			$idOrdine = $this->clear()->whereId((int)$this->lId)->field("id_ordine_acquisto");
			
			if ($idOrdine)
				OrdiniacquistoModel::g(false)->aggiornaTotali((int)$idOrdine);
			
			return true;
		}
		
		return false;
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
    
    public function prodottoCrud($record)
	{
		if ($record["ordini_acquisto_righe"]["id_articolo"])
			return "<i class='fa text text-success fa-check'></i>";
		
		return "<i class='fa fa-ban'></i>";
	}
    
    public function titoloAssociaCrud($record)
	{
		if ($record["ordini_acquisto_righe"]["id_articolo"])
			return $record["ordini_acquisto_righe"]["titolo"];
		else
		{
			ob_start();
			$nascontiPulsanteAggiungiRiga = true;
			include(LIBRARY."/Application/Views/Ordiniacquisto/gestisci_associato_pulsante_righe.php");
			return $record["ordini_acquisto_righe"]["titolo"]."<br /><form class='form_associa' id-riga='".(int)$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."'>".ob_get_clean()."</form>";
		}
	}
    
	public function titoloCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
		{
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='min-width:350px;' class='form-control' name='titolo' value='".$record["ordini_acquisto_righe"]["titolo"]."' />";
		}
		else
			return $record["ordini_acquisto_righe"]["titolo"];
	}
    
    public function ordineCrud($record)
	{
		return "<a href='".Url::getRoot()."ordiniacquisto/righe/".$record["ordini_acquisto_righe"]["id_ordine_acquisto"]."' target='_blank'>".$record["ordini_acquisto_righe"]["id_ordine_acquisto"]."</a>";
	}
    
    public function attributiCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
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
	
	public function varianteCrud($record)
	{
		if (!$record["ordini_acquisto_righe"]["id_page"])
			return "";
		
		return CombinazioniModel::g()->getStringa($record["ordini_acquisto_righe"]["id_c"], "<br />");
	}
	
	public function codiceCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
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
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
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
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		$sconto = $record["ordini_acquisto_righe"]["sconto_2"];
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='max-width:65px;' class='form-control sconto_2_riga_ordine' name='sconto_2' value='".$sconto."' />";
		else
			return $sconto;
	}
	
	public function omaggioAssociaCrud($record)
	{
		$omaggio = $record["ordini_acquisto_righe"]["omaggio"];
		
		return $omaggio ? "<i class='fa fa-check text text-success'></i>" : "";
	}
	
	public function omaggioCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		$omaggio = $record["ordini_acquisto_righe"]["omaggio"];
		
		$checked = $omaggio ? "checked" : "";
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
			return "<input $checked type='checkbox' id-riga='".$record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"]."' style='position:relative;bottom:4px;' class='omaggio_riga_ordine' name='omaggio' value='".$omaggio."' />";
		else
			return $omaggio ? "<i class='fa fa-check text text-success'></i>" : "";
	}
	
	public function quantitaCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
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
	
	public function getTitoloRigaDaOrdinare($o)
	{
		$sigla = $o["orders"]["sezionale"] ? $o["orders"]["sezionale"].": ".$o["orders"]["numero_documento"] : "O:".$o["orders"]["id_o"];
		
		return $sigla." - SKU: ".$o["righe"]["codice"]." ".$o["righe"]["title"]." ".strip_tags($o["righe"]["attributi_backend"]);
	}
	
	public function riferimentoRigaCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		$idR = $record["ordini_acquisto_righe"]["id_r"];
		
		$recordAttuale = array();
		
		if ($idR)
		{
			$rModel = new RigheModel();
			
			$recordAttuale = $rModel->clear()->select("righe.id_r,righe.title,righe.codice,righe.attributi_backend,righe.qta_da_ordinare,orders.numero_documento,orders.data_documento,orders.sezionale,orders.id_o")->whereId((int)$idR)->inner("orders")->on("orders.id_o = righe.id_o")->first();
		}
		
		if (OrdiniacquistoModel::g()->isBozza($record["ordini_acquisto_righe"]["id_ordine_acquisto"]))
		{
			$opzioni = OrdiniModel::righeDaOrdinare($record["ordini_acquisto_righe"]["id_c"], 0, true);
			
			$arraySelect = array(0 => "--");
			
			if (!empty($recordAttuale))
				$arraySelect[$idR] = $this->getTitoloRigaDaOrdinare($recordAttuale);
			
			foreach ($opzioni as $o)
			{
				if (!isset($arraySelect[$o["righe"]["id_r"]]))
					$arraySelect[$o["righe"]["id_r"]] = $this->getTitoloRigaDaOrdinare($o);
			}
			
			return '<span style="display:inline-block;width:250px;" select2="">'.Html_Form::select("id_r", $record["ordini_acquisto_righe"]["id_r"], $arraySelect,"form-control select_id_r_riga_acquisto", null, "yes")."<span>";
		}
		else
		{
			if (!empty($recordAttuale))
				return $this->getTitoloRigaDaOrdinare($recordAttuale);
		}
		
		return "";
	}
	
	public function acquistabileCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		if (!$record["ordini_acquisto_righe"]["id_page"])
			return "";
		
		$cModel = new CombinazioniModel();
		$pModel = new PagesModel();
		
		if ($pModel->whereId((int)$record["ordini_acquisto_righe"]["id_page"])->field("attivo") == "N" || !$cModel->clear()->whereId((int)$record["ordini_acquisto_righe"]["id_c"])->field("acquistabile"))
			return "<i class='text-danger fa fa-ban'></i>";
		
		return "";
	}
	
	public function aliquitaIvaCrud($record)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga_tipologia"]))
			return "";
		
		return $record["ordini_acquisto_righe"]["aliquota_iva"]."%";
	}
	
	public static function subtotale($riga, $righeTestata = array(), $salvaSubtotale = false)
	{
		if (OrdiniacquistorighetipologieModel::rigaDiTestata($riga["id_ordine_acquisto_riga_tipologia"]))
			return 0;
		
		$subtotale = number_format($riga["prezzo"] * $riga["quantita"],2,".","");
		$scontato1 = number_format($subtotale * (1 - ($riga["sconto_1"] / 100)),2,".","");
		$scontatofinale = number_format($scontato1 * (1 - ($riga["sconto_2"] / 100)),2,".","");
		
		if ($riga["omaggio"])
			$scontatofinale = 0;
		
		if ($salvaSubtotale)
		{
			$rModel = new OrdiniacquistorigheModel();
			
			$rModel->sValues(array(
				"subtotale"	=>	$scontatofinale,
			));
			
			$rModel->update((int)$riga["id_ordine_acquisto_riga"]);
		}
		
		foreach ($righeTestata as $rigaTestata)
		{
			$scontatofinale = number_format($scontatofinale * (1 - ($rigaTestata["sconto_1"] / 100)),2,".","");
		}
		
		return $scontatofinale;
	}
	
	public function del($id = null, $where = null)
	{
		$idOrdine = $this->clear()->whereId((int)$id)->field("id_ordine_acquisto");
		
		if (parent::del($id, $where) && $idOrdine)
		{
			OrdiniacquistoModel::g(false)->aggiornaTotali((int)$idOrdine);
		}
	}
	
	public function statoordinelabel($record)
	{
		return OrdiniacquistoModel::g(false)->statoordinelabel($record);
	}
	
	public static function getWhereClauseRicercaLibera($search)
	{
		$tokens = explode(" ", $search);
		$andArray = array();
		$iCerca = 10;
		
		foreach ($tokens as $token)
		{
			$andArray[str_repeat(" ", $iCerca)."lk"] = array(
				"ordini_acquisto_righe.titolo"	=>	sanitizeAll(htmlentitydecode($token)),
			);
			
			$iCerca++;
		}
		
		return $andArray;
	}
	
	public static function numeroNonCollegate($idOrdine = 0)
	{
		if (!isset(self::$numeroNonCollegateCache[$idOrdine]))
		{
			$oarModel = new OrdiniacquistorigheModel();
			
			$oarModel->clear()->where(array(
				"id_articolo"	=>	0,
				"id_ordine_acquisto_riga_tipologia"	=>	0,
			));
			
			if ($idOrdine)
				$oarModel->aWhere(array(
					"id_ordine_acquisto"	=>	(int)$idOrdine,
				));
			
			self::$numeroNonCollegateCache[$idOrdine] = $oarModel->rowNumber();
		}
		
		return self::$numeroNonCollegateCache[$idOrdine];
	}
	
	public function titoloJson($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (!empty($record))
			return "ID Riga:". $record["id_ordine_acquisto_riga"]." - ".$record["codice"]." ".$record["titolo"]." ".$record["attributi"];
		
		return "";
	}
	
	public function aggiungiaricezione($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !$record["id_ordine_acquisto_riga_tipologia"] && isset($_GET["id_ordine_acquisto_ricezione"]))
		{
			$oaRic = new OrdiniacquistoricezioniModel();
			$oaRicRighe = new OrdiniacquistoricezionirigheModel();
			$recordRicezione = $oaRic->selectId((int)$_GET["id_ordine_acquisto_ricezione"]);
			
			if (!empty($recordRicezione) && !OrdiniacquistoModel::g(false)->isBozza((int)$record["id_ordine_acquisto"]))
			{
				$ultimaQuantita = $this->getUltimaQuantita((int)$id);
				
				$oaRicRighe->sValues(array(
					"id_admin"		=>	(int)User::$idAdmin,
					"id_ordine_acquisto_ricezione"	=>	(int)$_GET["id_ordine_acquisto_ricezione"],
					"quantita"		=>	$this->prodottiDaRicevere((int)$id),
					"id_ordine_acquisto_riga"		=>	(int)$id,
				), "sanitizeDb");
				
				$oaRicRighe->insert();
			}
		}
    }
    
    public function getClauseRigheOrdinate($idRigaAcquisto)
	{
		$oarrModel = new OrdiniacquistoricezionirigheModel();
		
		return $oarrModel->clear()
			->where(array(
				"ordini_acquisto_ricezioni_righe.id_ordine_acquisto_riga"		=>	(int)$idRigaAcquisto,
			));
	}
    
    public function prodottiRicevuti($idRigaAcquisto)
	{
		$oarModel = new OrdiniacquistoricezionirigheModel();
		
		$res = $this->getClauseRigheOrdinate($idRigaAcquisto)->select("sum(ordini_acquisto_ricezioni_righe.quantita) as SOMMA")->send();
			
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
	}
	
	public function prodottiDaRicevere($idRigaAcquisto)
	{
		$quantita = (int)$this->clear()->select("quantita")->whereId((int)$idRigaAcquisto)->field("quantita");
		
		$ricevuti = $this->prodottiRicevuti($idRigaAcquisto);
		
		return ($quantita > $ricevuti) ? (int)($quantita - $ricevuti) : 0;
	}
}
