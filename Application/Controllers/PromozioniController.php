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

class PromozioniController extends BaseController {
	
	public $mainFields = array("[[ledit]];promozioni.titolo;","promozioni.codice","reverseData|promozioni.dal","reverseData|promozioni.al","promozioni.sconto","PromozioniModel.getNUsata|promozioni.id_p","getYesNo|promozioni.attivo");
	
	public $mainHead = "Titolo,Codice promozione,Dal,Al,Sconto (%),N° usata,Attiva?";
	
	public $formValuesToDb = 'titolo,codice,attivo,dal,al,sconto,numero_utilizzi';
	
	public $orderBy = "promozioni.dal desc,promozioni.al desc";
	
	public $argKeys = array('attivo:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->model("PromozionicategorieModel");
		$this->model("CategorieModel");
		$this->model("PromozionipagineModel");
		$this->model("PagesModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->filters = array(array("attivo",null,$this->filtroAttivo));
		
		$this->m[$this->modelName]->where(array(
				'attivo'	=>	$this->viewArgs['attivo'],
// 				"attivo"	=>	$this->viewArgs["attivo"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		parent::form($queryType, $id);
	}
	
	public function categorie($id = 0)
	{
		$this->_posizioni['categorie'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->m['PromozionicategorieModel']->setFields('id_c','sanitizeAll');
		$this->m['PromozionicategorieModel']->values['id_p'] = $clean['id'];
		$this->m['PromozionicategorieModel']->updateTable('insert,del');
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PromozionicategorieModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("CategoriesModel.indentNoHtml|promozioni_categorie.id_c");
		$this->mainHead = "Categoria";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"categorie/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("promozioni_categorie.*")->inner(array("categoria"))->orderBy("categories.lft")->where(array("id_p"=>$clean['id']))->convert()->save();
		
		$this->tabella = "promozioni";
		
		parent::main();
		
		$data["listaCategorie"] = $this->m["CategorieModel"]->buildSelect();
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function pagine($id = 0)
	{
		$this->_posizioni['pagine'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->m['PromozionipagineModel']->setFields('id_page','sanitizeAll');
		$this->m['PromozionipagineModel']->values['id_p'] = $clean['id'];
		$this->m['PromozionipagineModel']->updateTable('insert,del');
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PromozionipagineModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("pages.title");
		$this->mainHead = "Prodotto";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"pagine/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("pages.title,promozioni_pages.*")->inner(array("pagina"))->orderBy("pages.title")->where(array("id_p"=>$clean['id']))->convert()->save();
		
		$this->tabella = "promozioni";
		
		parent::main();
		
		$data["listaProdotti"] = array();
		
		$idP = $this->m["CategorieModel"]->clear()->where(array("section"=>Parametri::$nomeSezioneProdotti))->field("id_c");
		$children = $this->m["CategorieModel"]->children((int)$idP, true);

		$res = $this->m['PagesModel']->clear()->where(array(
			"attivo" => "Y",
			"principale"=>"Y",
			"in" => array("-id_c" => $children),
		))->orderBy("id_order")->send();
		foreach ($res as $r)
		{
			$data["listaProdotti"][$r["pages"]["id_page"]] = $r["pages"]["codice"] . " - " . $r["pages"]["title"];
		}
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
