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
	public $metaQueryFields = 'meta_title,keywords,meta_description,template,add_in_sitemap,priorita_sitemap';
	
	public $orderBy = "pages.id_order";
	
	public $formFields = null;
	
	public $tabContenuti = array();
	public $tabCaratteristiche = array();
	public $tabSezioni = array();
	
	public $section = null;
	
	public $baseArgsKeys = array(
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
		'id_tag:sanitizeAll' => "tutti",
		'id_tipo_car:sanitizeAll' => "tutti",
		'id_pcorr:sanitizeAll' => "tutti",
		'pcorr_sec:sanitizeAll' => "tutti",
		'cl_on_sv:sanitizeAll' => "tutti",
		'nobuttons:sanitizeAll' => "tutti",
		'lingua_page:sanitizeAll' => "tutti",
		'lingua_page_escl:sanitizeAll' => "tutti",
		'imm_1:sanitizeAll' => "tutti",
	);
	
	protected $_posizioni = array(
		"main"		=>	null,
		"immagini"	=>	null,
		"prod_corr"	=>	null,
		"attributi"	=>	null,
		"caratteristiche"	=> null,
		"meta"	=> null,
		"contenuti"	=> null,
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
// 		$this->load('header_sito');
// 		$this->load('footer','last');

		$this->session('admin');
		$this->model();
		$this->model("PagesModel");
		
		$data['posizioni'] = $this->_posizioni;
		$data['sezione'] = $clean["section"] = $this->section = sanitizeAll($this->m[$this->modelName]->hModel->section); 

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
		$this->model("TipologiecaratteristicheModel");
		$this->model("PagespagesModel");
		$this->model("SectionssectionsModel");
		$this->model("FeedbackModel");
		$this->model("PagesregioniModel");
		$this->model("PageslingueModel");
		
		// Estraggo tutte le tab dei contenuti
		$data["tabContenuti"] = $this->tabContenuti = $this->m["TipicontenutoModel"]->clear()->where(array(
			"section" => $clean["section"],
		))->orderBy("id_order")->toList("id_tipo", "titolo")->send();
		
		// Estraggo tutte le tab delle caratteristiche
		if (v("caratteristiche_in_tab_separate"))
			$data["tabCaratteristiche"] = $this->tabCaratteristiche = $this->m["TipologiecaratteristicheModel"]->clear()
				->select("id_tipologia_caratteristica,coalesce(NULLIF(nota_interna, ''),titolo) as titolo_tab")
				->orderBy("tipologie_caratteristiche.id_order")
				->toList("id_tipologia_caratteristica", "aggregate.titolo_tab")
				->send();
		
		$data["tabSezioni"] = $this->tabSezioni = $this->m["SectionssectionsModel"]->clear()->where(array(
			"in_section"	=>	sanitizeAll($this->section),
		))->toList("sections_sections.section", "sections_sections.titolo")->send();
		
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
			$this->m[$this->modelName]->controllaElementoInSitemap($clean['id']);
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
	
	protected function pmain()
	{
		parent::main();
	}
	
	public function main()
	{
		if (v("attiva_cache_prodotti") && empty($_POST))
			Cache::$cachedTables = array("pages", "categories", "contenuti_tradotti", "fatture");
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->shift();
		
		if (!partial())
		{
			$this->viewArgs["tipocontenuto"] = "tutti";
			$this->viewArgs["id_tipo_car"] = "tutti";
			$this->viewArgs["pcorr_sec"] = "tutti";
			
			$this->buildStatus();
		}
		
		Params::$nullQueryValue = 'tutti';
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$this->m[$this->modelName]->updateTable('del');
		
		$bulkQueryActions = "del";
		
		if ($this->viewArgs["id_pcorr"] != "tutti")
			$bulkQueryActions = "aggiungiaprodotto";
		
		$this->m[$this->modelName]->bulkAction($bulkQueryActions);
		
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
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>v("numero_per_pagina_pages"), 'mainMenu'=>$this->mainMenu));
		
		if ($this->addTraduzioniInMain)
		{
			foreach (self::$traduzioni as $codiceLingua)
			{
				$this->tableFields[] = "link".str_replace("-","",$codiceLingua);
				$this->head .= ",".strtoupper($codiceLingua);
			}
		}
		
		$azioni = "ldel,ledit";
		if (strstr($this->orderBy, "id_order") && v("mostra_pulsanti_ordinamenti"))
			$azioni = "moveup,movedown,ldel,ledit";
		
		if ($this->viewArgs["id_pcorr"] != "tutti")
			$azioni = "ledit";
		
		$this->scaffold->loadMain($this->tableFields,'pages:id_page',$azioni);
		
		$this->scaffold->setHead($this->head);
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci un nuovo prodotto';
		
		$this->scaffold->fields = "distinct pages.codice_alfa,categories.*,pages.*,marchi.titolo";
		$this->scaffold->model->clear()->restore(true)->inner("categories")->using("id_c")->left(array("marchio"))->orderBy($this->orderBy);
		
		$where = array(
			'attivo'		=>	$this->viewArgs['attivo'],
			'in_evidenza'	=>	$this->viewArgs['in_evidenza'],
			'in_promozione'	=>	$this->viewArgs['in_promozione'],
			'id_marchio'	=>	$this->viewArgs['-id_marchio'],
		);
		
		$this->scaffold->model->aWhere($where);
		
		//add the where clause to get only the pages of that category
