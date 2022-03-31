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

Cache::removeTablesFromCache(array("categories", "pages", "contenuti_tradotti"));

class BasePagineController extends PublicCrudController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->filters = array(
			GenericModel::getFiltroAttivo(),
			GenericModel::getFiltroCestino(),
		);
		
		$this->menuLinks = "back,save,copia";
	}
	
	protected function form($queryType = 'insert', $id = 0)
	{
		if ((string)$queryType === "insert")
		{
			$idPage = $this->m[$this->modelName]->addTemporaneo();
			
			if ($idPage)
				$this->redirect($this->applicationUrl.$this->controller.'/form/update/'.$idPage.$this->viewStatus);
			else
			{
				$_SESSION['result'] = "error";
				$this->redirect("avvisi");
			}
		}
		
		$this->basePublicForm($queryType, $id);
	}
	
	protected function main()
	{
		$this->shift();
		
		if ((int)$this->viewArgs["cestino"] === 1)
		{
			$this->bulkQueryActions = "ripristina";
			
			$this->bulkActions = array(
				"checkbox_pages_id_page"	=>	array("ripristina",gtext("Ripristina elementi selezionati"), "confirm"),
			);
		}
		
		$this->m[$this->modelName]->clear()->restore(true)->where(array(
			"temp"	=>	0,
			"cestino"	=>	$this->viewArgs["cestino"],
			"attivo"	=>	$this->viewArgs["attivo"],
		))->save();
		
		$this->baseMain();
	}
}
