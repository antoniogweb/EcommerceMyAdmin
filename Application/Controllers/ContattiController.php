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

class ContattiController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array('dal:sanitizeAll'=>'tutti', 'al:sanitizeAll'=>'tutti', 'fonte:sanitizeAll'=>'tutti', 'verificato:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "marketing";
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta');
		
		$this->shift();
		
		$this->mainFields = array("cleanDateTime", "FORM ;contatti.fonte_iniziale;", "contatti.email", "contatti.nome", "contatti.telefono");
		$this->mainHead = "Data creazione,Fonte,Email,Nome,Telefono";
		
		$filtroFonte = array(
			"tutti"		=>	"Fonte",
		) + ContattiModel::$elencoFonti;
		
		$filtri = array("dal","al",array("fonte",null,$filtroFonte));
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()->where(array(
			"fonte_iniziale"	=>	$this->viewArgs["fonte"],
			"verificato"		=>	$this->viewArgs["verificato"],
		))->orderBy("contatti.data_creazione desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->m[$this->modelName]->save();
		
		$this->getTabViewFields("main");
		
		parent::main();
	}
}
