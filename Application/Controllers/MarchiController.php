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

class MarchiController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti', 'nazione:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->tabella = gtext("famiglie",true);
		
		$this->model("ContenutitradottiModel");
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("marchi.titolo", "marchi.codice", "attivo");
		$this->mainHead = "Titolo,Codice,Attivo";
		
		if (v("attiva_nazione_marchi"))
		{
			$this->mainFields[] = "nazione";
			$this->mainHead .= ",Nazione";
		}
		
		if (v("attiva_in_evidenza_marchi"))
		{
			$this->mainFields[] = "marchi.in_evidenza";
			$this->mainHead .= ",In evidenza";
		}
		
		if (v("attiva_nuovo_marchi"))
		{
			$this->mainFields[] = "marchi.nuovo";
			$this->mainHead .= ",Nuovo";
		}
		
		$this->filters = array("titolo",array("attivo",null,array("tutti"	=>	"Attivo / Disattivo") + MarchiModel::$attivoSiNo));
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"OR"	=>	array(
						"lk" => array('titolo' => $this->viewArgs['titolo']),
						" lk" => array('codice' => $this->viewArgs['titolo']),
					),
					"attivo"	=>	$this->viewArgs['attivo'],
				))
				->orderBy("id_order")->convert()->save();
		
		parent::main();
	}
	
	public function ordina()
	{
		$this->modelName = "MarchiModel";
		
		parent::ordina();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$campi = 'titolo,alias,attivo,codice,descrizione,immagine,immagine_2x,sottotitolo';
		
		if (v("attiva_nazione_marchi"))
		{
			$campi .= ",nazione";
			
			$this->formDefaultValues = array(
				"nazione"	=>	v("nazione_default"),
			);
		}
		
		if (v("attiva_in_evidenza_marchi"))
			$campi .= ",in_evidenza";
		
		if (v("attiva_nuovo_marchi"))
			$campi .= ",nuovo";
		
		$this->m[$this->modelName]->setValuesFromPost($campi);
		
		parent::form($queryType, $id);
	}
	
	public function meta($queryType, $id = 0)
	{
		$this->_posizioni['meta'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost('keywords,meta_description');
		$this->m[$this->modelName]->setValue("meta_modificato", 1);
		
		parent::form("update", $id);
	}
}
