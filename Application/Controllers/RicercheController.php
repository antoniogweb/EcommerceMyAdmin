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

class RicercheController extends BaseController
{
	public $tabella = "ricerche";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti',
		'tipo:sanitizeAll'=>'R',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_motori_ricerca"))
			$this->responseCode(403);
	}

	public function main()
	{
		$this->shift();
		
		if ($this->viewArgs['dal'] == "tutti")
		{
			$date = new DateTime();
			$date->modify("-3 month");
			
			$_GET['dal'] = $date->format("d-m-Y");
			$this->shift();
		}
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'esporta');
		
		if ($this->viewArgs['tipo'] == "T")
		{
			$this->mainFields = array("ricerche.termini", "aggregate.numero");
			$this->mainHead = "Termine ricercato,Numero di volte";
			$groupBy = "termini";
			$orderBy = "(count(ricerche.termini) * ricerche.numero)";
		}
		else
		{
			$this->mainFields = array("ricerche.ricerca", "aggregate.numero");
			$this->mainHead = "Ricerca effettuata,Numero di volte";
			$groupBy = "ricerca";
			$orderBy = "(count(ricerche.ricerca) * ricerche.numero)";
		}
		
		$this->filters = array(array("tipo",null,array("T"=>"Termini","R"=>"Ricerche complete")),"titolo","dal","al");
		
		$this->m[$this->modelName]->clear()
				->select("$orderBy as numero,ricerche.termini,ricerche.ricerca,ricerche.data_creazione")
				->where(array(
					"lk" => array('termini' => $this->viewArgs['titolo']),
				))
				->orderBy($orderBy." DESC")->groupBy($groupBy)->convert();
		
		if ($this->viewArgs['tipo'] == "T")
			$this->m[$this->modelName]->sWhere("ricerca = ''");
		else
			$this->m[$this->modelName]->sWhere("termini = ''");
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
