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

class CookiearchivioController extends BaseController
{
	public $setAttivaDisattivaBulkActions = true;
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti'
	);
	
	public $sezionePannello = "utenti";

	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		// $this->addBulkActions = false;
		
		// $this->colProperties = array();
		$this->filters = array("titolo");
		
		$mainMenu = "";
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>200, 'mainMenu'=>$mainMenu);
		
		$this->mainFields = array("cookie_archivio.titolo", "cookie_archivio.dominio", "cookie_archivio.path", "cookie_archivio.durata", "cookie_archivio.servizio", "cookie_archivio.secure", "cookie_archivio.same_site", "cookie_archivio.cross_site", "cookie_archivio.note");
		$this->mainHead = "Titolo,Dominio,Path,Durata,Servizio,Secure,SameSite,CrossSite,Note";
		
		$this->m[$this->modelName]->clear()->where(array(
			"OR"	=>	array(
				"lk"	=>	array("titolo"	=>	$this->viewArgs["titolo"]),
				" lk"	=>	array("dominio"	=>	$this->viewArgs["titolo"]),
				"  lk"	=>	array("servizio"	=>	$this->viewArgs["titolo"]),
			),
		))->orderBy("titolo")->convert()->save();
		
		$this->tabella = "archivio cookie";
		
		parent::main();
	}
}