// 		print_r($this->m[$this->modelName]->hModel->getChildrenSectionWhere());
		$this->scaffold->model->aWhere($this->m[$this->modelName]->hModel->getChildrenSectionWhere());
		
		if (strcmp($this->viewArgs['title'],'tutti') !== 0)
		{
			$where = array(
				"OR"	=> array(
					"lk" => array('n!pages.title' => $this->viewArgs['title']),
					" lk" => array('n!pages.codice' => $this->viewArgs['title']),
					)
			);

			$this->scaffold->model->aWhere($where);
		}
		
		$data["sId"] = 0;
		
		if (strcmp($this->viewArgs['id_tag'],'tutti') !== 0)
		{
			$this->scaffold->model->inner(array("tag"))->aWhere(array(
				"pages_tag.id_tag"	=>	$this->viewArgs['id_tag'],
			));
		}
		
		if (strcmp($this->viewArgs['lingua_page'],'tutti') !== 0)
		{
			$this->scaffold->model->left("pages_lingue as lingue_includi")->on("pages.id_page = lingue_includi.id_page and lingue_includi.includi = 1");
			
			if ($this->viewArgs['lingua_page'] == "tutte")
				$this->scaffold->model->sWhere("lingue_includi.id_page is null");
			else
				$this->scaffold->model->sWhere("lingue_includi.lingua = '".$this->viewArgs['lingua_page']."'");
		}
		
		if (strcmp($this->viewArgs['lingua_page_escl'],'tutti') !== 0)
		{
			$this->scaffold->model->left("pages_lingue as lingue_escludi")->on("pages.id_page = lingue_escludi.id_page and lingue_escludi.includi = 0");
			
			$this->scaffold->model->sWhere("lingue_escludi.lingua = '".$this->viewArgs['lingua_page_escl']."'");
		}
		
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
		
		if ($this->viewArgs["id_pcorr"] != "tutti")
		{
			$this->scaffold->model->sWhere("pages.id_page not in (select id_corr from pages_pages where id_page = ".(int)$this->viewArgs["id_pcorr"].")");
			
			$this->scaffold->itemList->setBulkActions(array(
				"++checkbox_pages_id_page"	=>	array("aggiungiaprodotto","Aggiungi al prodotto"),
			));
		}
		else
			$this->scaffold->itemList->setBulkActions(array(
				"++checkbox_pages_id_page"	=>	array("del","Elimina selezionati","confirm"),
			));
		
		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = $this->colProperties;
		
		$this->scaffold->itemList->setFilters($this->filters);
		
		$data['scaffold'] = $this->scaffold->render();
