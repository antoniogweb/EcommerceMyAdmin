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

class SpedizionieriController extends BaseController {
	
	public $orderBy = "id_order";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "utenti";
	
	public function __construct($model, $controller, $queryString, $application, $action)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_gestione_spedizionieri") && !v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->model("SpedizionieriletterevetturaModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];spedizionieri.titolo;", ";spedizionieri.codice;", "attivoCrud");
		$this->mainHead = "Titolo,Tipologia,Attivo";
		
		$this->m[$this->modelName]->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = SpedizionieriModel::getModulo((int)$id)->gCampiForm();
		
		if (!$fields)
			$fields = 'titolo,modulo,attivo';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function lettere($id = 0)
	{
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = "spedizionieri";
		
		$this->_posizioni['lettere'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_spedizioniere";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "SpedizionieriletterevetturaModel";
		
		$this->mainFields = array("titoloCrud", "filename", "attivoCrud");
		$this->mainHead = "Titolo,File,Attivo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"lettere/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->orderBy("id_order")->where(array("id_spedizioniere"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["SpedizionieriModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
