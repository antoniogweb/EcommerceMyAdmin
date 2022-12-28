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

class IvaController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("iva.titolo","iva.valore","iva.tipo","iva.commercio","nascondi","usataperspedizione");
		$this->mainHead = "Titolo,Valore,Tipo,Tipo commercio,Nascondi al cliente,Usata per la spedizione";
		
		$this->aggiungiCodiceGestionale();
		
		$this->m[$this->modelName]->clear()->orderBy("id_order")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = 'titolo,valore,tipo,commercio,usata_per_spedizione,nascondi';
		
		if (v("attiva_collegamento_gestionali"))
			$fields .= ",codice_gestionale";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
