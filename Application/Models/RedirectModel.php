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

class RedirectModel extends GenericModel {
	
	public $campoTitolo = "vecchio_url";
	
	public function __construct() {
		$this->_tables='redirect';
		$this->_idFields='id_redirect';
		
		$this->_idOrder='id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"vecchio_url,nuovo_url,codice_redirect");
		$this->addStrongCondition("both",'checkIsStrings|301,302,303',"codice_redirect");
		
		parent::__construct();
	}
	
	public function sistemaVecchioUrl()
	{
		if (isset($this->values["vecchio_url"]) && $this->values["vecchio_url"])
			$this->values["vecchio_url"] = rtrim($this->values["vecchio_url"], "/");
	}
	
	public function insert()
	{
		$this->sistemaVecchioUrl();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->sistemaVecchioUrl();
		
		return parent::update($id, $where);
	}
	
	// Esegue il redirect
// 	public static function cerca()
// 	{
// 		if (isset($_SERVER["REQUEST_URI"]))
// 		{
// 			$r = new RedirectModel();
// 			
// 			$record = $r->clear()->where(array(
// 				"vecchio_url"	=>	sanitizeAll(rtrim($_SERVER["REQUEST_URI"],"/"))
// 			))->record();
// 			
// 			if (!empty($record))
// 			{
// 				header('Location: '.html_entity_decode($record["nuovo_url"], ENT_QUOTES, "UTF-8"), true, $record["codice_redirect"]);
// 				exit();
// 			}
// 		}
// 	}
	
	// Genera il file redirect.php nella root del frontend sito
	public static function generaRedirectFile()
	{
		$r = new RedirectModel();
		
		$stringaTemplate  = file_get_contents(ROOT."/Application/Views/Redirect/template.txt");
		
		$records = $r->clear()->orderBy("id_order")->send(false);
		
		$arrayRecords = array();
		
		foreach ($records as $record)
		{
			$arrayRecords[] = '"'.htmlentitydecode($record["vecchio_url"]).'" => "'.htmlentitydecode($record["nuovo_url"]).'",';
		}
		
		$stringaTemplate = str_replace("[DIZIONARIO_REDIRECT]", implode("\n", $arrayRecords), $stringaTemplate);
		
		file_put_contents(Domain::$parentRoot."/redirect_url.php", $stringaTemplate);
	}
}
