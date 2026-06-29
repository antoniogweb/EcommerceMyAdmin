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

class OrdiniacquistoricezionirigheModel extends GenericModel
{
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public function __construct() {
		$this->_tables = 'ordini_acquisto_ricezioni_righe';
		$this->_idFields = 'id_ordine_acquisto_ricezione_riga';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'ricezione' => array("BELONGS_TO", 'OrdiniacquistoricezioniModel', 'id_ordine_acquisto_ricezione',null,"CASCADE"),
			'riga' => array("BELONGS_TO", 'OrdiniacquistorigheModel', 'id_ordine_acquisto_riga',null,"CASCADE"),
			'articolo' => array("BELONGS_TO", 'MagazzinoarticoliModel', 'id_articolo',null,"CASCADE"),
		);
    }
    
    public function primaImmagineCarrelloCrud($record)
    {
		if ($record["ordini_acquisto_ricezioni_righe"]["id_ordine_acquisto_riga"])
			$model = new OrdiniacquistorigheModel();
		else
			$model = new MagazzinoarticoliModel();
		
		return $model->primaImmagineCarrelloCrud($record);
    }
    
    public function titoloCrud($record)
	{
		if ($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"])
			return $record["ordini_acquisto_righe"]["titolo"];
		else
			return $record["pages"]["title"];
	}
	
	public function attributiCrud($record)
	{
		if ($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"])
			return $record["ordini_acquisto_righe"]["attributi"];
		else
			return strip_tags(MagazzinoarticoliModel::g()->varianteCrud($record));
	}
	
	public function codiceCrud($record)
	{
		if ($record["ordini_acquisto_righe"]["id_ordine_acquisto_riga"])
			return $record["ordini_acquisto_righe"]["codice"];
		else
			return $record["magazzino_articoli"]["codice"];
	}
    
    public function ordineCrud($record)
	{
		$oarModel = new OrdiniacquistorigheModel();

		return $oarModel->ordineCrud($record);
	}
	
	public function quantitaCrud($record)
	{
		return "<input id-riga='".$record["ordini_acquisto_ricezioni_righe"]["id_ordine_acquisto_ricezione_riga"]."' style='max-width:60px;' class='form-control quantita_riga_ricezione' name='quantita' value='".$record["ordini_acquisto_ricezioni_righe"]["quantita"]."' />";
	}
	
	public function riferimentoRigaCrud($record)
	{
		$rModel = new RigheModel();
		
		$recordAttuale = $rModel->clear()->select("righe.id_r,righe.title,righe.codice,righe.attributi_backend,righe.qta_da_ordinare,orders.numero_documento,orders.data_documento,orders.sezionale,orders.id_o")->whereId((int)$record["ordini_acquisto_righe"]["id_r"])->inner("orders")->on("orders.id_o = righe.id_o")->first();
		
		if (!empty($recordAttuale))
			return OrdiniacquistorigheModel::g()->getTitoloRigaDaOrdinare($recordAttuale);
	}
}
