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

class NazioniController extends BaseController {
	
	public $orderBy = "titolo";
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti',
		'tipo:sanitizeAll'=>'tutti',
		'attiva:sanitizeAll'=>'tutti',
		'attiva_spedizione:sanitizeAll'=>'tutti',
		'id_page:sanitizeAll'=>'tutti',
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->model("NazioniModel");
	}
	
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
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->mainButtons = "";
			$this->mainFields = array("[[ledit]];nazioni.titolo;","nazioni.iso_country_code");
			$this->mainHead = "Titolo,Codice nazione";
			
			$this->filters = array("titolo");
		}
		else
		{
			$this->mainFields = array("[[ledit]];nazioni.titolo;","nazioni.iso_country_code","tipo","attivaCrud","attivaSpedizioneCrud","pivaAttiva");
			$this->mainHead = "Titolo,Codice nazione,Tipo,Attiva,Spedizione attiva,Attiva P.IVA";
			
			$this->filters = array("titolo",array(
				"tipo",null,$filtroTipo),
				array(
					"attiva",null,$attivaDisattiva
				),  array(
					"attiva_spedizione",null,$attivaDisattivaSped
				));
		}
		
		$this->m[$this->modelName]->where(array(
				"OR"	=>	array(
					"lk" => array("titolo" => $this->viewArgs["titolo"]),
					" lk" => array("iso_country_code" => $this->viewArgs["titolo"]),
				),
				"tipo"	=>	$this->viewArgs["tipo"],
				"attiva"	=>	$this->viewArgs["attiva"],
				"attiva_spedizione"	=>	$this->viewArgs["attiva_spedizione"],
			))->orderBy($this->orderBy)->convert();
		
		$this->bulkQueryActions = "attiva,disattiva,attivasped,disattivasped";
		
		$this->bulkActions = array(
			"checkbox_nazioni_id_nazione"	=>	array("attiva","ATTIVA"),
			" checkbox_nazioni_id_nazione"	=>	array("disattiva","DISATTIVA"),
			"  checkbox_nazioni_id_nazione"	=>	array("attivasped","ATTIVA SPEDIZIONE"),
			"   checkbox_nazioni_id_nazione"	=>	array("disattivasped","DISATTIVA SPEDIZIONE"),
		);
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->bulkQueryActions = "aggiungiaprodotto";
			
			$this->bulkActions = array(
				"checkbox_nazioni_id_nazione"	=>	array("aggiungiaprodotto","Aggiungi al prodotto"),
			);
			
			$this->m[$this->modelName]->sWhere(array("nazioni.id_nazione not in (select id_nazione from pages_regioni where id_nazione is not null and id_page = ?)",array((int)$this->viewArgs["id_page"])));
		}
		
		$this->getTabViewFields("main");
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$campi = 'titolo,iso_country_code,tipo,attiva,attiva_spedizione,campo_p_iva,id_iva,lingua';
		
		if (v("attiva_in_evidenza_nazioni"))
			$campi .= ",in_evidenza";
		
		if (v("soglia_spedizioni_gratuite_diversa_per_ogni_nazione"))
			$campi .= ",soglia_spedizioni_gratuite";

		$campi .= ",prefisso_telefonico";
		
		$this->m[$this->modelName]->setValuesFromPost($campi);
		
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
	
	public function regusers($id = 0)
	{
		$this->model("RegusersnazioniModel");
		
		$this->_posizioni['regusers'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_nazione";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "RegusersnazioniModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("regusers.username", "nome");
		$this->mainHead = "Email,Nominativo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"regusers/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("cliente"))->orderBy("regusers_nazioni.id_order")->where(array(
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
