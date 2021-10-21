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

class CategoriesController extends BaseController {

	public $voceMenu = "prodotti";
	
	public $queryFields = "title,alias,sottotitolo,id_p,immagine,description";
	public $metaQueryFields = "keywords,meta_description,template,add_in_sitemap,priorita_sitemap";
	public $formFields = null;
	public $sezionePannello = "sito";
	
	public $orderBy = "id_order";
	
	public $tabella = "categoria";
	
	protected $_posizioni = array(
		"main"		=>	null,
		"meta"		=> null,
		"gruppi"	=> null,
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if ($model == "CategoriesModel")
			$this->sezionePannello = "utenti";
			
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->session('admin');
		$this->model();
		$this->model("CategoriesModel");
		$this->model("ReggroupscategoriesModel");
		$this->model("ClassiscontocategoriesModel");
		$this->model("ClassiscontoModel");
		$this->model("ContenutitradottiModel");
		$this->model("ContenutiModel");
		
		$this->setArgKeys(array(
			'page:forceNat'=>1,
			'title:sanitizeAll'=>'tutti',
			'token:sanitizeAll'=>'token',
			'titolo_contenuto:sanitizeAll' => "tutti",
			'lingua:sanitizeAll' => "tutti",
			'tipocontenuto:sanitizeAll' => "tutti",
			'partial:sanitizeAll' => "tutti",
			'nobuttons:sanitizeAll' => "tutti",
		));

		$this->_topMenuClasses[$this->voceMenu] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = $this->sezionePannello;
		
		$this->append($data);
		
		if (v("attiva_immagine_sfondo"))
			$this->queryFields .= ",immagine_sfondo";
		
		$this->s['admin']->check();
		
		if ($model == "CategoriesModel")
			$this->m[$this->modelName]->sistemaVisibilitaSezioni();
	}

