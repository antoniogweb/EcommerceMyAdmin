<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class MagazzinoarticoliModel extends GenericModel
{
	public $metodoPerTitolo = "titoloJson";
	public $campoValore = "id_articolo";
	
	public function __construct()
	{
		$this->_tables = 'magazzino_articoli';
		$this->_idFields = 'id_articolo';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'combinazioni' => array("HAS_MANY", 'MagazzinoarticolicombinazioniModel', 'id_articolo', null, "CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
		);
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_iva'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_iva',
					'labelString'=>	'Aliquota Iva',
					'options'	=>	$this->selectIva(),
					'reverse' => 'yes',
					
				),
			),
		);
	}
    
    public function setAliquotaIva()
	{
		if (isset($this->values["id_iva"]) && !isset($this->values["aliquota_iva"]))
			$this->values["aliquota_iva"] = sanitizeAll(IvaModel::g()->getValore((int)$this->values["id_iva"]));
	}
    
    public function insert()
	{
		$this->setAliquotaIva();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->setAliquotaIva();
		
		return parent::update($id, $where);
	}
    
    public function importaArticoliDaEcommerce($log = null)
	{
		VariabiliModel::$valori["template_attributo"] = "[VALORE]";
	
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		$combModel = new CombinazioniModel();
		$macModel = new MagazzinoarticolicombinazioniModel();
		
		$combinazioni = $combModel->clear()->select("combinazioni.*,pages.id_iva,pages.title,iva.valore,pages.id_marchio,pages.id_page")
			->inner(array("pagina"))
			->left("iva")->on("pages.id_iva = iva.id_iva")
			->sWhere("NOT EXISTS ( select 1 from magazzino_articoli_combinazioni where magazzino_articoli_combinazioni.id_c = combinazioni.id_c)")
			->send();
		
		// echo count($combinazioni);die();
			
		foreach ($combinazioni as $c)
		{
			$idC = (int)$c["combinazioni"]["id_c"];
			
			$stringa = htmlentitydecode($combModel->getStringa($idC, " ", false, true));
			
			$titolo = htmlentitydecode($c["pages"]["title"]);
			
			if ($stringa)
				$titolo .= " ".trim($stringa);
			
			$this->sValues(array(
				"titolo"	=>	$titolo,
				"codice"	=>	htmlentitydecode($c["combinazioni"]["codice"]),
				"gtin"		=>	htmlentitydecode($c["combinazioni"]["gtin"]),
				"prezzo"	=>	0,
				"sconto_1"	=>	0,
				"sconto_2"	=>	0,
				"id_iva"	=>	(int)$c["pages"]["id_iva"],
				"aliquota_iva"	=>	$c["iva"]["valore"],
				"id_marchio"	=>	(int)$c["pages"]["id_marchio"],
			));
			
			if ($this->insert())
			{
				$lastId = (int)$this->lId;
				
				$macModel->sValues(array(
					"id_articolo"	=>	$lastId,
					"id_page"		=>	(int)$c["pages"]["id_page"],
					"id_c"			=>	(int)$idC
				));
				
				if (!$macModel->insert())
				{
					$this->del($lastId);
				}
				else
				{
					if ($log)
						$log->writeString("Prodotto: $titolo, ID Combinazione: $idC");
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
	
	public function titoloCrud($record)
	{
		if (!$record["pages"]["id_page"])
			return $record["magazzino_articoli"]["titolo"];
		
		return $record["pages"]["title"];
	}
	
	public function varianteCrud($record)
	{
		if (!$record["pages"]["id_page"])
			return "";
		
		return CombinazioniModel::g()->getStringa($record["combinazioni"]["id_c"], "<br />");
	}
	
	public function attivoCrud($record)
	{
		if (!$record["pages"]["id_page"])
			return "";
		
		return CombinazioniModel::g()->attivoCrud($record);
	}
	
	public function prodottoCrud($record)
	{
		if ($record["pages"]["id_page"])
		{
			if (ControllersModel::checkAccessoAlController(array("prodotti")))
				return "<a target='_blank' href='".Url::getRoot()."prodotti/form/update/".$record["pages"]["id_page"]."'> <i class='fa fa-eye'></i></a>";
			else
				return $record["pages"]["title"];
		}
		
		return "";
	}
	
	public function acquistabileCrud($record)
	{
		if (!$record["pages"]["id_page"])
			return "";
		
		if (!isset($_GET["esporta"]))
		{
			if ($record["combinazioni"]["acquistabile"] && $record["pages"]["attivo"] == "Y")
				return "<i class='fa fa-check text text-success'></i>";
			else
				return "<i class='fa fa-ban text text-danger'></i>";
		}
		else
			return ($record["combinazioni"]["acquistabile"] && $record["pages"]["attivo"] == "Y") ? gtext("Sì") : gtext("No");
	}
	
	public function primaImmagineCarrelloCrud($record)
    {
		if (!$record["pages"]["id_page"])
			return "";
		
		$immagine = ProdottiModel::immagineCarrello($record["pages"]["id_page"], $record["combinazioni"]["id_c"]);
		
		if ($immagine)
			return "<img src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$immagine."' />";
		
		return "";
    }
    
    public static function getWhereClauseRicercaLibera($search)
	{
		$tokens = explode(" ", $search);
		$andArray = array();
		$iCerca = 8;
		
		foreach ($tokens as $token)
		{
			$andArray[str_repeat(" ", $iCerca)."lk"] = array(
				"n!concat(magazzino_articoli.titolo,' ',marchi.titolo,' ',categories.title,' ',magazzino_articoli.codice,' ',pages.title,' ',magazzino_articoli.codice)"	=>	sanitizeAll(htmlentitydecode($token)),
			);
			
			$iCerca++;
		}
		
		return $andArray;
	}
	
	public function bulkaggiungiaordine($record)
    {
		return "<i data-azione='aggiungiaordine' title='".gtext("Aggiungi all'ordine")."' class='bulk_trigger help_trigger_aggiungi_ad_ordine_acquisto fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungiaordine($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_ordine_acquisto"]))
		{
			if (OrdiniacquistoModel::g(false)->isBozza((int)$_GET["id_ordine_acquisto"]))
			{
				$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb((int)$id);
				$titoloWeb = null;
				
				$combModel = new CombinazioniModel();
				
				if (!empty($recordWeb))
				{
					$pagesModel = new PagesModel();
					$titoloWeb = $pagesModel->clear()->select("title")->whereId((int)$recordWeb["id_page"])->field("title");
				}
				
				$oarModel = new OrdiniacquistorigheModel();
				
				$oarModel->sValues(array(
					"id_articolo"	=>	(int)$id,
					"id_ordine_acquisto"	=>	(int)$_GET["id_ordine_acquisto"],
					"titolo"		=>	$titoloWeb ?? $record["titolo"],
					"codice"		=>	$record["codice"],
					"gtin"			=>	$record["gtin"],
					"prezzo"		=>	$record["prezzo"],
					"sconto_1"		=>	$record["sconto_1"],
					"sconto_2"		=>	$record["sconto_2"],
					"quantita"		=>	1,
					"id_iva"		=>	$record["id_iva"],
					"aliquota_iva"	=>	$record["aliquota_iva"],
					"id_marchio"	=>	$record["id_marchio"],
					"id_c"			=>	$recordWeb["id_c"] ?? 0,
					"id_page"		=>	$recordWeb["id_page"] ?? 0,
					"attributi"		=>	isset($recordWeb["id_c"]) ? $combModel->getStringa($recordWeb["id_c"], "<br />") : "",
				), "sanitizeDb");
				
				$oarModel->insert();
			}
		}
    }
    
    public function codiceView($record)
	{
		return gtext("SKU").": <b>".$record["magazzino_articoli"]["codice"]."</b><br />\n".gtext("GTIN").": ".$record["magazzino_articoli"]["gtin"]."<br />\n".gtext("MPN").": ".$record["magazzino_articoli"]["mpn"];
	}
	
	public function codiceCrud($record)
	{
		if (!isset($_GET["esporta"]))
		{
			$html = "<div style='min-width:180px;margin-bottom:5px;'><b style='width:36px;display:inline-block;'>".gtext("SKU").":</b> <input id-articolo='".$record["magazzino_articoli"]["id_articolo"]."' style='max-width:140px;display:inline;' class='form-control class_combinazione class_combinazione_".$record["magazzino_articoli"]["id_articolo"]."' name='codice' value='".$record["magazzino_articoli"]["codice"]."' /></div>";
			
			$html .= "<div style='min-width:180px;margin-bottom:5px;'><b style='width:36px;display:inline-block;'>".gtext("GTIN").":</b> <input id-articolo='".$record["magazzino_articoli"]["id_articolo"]."' style='max-width:140px;display:inline;' class='form-control' name='gtin' value='".$record["magazzino_articoli"]["gtin"]."' /></div>";
			
			return $html;
		}
		else
			return $this->codiceView($record);
	}
	
	public function titoloJson($id)
	{
		$clean["id"] = (int)$id;
		
		$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb($clean["id"]);
		
		if (!empty($recordWeb))
		{
			$pModel = new PagesModel();
			
			return $pModel->titoloJson((int)$recordWeb["id_page"]);
		}
		else
			return  $this->select("titolo")->whereId((int)$id)->field("titolo");
	}
	
	public function titoloCombinazioneJson($id)
	{
		$clean["id"] = (int)$id;
		
		$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb($clean["id"]);
		
		if (!empty($recordWeb))
		{
			$pModel = new CombinazioniModel();
			
			return $pModel->titoloJson((int)$recordWeb["id_c"]);
		}
		else
			return  "--";
	}
}
