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

Helper_Menu::$htmlLinks["refresh"]["attributes"] = 'class="pull-right btn btn-info" title="Aggiungi alla sitemap le nuove pagine, se mancanti"';
Helper_Menu::$htmlLinks["refresh"]["url"] = "main";
Helper_Menu::$htmlLinks["refresh"]["queryString"] = "&aggiorna_sitemap=Y";

Helper_Menu::$htmlLinks["rigenera"]["url"] = "main";
Helper_Menu::$htmlLinks["rigenera"]["queryString"] = "&rigenera_sitemap=Y";
Helper_Menu::$htmlLinks["rigenera"]["attributes"] = 'style="margin-left:10px;" class="pull-right btn btn-warning make_spinner" title="Svuota e ricrea la sitemap completamente"';

Helper_Menu::$htmlLinks["vedi"] = array(
	"attributes" => 'role="button" class="btn btn-success" target="_blank"',
	'text'	=>	"Vedi sitemap",
	"classIconBefore"	=>	'<i class="fa fa-eye"></i>',
	"htmlBefore"	=>	"",
	"htmlAfter"		=>	"",
);

Helper_Menu::$htmlLinks["add"]["text"] = "aggiungi link libero";

class SitemapController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $sezionePannello = "utenti";
	
	public $tabella = "sitemap";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		Helper_Menu::$htmlLinks["vedi"]["absolute_url"] = Domain::$publicUrl."/sitemap.xml";
	}
	
	public function main()
	{
		if (!v("permetti_gestione_sitemap"))
			die();
		
		$recuperaBackup = 0;
		
		if (isset($_GET["rigenera_sitemap"]))
		{
			$this->m[$this->modelName]->query("truncate sitemap");
			$_GET["aggiorna_sitemap"] = "Y";
			$recuperaBackup = 1;
		}
		
		if (isset($_GET["aggiorna_sitemap"]))
		{
			$this->m[$this->modelName]->aggiorna($recuperaBackup);
			
			flash("notice", wrap("Operazione eseguita", array(
				"div"	=>	"alert alert-success"
			)));
			
// 			$this->redirect("sitemap/main");
		}
		
		$this->shift();
		
		$this->mainFields = array("titolocrud", "sitemap.priorita", "tipo", "url");
		$this->mainHead = "Titolo,Priorita,Tipo,Url";
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("categoria", "pagina"))
				->orderBy("sitemap.id_order")->convert()->save();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>1000, 'mainMenu'=>'rigenera,add,vedi');
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$fields = 'priorita';
		
		$libero = false;
		
		if ($queryType == "insert")
		{
			$fields .= ',titolo,url';
			$libero = true;
		}
		else
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($record) && $record["tipo"] == "L")
			{
				$fields .= ',titolo,url';
				$libero = true;
			}
		}
		
		if ($libero)
			$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"url");
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert")
			$this->m[$this->modelName]->setValue("tipo", "L");
		
		parent::form($queryType, $id);
	}
}
