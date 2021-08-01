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

Helper_List::$filtersFormLayout["form"]["innerWrap"] = array("", "");
Helper_List::$filtersFormLayout["form"]["attributes"] = array(
	"class"	=>	"list_filter_form list_filter_form_top",
);
Helper_List::$filtersFormLayout["submit"]["wrap"] = array("", "");

class TraduzioniController extends BaseController {
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString) {
		
		$this->argKeys = array(
			'valore:sanitizeAll'=>'tutti',
			'id_t:sanitizeAll'=>'tutti',
			'part:sanitizeAll'=>'tutti',
			'tradotta:sanitizeAll'=>'tutti',
		);
		
		parent::__construct($model, $controller, $queryString);

		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();

		$this->_topMenuClasses['pagine'] = array("panel-primary","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = "utenti";
		
// 		$this->setArgKeys(array('page:forceInt'=>1,'id_t:sanitizeAll'=>'tutti','part:sanitizeAll'=>'tutti'));
		
		$this->append($data);
		
		$this->s['admin']->check();
		
		Params::$rewriteStatusVariables = false;
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("editIt");
		$this->mainHead = "IT";
		$this->addBulkActions = false;
		
// 		$this->filters = array("valore", array("tradotta",null,array(
// 			"tutti"	=>	"Tipo",
// 			0	=>	"Da tradurre",
// 			1	=>	"Tradotti",
// 		)));
		
		$this->filters = array("valore");
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>300, 'mainMenu'=>'esporta,importa');
		
		foreach (self::$traduzioni as $codiceLingua)
		{
			$this->mainFields[] = "edit".str_replace("-","",$codiceLingua);
			$this->mainHead .= ",".strtoupper($codiceLingua);
		}
		
		$this->mainFields[] = "elimina";
		$this->mainHead .= ",";
		
		$this->mainCsvFields = array_merge(array("traduzioni.chiave"),$this->mainFields);
		$this->mainCsvHead = "Chiave (non modificare),".$this->mainHead;
		
		$this->colProperties = array();
		
		$this->mainButtons = '';
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"lingua"	=>	"it",
					"tradotta"	=>	$this->viewArgs['tradotta'],
					"lk" => array('valore' => $this->viewArgs['valore']),
					"contesto"	=>	"front",
					"gestibile"	=>	1,
				))->orderBy("id_t")->save();
		
		parent::main();
	}
	
	public function carica()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$errori = array();
		
		if (isset($_FILES['file']['tmp_name']))
		{
			if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE)
			{
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
				{
					$chiave = $data[0];
// 					
					$traduzione =  $this->m[$this->modelName]->clear()->where(array(
						"lingua"	=>	"it",
						"chiave"	=>	sanitizeDb($chiave),
						"contesto"	=>	"front",
					))->record();
					
					if (!empty($traduzione))
					{
						$indice = 1;
						
						foreach ($this->elencoLingue as $lingua => $descr)
						{
							if (isset($data[$indice]))
							{
								$traduzione =  $this->m[$this->modelName]->clear()->where(array(
									"lingua"	=>	$lingua,
									"chiave"	=>	sanitizeDb($chiave),
									"contesto"	=>	"front",
								))->record();
								
								if (!empty($traduzione))
								{
									$this->m[$this->modelName]->values = array(
										"chiave"	=>	sanitizeDb($chiave),
										"valore"	=>	sanitizeDb($data[$indice]),
										"lingua"	=>	$lingua,
										"contesto"	=>	"front",
									);
									
									if (!$this->m[$this->modelName]->update($traduzione["id_t"]))
									{
// 										print_r($this->m[$this->modelName]->values);
// 										echo $this->m[$this->modelName]->getQUery();
										$errori[] = $data[$indice]." - ".$this->m[$this->modelName]->notice;
									}
								}
							}
							
							$indice++;
						}
					}
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		$this->redirect("traduzioni/main");
	}
	
	public function aggiorna()
	{
		$this->clean();
		
		$idT = $this->request->post("id_t",0,"forceInt");
		$valore = $this->request->post("valore",0,"none");
		
		$this->m[$this->modelName]->setValues(array(
			"valore"	=>	$valore,
		));
		
		$this->m[$this->modelName]->update($idT);
	}
	
	public function elimina($id_t)
	{
		$this->clean();
		
		$record = $this->m[$this->modelName]->selectId((int)$id_t);
		
		foreach ($this->elencoLingue as $codiceLingua => $desc)
		{
			$traduzione =  $this->m[$this->modelName]->clear()->where(array(
				"lingua"	=>	$codiceLingua,
				"chiave"	=>	sanitizeDb($record["chiave"]),
			))->record();
			
			if (!empty($traduzione))
				$this->m[$this->modelName]->del($traduzione["id_t"]);
		}
	}
	
// 	public function main()
// 	{ //view all the users
// 
// 		$this->s['admin']->check("sito,admin");
// 		
// 		$this->shift();
// 
// 		Params::$nullQueryValue = 'tutti';
// 		
// 		$this->m["TraduzioniModel"]->updateTable('del');
// 		
// 		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>40));
// 		
// 		$this->scaffold->loadMain("[[ledit]];testi.id_t;,[[ledit]];testi.titolo;",'testi.id_t','ledit,del');
// 		
// 		$this->scaffold->itemList->setFilters(array('id_t','titolo'));
// 		
// 		$this->scaffold->setHead("ID,TITOLO");
// 		
// 		$this->scaffold->model->orderBy("id_t");
// 		
// 		$where = array(
// 			'id_t'		=>	$this->viewArgs['id_t'],
// 			'titolo'	=>	"lk:".$this->viewArgs['titolo'],
// 		);
// 		
// 		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
// 		
// 		$this->scaffold->model->where($where)->convert();
// 			
// 		$data['scaffold'] = $this->scaffold->render();
// // 		echo $this->scaffold->model->getQuery();
// 		
// 		$data['menu'] = $this->scaffold->html['menu'];
// 		$data['main'] = $this->scaffold->html['main'];
// 		$data['pageList'] = $this->scaffold->html['pageList'];
// 		$data['notice'] = $this->scaffold->model->notice;
// 		
// 		$this->append($data);
// 		$this->load('main');
// 		
// 	}
	
	public function form($queryType = 'insert',$id = 0)
	{
		$this->shift(2);
		
		$_GET["partial"] = "Y";
		
		$this->_posizioni['main'] = 'class="active"';
		$data['posizioni'] = $this->_posizioni;
		
		$qAllowed = array("insert","update");
		
		$clean['id'] = (int)$id;
		
		if (in_array($queryType,$qAllowed))
		{
			$data['type'] = $queryType;
			
			$fields = 'valore';
			
			$this->m['TraduzioniModel']->setFields($fields,'sanitizeAll');
			
			$this->m['TraduzioniModel']->updateTable('update',$clean['id']);
			
			$menuLinks = 'panel,copia,back,resetta,save,elimina';
			$params = array(
				'formMenu'=>$menuLinks,
			);
			
			$this->loadScaffold('form', $params);
			$this->scaffold->loadForm($queryType,"traduzioni/form/$queryType/".$clean['id']);
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);
			
			$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
			
			$data['scaffold'] = $this->scaffold->render();
			
			if (isset($_GET["insert"]))
			{
				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
			}
			
			$data['menu'] = $this->scaffold->html['menu'];
			$data['main'] = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
		
			$this->append($data);
			$this->load('form');
		}
	}

}
