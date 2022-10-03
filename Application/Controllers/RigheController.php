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

class RigheController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array('dal:sanitizeAll'=>'tutti', 'al:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "marketing";
	
	public $tabella = "prodotti più venduti";
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array(
			array(
				'width'	=>	'80px',
			),
		);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta');
		
		$this->shift();
		
		$this->mainFields = array("thumb", "titolocompleto", "categories.title", "ordini");
		$this->mainHead = "Immagine,Prodotto,Categoria,Ordini";
		
		$filtri = array("dal","al");
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
				->select("sum(quantity) as numero_totale,righe.id_r,righe.title,righe.attributi,righe.immagine,categories.title,righe.id_c,pages.title")
				->inner("orders")->on("righe.id_o = orders.id_o")
				->left("pages")->on("pages.id_page = righe.id_page")
				->left("categories")->on("pages.id_c = categories.id_c")
				->where(array(
					"ne" => array(
						"orders.stato"	=>	"deleted"
					),
				))
				->groupBy("righe.id_page")
				->orderBy("sum(quantity) desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
