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

class PagesController extends BaseController {

	public $voceMenu = "prodotti";
	
	public $tableFields;
	public $head = '[[bulkselect:checkbox_pages_id_page]],THUMB,CODICE / TITOLO,CATEGORIE,PUBBL?,IN EVID?,ORDINAMENTO';
	public $filters = array(null,null,'title');
	public $colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'80px',
			),
		);
		
	public $queryFields = "title,alias,id_c,attivo,in_evidenza,description,immagine,use_editor";
	public $metaQueryFields = 'keywords,meta_description,template,add_in_sitemap';
	
	public $orderBy = "pages.id_order";
	
	public $formFields = null;
	
	protected $_posizioni = array(
		"main"		=>	null,
		"immagini"	=>	null,
		"prod_corr"	=>	null,
		"attributi"	=>	null,
		"caratteristiche"	=> null,
		"meta"	=> null,
		"contenuti"	=> null,
	);
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);
		
// 		$this->load('header_sito');
// 		$this->load('footer','last');

		$this->session('admin');
		$this->model();
		$this->model("PagesModel");

		$data['posizioni'] = $this->_posizioni;
		
		$this->setArgKeys(array(
			'page:forceInt'=>1,
			'title:sanitizeAll'=>'tutti',
			'attivo:sanitizeAll'=>'tutti',
			'in_evidenza:sanitizeAll'=>'tutti',
			'in_promozione:sanitizeAll'=>'tutti',
			'id_c:sanitizeAll'=>'tutti',
			'page_corr:forceNat'=>1,
			'token:sanitizeAll'=>'token',
			'partial:sanitizeAll' => "tutti",
			'titolo_contenuto:sanitizeAll' => "tutti",
			'lingua:sanitizeAll' => "tutti",
			'tipocontenuto:sanitizeAll' => "tutti",
			'titolo_documento:sanitizeAll' => "tutti",
			'lingua_doc:sanitizeAll' => "tutti",
			'id_tipo_doc:sanitizeAll' => "tutti",
			'-id_marchio:sanitizeAll' => "tutti",
		));

		$this->model("CategoriesModel");
		$this->model('ImmaginiModel');
		$this->model('CorrelatiModel');
		$this->model('AttributiModel');
		$this->model('PagesattributiModel');
		$this->model('CombinazioniModel');
		$this->model('CaratteristicheModel');
		$this->model('CaratteristichevaloriModel');
		$this->model('PagescarvalModel');
		$this->model('ScaglioniModel');
		$this->model("LayerModel");
		$this->model("ContenutitradottiModel");
		$this->model("ContenutiModel");
		$this->model("AttributivaloriModel");
		$this->model("LingueModel");
		$this->model("DocumentiModel");
		$this->model("PageslinkModel");
		$this->model("TipicontenutoModel");
		$this->model("PersonalizzazioniModel");
		$this->model("PagespersonalizzazioniModel");
		$this->model("TagModel");
		$this->model("PagestagModel");
		
		$this->_topMenuClasses[$this->voceMenu] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["tabella"] = $this->m["PagesModel"]->table();
		
		$this->append($data);

		Params::$setValuesConditionsFromDbTableStruct = true;
		Params::$automaticConversionToDbFormat = true;
		Params::$automaticConversionFromDbFormat = true;
		Params::$automaticallySetFormDefaultValues = true;
		
		$this->shift();
		
		$this->tableFields = array(
			'[[checkbox]];pages.id_page;',
			'<a href="'.$this->baseUrl.'/'.$this->controller.'/form/update/;pages.id_page;'.$this->viewStatus.'">;PagesModel.getThumb|pages.id_page;</a>',
			"<div class='record_id' style='display:none'>;pages.id_page;</div><a href='".$this->baseUrl."/".$this->controller."/form/update/;pages.id_page;".$this->viewStatus."'>;pages.title;</a> <br /><span class='get_title'>(alias: ;pages.alias;)</span>",
			'PagesModel.categoriesS|pages.id_page',
			'PagesModel.getPubblicatoCheckbox|pages.id_page',
			'PagesModel.getInEvidenzaCheckbox|pages.id_page',
			'PagesModel.getInputOrdinamento|pages.id_page',
		);
		
	}
	
	public function pubblica($id,$value)
	{
		$this->s['admin']->check();
		
		header('Content-type: text/html; charset=UTF-8');

		$this->clean();

		$clean['id'] = (int)$id;
		$clean['value'] = sanitizeAll($value);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$possible = array('Y','N');

		if (in_array($value,$possible))
		{
			$this->m[$this->modelName]->values = array(
				'attivo'	=>	$clean['value'],
			);
			$this->m[$this->modelName]->pUpdate($clean['id']);
			$this->m[$this->modelName]->sincronizza($clean['id']);
		}
	}
	
	public function inevidenza($id,$value)
	{
		$this->s['admin']->check();
		
		header('Content-type: text/html; charset=UTF-8');

		$this->clean();

		$clean['id'] = (int)$id;
		$clean['value'] = sanitizeAll($value);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$possible = array('Y','N');

		if (in_array($value,$possible))
		{
			$this->m[$this->modelName]->values = array(
				'in_evidenza'	=>	$clean['value'],
			);
			$this->m[$this->modelName]->pUpdate($clean['id']);
			$this->m[$this->modelName]->sincronizza($clean['id']);
		}
	}
	
	public function main()
	{
		$this->m[$this->modelName]->db->beginTransaction();
		
		$this->shift();
		
		Params::$nullQueryValue = 'tutti';
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->m[$this->modelName]->bulkAction("del");
		
		if (isset($_POST["ordinaPagine"]))
		{
			$clean["order"] = $this->request->post("order","","sanitizeAll");
		
			$orderArray = explode("|",$clean["order"]);
			
			foreach ($orderArray as $q)
			{
				if (strcmp($q,"") !== 0 and strstr($q, ':'))
				{
					$temp = explode(":",$q);
					$this->m[$this->modelName]->values = array("id_order" => (int)$temp[1]);
					$this->m[$this->modelName]->pUpdate((int)$temp[0]);
				}
			}
		}
		
		$data["orderBy"] = $this->orderBy;
		
		$this->m[$this->modelName]->setFilters();
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'add'));
		
		foreach (self::$traduzioni as $codiceLingua)
		{
			$this->tableFields[] = "link".$codiceLingua;
			$this->head .= ",".strtoupper($codiceLingua);
		}
		
		$this->scaffold->loadMain($this->tableFields,'pages:id_page','ldel,ledit');
		
		$this->scaffold->setHead($this->head);
		
		$this->scaffold->itemList->setBulkActions(array(
			"++checkbox_pages_id_page"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci un nuovo prodotto';
		
		$this->scaffold->fields = "distinct pages.codice_alfa,categories.*,pages.*,marchi.titolo";
		$this->scaffold->model->clear()->inner("categories")->using("id_c")->left(array("marchio"))->orderBy($this->orderBy);
		
		$where = array(
			'attivo'		=>	$this->viewArgs['attivo'],
			'in_evidenza'	=>	$this->viewArgs['in_evidenza'],
			'in_promozione'	=>	$this->viewArgs['in_promozione'],
			'id_marchio'	=>	$this->viewArgs['-id_marchio'],
		);
		
		$this->scaffold->model->where($where);
		
		//add the where clause to get only the pages of that category
// 		print_r($this->m[$this->modelName]->hModel->getChildrenSectionWhere());
		$this->scaffold->model->aWhere($this->m[$this->modelName]->hModel->getChildrenSectionWhere());
		
		if (strcmp($this->viewArgs['title'],'tutti') !== 0)
		{
			$where = array(
				"OR"	=> array(
					"lk" => array('n!pages.title' => $this->viewArgs['title']),
					" lk" => array('n!pages.codice' => $this->viewArgs['title']),
// 					'n!pages.title'		=>	"like '%".$this->viewArgs['title']."%'",
// 					'n!pages.codice'	=>	"like '%".$this->viewArgs['title']."%'",
					)
			);

			$this->scaffold->model->aWhere($where);
		}
		
		$data["sId"] = 0;
		
		if (strcmp($this->viewArgs['id_c'],'tutti') !== 0)
		{
			$children = $this->m[$this->modelName]->hModel->children($this->viewArgs['id_c'],true);
			
			$sId = $this->m[$this->modelName]->hModel->sId;
			
			$data["sId"] = !is_array($sId) ? $sId : 0;
			
			if (strcmp($this->viewArgs['id_c'],'1') === 0 or (!is_array($sId) and strcmp($this->viewArgs['id_c'],$sId) === 0))
			{
				$where = array(
					"in" => array('-id_c' => $children),
// 					'-id_c' =>	"in(".implode(',',$children).")",
				);
			}
			else
			{
				$where = array(
					'-id_c' =>	$this->viewArgs['id_c'],
				);
			}
			
			$this->scaffold->model->aWhere($where);
		}
		
		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = $this->colProperties;
		
		$this->scaffold->itemList->setFilters($this->filters);
		
		$data['scaffold'] = $this->scaffold->render();
// 		print_r ($this->scaffold->model->db->queries);
		
		$this->m[$this->modelName]->db->commit();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
		
		$this->append($data);
		$this->load('pages_main');
	}

	public function eliminacategoria($id = 0)
	{
		$this->s['admin']->check();
		
		$this->clean();
		
		$clean['id'] = (int)$id;
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		if (!$this->m["PagesModel"]->principale($clean['id']))
		{
			$this->m["PagesModel"]->pDel($clean['id']);
		}
	}
	
	public function aggiungicategoria($id = 0)
	{
		$_GET["partial"] = "Y";
		
		$this->s['admin']->check();
		
		$clean['id'] = $data['id_page'] = (int)$id;
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$data["listaCategorie"] = $this->m["CategoriesModel"]->buildSelect();
		
		$data["notice"] = null;
		
		if (isset($_POST["insertAction"]))
		{
			$record = $this->m[$this->modelName]->selectId($clean['id']);
			
			if ($record > 0)
			{
				$clean["id_c"] = $this->request->post("id_c",0,"forceInt");
				
				if ($clean["id_c"] !== 0)
				{
					if ($this->m[$this->modelName]->where(array("codice_alfa"=>$record["codice_alfa"],"-id_c"=>$clean["id_c"]))->rowNumber() === 0)
					{
						$this->m[$this->modelName]->incategoria($clean['id'], $clean["id_c"]);
						$data["queryResult"] = true;
					}
					else
					{
						$data["notice"] = "<div class='alert'>Questa pagina è già stata inserita all'interno di questa categoria</div>\n";
					}
				}
			}
		}
		
		$this->append($data);
		
		$this->load("pages_aggiungi_categoria");
	}
	
	public function ordinacontenuti()
	{
		$this->modelName = "ContenutiModel";
		
		parent::ordina();
	}
	
	public function ordinadocumenti()
	{
		$this->modelName = "DocumentiModel";
		
		parent::ordina();
	}
	
	public function ordinapersonalizzazioni()
	{
		$this->modelName = "PagespersonalizzazioniModel";
		
		parent::ordina();
	}
	
	public function ordina()
	{
		parent::ordina();
		
// 		$this->s['admin']->check();
// 		
// 		$this->clean();
// 		
// 		if (strstr($this->orderBy, "id_order"))
// 		{
// 			if (isset($_POST["ordinaPagine"]))
// 			{
// 				$clean["order"] = $this->request->post("order","","sanitizeAll");
// 			
// 				$orderArray = explode(",",$clean["order"]);
// 				
// 				$orderClean = array();
// 				
// 				foreach ($orderArray as $id_page)
// 				{
// 					if ((int)$id_page !== 0)
// 					{
// 						$orderClean[] = (int)$id_page;
// 					}
// 				}
// 				
// 				$where = "in(".implode(",",$orderClean).")";
// 				
// 				$idOrderArray = $this->m[$this->modelName]->where(array(
// 					"in" => array("id_page" => $orderClean),
// 				))->toList("id_order")->send();
// 				
// 				if ($this->orderBy === "pages.id_order")
// 				{
// 					sort($idOrderArray);
// 				}
// 				else
// 				{
// 					rsort($idOrderArray);
// 				}
// 				
// 				for ($i=0; $i<count($orderClean); $i++)
// 				{
// 					if (isset($idOrderArray[$i]))
// 					{
// 						$this->m[$this->modelName]->values = array(
// 							"id_order" => (int)$idOrderArray[$i],
// 						);
// 						$this->m[$this->modelName]->pUpdate((int)$orderClean[$i]);
// 					}
// 				}
// 			}
// 		}
	}
	
	public function meta($id = 0)
	{
		$this->s['admin']->check();
		
		$this->shift(1);
		
		$this->_posizioni['meta'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$this->m[$this->modelName]->clearConditions("strong");
		$this->m[$this->modelName]->checkAll = false;
		
		$clean['id'] = $data['id_page'] = (int)$id;
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$this->m[$this->modelName]->setFields($this->metaQueryFields,'sanitizeAll');
					
		$data["type"] = "meta";
		
		$this->m[$this->modelName]->updateTable('update',$clean["id"]);
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$params = array(
			'formMenu'=>'back,copia,save',
		);
		
		$this->m[$this->modelName]->setFormStruct();
		
		$this->loadScaffold('form',$params);
		$this->scaffold->loadForm("update",$this->controller."/meta/".$clean["id"]);
		
		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		
		$this->scaffold->getFormValues('sanitizeHtml',$clean["id"]);
		$this->scaffold->render();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['main'] = $this->scaffold->html['main'];
		$data['notice'] = $this->scaffold->model->notice;

		$this->append($data);
		$this->load('pages_meta');
	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$this->shift(2);
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$qAllowed = array("insert","update");
		
		$clean['id'] = $data['id_page'] = (int)$id;
		
		if ((strcmp($queryType,'update') === 0 or strcmp($queryType,'copia') === 0) and !$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["altreCategorie"] = array();
		
		$data["use_editor"] = "Y";
		
		$data["section"] = $this->m[$this->modelName]->hModel->section;
		
		if ($queryType === "insert" or $this->m[$this->modelName]->principale($clean['id']))
		{
			if (in_array($queryType,$qAllowed))
			{
				$this->m[$this->modelName]->setFields($this->queryFields,'sanitizeAll');
				
				$this->m[$this->modelName]->updateTable('insert,update',$clean['id']);
				
				if (isset($_POST["gAction"]))
				{
					$this->m[$this->modelName]->result = false;
				}
			
				if ($this->m[$this->modelName]->queryResult and $queryType === "insert")
				{
					$lId = $this->m[$this->modelName]->lId;
					$this->redirect($this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus."&insert=ok");
				}

				$this->m[$this->modelName]->setFormStruct();
				
				$this->m[$this->modelName]->setUploadForms($clean["id"]);
				
				$menuLinks = 'back,save';
				if ($queryType === "update")
				{
					$menuLinks = 'back,copia,save';
				}
				$params = array(
					'formMenu'=>$menuLinks,
				);
				
				$this->loadScaffold('form',$params);
				$this->scaffold->loadForm($queryType,$this->controller."/form/$queryType/".$clean['id']);
				
				if (isset($this->formFields))
				{
					$this->scaffold->model->fields = $this->formFields;
				}
				
				$this->scaffold->getFormValues('sanitizeHtml',$clean['id'],array());

				if (strcmp($queryType,'update') === 0)
				{
					$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
					
					$data["contenutiTradotti"] = $this->m["ContenutitradottiModel"]->clear()->where(array(
						"id_page"	=>	$clean['id'],
						"in"	=>	array(
							"lingua"	=>	self::$traduzioni,
						),
					))->send(false);
					
					$record = $this->m[$this->modelName]->selectId($clean['id']);
					
					if (count($record) > 0)
					{
						$data["use_editor"] = $record["use_editor"];
						
						$options = $this->scaffold->model->form->entry["id_c"]->options;
						if (!array_key_exists($record["id_c"],$options))
						{
							$this->scaffold->model->form->entry["id_c"]->options[$record["id_c"]] = $this->m[$this->modelName]->hModel->indent($record["id_c"], false, false, false);
						}
						
						$data["altreCategorie"] = $this->m[$this->modelName]->clear()->select("categories.*,pages.id_page")->inner("categories")->using("id_c")->where(array("codice_alfa"=>$record["codice_alfa"],"ne"=>array("id_page" => $clean['id'])))->orderBy("categories.lft")->send();
					}
					
					$data["urlPagina"] = $this->m["PagesModel"]->getUrlAlias($clean['id']);
				}
				
				$data["form"] = array();
				
				foreach ($this->scaffold->values as $key => $value)
				{
					$data["form"][$key] = $this->scaffold->model->form->entry[$key]->render($value);
				}
				
				if (isset($_GET["insert"]))
				{
					$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
				}
				
				$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
				
				$data['scaffold'] = $this->scaffold->render();
				
				$data['menu'] = $this->scaffold->html['menu'];
				$data['main'] = $this->scaffold->html['main'];
				
				$data['notice'] = $this->scaffold->model->notice;
				
				$data['type'] = $queryType;
				
				$data["use_editor"] = (isset($_POST["use_editor"]) and in_array($_POST["use_editor"],array("Y","N"))) ? $_POST["use_editor"] : $data["use_editor"];
				
				$this->append($data);
				$this->load('pages_form');
			}
			else if (strcmp($queryType,"copia") === 0)
			{
				$this->clean();
				$res = $this->m[$this->modelName]->clear()->where(array("id_page"=>$clean['id']))->send();
				
				if (count($res) > 0)
				{
					$this->m[$this->modelName]->values = $res[0]["pages"];
					
					$this->m[$this->modelName]->values["title"] = "(Copia di) " . $this->m[$this->modelName]->values["title"];
					
					$this->m[$this->modelName]->checkDates();
// 					$this->m[$this->modelName]->values["dal"] = reverseData($this->m[$this->modelName]->values["dal"]);
// 					$this->m[$this->modelName]->values["al"] = reverseData($this->m[$this->modelName]->values["al"]);
			
// 					$this->m[$this->modelName]->values["codice"] = "(copia)-".$this->m[$this->modelName]->values["codice"] . "-". generateString(8);
					
					$this->m[$this->modelName]->values["principale"] = "Y";
					
					$this->m[$this->modelName]->delFields("id_page");
					$this->m[$this->modelName]->delFields("data_creazione");
					$this->m[$this->modelName]->delFields("id_order");
					
					$this->m[$this->modelName]->values["codice_alfa"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
					
					$clean["id_c"] = 1;
					
					if (isset($this->m[$this->modelName]->hModel->section))
					{
						$section = $this->m[$this->modelName]->hModel->section;
						
						if (strcmp($section,$this->m[$this->modelName]->hModel->rootSectionName) !== 0)
						{
							$cat = $this->m[$this->modelName]->hModel->clear()->where(array("section"=>$this->m[$this->modelName]->hModel->section))->record();
						
							if (count($cat) > 0)
							{
								$clean["id_c"] = $cat["id_c"];
							}
						}
					}
					
					$this->m[$this->modelName]->values["id_c"] = $clean["id_c"];
					
					$this->m[$this->modelName]->sanitize();

					Params::$setValuesConditionsFromDbTableStruct = false;
					$this->m[$this->modelName]->clearConditions("values");
					$this->m[$this->modelName]->clearConditions("strong");
					$this->m[$this->modelName]->clearConditions("soft");
					
					$this->m[$this->modelName]->insert();
					if ($this->m[$this->modelName]->queryResult)
					{
						$lId = $this->m[$this->modelName]->lId;
						
						$this->m["ImmaginiModel"]->duplica($clean['id'], $lId);
						$this->m["LayerModel"]->duplica($clean['id'], $lId);
						$this->m["ScaglioniModel"]->duplica($clean['id'], $lId);
						$this->m["ContenutiModel"]->duplica($clean['id'], $lId);
						$this->m["DocumentiModel"]->duplica($clean['id'], $lId);
						$this->m["PageslinkModel"]->duplica($clean['id'], $lId);
						$this->m["CorrelatiModel"]->duplica($clean['id'], $lId);
						$this->m["PagespersonalizzazioniModel"]->duplica($clean['id'], $lId);
						$this->m["PagestagModel"]->duplica($clean['id'], $lId);
						
						$this->redirect($this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus."&insert=ok");
					}
				}
			}
		}
		else
		{
			$principale = $this->m[$this->modelName]->getPrincipale($clean['id']);
			
			if ($principale !== 0)
			{
				$this->redirect($this->controller."/form/update/".$principale.$this->viewStatus);
			}
		}
	}

	public function immagini($id = 0)
	{
		$this->_posizioni['immagini'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "immagini";
		
		$this->shift(1);
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$clean['id'] = (int)$id;
		$data['id_page'] = $clean['id'];
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$this->helper("Menu",$this->controller,"main");
		
		$this->h["Menu"]->links['copia']['url'] = 'form/copia/'.$clean['id'];
		$this->h["Menu"]->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$data["menu"] = $this->h["Menu"]->render("back,copia");
		
		$this->append($data);
		$this->load('pages_immagini');
	}
	
	public function layer($id = 0)
	{
		$this->ordinaAction = "ordinalayer";
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'100px',
			),
		);
		
		$this->_posizioni['layer'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $data['id_page'] = $this->id = (int)$id;
		$this->id_name = "id_user";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "LayerModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("thumb","edit","slide_layer.animazione");
		$this->mainHead = "Immaginee,Titolo,Animazione";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"layer/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("slide_layer.*")->orderBy("slide_layer.id_layer")->where(array("id_page"=>$clean['id']))->orderBy("id_order")->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function scaglioni($id = 0)
	{
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->_posizioni['scaglioni'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $data['id_page'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ScaglioniModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("scaglioni.quantita","scaglioni.sconto");
		$this->mainHead = "Quantità,Sconto (%)";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"scaglioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
// 		$this->h["Menu"]->links['copia']['url'] = 'form/copia/'.$clean['id'];
// 		$this->h["Menu"]->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->m[$this->modelName]->orderBy("scaglioni.quantita")->where(array("id_page"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data['tabella'] = "prodotti";
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function accessori($id = 0)
	{
		$this->correlatigeneric($id, 1);
	}
	
	public function correlati($id = 0)
	{
		$this->correlatigeneric($id);
	}
	
	private function correlatigeneric($id = 0, $accessori = 0)
	{
		$posizione = $accessori ? "accessori" : "prod_corr";
		$action = $accessori ? "accessori" : "correlati";
		
		$this->_posizioni[$posizione] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "prodotti_correlati";
		
		$this->shift(1);
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$clean['id'] = (int)$id;
		$data['id_page'] = $clean['id'];
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$this->modelName = "CorrelatiModel";
		
		Params::$nullQueryValue = 'tutti';
		
		$this->m['CorrelatiModel']->setFields('id_corr','sanitizeAll');
		$this->m['CorrelatiModel']->values['id_page'] = $clean['id'];
		
		$this->m['CorrelatiModel']->values['accessorio'] = (int)$accessori;
		
		$this->m['CorrelatiModel']->updateTable('insert,del');
		
		$mainAction = "$action/".$clean['id'];
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction,'pageVariable'=>'page_corr'));

		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->scaffold->fields = "pages.*,prodotti_correlati.*";
		$this->scaffold->loadMain('PagesModel.getThumb|pages.id_page,;pages.codice; - ;pages.title;,getYesNo|pages.attivo','prodotti_correlati:id_pc','moveup,movedown,ldel');
		$this->scaffold->setHead('Thumb,Titolo prodotto,Pubblicato?');
		
		$this->scaffold->model->clear()->inner("pages")->on("prodotti_correlati.id_corr = pages.id_page")->orderBy("prodotti_correlati.id_order")->where(array(
			"id_page"		=>	$clean['id'],
			"accessorio"	=>	$accessori,
		));
		
		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$data['scaffold'] = $this->scaffold->render();
		$data['numeroCorrelati'] = $this->scaffold->model->rowNumber();
		
		$data["listaProdotti"] = array();
		
		$firstSection = $this->m["PagesModel"]->section($clean['id'], true);
		
		$idFirstParent = $this->m["CategoriesModel"]->clear()->where(array(
			"section"	=>	sanitizeDb($firstSection),
		))->field("id_c");
		
		$children = $this->m["CategoriesModel"]->children((int)$idFirstParent, true);

		$res = $this->m['PagesModel']->clear()->where(array(
			"ne" => array("id_page" => $clean['id']),
			"attivo" => "Y",
			"principale"=>"Y",
			"in" => array("-id_c" => $children),
		))->orderBy("id_order")->send();
		
		foreach ($res as $r)
		{
			$data["listaProdotti"][$r["pages"]["id_page"]] = $r["pages"]["codice"] . " - " . $r["pages"]["title"];
		}
		
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$this->append($data);
		$this->load('pages_correlati');
	}
	
	public function attributi($id = 0)
	{
		$this->_posizioni['attributi'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "attributi";
		
		$this->shift(1);
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$clean['id'] = (int)$id;
		$data['id_page'] = $clean['id'];
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$this->modelName = "PagesattributiModel";
		
		Params::$nullQueryValue = 'tutti';
		
		$this->m['PagesattributiModel']->setFields('id_a','sanitizeAll');
		$this->m['PagesattributiModel']->values['id_page'] = $clean['id'];
		$this->m['PagesattributiModel']->updateTable('insert,del');
		
		$mainAction = "attributi/".$clean['id'];
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction));

		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->scaffold->fields = "attributi.*,pages_attributi.*";
		$this->scaffold->loadMain('attributi.titolo','pages_attributi:id_pa','moveup,movedown,del');
		$this->scaffold->setHead('ATTRIBUTO');
		
		$this->scaffold->model->clear()->inner("attributi")->on("attributi.id_a = pages_attributi.id_a")->orderBy("pages_attributi.id_order")->where(array("n!pages_attributi.id_page"=>$clean['id']));
		
		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['numeroAttributi'] = $this->scaffold->model->rowNumber();
		
		$data["listaAttributi"] = $this->m['AttributiModel']->clear()->toList("attributi.id_a","attributi.titolo")->orderBy("titolo")->send();
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['main'] = $this->scaffold->html['main'];
		$data['notice'] = $this->scaffold->model->notice;
		
		//lista combinazioni del prodotto
		$data['noticeComb'] = null;
		if (isset($_GET["action"]))
		{
			if (strcmp($_GET["action"],"aggiorna") === 0)
			{
				$this->m["CombinazioniModel"]->creaCombinazioni($clean['id']);
				$this->redirect($this->controller."/attributi/$id".$this->viewStatus."&refresh=y#refresh_link");
			}
			else if (strcmp($_GET["action"],"del_comb") === 0)
			{
				$clean["id_c"] = $this->request->get("id",0,"forceInt");
				$this->m["CombinazioniModel"]->del($clean["id_c"]);
				$this->redirect($this->controller."/attributi/$id".$this->viewStatus."&refresh=y#refresh_link");
			}
		}
		$this->m['CombinazioniModel']->creaColonne($clean['id']);
		
		$result = $this->m['CombinazioniModel']->clear()->where(array('id_page'=>$clean['id']))->orderBy("id_order")->send();
		$data['numeroCombinazioni'] = count($result);
		
		if (isset($_GET["refresh"]))
		{
			$data['noticeComb'] = "<div class='alert alert-success'>operazione eseguita!</div>\n";
		}
		
		$this->helper('List','id_c');
		$this->h['List']->submitImageType = 'yes';
		$this->h['List']->position = array(1,1);

		$this->h['List']->colProperties = array(
// 			array(
// 				'class'	=>	'td_val_attr',
// 				'width'	=>	'60px',
// 			),
			array(
				'class'	=>	'td_val_attr',
				'width'	=>	'200px',
			),
			array(
				'class'	=>	'td_val_attr',
				'width'	=>	'200px',
			),
			array(
				'class'	=>	'td_val_attr',
				'width'	=>	'150px',
			),
			array(
				'width'	=>	'3%',
			),
			array(
				'width'	=>	'3%',
			),
			array(
				'width'	=>	'3%',
			),
			array(
				'width'	=>	'3%',
			),
// 			array(
// 				'width'	=>	'3%',
// 			),
		);
		
// 		$this->h['List']->addItem("text","<img class='immagine_variante' src='http://".DOMAIN_NAME."/thumb/immagineinlistaprodotti/0/;combinazioni.immagine;' /><img id=';combinazioni.id_c;' title='modifica immagine' class='img_attributo_aggiorna immagine_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><img class='attributo_loading align_middle' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' />");
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;combinazioni.codice;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='codice' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;setPriceReverse|combinazioni.price;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='price' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;setPriceReverse|combinazioni.peso;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='peso' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		foreach ($this->m['CombinazioniModel']->colonne as $col)
		{
			$this->h['List']->addItem("text",";AttributivaloriModel.getName|combinazioni.$col;");
		}
		
// 		$this->h['List']->addItem("text","<a class='del_row' href='".$this->baseUrl."/".$this->controller."/attributi/".$clean['id'].$this->viewStatus."&action=del_comb&id=;combinazioni.id_c;#refresh_link'><img src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/delete.png' /></a>");
		
		$colonne = $this->m["PagesattributiModel"]->getNomiColonne($clean["id"]);
		
// 		$this->h['List']->addItem('delForm','pages/attributi/'.$clean['id'],';combinazioni.id_c;');
		$head = 'Codice,Prezzo,Peso';
		foreach ($colonne as $col)
		{
			$head .= ",$col";
		}
		
		if (v("attiva_giacenza"))
		{
			$this->h['List']->addItem("text",";combinazioni.giacenza;");
			$head .= ",Giacenza";
		}
		
		$this->h['List']->setHead($head);
		
		$data['listaCombinazioni'] = $this->h['List']->render($result);
		
		$imm1 = $this->m["PagesModel"]->clear()->where(array("id_page"=>$clean['id'],"ne" => array("immagine" => "")))->toList('immagine')->send();
		$imm2 = $this->m["ImmaginiModel"]->clear()->where(array("id_page"=>$clean['id']))->toList("immagine")->orderBy("id_order")->send();
		
		$data['listaImmagini'] = array_merge($imm1, $imm2);
		
		$this->append($data);
		$this->load('pages_attributi');
	}
	
	public function contenuti($id = 0)
	{
		$this->orderBy = "contenuti.id_order";
		
		$this->_posizioni['contenuti'] = 'class="active"';
		
		$this->ordinaAction = "ordinacontenuti";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ContenutiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$filtroLingua = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectLingua();
		$filtroTipo = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectTipo("FASCIA");
		
		$this->filters = array(null,"titolo_contenuto", array("tipocontenuto","",$filtroTipo), array("lingua","",$filtroLingua));
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		$this->mainFields = array("titoloContenuto","tipi_contenuto.titolo","lingua","attivo");
		$this->mainHead = "Titolo,Tipo,Lingua,Attivo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"contenuti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->orderBy("contenuti.id_order")->where(array(
			"id_page"	=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua"],
			"id_tipo"	=>	$this->viewArgs["tipocontenuto"],
			"lk"		=>	array("contenuti.titolo" => $this->viewArgs["titolo_contenuto"]),
			"tipo"		=>	"FASCIA",
		))->convert()->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function testi($id = 0)
	{
		$this->orderBy = "contenuti.id_order";
		
		$this->_posizioni['testi'] = 'class="active"';
		
		$this->ordinaAction = "ordinacontenuti";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ContenutiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$filtroLingua = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectLingua();
		$filtroTipo = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectTipo("GENERICO");
		
		$this->filters = array(null,"titolo_contenuto", array("tipocontenuto","",$filtroTipo), array("lingua","",$filtroLingua));
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		$this->mainFields = array("titoloContenuto","tipi_contenuto.titolo","lingua","attivo");
		$this->mainHead = "Titolo,Tipo,Lingua,Attivo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"testi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->orderBy("contenuti.id_order")->where(array(
			"id_page"	=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua"],
			"id_tipo"	=>	$this->viewArgs["tipocontenuto"],
			"lk"		=>	array("contenuti.titolo" => $this->viewArgs["titolo_contenuto"]),
			"ne"		=>	array("tipo"	=>	"FASCIA"),
		))->convert()->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["tipoContenuti"] = $this->m["TipicontenutoModel"]->clear()->select("distinct tipo")->where(array(
			"ne" => array("tipo" => "FASCIA"),
		))->toList("tipo")->send();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function link($id = 0)
	{
		$this->_posizioni['link'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PageslinkModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("titoloLink");
		$this->mainHead = "Titolo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"link/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("pages_link.*")->orderBy("pages_link.titolo")->where(array(
			"id_page"	=>	$clean['id'],
		))->convert()->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function documenti($id = 0)
	{
		$this->orderBy = "documenti.id_order";
		
		$this->_posizioni['documenti'] = 'class="active"';
		
		$this->ordinaAction = "ordinadocumenti";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "DocumentiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'160px',
			),
		);
		
		$filtroLingua = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectLingua();
		$filtroTipoDoc = array("tutti" => "VEDI TUTTO") + $this->m[$this->modelName]->selectTipo();
		
		$this->filters = array(null,null,"titolo_documento", null, array("lingua_doc","",$filtroLingua), array("id_tipo_doc","",$filtroTipoDoc));
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		$this->mainFields = array("immagine","titoloDocumento","filename","lingua","tipi_documento.titolo");
		$this->mainHead = "Thumb,Titolo,File,Lingua,Tipo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"documenti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("documenti.*,tipi_documento.*")->inner(array("page"))->left(array("tipo"))->orderBy("documenti.id_order")->where(array(
			"id_page"	=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua_doc"],
			"id_tipo_doc"	=>	$this->viewArgs["id_tipo_doc"],
			"lk"		=>	array("documenti.titolo" => $this->viewArgs["titolo_documento"]),
		))->convert()->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function personalizzazioni($id = 0)
	{
		$this->orderBy = "personalizzazioni.id_order";
		
		$this->_posizioni['personalizzazioni'] = 'class="active"';
		
		$this->ordinaAction = "ordinapersonalizzazioni";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PagespersonalizzazioniModel";
		
		$this->m[$this->modelName]->setFields('id_pers','sanitizeAll');
		$this->m[$this->modelName]->values['id_page'] = $clean['id'];		
		$this->m[$this->modelName]->updateTable('insert,del');
		
		if ($this->m[$this->modelName]->queryResult)
			$this->redirect($this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("personalizzazioni.titolo", "personalizzazioni.numero_caratteri");
		$this->mainHead = "Titolo,Numero caratteri";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"personalizzazioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("personalizzazioni.*,pages_personalizzazioni.*")->inner(array("personalizzazione"))->orderBy("pages_personalizzazioni.id_order")->where(array(
			"pages_personalizzazioni.id_page"	=>	$clean['id'],
		))->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$data["lista"] = $this->m["PersonalizzazioniModel"]->clear()->sWhere("id_pers not in (select id_pers from pages_personalizzazioni where id_page = ".$clean['id'].")")->orderBy("titolo")->toList("id_pers","titolo")->send();
		
		$this->append($data);
	}
	
	public function tag($id = 0)
	{
		$this->orderBy = "tag.id_order";
		
		$this->_posizioni['tag'] = 'class="active"';
		
		$this->ordinaAction = "ordinatag";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PagestagModel";
		
		$this->m[$this->modelName]->setFields('id_tag','sanitizeAll');
		$this->m[$this->modelName]->values['id_page'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		if ($this->m[$this->modelName]->queryResult)
			$this->redirect($this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("tag.titolo");
		$this->mainHead = "Titolo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"tag/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("tag.*,pages_tag.*")->inner(array("tag"))->orderBy("pages_tag.id_order")->where(array(
			"pages_tag.id_page"	=>	$clean['id'],
		))->save();
		
		$this->tabella = "pages";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$data["lista"] = $this->m["TagModel"]->clear()->sWhere("id_tag not in (select id_tag from pages_tag where id_page = ".$clean['id'].")")->orderBy("titolo")->toList("id_tag","titolo")->send();
		
		$this->append($data);
	}
	
	public function caratteristiche($id = 0)
	{
		$this->_posizioni['caratteristiche'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "caratteristiche";
		
		$this->shift(1);
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$clean['id'] = (int)$id;
		$data['id_page'] = $clean['id'];
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
		{
			die("non permesso");
		}
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$this->modelName = "PagescarvalModel";
		
		Params::$nullQueryValue = 'tutti';
		
		$this->m['PagescarvalModel']->setFields('id_cv,titolo,id_car','sanitizeAll');
		$this->m['PagescarvalModel']->values['id_page'] = $clean['id'];
		$this->m['PagescarvalModel']->updateTable('insert,del');
		
		$mainAction = "caratteristiche/".$clean['id'];
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction));

		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->scaffold->fields = "pages_caratteristiche_valori.*,caratteristiche_valori.*,caratteristiche.*";
		$this->scaffold->loadMain('caratteristiche.titolo,edit','pages_caratteristiche_valori:id_pcv','moveup,movedown,del');
		$this->scaffold->setHead('CARATTERISTICA,VALORE');
		
		$this->scaffold->model->clear()->inner("caratteristiche_valori")->using("id_cv")->inner("caratteristiche")->using("id_car")->orderBy("pages_caratteristiche_valori.id_order")->where(array("n!pages_caratteristiche_valori.id_page"=>$clean['id']));
		
		$this->scaffold->update('moveup,movedown');
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['numeroCaratteristicheVal'] = $this->scaffold->model->rowNumber();
		
		$data["listaCaratteristiche"] = $this->m['CaratteristicheModel']->clear()->toList("caratteristiche.id_car","caratteristiche.titolo")->orderBy("caratteristiche.titolo")->send();
		
		$data["lastCar"] = $this->request->post("id_car",0,"forceInt");
		
		$data["listaCarattVal"] = array("0"	=>	"-- seleziona --");

// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['main'] = $this->scaffold->html['main'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$this->append($data);
		$this->load('pages_caratteristiche');
	}
	
	public function updatevalue()
	{
		$this->s['admin']->check();
		
		header('Content-type: text/html; charset=UTF-8');

		$this->clean();

		$clean['id_c'] = $this->request->post("id_c",0,"forceInt");
		$clean['value'] = $this->request->post("value","","sanitizeAll");
		$clean['field'] = $this->request->post("field","","sanitizeAll");

		$res = $this->m["CombinazioniModel"]->clear()->where(array("id_c" => $clean['id_c']))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($clean['field'],"price") === 0)
			{
				if (!preg_match("/^[0-9]{1,8}(\,[0-9]{1,4})?$/",$clean['value']))
				{
					die("Si prega di ricontrollare il formato del campo prezzo");
				}
				
				$clean['value'] = setPrice($clean['value']);
			}
			
			if (strcmp($clean['field'],"peso") === 0)
			{
				if (!preg_match("/^[0-9]{1,8}(\,[0-9]{1,2})?$/",$clean['value']))
				{
					die("Si prega di ricontrollare il formato del campo peso");
				}
				
				$clean['value'] = setPrice($clean['value']);
			}
			
			if (strcmp($clean['field'],"immagine") === 0)
			{
				if (!$this->m["ImmaginiModel"]->imageExists($clean['value'],$res[0]["combinazioni"]["id_page"]))
				{
					die("L'immagine scelta non esiste");
				}
			}
			
			$this->m["CombinazioniModel"]->values = array(
				$clean['field'] => $clean['value'],
			);
			
			$allowedFields = array("codice","price","immagine","peso");
			if (!in_array($clean['field'],$allowedFields))
			{
				die("KO");
			}
			
			if ($this->m["CombinazioniModel"]->update($clean['id_c']))
			{
				echo "OK";
			}
			else
			{
				echo "KO";
			}
		}
		else
		{
			die("La combinazione non esiste");
		}

	}
	
	public function move()
	{
		$this->clean();
		$clean['token'] = $this->request->post("token","","sanitizeAll");
		$clean['id_page'] = $this->request->post("id_page","0","forceInt");
		$clean['is_main'] = $this->request->post("is_main",0,"forceInt");
		
// 		$this->m[$this->modelName]->checkPrincipale($clean['id_page']);
// 		
// 		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id_page']))
// 		{
// 			die("non permesso");
// 		}
		
		$res = $this->m[$this->modelName]->query("select * from adminsessions where token = '".$clean['token']."';");

		$cartellaImmagini = Parametri::$cartellaImmaginiContenuti;
		
		if (count($res) > 0)
		{
			if ($clean['is_main'] === 1 or $this->m[$this->modelName]->recordExists($clean['id_page']))
			{
				if (!empty($_FILES)) {
					if ($_FILES["Filedata"]["size"] <= Parametri::$maxUploadSize)
					{
						$tempFile = $_FILES['Filedata']['tmp_name'];
						
						$extArray = explode('.', $_FILES['Filedata']['name']);
						$ext = strtolower(end($extArray));
						
						$targetPath = $this->parentRootFolder."/".$cartellaImmagini;
						
						array_pop($extArray);
						
						$tempName = encodeUrl(implode(".",$extArray)).".$ext";
// 						$tempName = $this->m[$this->modelName]->getAlias($clean['id_page'])."_".generateString(1);
						
						$tree = new Files_Upload($targetPath);
						$clean['fileName'] = strcmp($tempName,"") !==0 ? $tree->getUniqueName($tempName) : $tree->getUniqueName("file".".$ext");
						
						$targetFile = rtrim($targetPath,'/') . '/' . $clean['fileName'];
						
						// Validate the file type
						$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
						
						if (in_array($ext,$fileTypes)) {
							if (!file_exists($targetFile))
							{
								if (@move_uploaded_file($tempFile,$targetFile))
								{
									$params = array(
										'imgWidth'		=>	3000,
										'imgHeight'		=>	3000,
										'defaultImage'	=>  null,
									);
									$thumb = new Image_Gd_Thumbnail($targetPath,$params);
									$thumb->render($clean['fileName'],$targetFile);
									
									if ($clean['is_main'] === 0)
									{
										$this->m['ImmaginiModel']->values = array('immagine'=>$clean['fileName'],'id_page'=>$clean['id_page']);
										$this->m['ImmaginiModel']->insert();
									}
									
									echo $clean['fileName'];
								}
							}
						} else {
							echo 'KO';
						}
					}
				}
			}
		}
	}
	
	public function esportaprodotti()
	{
		$this->clean();
		
		$clean["idShop"] = $this->m["CategoriesModel"]->getShopCategoryId();
		
		$childrenProdotti = $this->m["CategoriesModel"]->children($clean["idShop"]);
		
		$pagine = $this->m["PagesModel"]->clear()->select("categories.*,pages.*,c2.*")->inner("categories")->on("pages.id_c = categories.id_c")->left("categories as c2")->on("c2.id_c = categories.id_p")->where(array(
			"pages.principale"	=>	"Y",
			"in" => array("-id_c" => $childrenProdotti),
		))->orderBy("c2.title,categories.title,pages.title")->send();
		
		$strutturaCSV = array();
		
		$listaAttributiValori = $this->m["AttributivaloriModel"]->clear()->toList("id_av","titolo")->send();
		
		$listaAttributiValori = htmlentitydecodeDeep($listaAttributiValori);
		
		foreach ($pagine as $p)
		{
			$p["pages"] = htmlentitydecodeDeep($p["pages"]);
			$p["categories"] = htmlentitydecodeDeep($p["categories"]);
			
			$clean['id'] = (int)$p["pages"]["id_page"];
			
			$rigaCSVStandard = array(
				"CATEGORIA 1"	=>	$p["c2"]["title"],
				"CATEGORIA 2"	=>	$p["categories"]["title"],
				"TITOLO"	=>	$p["pages"]["title"],
				"DESCRIZIONE"	=>	strip_tags($p["pages"]["description"]),
// 				"DETTAGLI"	=>	strip_tags($p["pages"]["dettagli"]),
				"IN PROMOZIONE"	=>	$p["pages"]["in_promozione"],
				"PROMOZIONE"	=>	$p["pages"]["in_promozione"] == "Y" ? $p["pages"]["prezzo_promozione"] . "%" : 0,
				"PROMOZIONE_DAL"	=>	$p["pages"]["dal"] != "0000-00-00" ? date("d/m/Y",strtotime($p["pages"]["dal"])) : "",
				"PROMOZIONE_AL"	=>	$p["pages"]["al"] != "0000-00-00" ? date("d/m/Y",strtotime($p["pages"]["al"])) : "",
				"IN EVIDENZA"	=>	$p["pages"]["in_evidenza"],
			);
			
			$colonne = $this->m["PagesattributiModel"]->getNomiColonne($clean['id']);
			
			$this->m['CombinazioniModel']->creaColonne($clean['id']);
			
			$result = $this->m['CombinazioniModel']->clear()->where(array('id_page'=>$clean['id']))->orderBy("id_order")->send();
			
			if (count($result) > 0)
			{
				foreach ($result as $r)
				{
					$rigaCSV = $rigaCSVStandard;
					
					$combinazione = $r["combinazioni"];
					
					$indice = 1;
					
					foreach (array("col_1","col_2") as $colName)
					{
						if (isset($colonne[$colName]))
						{
							$rigaCSV["VARIANTE $indice"] = strtoupper($colonne[$colName]);
							$rigaCSV["VALORE $indice"] = isset($listaAttributiValori[$combinazione[$colName]]) ? $listaAttributiValori[$combinazione[$colName]] : "--";
						}
						else
						{
							$rigaCSV["VARIANTE $indice"] = "--";
							$rigaCSV["VALORE $indice"] = "--";
						}
						
						$indice++;
					}
					
					$rigaCSV["CODICE"] = $combinazione["codice"];
// 					$rigaCSV["CONFEZIONE"] = $combinazione["confezione"];
					$rigaCSV["PESO"] = $combinazione["peso"];
					$rigaCSV["PREZZO"] = $combinazione["price"];
					
					$strutturaCSV[] = $rigaCSV;
				}
			}
			else
			{
				$rigaCSV = $rigaCSVStandard;
				
				$rigaCSV["VARIANTE 1"] = "--";
				$rigaCSV["VALORE 1"] = "--";
				$rigaCSV["VARIANTE 2"] = "--";
				$rigaCSV["VALORE 2"] = "--";
				$rigaCSV["CODICE"] = $p["pages"]["codice"];
// 				$rigaCSV["CONFEZIONE"] = $p["pages"]["confezione"];
				$rigaCSV["PESO"] = $p["pages"]["peso"];
				$rigaCSV["PREZZO"] = $p["pages"]["price"];
				
				$strutturaCSV[] = $rigaCSV;
			}
			
// 			print_r($this->m['CombinazioniModel']->colonne);
// 			print_r($colonne);
// 			print_r($result);
		}
		
		if (count($strutturaCSV) > 0)
		{
			$html = "<tr><td>".implode("</td><td>", array_keys($strutturaCSV[0]))."</td></tr>";
			
			foreach ($strutturaCSV as $rigaCSV)
			{
				$html .= "<tr><td>".implode("</td><td>", array_values($rigaCSV))."</td></tr>";
			}
			
			header('Content-disposition: attachment; filename='.date("Y-m-d_H_i_s")."_esportazione_prodotti.xls");
			header('Content-Type: application/vnd.ms-excel');
			
			echo "<table>$html</table>";
		}
	}
}