	public function main()
	{
		if (v("attiva_cache_prodotti") && empty($_POST))
			Cache::$cachedTables = array("pages", "categories", "contenuti_tradotti", "fatture");
		
		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		CategoriesModel::$controllerName = $this->controller;
		CategoriesModel::$actionName = $this->action;
		CategoriesModel::$viewStatus = $this->viewStatus;
		
		$mainMenu = '';
		$numeroPerPagina = 100;
		
		if ($this->m[$this->modelName]->section)
		{
			$this->m[$this->modelName]->bulkAction("del");
			$mainMenu = 'add';
			
			$numeroPerPagina = 9999999;
		}
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>$numeroPerPagina, 'mainMenu'=>$mainMenu));
		
		if ($this->m[$this->modelName]->section)
		{
			$tabelFields = array(
				'[[checkbox]];categories.id_c;',
				"titoloElenco",
// 				$this->modelName.'.indentList|categories.id_c',
			);
			
			$head = '[[bulkselect:checkbox_categories_id_c]],Titolo';
		}
		else
		{
			$tabelFields = array(
				"titoloElenco",
// 				$this->modelName.'.indentList|categories.id_c',
			);
			
			$head = 'Titolo';
		}
		
		foreach (self::$traduzioni as $codiceLingua)
		{
			$tabelFields[] = "link".str_replace("-","",$codiceLingua);
			$head .= ",".strtoupper($codiceLingua);
		}
		
		if ($this->m[$this->modelName]->section)
		{
// 			$tabelFields[] = $this->modelName.'.arrowUp|categories.id_c';
// 			$tabelFields[] = $this->modelName.'.arrowDown|categories.id_c';
// 			
// 			$head .= ",,";
			
			$editAction = 'ldel,ledit';
			
			$this->scaffold->itemList->setBulkActions(array(
				"checkbox_categories_id_c"	=>	array("del","Elimina selezionati","confirm"),
			));
		}
		else
		{
			$editAction = "ledit";
		}
		
		$this->scaffold->loadMain($tabelFields,'categories:id_c',$editAction);

		$this->scaffold->update('del');
		
		$this->scaffold->setHead($head);
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci una nuova categoria';
		
		$this->scaffold->model->clear()->where(array(
			"ne" => array("id_c" => "1"),
		));
		
		$this->scaffold->model->getSectionElements();
		$this->scaffold->model->getSectionWhere();
		
		if (strcmp($this->viewArgs['title'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('n!categories.title' => $this->viewArgs['title']),
			);

			$this->scaffold->model->aWhere($where);
		}
		
		if (!$this->m[$this->modelName]->section)
			$this->scaffold->model->aWhere(array(
				"installata"	=>	1,
			));
		
		CategoriesModel::$orderWhere = $this->scaffold->model->where;
		
		if (isset($_POST["moveupAction"]) or isset($_POST["movedownAction"]))
		{
			$clean["id"] = $this->request->post("id_c",0,"forceInt");
			$oldWhere = $this->scaffold->model->where;
			$rowData = $this->scaffold->model->selectId($clean["id"]);
			$orderType = $this->scaffold->model->orderType;
			$this->scaffold->model->aWhere(array("id_p"=>$rowData["id_p"]))->orderBy("id_order $orderType");
			$this->scaffold->update('moveup,movedown');
			$this->scaffold->model->where = $oldWhere;
		}
		
		$this->scaffold->model->orderBy("lft asc");
		
		if ($this->m[$this->modelName]->section)
			$colProperties = array(
				array('width'	=>	'45px',),null,
			);
		else
			$colProperties = array(
				null,
			);
		
		foreach (self::$traduzioni as $codiceLingua)
		{
			$colProperties[] = null;
		}
		
		$this->scaffold->itemList->colProperties = $colProperties;
		
		$data["section"] = $this->m[$this->modelName]->section;
		
// 		if ($this->m[$this->modelName]->section)
// 			$this->scaffold->itemList->setFilters(array(null,'title'));
// 		else
// 			$this->scaffold->itemList->setFilters(array('title'));
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
// 		echo $this->scaffold->model->getQuery();
		
		$data['notice'] = $this->scaffold->model->notice;
			
		$this->append($data);
		$this->load('categories_main');
	}

	public function meta($id = 0)
	{
		$this->shift(1);
		
		$this->_posizioni['meta'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;

		$this->s['admin']->check();
		
		$this->m[$this->modelName]->clearConditions("strong");
		$this->m[$this->modelName]->checkAll = false;
		
		$clean['id'] = $data['id'] = (int)$id;
		
		if (!$this->m[$this->modelName]->modificaCategoriaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$this->m[$this->modelName]->setFields($this->metaQueryFields,'sanitizeAll');
					
		$data["type"] = "meta";
		
		$this->m[$this->modelName]->updateTable('update',$clean["id"]);
		
		$this->m[$this->modelName]->setFormStruct();
		
		$menuLinks = 'back,save';

		$params = array(
			'formMenu'=>$menuLinks,
		);
			
		$this->loadScaffold('form', $params);
		$this->scaffold->loadForm("update",$this->applicationUrl.$this->controller."/meta/".$clean["id"]);
		
		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		
		$this->scaffold->getFormValues('sanitizeHtml',$clean["id"]);
		$this->scaffold->render();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['main'] = $this->scaffold->html['main'];
		$data['notice'] = $this->scaffold->model->notice;

		$data["titoloPagina"] = $this->m["CategoriesModel"]->where(array("id_c"=>$clean['id']))->field("title");
		$data['numeroGruppi'] = $this->m["ReggroupscategoriesModel"]->where(array("id_c"=>$clean['id']))->rowNumber();
		
		$this->append($data);
		$this->load('categories_meta');
	}
	
	public function gruppi($id = 0)
	{
		$this->_posizioni['gruppi'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$this->m["ReggroupscategoriesModel"]->bulkAction("del");
		
		$data['type'] = "gruppi";
		
		$this->shift(1);
		
		$clean['id'] = (int)$id;
		$data['id'] = $clean['id'];
		
		if (!$this->m[$this->modelName]->modificaCategoriaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
// 		$data["titoloPagina"] = $this->m[$this->modelName]->nome($clean['id']);
		
		$this->modelName = "ReggroupscategoriesModel";
		
		Params::$nullQueryValue = 'tutti';
		
		$this->m['ReggroupscategoriesModel']->setFields('id_group','sanitizeAll');
		$this->m['ReggroupscategoriesModel']->values['id_c'] = $clean['id'];
		$this->m['ReggroupscategoriesModel']->updateTable('insert,del');
		
		$mainAction = "gruppi/".$clean['id'];
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>$mainAction));

		$this->scaffold->fields = "reggroups_categories.*,reggroups.*";
		$this->scaffold->loadMain('[[checkbox]];reggroups_categories.id_gc;,reggroups.name','reggroups_categories:id_gc','del');
		$this->scaffold->setHead('[[bulkselect:checkbox_reggroups_categories_id_gc]],GRUPPO');
		
		$this->scaffold->itemList->setBulkActions(array(
			"checkbox_reggroups_categories_id_gc"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$this->scaffold->model->clear()->inner("reggroups")->using("id_group")->orderBy("reggroups.name")->where(array("id_c"=>$clean['id']))->convert();
		
// 		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);

		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_c" id="'.$clean['id'].'"';
		
		$data['scaffold'] = $this->scaffold->render();
		
// 		echo $this->scaffold->model->getQuery();
		$data['numeroGruppi'] = $this->scaffold->model->rowNumber();
		$data['numeroProduttori'] = $this->m["ReggroupscategoriesModel"]->where(array("id_c"=>$clean['id']))->rowNumber();
		
// 		echo $this->scaffold->model->getQuery();
		
		$data["titoloPagina"] = $this->m["CategoriesModel"]->where(array("id_c"=>$clean['id']))->field("title");
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$data["listaGruppi"] = $this->m['CategoriesModel']->allowedGroups($clean['id']);
		
// 		$data["listaGruppi"] = $this->scaffold->model->clear()->from("reggroups")->select("reggroups.name,reggroups.id_group")->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$this->append($data);
		$this->load('categories_gruppi');
	}
	
	public function classisconto($id = 0)
	{
		$this->_posizioni['classisconto'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_c";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ClassiscontocategoriesModel";
		
		$this->m[$this->modelName]->setFields('id_classe','sanitizeAll');
		$this->m[$this->modelName]->values['id_c'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("classi_sconto.titolo","classi_sconto.sconto");
		$this->mainHead = "Classe sconto,Sconto";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"classisconto/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("classi_sconto_categories.*,classi_sconto.*")->inner("classi_sconto")->using("id_classe")->orderBy("classi_sconto.sconto")->where(array("classi_sconto_categories.id_c"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["CategoriesModel"]->where(array("id_c"=>$clean['id']))->field("title");
		$data["tabella"] = "categoria";
		
		$data["listaClassi"] = $this->m['ClassiscontoModel']->clear()->toList("id_classe","titolo")->send();
		
		$data["stepsAssociato"] = "categories_steps";
		
		$this->append($data);
	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$qAllowed = array("insert","update");
		
		if (in_array($queryType,$qAllowed))
		{
			$clean['id'] = $data['id'] = (int)$id;
			
			if (strcmp($queryType,'update') === 0 and !$this->m[$this->modelName]->modificaCategoriaPermessa($clean['id']))
			{
				die("non permesso");
			}
		
			$data['type'] = $queryType;
			
			if (!$this->m[$this->modelName]->section)
				$this->queryFields = "title,alias,sottotitolo,immagine,description";
			
			$this->m[$this->modelName]->setFields($this->queryFields,'sanitizeAll');
			
			$data["section"] = $this->m[$this->modelName]->section;
			
			//togli select parent se stai modificando il nodo sezione
			if (isset($this->m[$this->modelName]->section) and $this->m[$this->modelName]->section !== $this->m[$this->modelName]->rootSectionName)
			{
				if (isset($this->m[$this->modelName]->sId) and strcmp($this->m[$this->modelName]->sId,$clean['id']) === 0)
				{
					$this->m[$this->modelName]->delFields("id_p");
					$this->m[$this->modelName]->fields = implode(",",array_keys($this->m[$this->modelName]->values));
					$this->m[$this->modelName]->values["id_p"] = $this->m[$this->modelName]->sIdParent;
				}
			}
// 			if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
			
			if (v("usa_transactions"))
				$this->m[$this->modelName]->db->beginTransaction();
			
			$this->m[$this->modelName]->updateTable('insert,update',$clean['id']);
			
			if (v("usa_transactions"))
				$this->m[$this->modelName]->db->commit();
			
			if ($this->m[$this->modelName]->queryResult and $queryType === "insert")
			{
				$lId = $this->m[$this->modelName]->lId;
				$this->redirect($this->applicationUrl.$this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus."&insert=ok");
			}

			$this->m[$this->modelName]->setFormStruct();
			
			$this->m[$this->modelName]->setUploadForms($clean["id"]);
			
			$params = array(
				'formMenu'=>"back,save",
			);
			
			$this->loadScaffold('form',$params);
			$this->scaffold->loadForm($queryType,$this->applicationUrl.$this->controller."/form/$queryType/".$clean['id']);
			
			if ($queryType === "update")
			{
				$this->m[$this->modelName]->form->entry["id_p"]->options = $this->m[$this->modelName]->buildSelect($clean['id']);
			}
			
			if (isset($this->formFields))
			{
				$this->scaffold->model->fields = $this->formFields;
			}
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);

			if (isset($_GET["insert"]))
			{
				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
			}
			$data['scaffold'] = $this->scaffold->render();
			
			$data['menu'] = $this->scaffold->html['menu'];
			$data['main'] = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
			
			if (strcmp($queryType,'update') === 0)
			{
				$data["titoloPagina"] = $this->m[$this->modelName]->where(array("id_c"=>$clean['id']))->field("title");
				$data['numeroGruppi'] = $this->m["ReggroupscategoriesModel"]->where(array("id_c"=>$clean['id']))->rowNumber();
				
				$data["contenutiTradotti"] = $this->m["ContenutitradottiModel"]->clear()->where(array(
					"id_c"	=>	$clean['id'],
					"in"	=>	array(
						"lingua"	=>	self::$traduzioni,
					),
				))->send(false);
				
				$data["urlPagina"] = $this->m["CategoriesModel"]->getUrlAlias($clean['id']);
			}
		
			$this->append($data);
			$this->load('categories_form');
		}
	}
	
	public function contenuti($id = 0)
	{
		$this->orderBy = "contenuti.id_order";
		
		$this->_posizioni['contenuti'] = 'class="active"';
		
		$this->ordinaAction = "ordinacontenuti";
		
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_c";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ContenutiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$filtroLingua = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectLingua();
		$filtroTipo = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectTipo();
		
		$this->filters = array(null,"titolo_contenuto", array("tipocontenuto","",$filtroTipo), array("lingua","",$filtroLingua));
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		$this->mainFields = array("titoloContenuto","tipi_contenuto.titolo","lingua","attivo");
		$this->mainHead = "Titolo,Tipo,Lingua,Attivo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"contenuti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->orderBy("contenuti.id_order")->where(array(
			"id_c"		=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua"],
			"id_tipo"	=>	$this->viewArgs["tipocontenuto"],
			"lk"		=>	array("contenuti.titolo" => $this->viewArgs["titolo_contenuto"]),
			"tipo"		=>	"FASCIA",
		))->convert()->save();
		
		$this->tabella = "categoria";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["CategoriesModel"]->where(array("id_c"=>$clean['id']))->field("title");
		
		$data["stepsAssociato"] = "categories_steps";
		
		$this->append($data);
	}
	
	public function ordinacontenuti()
	{
		$this->orderBy = "contenuti.id_order";
		
		$this->modelName = "ContenutiModel";
		
		parent::ordina();
	}
	
	public function ordina()
	{
		parent::ordinaGerarchico();
	}
	
}
