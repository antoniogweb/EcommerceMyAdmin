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
		
		$combinazioni = $combModel->clear()->select("combinazioni.*,pages.id_iva,pages.title,iva.valore,pages.id_marchio")
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
				"prezzo"	=>	number_format($c["combinazioni"]["price_scontato"],2,".",""),
				"id_iva"	=>	(int)$c["pages"]["id_iva"],
				"aliquota_iva"	=>	$c["iva"]["valore"],
				"id_marchio"	=>	(int)$c["pages"]["id_marchio"],
			));
			
			if ($this->insert())
			{
				$lastId = (int)$this->lId;
				
				$macModel->sValues(array(
					"id_articolo"	=>	$lastId,
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
	
	public function attivoCrud($record)
	{
		if (!$record["pages"]["id_page"])
			return "";
		
		return CombinazioniModel::g()->attivoCrud($record);
	}
	
	public function prodottoCrud($record)
	{
		if ($record["pages"]["id_page"])
			return "<a target='_blank' href='".Url::getRoot()."prodotti/form/update/".$record["pages"]["id_page"]."'>".gtext("Vedi")." <i class='fa fa-angle-right'></i></a>";
		
		return $record["magazzino_articoli"]["titolo"];
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
				"n!concat(magazzino_articoli.titolo,' ',marchi.titolo,' ',categories.title,' ',magazzino_articoli.codice)"	=>	sanitizeAll(htmlentitydecode($token)),
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
				$oarModel = new OrdiniacquistorigheModel();
				
				$oarModel->sValues(array(
					"id_articolo"	=>	(int)$id,
					"id_ordine_acquisto"	=>	(int)$_GET["id_ordine_acquisto"],
					"titolo"	=>	$record["titolo"],
					"codice"		=>	$record["codice"],
					"prezzo"		=>	$record["prezzo"],
					"quantita"		=>	1,
					"id_iva"		=>	$record["id_iva"],
					"aliquota_iva"	=>	$record["aliquota_iva"],
					"id_marchio"	=>	$record["id_marchio"],
					"id_marchio"	=>	$record["id_marchio"],
				), "sanitizeDb");
				
				$oarModel->insert();
			}
		}
    }
}