// 		print_r ($this->scaffold->model->db->queries);
		
		if (v("usa_transactions"))
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
	
	public function ordinacaratteristiche()
	{
		$this->modelName = "PagescarvalModel";
		
		parent::ordina();
	}
	
	public function ordinavalori()
	{
		$this->orderBy = "id_order";
		
		$this->modelName = "CaratteristichevaloriModel";
		
		parent::ordina();
	}
	
	public function ordinacorrelate()
	{
		$this->orderBy = "id_order";
		
		$this->modelName = "PagespagesModel";
		
		parent::ordina();
	}
	
	public function ordinafeedback()
	{
		$this->orderBy = "id_order";
		
		$this->modelName = "FeedbackModel";
		
		parent::ordina();
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
		
		if (v("attiva_codice_js_pagina"))
			$this->metaQueryFields .= ",codice_js";
		
		$this->m[$this->modelName]->setFields($this->metaQueryFields,'sanitizeAll');
		$this->m[$this->modelName]->setValue("meta_modificato", 1);
		
		$data["type"] = "meta";
		
		$this->m[$this->modelName]->updateTable('update',$clean["id"]);
		
		$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
		
		$params = array(
			'formMenu'=>'back,copia,save',
		);
		
		$this->m[$this->modelName]->setFormStruct();
		
		$this->loadScaffold('form',$params);
		$this->scaffold->loadForm("update",$this->applicationUrl.$this->controller."/meta/".$clean["id"]);
		
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
		if (v("attiva_cache_prodotti") && empty($_POST))
			Cache::$cachedTables = array("pages", "categories", "contenuti_tradotti", "fatture");
		
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
		$sectionDetail = $data["sectionCampiAggiuntivi"] = $data["section"]."_detail";
		
		if ($queryType === "insert" or $this->m[$this->modelName]->principale($clean['id']))
		{
			if (in_array($queryType,$qAllowed))
			{
				if (defined("CAMPI_AGGIUNTIVI_PAGINE") && isset(CAMPI_AGGIUNTIVI_PAGINE[$sectionDetail]))
				{
					foreach (CAMPI_AGGIUNTIVI_PAGINE[$sectionDetail] as $campo => $form)
					{
						$this->queryFields .= ",$campo";
						
						$this->m[$this->modelName]->formStructAggiuntivoEntries[$campo] = $form;
					}
				}
				
				// Campi aggiuntivi dalle APP
				if (isset(PagesModel::$campiAggiuntivi[$sectionDetail]))
				{
					foreach (PagesModel::$campiAggiuntivi[$sectionDetail] as $campo => $form)
					{
						$this->queryFields .= ",$campo";
						
						if (!empty($form))
							$this->m[$this->modelName]->formStructAggiuntivoEntries[$campo] = $form;
					}
				}
				
				$this->m[$this->modelName]->setFields($this->queryFields,'sanitizeAll');
				
				$this->m[$this->modelName]->updateTable('insert,update',$clean['id']);
				
				if ($this->viewArgs["cl_on_sv"] == "Y" && $this->m[$this->modelName]->queryResult)
					$data["closeModal"] = $this->closeModal = true;
				
				if (isset($_POST["gAction"]))
				{
					$this->m[$this->modelName]->result = false;
				}
			
				if ($this->m[$this->modelName]->queryResult and $queryType === "insert")
				{
					$lId = $this->m[$this->modelName]->lId;
					
					// Collego il prodotto
					if (isset($_GET["id_pcorr"]) && isset($_GET["pcorr_sec"]))
						$this->m[$this->modelName]->aggiungiaprodotto($lId);
						
					if ($this->viewArgs["cl_on_sv"] != "Y")
					{
						flash("notice",$this->m[$this->modelName]->notice);
						
						$this->redirect($this->applicationUrl.$this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus);
					}
				}
				
				if (strcmp($queryType,'update') === 0 and $this->m[$this->modelName]->queryResult)
				{
					flash("notice",$this->m[$this->modelName]->notice);
					
					$this->redirect($this->applicationUrl.$this->controller.'/form/update/'.$clean["id"].$this->viewStatus);
				}
				
				$this->m[$this->modelName]->setFormStruct($clean['id']);
				
				$this->m[$this->modelName]->setUploadForms($clean["id"]);
				
				$this->menuLinks = 'back,save';
				if ($queryType === "update")
					$this->menuLinks = 'back,copia,save';
				
				$this->getTabViewFields("form");
				
				$params = array(
					'formMenu'=>$this->menuLinks,
				);
				
				$this->loadScaffold('form',$params);
				$this->scaffold->loadForm($queryType,$this->controller."/form/$queryType/".$clean['id']);
				
				if (isset($this->disabledFields))
					$this->scaffold->model->disabilita($this->disabledFields);
					
				if (isset($this->formFields))
					$this->scaffold->model->fields = $this->formFields;
				
				$this->scaffold->getFormValues('sanitizeHtml',$clean['id'],$this->formDefaultValues);

				if (strcmp($queryType,'update') === 0)
				{
					$data["titoloPagina"] = $this->m[$this->modelName]->getSimpleTitle($clean['id']);
					
					$data["contenutiTradotti"] = $this->m["ContenutitradottiModel"]->clear()->where(array(
						"id_page"	=>	$clean['id'],
						"in"	=>	array(
							"lingua"	=>	self::$traduzioni,
						),
					))->send(false);
					
					$record = $data["dettagliPagina"] = $this->m[$this->modelName]->selectId($clean['id']);
					
					if (count($record) > 0)
					{
						$data["use_editor"] = $record["use_editor"];
						
						$options = $this->scaffold->model->form->entry["id_c"]->options;
						if (!array_key_exists($record["id_c"],$options))
						{
							$this->scaffold->model->form->entry["id_c"]->options[$record["id_c"]] = $this->m[$this->modelName]->hModel->indent($record["id_c"], false, false, false);
						}
						
						$data["altreCategorie"] = $this->m[$this->modelName]->clear()->select("categories.*,pages.id_page")->inner("categories")->using("id_c")->where(array("codice_alfa"=>$record["codice_alfa"],"ne"=>array("id_page" => $clean['id'])))->orderBy("categories.lft")->send();
						
						if ($record["tipo_pagina"] == "HOME")
							$data["urlPagina"] = "";
						else
							$data["urlPagina"] = $this->m["PagesModel"]->getUrlAlias($clean['id']);
						
						if (v("attiva_gestione_fasce_frontend") && !isProdotto($clean['id']))
							$data["urlPaginaEditFrontend"] = $data["urlPagina"]."?".v("token_edit_frontend")."&em_edit_frontend";
					}
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
					
					$this->m[$this->modelName]->values["principale"] = "Y";
					
					$this->m[$this->modelName]->delFields("id_page");
					$this->m[$this->modelName]->delFields("data_creazione");
					$this->m[$this->modelName]->delFields("id_order");
					
					$this->m[$this->modelName]->values["codice_alfa"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
					
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
						$this->m["PagespagesModel"]->duplica($clean['id'], $lId);
						$this->m["PagescarvalModel"]->duplica($clean['id'], $lId);
						$this->m["PagespersonalizzazioniModel"]->duplica($clean['id'], $lId);
						$this->m["PagesattributiModel"]->duplica($clean['id'], $lId);
						$this->m["CombinazioniModel"]->duplica($clean['id'], $lId);
						
						if ($data["section"] != "sedi")
							$this->m["PagesregioniModel"]->duplica($clean['id'], $lId);
						
						$this->m["PageslingueModel"]->duplica($clean['id'], $lId);
						
						// Duplico i model associati
						foreach ($this->modelAssociati as $modelAssociato => $modelParams)
						{
							if (isset($modelParams["duplica"]) && $modelParams["duplica"])
								$this->m[$modelAssociato]->duplica($clean['id'], $lId);
						}
						
						$this->redirect($this->applicationUrl.$this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus."&insert=ok");
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
		
		$this->helper("Menu",$this->applicationUrl.$this->controller,"main");
		
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
		if (v("attiva_cache_prodotti") && empty($_POST))
			Cache::$cachedTables = array("pages", "categories", "contenuti_tradotti", "fatture");
		
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
			"acquistabile"	=>	$accessori ? "N" : "Y",
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
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction, 'pageVariable'=>'page_fgl'));

		$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
		$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->scaffold->fields = "attributi.*,pages_attributi.*";
		$this->scaffold->loadMain('titoloConNota','pages_attributi:id_pa','moveup,movedown,del');
		$this->scaffold->setHead('Variante');
		
		$this->scaffold->model->clear()->inner("attributi")->on("attributi.id_a = pages_attributi.id_a")->orderBy("pages_attributi.id_order")->where(array("n!pages_attributi.id_page"=>$clean['id']));
		
		$this->scaffold->update('moveup,movedown');
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$data['scaffold'] = $this->scaffold->render();
		
		$data['numeroAttributi'] = $this->scaffold->model->rowNumber();
		
		$resAttributi = $this->m['AttributiModel']->clear()->orderBy("titolo")->send();
		
		$data["listaAttributi"] = array();
		
		foreach ($resAttributi as $rowAttr)
		{
			$selectVal = $rowAttr["attributi"]["nota_interna"] ? $rowAttr["attributi"]["titolo"]. " (".$rowAttr["attributi"]["nota_interna"].")" : $rowAttr["attributi"]["titolo"];
			$data["listaAttributi"][$rowAttr["attributi"]["id_a"]] = $selectVal;
		}
		
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
				$this->redirect($this->applicationUrl.$this->controller."/attributi/$id".$this->viewStatus."&refresh=y#refresh_link");
			}
			else if (strcmp($_GET["action"],"del_comb") === 0)
			{
				$clean["id_c"] = $this->request->get("id",0,"forceInt");
				$this->m["CombinazioniModel"]->del($clean["id_c"]);
				$this->redirect($this->applicationUrl.$this->controller."/attributi/$id".$this->viewStatus."&refresh=y#refresh_link");
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
		
		if (v("immagine_in_varianti"))
			$this->h['List']->addItem("text","<img class='immagine_variante' src='".$this->baseUrl."/thumb/immagineinlistaprodotti/0/;combinazioni.immagine;' /><img id=';combinazioni.id_c;' title='modifica immagine' class='img_attributo_aggiorna immagine_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><img class='attributo_loading align_middle' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' />");
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;combinazioni.codice;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='codice' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		$campoPrice = "price";
		
		if (v("prezzi_ivati_in_prodotti"))
			$campoPrice = "price_ivato";
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;setPriceReverse|combinazioni.$campoPrice;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='price' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		$this->h['List']->addItem("text","<span class='valore_attributo'>;setPriceReverse|combinazioni.peso;</span><img title='modifica valore' class='img_attributo_aggiorna attributo_event' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/edit.png'/><div class='edit_attrib_box'><input class='update_attributo' type='text' name='update_attributo' value='' /><img title='conferma modifica' id=';combinazioni.id_c;' rel='peso' class='attributo_edit' src='".$this->baseUrl."/Public/Img/Icons/view-refresh.png'/><img title='annulla modifica' class='attributo_close' src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/clear_filter.png'/><img class='attributo_loading' src='".$this->baseUrl."/Public/Img/Icons/loading4.gif' /></div>");
		
		foreach ($this->m['CombinazioniModel']->colonne as $col)
		{
			$this->h['List']->addItem("text",";AttributivaloriModel.getName|combinazioni.$col;");
		}
		
// 		$this->h['List']->addItem("text","<a class='del_row' href='".$this->baseUrl."/".$this->controller."/attributi/".$clean['id'].$this->viewStatus."&action=del_comb&id=;combinazioni.id_c;#refresh_link'><img src='".$this->baseUrl."/Public/Img/Icons/elementary_2_5/delete.png' /></a>");
		
		$colonne = $this->m["PagesattributiModel"]->getNomiColonne($clean["id"]);
		
// 		$this->h['List']->addItem('delForm','pages/attributi/'.$clean['id'],';combinazioni.id_c;');
		
		if (v("immagine_in_varianti"))
			$head = "Immagine,Codice,Prezzo,Peso";
		else
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
		
		if (v("attiva_gruppi_contenuti"))
		{
			$this->mainFields[] = "accessi";
			$this->mainHead .= ",Accessi";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"contenuti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->orderBy("contenuti.id_order")->where(array(
			"id_page"	=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua"],
			"id_tipo"	=>	$this->viewArgs["tipocontenuto"],
			"lk"		=>	array("contenuti.titolo" => $this->viewArgs["titolo_contenuto"]),
			"tipo"		=>	"FASCIA",
		))->convert();
		
		if (v("filtra_fasce_per_tema"))
			$this->m[$this->modelName]->aWhere(array(
				"contenuti.tema"	=>	sanitizeDb(v("theme_folder")),
			));
		
		$this->m[$this->modelName]->save();
		
		$this->tabella = gtext("prodotti");
		
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
		
		$this->filters = array(null,"titolo_contenuto","imm_1");
		
		if ((int)count($this->tabContenuti) === 0)
			$this->filters[] = array("tipocontenuto","",$filtroTipo);
		
		$this->filters[] = array("lingua","",$filtroLingua);
		
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		$this->mainFields = array("titoloContenuto","contenuti.immagine_1");
		$this->mainHead = "Titolo,File";
		
		if ((int)count($this->tabContenuti) === 0)
		{
			$this->mainFields[] = "tipi_contenuto.titolo";
			$this->mainHead .= ",Tipo";
		}
		
		$this->mainFields[] = "lingua";
		$this->mainFields[] = "attivo";
		$this->mainHead .= ",Visibile su lingua,Attivo";
		
		if (v("attiva_gruppi_contenuti"))
		{
			$this->mainFields[] = "accessi";
			$this->mainHead .= ",Accessi";
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"testi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->orderBy("contenuti.id_order")->where(array(
			"id_page"	=>	$clean['id'],
			"lingua"	=>	$this->viewArgs["lingua"],
			"id_tipo"	=>	$this->viewArgs["tipocontenuto"],
			"lk"		=>	array("contenuti.titolo" => $this->viewArgs["titolo_contenuto"]),
			"lk"		=>	array("contenuti.immagine_1" => $this->viewArgs["imm_1"]),
			"ne"		=>	array("tipo"	=>	"FASCIA"),
		))->convert()->save();
		
		$this->tabella = gtext("prodotti");
		
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
		
		$this->tabella = gtext("prodotti");
		
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
		
		$this->modelName = $this->m[$this->modelName]->documentiModelAssociato;//"DocumentiModel";
		
		if (!isset($this->m[$this->modelName]))
			$this->model($this->modelName);
		
		if (isset($_GET["pulisci_file"]) && $_GET["pulisci_file"] == "Y")
		{
			$this->m[$this->modelName]->pulisciFile();
			
			flash("notice","<div class='alert alert-success'>".gtext("Pulizia avvenuta")."</div>");
			
			$this->redirect($this->applicationUrl.$this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		}
		
		$this->m[$this->modelName]->updateTable('del');
		
		$filtroLingua = array("tutti" => gtext("VEDI TUTTO")) + array("tutte" => gtext("TUTTE LE LINGUE")) + $this->m[$this->modelName]->selectLingua();
		$filtroTipoDoc = array("tutti" => gtext("VEDI TUTTO")) + $this->m[$this->modelName]->selectTipo("ecludi ");
		
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		if (v("attiva_immagine_in_documenti"))
		{
			$this->filters = array(null,null,"titolo_documento", null, array("lingua_doc","",$filtroLingua));
			$this->mainFields = array("immagine","titoloDocumento","filename","lingua");
			$this->mainHead = "Thumb,Titolo,File,Visibile su lingua";
			
			$this->colProperties = array(
				array(
					'width'	=>	'60px',
				),
				array(
					'width'	=>	'160px',
				),
			);
		}
		else
		{
			$this->filters = array(null,"titolo_documento", null, array("lingua_doc","",$filtroLingua));
			$this->mainFields = array("titoloDocumento","filename","lingua");
			$this->mainHead = "Titolo,File,Visibile su lingua";
			
			$this->colProperties = array(
				array(
					'width'	=>	'60px',
				),
			);
		}
		
		if (v("attiva_altre_lingue_documento"))
		{
			$this->filters[] = null;
			$this->mainFields[] = "escludilingua";
			$this->mainHead .= ",Escludi lingua";
		}
		
		// Traduzione documenti
		if (!v("abilita_traduzioni_documenti"))
			$this->addTraduzioniInMain = false;
		
		$this->filters[] = array("id_tipo_doc","",$filtroTipoDoc);
		$this->mainFields[] = "tipi_documento.titolo";
		$this->mainHead .= ",Tipo";
		
		if (v("attiva_gruppi_documenti"))
		{
			$this->mainFields[] = "accessi";
			$this->mainHead .= ",Accessi";
		}
		
		$this->mainButtons = "ldel";
		
		$this->getTabViewFields("documenti");
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$this->mainMenuAssociati,'mainAction'=>"documenti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("distinct documenti.id_doc,documenti.*,tipi_documento.*")
			->inner(array("page"))
			->left(array("tipo"))
			->left("documenti_lingue")->on("documenti_lingue.id_doc = documenti.id_doc and documenti_lingue.includi = 1")
			->orderBy("documenti.id_order")
			->where(array(
				"id_page"	=>	$clean['id'],
				"id_tipo_doc"	=>	$this->viewArgs["id_tipo_doc"],
				"visibile"	=>	1,
				"lk"		=>	array("documenti.titolo" => $this->viewArgs["titolo_documento"]),
			))->convert();
		
		if ($this->viewArgs["lingua_doc"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"OR"	=>	array(
					"lingua"	=>	$this->viewArgs["lingua_doc"],
					"AND"	=>	array(
						"documenti_lingue.lingua"	=>	$this->viewArgs["lingua_doc"],
						"ne"	=>	array(
							"lingua"	=>	"tutte",
						),
					),
				),
			));
		}
		
		$this->m[$this->modelName]->save();
		
// 		$this->tabella = gtext("prodotti");
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function lingue($id = 0)
	{
		$this->model("LingueModel");
		
		$this->_posizioni['lingue'] = 'class="active"';
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->modelName = "PageslingueModel";
		
		$this->m[$this->modelName]->setFields('id_lingua','sanitizeAll');
		$this->m[$this->modelName]->values['id_page'] = $clean['id'];
		
		if (isset($_POST["includi"]))
			$this->m[$this->modelName]->values['includi'] = 1;
		else if (isset($_POST["escludi"]))
			$this->m[$this->modelName]->values['includi'] = 0;
		
		if (isset($_POST["includi"]) || isset($_POST["escludi"]))
			$_POST["insertAction"] = $_REQUEST["insertAction"] = 1;
		
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("lingua","tipoVisibilitaLingua");
		$this->mainHead = "Lingua,Tipo";
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainButtons = "ldel";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"lingue/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("lingua"))->where(array(
			"pages_lingue.id_page"	=>	$clean['id'],
		))->orderBy("lingue.descrizione")->convert()->save();
		
		parent::main();
		
		$data["listaLingue"] = PageslingueModel::lingueCheMancano($clean['id']);
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function paginecorrelate($id = 0)
	{
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$data["ordinaAction"] = "ordinacorrelate";
		
		$this->_posizioni['paginecorrelate'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "paginecorrelate";
		
		$this->shift(1);
		
		$this->s['admin']->check();
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
			die("non permesso");
		
		$this->modelName = "PagespagesModel";
		$this->mainButtons = 'ldel';
		
		$mainAction = "paginecorrelate/".$clean['id'];
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction,'pageVariable'=>'page_fgl');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("pages.title");
		$this->mainHead = "Titolo";
		
		$this->m[$this->modelName]->clear()
			->select("pages.*,pages_pages.*")
			->inner(array("corr"))
			->where(array(
				"id_page"	=>	$clean['id'],
			))
			->orderBy("pages_pages.id_order")->save();
		
		$this->tabella = gtext("prodotti");
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
	}
	
	public function aggiungicaratteristica($id)
	{
		$this->clean();
		
		$this->shift(1);
		
		$pagina = $this->m["PagesModel"]->selectId((int)$id);
		
		if (empty($pagina))
			die();
		
		if (!isset($_POST["titolo_car"]) || !isset($_POST["titolo_carval"]))
			die();
		
		if (!trim($_POST["titolo_car"]))
			flash("notice",wrap(gtext("Indicare una caratteristica"),array(
				"div"	=>	"alert alert-danger"
			)));
		
		if (!trim($_POST["titolo_carval"]))
			flash("notice",wrap(gtext("Indicare un valore"),array(
				"div"	=>	"alert alert-danger"
			)));
		
		if (trim($_POST["titolo_car"]) && trim($_POST["titolo_carval"]))
		{
			$_GET["id_page"] = $_GET["id_page_update"] = (int)$id;
			
			$this->m["CaratteristicheModel"]->setValues(array(
				"titolo"	=>	$_POST["titolo_car"],
				"alias"		=>	"",
			));
			
			$where = array(
				"titolo"	=>	$_POST["titolo_car"],
			);
			
			if ($this->viewArgs["id_tipo_car"] != "tutti")
			{
				$this->m["CaratteristicheModel"]->setValue("id_tipologia_caratteristica", (int)$this->viewArgs["id_tipo_car"]);
				
				$where["id_tipologia_caratteristica"] = (int)$this->viewArgs["id_tipo_car"];
			}
			
			$idCar = $this->m["CaratteristicheModel"]->insertOrUpdate($where);
			
			$this->m["CaratteristichevaloriModel"]->setValues(array(
				"titolo"	=>	$_POST["titolo_carval"],
				"id_car"	=>	$idCar,
				"alias"		=>	"",
			));
			
			$idCarVal = $this->m["CaratteristichevaloriModel"]->insertOrUpdate(array(
				"id_car"	=>	$idCar,
				"titolo"	=>	$_POST["titolo_carval"],
			));
			
			if ($idCar && $idCarVal)
				flash("notice",wrap(gtext("Operazione eseguita"),array(
					"div"	=>	"alert alert-success"
				)));
		}
		
		$this->redirect($this->applicationUrl.$this->controller."/caratteristiche/".(int)$id.$this->viewStatus);
	}
	
	public function caratteristiche($id = 0)
	{
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$data["ordinaAction"] = "ordinacaratteristiche";
		
		$this->_posizioni['caratteristiche'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "caratteristiche";
		
		$this->shift(1);
		
		$this->s['admin']->check();
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
			die("non permesso");
		
		$this->modelName = "PagescarvalModel";
		$this->mainButtons = 'ldel';
		
		if (!v("nuova_modalita_caratteristiche"))
		{
			$this->m['PagescarvalModel']->setFields('id_cv,titolo,id_car','sanitizeAll');
			$this->m['PagescarvalModel']->values['id_page'] = $clean['id'];
			$this->m['PagescarvalModel']->updateTable('insert,del');
			
			if ($this->m['PagescarvalModel']->queryResult)
				$this->redirect($this->applicationUrl.$this->controller."/caratteristiche/".$clean['id'].$this->viewStatus);
		}
		
		$data["aggiuntaLibera"] = false;
		
		if (!v("immagine_in_caratteristiche") && (!v("attiva_tipologie_caratteristiche") || v("caratteristiche_in_tab_separate")))
			$data["aggiuntaLibera"] = true;
		
		$mainAction = "caratteristiche/".$clean['id'];
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction,'pageVariable'=>'page_fgl');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("caratteristiche.titolo", "edit");
		$this->mainHead = "Caratteristica,Valore";
		
		if (!v("caratteristiche_in_tab_separate") && v("attiva_tipologie_caratteristiche"))
		{
			array_unshift($this->mainFields, "tipologie_caratteristiche.titolo");
			$this->mainHead = "Tipologia,".$this->mainHead;
		}
		
		if (v("immagine_in_caratteristiche"))
		{
			$this->mainFields[] = "thumb";
			$this->mainHead .= ",Immagine";
		}
		
		$this->m[$this->modelName]->clear()->select("tipologie_caratteristiche.*,pages_caratteristiche_valori.*,caratteristiche_valori.*,caratteristiche.*")
			->inner("caratteristiche_valori")->on("caratteristiche_valori.id_cv = pages_caratteristiche_valori.id_cv")
			->inner("caratteristiche")->on("caratteristiche.id_car = caratteristiche_valori.id_car")
			->left("tipologie_caratteristiche")->on("tipologie_caratteristiche.id_tipologia_caratteristica = caratteristiche.id_tipologia_caratteristica")
			->where(array(
				"pages_caratteristiche_valori.id_page"	=>	$clean['id'],
				"tipologie_caratteristiche.id_tipologia_caratteristica"	=>	$this->viewArgs["id_tipo_car"],
			))
			->orderBy("pages_caratteristiche_valori.id_order")->save();
		
		$this->tabella = "prodotti";
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		if (!v("nuova_modalita_caratteristiche"))
		{
			$data["listaCaratteristiche"] = $this->m['CaratteristicheModel']->clear()->toList("caratteristiche.id_car","caratteristiche.titolo")->orderBy("caratteristiche.titolo")->send();
			
			$data["lastCar"] = $this->request->post("id_car",0,"forceInt");
			
			$data["listaCarattVal"] = array("0"	=>	"-- seleziona --");
		}
		
		$this->append($data);
	}
	
	public function regioni($id = 0)
	{
		$this->_posizioni['regioni'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$data['type'] = "regioni";
		
		$this->shift(1);
		
		$this->s['admin']->check();
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->m[$this->modelName]->checkPrincipale($clean['id']);
		
		if (!$this->m[$this->modelName]->modificaPaginaPermessa($clean['id']))
			die("non permesso");
		
		$this->modelName = "PagesregioniModel";
		$this->mainButtons = 'ldel';
		
		$mainAction = "regioni/".$clean['id'];
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->mainFields = array("nazioni.titolo", "regioni.titolo");
		$this->mainHead = "Nazione,Regione";
		
		$this->getTabViewFields("regioni");
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>$mainAction,'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->clear()->select("regioni.*,nazioni.*,pages_regioni.id_page_regione")
			->left(array("regione"))
			->left(array("nazione"))
			->where(array(
				"pages_regioni.id_page"	=>	$clean['id'],
			))
			->orderBy("nazioni.titolo,regioni.titolo")->save();
		
		$this->tabella = "prodotti";
		
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
			$this->redirect($this->applicationUrl.$this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		
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
		
		$this->tabella = "prodotti";
		
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
			$this->redirect($this->applicationUrl.$this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		
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
		
		$this->tabella = gtext("prodotti");
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$data["lista"] = $this->m["TagModel"]->clear()->sWhere("id_tag not in (select id_tag from pages_tag where id_page = ".$clean['id'].")")->orderBy("titolo")->toList("id_tag","titolo")->send();
		
		$this->append($data);
	}
	
	public function feedback($id = 0)
	{
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
		
		$this->ordinaAction = "ordinafeedback";
		
		$this->_posizioni['feedback'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $data['id_page'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "FeedbackModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("feedback.data_feedback", "edit", "editutente", "punteggio", "attivo", "daapprovare", "datagestione", "gestisci");
		$this->mainHead = "Data feedback,Autore,Email,Punteggio,Pubblicato,Approvazione,Data approvazione/rifiuto,";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,copia','mainAction'=>"feedback/".$clean['id'],'pageVariable'=>'page_fgl');
		
// 		$this->h["Menu"]->links['copia']['url'] = 'form/copia/'.$clean['id'];
// 		$this->h["Menu"]->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="id_page" id="'.$clean['id'].'"';
		
		$this->m[$this->modelName]->gOrderBy()->select("feedback.*,regusers.username")->left(array("utente"))->where(array(
			"id_page"	=>	$clean['id'],
// 			"is_admin"	=>	0,
		))->convert()->save();
		
		parent::main();
		
		if (v("permetti_aggiunta_feedback"))
			$data["orderBy"] = "";
		
		$data['tabella'] = "prodotti";
		
		$data["titoloRecord"] = $this->m["PagesModel"]->getSimpleTitle($clean['id']);
		
		$this->append($data);
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
		header('Content-type: application/json');
		
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
		
		$cartellaImmagini = $this->m[$this->modelName]->cartellaImmaginiContenuti;
		
		// Creo la cartella
		GenericModel::creaCartellaImages($cartellaImmagini);
		
		$res = $this->m[$this->modelName]->query("select * from adminsessions where token = '".$clean['token']."';");
		
		$result = "OK";
		$immagine = $immagine_clean = "";
		$errorString = "";
		
		if (count($res) > 0)
		{
			if ($clean['is_main'] === 1 or $this->m[$this->modelName]->recordExists($clean['id_page']))
			{
				if (!empty($_FILES))
				{
					if ($_FILES["Filedata"]["size"] <= Parametri::$maxUploadSize)
					{
						$tempFile = $_FILES['Filedata']['tmp_name'];
						
						$extArray = explode('.', $_FILES['Filedata']['name']);
						$ext = strtolower(end($extArray));
						
						$targetPath = str_replace("/admin","",LIBRARY)."/".$cartellaImmagini;
						
						array_pop($extArray);
						
						$tempNameSenzaEstensione = encodeUrl(implode(".",$extArray));
						$tempName = $tempNameSenzaEstensione.".$ext";
// 						$tempName = $this->m[$this->modelName]->getAlias($clean['id_page'])."_".generateString(1);
						
						if ($this->m[$this->modelName]->fileNameRandom)
						{
							$clean['fileName'] = md5(randString(22).microtime().uniqid(mt_rand(),true)).".$ext";
							$clean['fileName_clean'] = sanitizeHtml(basename($_FILES['Filedata']['name']));
						}
						else
						{
							$tree = new Files_Upload($targetPath);
							$clean['fileName'] = $clean['fileName_clean'] = (strcmp($tempName,"") !== 0 && strcmp($tempNameSenzaEstensione,"") !== 0) ? $tree->getUniqueName($tempName) : $tree->getUniqueName("file".".$ext");
						}
						
						$targetFile = rtrim($targetPath,'/') . '/' . $clean['fileName'];
						
						// Validate the file type
// 						$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
						$fileTypes = $this->m[$this->modelName]->fileTypeAllowed;
						
						if (in_array($ext,$fileTypes)) {
							if (!file_exists($targetFile))
							{
								if (@move_uploaded_file($tempFile,$targetFile))
								{
									if ($this->m[$this->modelName]->rielaboraImmagine)
									{
										$params = array(
											'imgWidth'		=>	3000,
											'imgHeight'		=>	3000,
											'defaultImage'	=>  null,
										);
										$thumb = new Image_Gd_Thumbnail($targetPath,$params);
										$thumb->render($clean['fileName'],$targetFile);
									}
									
									if ($clean['is_main'] === 0)
									{
										$this->m['ImmaginiModel']->values = array('immagine'=>sanitizeDb($clean['fileName']),'id_page'=>$clean['id_page']);
										$this->m['ImmaginiModel']->insert();
									}
									
									$result = "OK";
									$immagine = $clean['fileName'];
									$immagine_clean = $clean['fileName_clean'];
// 									echo json_encode(array(
// 										"result"	=>	$result,
// 										"immagine"	=>	$clean['fileName'],
// 										"immagine_clean"	=>	$clean['fileName_clean'],
// 									));
								}
								else
								{
									$result = "KO";
									$errorString = gtext("Errore nel caricamento del file");
								}
							}
							else
							{
								$result = "KO";
								$errorString = gtext("File non esistente");
							}
						} else {
							$result = "KO";
							$errorString = gtext("L'estensione del file non è permessa");
						}
					}
					else
					{
						$result = "KO";
						$errorString = gtext("La dimensione del file è superiore a ".number_format(Parametri::$maxUploadSize/1000000,0,",",".")."MB");
					}
				}
				else
				{
					$result = "KO";
					$errorString = gtext("Non è stato caricato alcun file");
				}
			}
		}
		else
		{
			$result = "KO";
			$errorString = gtext("Pagina non esistente");
		}
		
		echo json_encode(array(
			"result"	=>	$result,
			"immagine"	=>	$immagine,
			"immagine_clean"	=>	$immagine_clean,
			"error"		=>	$errorString,
		));
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
