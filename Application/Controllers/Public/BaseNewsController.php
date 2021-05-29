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

class BaseNewsController extends BaseController
{

	public function __construct($model, $controller, $queryString)
	{
		parent::__construct($model, $controller, $queryString);

		$this->load('header');
		$this->load('footer','last');

		$data['title'] = Parametri::$nomeNegozio . ' - News';
		
		$this->append($data);
		
		Params::$language = "en";
	}

	public function index()
	{
		//load the Pages helper
		$this->helper('Pages','archivio','page');
		//get the number of records
		$this->m["NewsModel"]->clear()->where(array("attivo"=>"Y"))->orderBy("data_news desc");
		
		$rowNumber = $data['rowNumber'] = $this->m['NewsModel']->rowNumber();
		
		$data["elementsPerPage"] = 4;
		
		if ($rowNumber > $data["elementsPerPage"])
		{
			$argKeys = array(
				'p:forceNat'	=>	1,
			);

			$this->setArgKeys($argKeys);
			$this->shift();
			
			//load the Pages helper
			$this->helper('Pages','archivio-news','p');
			
			$page = $this->viewArgs['p'];
			$this->h['Pages']->previousString = "&lt;";
			$this->h['Pages']->nextString = "&gt;";
			
			$this->m['NewsModel']->limit = $this->h['Pages']->getLimit($page,$rowNumber,$data["elementsPerPage"]);
			
			$data['pageList'] = $this->h['Pages']->render($page-5,11);
		}
		
		$data['table'] = $this->m['NewsModel']->send();
		
		$this->append($data);
		$this->load('main');
	}
	
	public function dettaglio($alias = "")
	{
		$clean["alias"] = sanitizeAll($alias);
		
		if (strcmp($alias,"") !== 0)
		{
			$data['table'] = $this->m["NewsModel"]->where(array("attivo"=>"Y","alias"=>$clean["alias"]))->send();
			
			if (count($data['table']) > 0)
			{
				$data['title'] = Parametri::$nomeNegozio . " - " . $data['table'][0]["news"]["titolo"];
				$data['lastBreadcrumb'] = $data['table'][0]["news"]["titolo"];
				
				if (strcmp($data['table'][0]["news"]["meta_description"],"") !== 0)
				{
					$data["meta_description"] = htmlentitydecode($data['table'][0]["news"]["meta_description"]);
				}
				if (strcmp($data['table'][0]["news"]["keywords"],"") !== 0)
				{
					$data["keywords"] = htmlentitydecode($data['table'][0]["news"]["keywords"]);
				}
			}
		
			$this->append($data);
			$this->load('dettaglio');
		}
	}
}
