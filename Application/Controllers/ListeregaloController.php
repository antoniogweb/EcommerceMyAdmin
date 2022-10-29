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
	
	public $argKeys = array('dal:sanitizeAll'=>'tutti', 'al:sanitizeAll'=>'tutti', 'titolo:sanitizeAll'=>'tutti');
	
	public $tabella = "liste regalo";
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_liste_regalo"))
			die();
		
		$this->model("ListeregalotipiModel");
		$this->model("ListeregalopagesModel");
		$this->model("ListeregalolinkModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>100, 'mainMenu'=>'add');
		$this->mainFields = array("cliente", "liste_regalo.titolo", "liste_regalo_tipi.titolo", "liste_regalo.codice", "liste_regalo.nome_bambino", "liste_regalo.genitore_1", "liste_regalo.data_scadenza", "liste_regalo.attivo");
		$this->mainHead = "Cliente,Titolo,Tipo,Codice,Nome Bimbo/a,Genitore 1,Scadenza,Attivo";
		
		$filtri = array("dal","al","titolo");
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
			->select("*")
			->where(array(
				"OR"	=> array(
					"lk" => array('liste_regalo.titolo' => $this->viewArgs['titolo']),
					" lk" => array('liste_regalo.codice' => $this->viewArgs['titolo']),
					"  lk" => array('liste_regalo.nome_bambino' => $this->viewArgs['titolo']),
					"   lk" => array('liste_regalo.genitore_1' => $this->viewArgs['titolo']),
					"    lk" => array('liste_regalo.genitore_2' => $this->viewArgs['titolo']),
					)
			))
			->inner(array("tipo", "cliente"))
			->orderBy("id_lista_regalo desc");
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], "data_scadenza");
		
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
			$fields = 'id_lista_tipo,id_user,titolo,codice,data_scadenza,attivo';
			
			if ($tipoLista["campi"])
				$fields .= ','.$tipoLista["campi"];
		}
		else
			$fields = 'id_lista_tipo,id_user,titolo,codice,data_scadenza,attivo,nome_bambino,genitore_1,genitore_2,sesso,data_nascita,data_battesimo';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$disabledFields = "codice";
		
		if (!empty($lista))
			$disabledFields .= ",id_lista_tipo,id_user";
		
		$this->disabledFields = $disabledFields;
		$this->m['ListeregaloModel']->delFields($disabledFields);
		
		$this->functionsIfFromDb = array(
			"data_nascita"		=>	"svuotaData",
			"data_battesimo"	=>	"svuotaData",
		);
		
		$this->m['ListeregaloModel']->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::form($queryType, $id);
	}
	
	public function pagine($id = 0)
	{
		$this->_posizioni['pagine'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ListeregalopagesModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array(
			'<a href="'.$this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"pages.title",
			"variante",
			"combinazioni.codice",
			"prezzo",
			"quantita",
		);
		
		$this->mainHead = "Immagine,Prodotto,Variante,Codice,Prezzo (€),Quantità desiderata";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,save_regali','mainAction'=>"pagine/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("liste_regalo_pages.*,pages.*,combinazioni.*")->inner(array("pagina","combinazione"))->orderBy("liste_regalo_pages.id_lista_regalo_page")->where(array("id_lista_regalo"=>$clean['id']))->save();
		
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
	
	public function inviti($id = 0)
	{
		$this->_posizioni['inviti'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->modelName = "ListeregalolinkModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array(
			'liste_regalo_link.nome',
			'liste_regalo_link.cognome',
			'liste_regalo_link.email',
			'inviata',
			'ultimoinvito',
			'invia',
		);
		
		$this->mainHead = "Nome,Cognome,Email,Stato invio,Data ultimo invito,Invia nuovamente";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"inviti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("id_lista_regalo_link desc")->where(array("id_lista_regalo"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function salvapagine()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		CombinazioniModel::$aggiornaAliasAdInserimento = false;
		
		if (v("usa_transactions"))
			$this->m["ListeregalopagesModel"]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		foreach ($valori as $v)
		{
			if ((int)$v["quantity"] > 0)
			{
				$this->m["ListeregalopagesModel"]->sValues(array(
					"quantity"	=>	(int)$v["quantity"],
				));
				
				$this->m["ListeregalopagesModel"]->update($v["id_riga"]);
			}
			else
				$this->m["ListeregalopagesModel"]->del($v["id_riga"]);
		}
		
		if (v("usa_transactions"))
			$this->m["ListeregalopagesModel"]->db->commit();
	}
}
