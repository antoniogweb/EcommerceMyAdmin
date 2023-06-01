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

class PagesstatsController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'email:sanitizeAll'=>'tutti',
		'title:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "marketing";
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta');
		
		$this->shift();
		
		$this->mainFields = array("pages_stats.data_stat", "pages.title", "contatto", "cliente", "aggregate.numero");
		$this->mainHead = "Data,Pagina,Contatto,Utente,Visualizzazioni";
		
		$filtri = array("dal","al","email","title");
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
			->select("pages_stats.*,pages.title,count(pages.id_page) as numero,contatti.*,regusers.*")
			->inner(array("page"))
			->left(array("contatto", "utente"))
			->where(array(
				"OR"	=>	array(
					"lk"	=>	array(
						"contatti.email"	=>	$this->viewArgs["email"],
					),
					" lk"	=>	array(
						"regusers.username"	=>	$this->viewArgs["email"],
					)
				),
				"lk"	=>	array(
					"pages.title"	=>	$this->viewArgs["title"],
				),
			))
			->groupBy("pages_stats.id_page,pages_stats.data_stat,pages_stats.uid_stats,pages_stats.id_contatto,pages_stats.id_user")
			->orderBy("pages_stats.data_creazione desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->m[$this->modelName]->save();
		
		$this->getTabViewFields("main");
		
		parent::main();
	}
}
