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

class CorrieriController extends BaseController {
	
	public $mainFields = array("[[ledit]];corrieri.titolo;","corrieri.attivo");
	
	public $mainHead = "Titolo,Attivo";
	
	public $filters = array("titolo");
	
	public $formValuesToDb = 'titolo,attivo';
	
	public $orderBy = "id_order";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti', 'nazione:sanitizeAll'=>'W');
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function main()
	{
		$this->shift();
		
		$this->m[$this->modelName]->where(array(
				"lk" => array("titolo" => $this->viewArgs["titolo"]),
// 				"attivo"	=>	$this->viewArgs["attivo"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function ordina()
	{
		$this->modelName = "CorrieriModel";
		
		parent::ordina();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		parent::form($queryType, $id);
	}
	
	public function prezzi($id = 0)
	{
		$this->model("CorrierispeseModel");
		
		$this->_posizioni['prezzi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_corriere";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "CorrierispeseModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("peso","nazione","corrieri_spese.prezzo");
		$this->mainHead = "Peso (kg),Nazione,Prezzo IVA esclusa (€)";
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			$this->mainFields[] = "prezzoivato";
			$this->mainHead .= ",Prezzo IVA inclusa (€)";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"prezzi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("corrieri_spese.peso")->where(array(
			"id_corriere"	=>	$clean['id'],
			"nazione"		=>	$this->viewArgs["nazione"],
		))->convert()->save();
		
		$this->tabella = "corrieri";
		
		$data["elencoNazioniCorrieri"] = $this->m[$this->modelName]->clear()->select("distinct nazione")->where(array(
			"id_corriere"	=>	$clean['id'],
		))->orderBy("nazione")->toList("nazione")->send();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["CorrieriModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
