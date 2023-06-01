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

class ElementitemaController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_elementi_tema"))
			die();
	}

	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "ledit";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array("edit", "elementi_tema.codice", "elementi_tema.percorso", "elementi_tema.nome_file");
		$this->mainHead = "Titolo,Codice,Percorso,Layout";
		
		$this->m[$this->modelName]->clear()->orderBy("titolo")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		if ($queryType != "update")
			die();
		
		$fields = 'titolo,nome_file';
		
		$this->m[$this->modelName]->esportaTema = true;
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function importa()
	{
		$this->clean();
		
		$cartellaTema = v("theme_folder");
		
		$layout = Tema::getJsonLayout($cartellaTema);
		
		if ($layout)
		{
			$elementi = json_decode($layout, true);
			
// 			print_r($elementi);
			
			if (isset($elementi["varianti"]))
			{
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->beginTransaction();
				
				foreach ($elementi["varianti"] as $e)
				{
					$this->m[$this->modelName]->setValues($e["elementi_tema"], "sanitizeDb");
					
					$this->m[$this->modelName]->delFields("id_elemento_tema, data_creazione");
					
					$this->m[$this->modelName]->update($e["elementi_tema"]["id_elemento_tema"]);
				}
				
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
			}
		}
	}
	
	public function resetta()
	{
		$this->clean();
		
		$this->m[$this->modelName]->resetta();
	}
	
	public function esporta()
	{
		$this->clean();
		
		$this->m[$this->modelName]->esportaInTema();
	}
	
	public function crea()
	{
		$this->clean();
		
		$nomeTema = $this->request->post("nome_tema","","sanitizeAll");
		
		if ($nomeTema)
		{
			Tema::crea($nomeTema);
		}
	}
	
	public function elencotemi()
	{
		$this->clean();
		
		echo json_encode(Tema::getElencoTemi(null, true));
	}
}
