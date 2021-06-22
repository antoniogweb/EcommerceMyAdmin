<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class NazioniController extends BaseController {
	
	public $mainFields = array("[[ledit]];nazioni.titolo;","nazioni.iso_country_code","tipo","attivaCrud","attivaSpedizioneCrud","pivaAttiva");
	
	public $mainHead = "Titolo,Codice nazione,Tipo,Attiva,Spedizione attiva,Attiva P.IVA";
	
	public $orderBy = "titolo";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti', 'tipo:sanitizeAll'=>'tutti', 'attiva:sanitizeAll'=>'tutti', 'attiva_spedizione:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>1000, 'mainMenu'=>'add');
		
		$attivaDisattiva = array(
			"tutti"	=>	"Attiva / Disattiva",
			"1"		=>	"Attiva",
			"0"		=>	"Non attiva",
		);
		
		$attivaDisattivaSped = array(
			"tutti"	=>	"Sped. Attiva / Disattiva",
			"1"		=>	"Sped. attiva",
			"0"		=>	"Sped. non attiva",
		);
		
		$filtroTipo = array("tutti"=>"Tipo") + NazioniModel::$selectTipi;
		
		$this->filters = array("titolo",array(
			"tipo",null,$filtroTipo),
		array(
			"attiva",null,$attivaDisattiva
		),  array(
			"attiva_spedizione",null,$attivaDisattivaSped
		));
			
		$this->m[$this->modelName]->where(array(
				"OR"	=>	array(
					"lk" => array("titolo" => $this->viewArgs["titolo"]),
					" lk" => array("iso_country_code" => $this->viewArgs["titolo"]),
				),
				"tipo"	=>	$this->viewArgs["tipo"],
				"attiva"	=>	$this->viewArgs["attiva"],
				"attiva_spedizione"	=>	$this->viewArgs["attiva_spedizione"],
			))->orderBy($this->orderBy)->convert()->save();
		
		$this->bulkQueryActions = "attiva,disattiva,attivasped,disattivasped";
		
		$this->bulkActions = array(
			"checkbox_nazioni_id_nazione"	=>	array("attiva","ATTIVA"),
			" checkbox_nazioni_id_nazione"	=>	array("disattiva","DISATTIVA"),
			"  checkbox_nazioni_id_nazione"	=>	array("attivasped","ATTIVA SPEDIZIONE"),
			"   checkbox_nazioni_id_nazione"	=>	array("disattivasped","DISATTIVA SPEDIZIONE"),
		);
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('titolo,iso_country_code,tipo,attiva,attiva_spedizione,campo_p_iva,id_iva,soglia_iva_italiana');
		
		parent::form($queryType, $id);
	}
	
	public function regioni($id = 0)
	{
		$this->model("RegioniModel");
		
		$this->_posizioni['regioni'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_nazione";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "RegioniModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("edit","tipo");
		$this->mainHead = "Titolo,Tipo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"regioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("regioni.titolo")->where(array(
			"id_nazione"	=>	$clean['id'],
		))->convert()->save();
		
		$this->tabella = "nazioni";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["NazioniModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
// 	public function importa()
// 	{
// 		$this->clean();
// 		
// 		$n = new NazioniModel();
// 		
// 		if (($handle = fopen(ROOT."/nazioni.csv", "r")) !== FALSE)
// 		{
// 			while (($riga = fgetcsv($handle, 1000, ";")) !== FALSE)
// 			{
// 				$idN = (int)$n->clear()->where(array(
// 					"iso_country_code"	=>	sanitizeAll($riga[0]),
// 				))->field("id_nazione");
// 				
// 				if ($idN)
// 				{
// 					$n->setValues(array(
// 						"latitudine"	=>	$riga[1],
// 						"longitudine"	=>	$riga[2],
// 					));
// 					
// 					$n->update($idN);
// 				}
// 			}
// 		}
// 	}
}
