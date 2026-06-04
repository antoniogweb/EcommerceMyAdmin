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

		parent::__construct();
	}
	
	public function relations() {
		return array(
			'combinazioni' => array("HAS_MANY", 'MagazzinoarticolicombinazioniModel', 'id_articolo', null, "CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
		);
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
			
			// echo $titolo."\n";
			
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
}
