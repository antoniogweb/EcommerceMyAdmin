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

class AirichiesteController extends BaseController
{
	public $sezionePannello = "utenti";
	
	public $tabella = "richieste AI";

	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_richieste_ai"))
			$this->responseCode(403);

		$this->model("AirichiestecontestiModel");
	}

	public function main()
	{
		$this->shift();

		$this->mainFields = array("cleanDateTime", "titoloCrud", "ai_modelli.titolo");
		$this->mainHead = "Data ora,Titolo,Modello";
		
		$this->m[$this->modelName]->clear()->select("*")->inner(array("modello"))->orderBy("ai_richieste.data_creazione desc")->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';

		$fields = 'id_ai_modello,id_c,id_marchio,id_page';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "update")
		{
			$this->formQueryActions = "insert";
			$this->disabledFields = $fields;
			$this->menuLinks = "back";
		}

		parent::form($queryType, $id);
	}

	public function contesti($id = 0)
	{
		$this->_posizioni['contesti'] = 'class="active"';

// 		$data["orderBy"] = $this->orderBy = "id_order";

		$this->shift(1);

		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_ai_richiesta";

		$this->mainButtons = "ldel";

		$this->modelName = "AirichiestecontestiModel";

		$this->m[$this->modelName]->setFields('id_page','forceInt');
		$this->m[$this->modelName]->values['id_ai_richiesta'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');

		$this->mainFields = array("pages.title", "categories.title", "marchi.titolo", "bulksegnaimportante");
		$this->mainHead = "Pagina,Categoria,Marchio,Importante";

		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"contesti/".$clean['id'],'pageVariable'=>'page_fgl');

		$this->m[$this->modelName]->clear()->select("pages.title,marchi.titolo,categories.title,ai_richieste_contesti.*")
			->inner(array("pagina"))
			->inner("categories")->on("pages.id_c = categories.id_c")
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array(
				"id_ai_richiesta"	=>	$clean['id'],
			))
			->orderBy("ai_richieste_contesti.id_order")
			->save();

		// $this->tabella = "corrieri";

		$data["elencoPagine"] = PagesModel::g(false)->clear()->select("id_page,title")->addWhereAttivo()->sWhere(array(
			"id_page not in (select id_page from ai_richieste_contesti where id_ai_richiesta = ?)",
			array($clean['id'])
		))->toList("id_page", "title")->send();

		$data["ordinaAction"] = "ordinacontesti";
		$data["orderBy"] = "ai_richieste_contesti.id_order";

		$this->bulkQueryActions = "del,settaimportante,settanonimportante";

		$this->bulkActions = array(
			"checkbox_ai_richieste_contesti_id_ai_richiesta_contesto"	=>	array("del",gtext("Elimina selezionati"),"confirm"),
			" checkbox_ai_richieste_contesti_id_ai_richiesta_contesto"	=>	array("settaimportante","Segna importante"),
			"  checkbox_ai_richieste_contesti_id_ai_richiesta_contesto"	=>	array("settanonimportante","Segna NON importante"),
		);

		parent::main();

		$data["titoloRecord"] = $this->m["AirichiesteModel"]->titolo($clean['id']);

		$this->append($data);
	}

	public function ordinacontesti()
	{
		$this->orderBy = "ai_richieste_contesti.id_order";

		$this->modelName = "AirichiestecontestiModel";

		parent::ordina();
	}
}