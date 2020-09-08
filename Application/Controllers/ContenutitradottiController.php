<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class ContenutitradottiController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'section:sanitizeAll'=>'tutti'
	);
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->tabella = "traduzione";
		
		$this->s["admin"]->check();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->menuLinks = "save";
		
		$fields = 'title,alias,sottotitolo,description,keywords,meta_description';
		
		if ($queryType == "insert")
			$section = $this->viewArgs["section"];
		else
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($record))
				$section = $record["sezione"];
		}
		
		if ($section == "slide_detail")
			$fields = 'title,sottotitolo,url';
		else if ($section == "blog_detail")
			$fields = 'title,alias,sottotitolo,description,keywords,meta_description';
		else if ($section == "-car-")
			$fields = 'titolo';
		else if ($section == "-cv-" || $section == "-ruolo-" || $section == "attributi" || $section == "attributi_valori" || $section == "personalizzazioni")
			$fields = 'titolo';
		else if ($section == "-marchio-")
			$fields = 'titolo,alias,description';
		else if ($section == "tag")
			$fields = 'titolo,alias';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		// Lo imposto come salvato manualmente
		$this->m[$this->modelName]->setValue("salvato",1);
		
		if ($section != "tutti")
			$this->m[$this->modelName]->setValue("sezione",$section);
		
		parent::form($queryType, $id);
	}
}
