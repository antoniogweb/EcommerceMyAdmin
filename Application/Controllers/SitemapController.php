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

Helper_Menu::$htmlLinks["refresh"]["attributes"] = 'class="btn btn-warning"';
Helper_Menu::$htmlLinks["refresh"]["url"] = "main";
Helper_Menu::$htmlLinks["refresh"]["queryString"] = "&aggiorna_sitemap=Y";

class SitemapController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $sezionePannello = "utenti";
	
	public $tabella = "sitemap";
	
	public function main()
	{
		if (!v("permetti_gestione_sitemap"))
			die();
		
		if (isset($_GET["aggiorna_sitemap"]))
		{
			$this->m[$this->modelName]->aggiorna();
			
			flash("notice", wrap("Operazione eseguita", array(
				"div"	=>	"alert alert-success"
			)));
			
			$this->redirect("sitemap/main");
		}
		
		$this->shift();
		
		$this->mainFields = array("titolocrud", "sitemap.priorita");
		$this->mainHead = "Titolo,Priorita";
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("categoria", "pagina"))
				->orderBy("sitemap.id_order")->convert()->save();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>1000, 'mainMenu'=>'refresh');
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('priorita');
		
		parent::form($queryType, $id);
	}
}
