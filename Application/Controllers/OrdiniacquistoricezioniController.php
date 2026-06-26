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

class OrdiniacquistoricezioniController extends BaseController
{
	public $argKeys = array(
	
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public $tabella = "ricezioni di ordini di acquisto";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];ordini_acquisto_ricezioni.id_ordine_acquisto_ricezione;",'ordini_acquisto_ricezioni.data_ricezione_merce');
		$this->mainHead = "N° Ricezione,Data ricezione";
		
		$this->m[$this->modelName]->select("ordini_acquisto_ricezioni.*")
			->aWhere(array(
				// "numero_ordine"	=>	$this->viewArgs["id_ordine_acquisto_filtro"],
			))
			->orderBy("id_ordine_acquisto_ricezione desc")->convert();
		
		// $this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], 'data_ordine');
		
		$this->m[$this->modelName]->save();
		
		// $this->filters = array("id_ordine_acquisto_filtro","ragione_sociale","dal","al");
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('data_ricezione_merce,numero_documento_trasporto');
		
		parent::form($queryType, $id);
	}
	
	public function righe($id = 0)
	{
		
	}
}
