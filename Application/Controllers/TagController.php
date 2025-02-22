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

class TagController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'id_page:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->tabella = gtext("Tag / Linee",true);
		
		$this->model("ContenutitradottiModel");
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tag.titolo", "tag.attivo");
		$this->mainHead = "Titolo,Attivo";
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiaprodotto";
			$this->mainHead .= ",Aggiungi";
		}
		
// 		$this->filters = array(array("attivo",null,$this->filtroAttivo),"cerca");
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("id_order")->convert();
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiaprodotto";
			
			$this->bulkActions = array(
				"checkbox_tag_id_tag"	=>	array("aggiungiaprodotto","Aggiungi alla pagina"),
			);
			
			$this->m[$this->modelName]->sWhere(array("tag.id_tag not in (select id_tag from pages_tag where id_tag is not null and id_page = ?)",array((int)$this->viewArgs["id_page"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function ordina()
	{
		$this->modelName = "TagModel";
		
		parent::ordina();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'titolo,alias,attivo,description,immagine';
		
		if (v("mostra_seconda_immagine_tag"))
			$fields .= ",immagine_2";
		
		if (v("mostra_colore_testo"))
			$fields .= ",colore_testo_in_slide";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function meta($queryType, $id = 0)
	{
		$this->_posizioni['meta'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('keywords,meta_description');
		$this->m[$this->modelName]->setValue("meta_modificato", 1);
		
		parent::form("update", $id);
	}
}
