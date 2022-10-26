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

class ListeregaloController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $tabella = "liste regalo";
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_liste_regalo"))
			die();
		
		$this->model("ListeregalotipiModel");
		$this->model("ListeregalopagesModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>100, 'mainMenu'=>'add');
		$this->mainFields = array("liste_regalo.titolo", "liste_regalo_tipi.titolo", "liste_regalo.codice", "liste_regalo.nome_bambino", "liste_regalo.genitore_1", "liste_regalo.genitore_2", "liste_regalo.data_scadenza", "liste_regalo.attivo");
		$this->mainHead = "Titolo,Tipo,Codice,Nome Bimbo/a,Genitore 1,Genitore 2,Scadenza,Attivo";
		
		$this->m[$this->modelName]->clear()->select("*")->inner(array("tipo"))->orderBy("id_lista_regalo desc");
		
		$this->m[$this->modelName]->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$lista = $this->m['ListeregaloModel']->selectId((int)$id);
		
		$idTipoLista = !empty($lista) ? $lista["id_lista_tipo"] : $this->request->post("id_lista_tipo",0,"forceInt");
		
		$selectTipi = ListeregalotipiModel::getSelectTipi($idTipoLista);
		
		if (!$idTipoLista)
			$idTipoLista = count($selectTipi) > 0 ? key($selectTipi) : 0;
		
		$tipoLista = $this->m["ListeregalotipiModel"]->selectId((int)$idTipoLista);
		
		if (!empty($tipoLista))
		{
			$fields = 'id_lista_tipo,titolo,codice,data_scadenza,attivo';
			
			if ($tipoLista["campi"])
				$fields .= ','.$tipoLista["campi"];
		}
		else
			$fields = 'id_lista_tipo,titolo,codice,data_scadenza,attivo,nome_bambino,genitore_1,genitore_2,sesso,data_nascita,data_battesimo';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if (!empty($lista))
		{
			$this->disabledFields = "id_lista_tipo,codice";
			$this->m['ListeregaloModel']->delFields("id_lista_tipo,codice");
		}
		
		parent::form($queryType, $id);
	}
	
	public function pagine($id = 0)
	{
		$this->_posizioni['pagine'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_car";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ListeregalopagesModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array(
			'<a href="'.$this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"pages.title",
			"variante",
			"combinazioni.codice",
			"prezzo",
		);
		
		$this->mainHead = "Immagine,Prodotto,Variante,Codice,Prezzo (€)";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"pagine/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("liste_regalo_pages.*,pages.*,combinazioni.*")->inner(array("pagina","combinazione"))->orderBy("liste_regalo_pages.id_lista_regalo_page")->where(array("id_lista_Regalo"=>$clean['id']))->save();
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'80px',
			),
		);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
