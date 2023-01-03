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

class CombinazionimovimentiController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "movimenti prodotto";
	
	public $argKeys = array(
		'id_c:sanitizeAll'=>'tutti',
	);

	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array("cleanDateTime", "tipoCrud", "combinazioni_movimenti.valore", "orders.id_o", "statoOrdineCrud", "righe.id_r", "combinazioni_movimenti.giacenza", "resettaCrud");
		$this->mainHead = "Data e ora,Tipo movimento,Quantità,Ordine,Stato ordine,Riga ordine,Giacenza,Impostato manualmente";
		
		$this->m[$this->modelName]->clear()
			->select("combinazioni_movimenti.*,righe.id_r,orders.id_o,orders.stato")
			->left(array("riga"))
			->left("orders")->on("orders.id_o = righe.id_o")
			->where(array(
				"id_c"	=>	$this->viewArgs["id_c"],
			))
			->orderBy("combinazioni_movimenti.id_combinazione_movimento desc")
			->convert()
			->save();
		
		parent::main();
	}
}
