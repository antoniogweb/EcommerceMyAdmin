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

class GenericmenuController extends BaseController {

	public $mod = null;
	
	public $orderBy = "id_order";
	
// 	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->session('admin');
		$this->model();
		$this->mod = $this->m[$this->modelName];
		
		$this->setArgKeys(array(
			'page:forceInt'=>1,
			'title:sanitizeAll'=>'tutti',
			'token:sanitizeAll'=>'token',
			'lingua:sanitizeAll'=>'it',
			'partial:sanitizeAll' => "tutti",
			'nobuttons:sanitizeAll' => "tutti",
		));

// 		$data["sezionePannello"] = "utenti";
		
		$data["titoloMenu"] = $this->mod->titoloMenu;
		$this->append($data);
		
		$this->model("LingueModel");
	}

	protected function main()
	{
		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
		$this->s['admin']->check();
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		callFunction($this->modelName."::setControllerName", $this->controller, $this->modelName."::setControllerName");
		callFunction($this->modelName."::setActionName", $this->action, $this->modelName."::setActionName");
		callFunction($this->modelName."::setViewStatus", $this->viewStatus, $this->modelName."::setViewStatus");
		
		$params = array(
			'popup'=>true,
			'popupType'=>'inclusive',
			'recordPerPage'=>200,
			'mainMenu'=>'add,refresh',
			);
		
		$this->loadScaffold('main',$params);
		
		$this->scaffold->loadMain('[[checkbox]];'.$this->mod->getTableN().'.id_m;,'.$this->modelName.'.indentList|'.$this->mod->getTableN().'.id_m',$this->mod->getTableN().'.id_m','ldel,ledit');

		$this->scaffold->update('del');
		
		$this->m[$this->modelName]->bulkAction("del");
		
		if (isset($_GET["action"]) and strcmp($_GET["action"],"aggiorna") === 0)
		{
			$this->scaffold->model->updateAllLinksAlias();
		}
		
		$this->scaffold->setHead('[[bulkselect:checkbox_'.$this->mod->getTableN().'_id_m]],TITOLO,,');
		
		$this->scaffold->itemList->setBulkActions(array(
			"checkbox_".$this->mod->getTableN()."_id_m"	=>	array("del","Elimina selezionati","confirm"),
		));
		
		$this->scaffold->mainMenu->links['add']['url'] = 'form/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci una nuova voce di menù';
		
		$this->scaffold->mainMenu->links['refresh']['url'] = 'main';
		$this->scaffold->mainMenu->links['refresh']['class'] = 'mainMenuItem';
// 		$this->scaffold->mainMenu->links['refresh']['text'] = 'Aggiorna';
		$this->scaffold->mainMenu->links['refresh']['queryString'] = '&action=aggiorna';
		$this->scaffold->mainMenu->links['refresh']['icon'] = $this->baseUrl.'/Public/Img/Icons/view-refresh.png';
		$this->scaffold->mainMenu->links['refresh']['title'] = 'aggiorna i link dei menù (necessario se hai modificato gli ALIAS di qualche documento)';
			
		$this->scaffold->model->clear()->where(array(
			"ne" => array("id_m" => "1"),
			"lingua"	=>	$this->viewArgs['lingua'],
		));
		
		if (strcmp($this->viewArgs['title'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('n!'.$this->mod->getTableN().'.title' => $this->viewArgs['title']),
			);

			$this->scaffold->model->aWhere($where);
		}
		
		callFunction($this->modelName."::setOrderWhere", serialize($this->scaffold->model->where), $this->modelName."::setOrderWhere");
		
		if (isset($_POST["moveupAction"]) or isset($_POST["movedownAction"]))
		{
			$clean["id"] = $this->request->post("id_m",0,"forceInt");
			$oldWhere = $this->scaffold->model->where;
			$rowData = $this->scaffold->model->selectId($clean["id"]);
			$orderType = $this->scaffold->model->orderType;
			$this->scaffold->model->aWhere(array("id_p"=>$rowData["id_p"]))->orderBy("id_order $orderType");
			$this->scaffold->update('moveup,movedown');
			$this->scaffold->model->where = $oldWhere;
		}
		
		$this->scaffold->model->orderBy("lft asc");
		
		$this->scaffold->itemList->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			null,
		);
		
		$data['scaffold'] = $this->scaffold->render();
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$data['recordPerPage'] = $this->scaffold->params["recordPerPage"];
		$data["filtri"] = $this->scaffold->itemList->createFilters();
		
		$data['notice'] = $this->scaffold->model->notice;
		
		$this->append($data);
		$this->load('menu_main');
	}

	protected function form($queryType = 'insert',$id = 0)
	{
		$this->shift(2);
		
		$qAllowed = array("insert","update");
		
		if (in_array($queryType,$qAllowed))
		{
			$clean['id'] = (int)$id;
			
			$this->m[$this->modelName]->setFields('title,id_p,link_to,link_alias,id_c,id_page,id_marchio,id_tag,file_custom_html,active_link','sanitizeAll');
			
			if ($queryType == "insert")
				$this->m[$this->modelName]->setValue("lingua", $this->viewArgs["lingua"]);
			
			$this->s['admin']->check();
// 			if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
			$this->m[$this->modelName]->updateTable('insert,update',$clean['id']);
			
			if ($this->m[$this->modelName]->queryResult and $queryType === "insert")
			{
				$lId = $this->m[$this->modelName]->lId;
				$this->redirect($this->controller."/form/update/".$this->m[$this->modelName]->lId.$this->viewStatus."&insert=ok");
			}

			$this->m[$this->modelName]->setFormStruct();
			
			$params = array(
				'formMenu'=>"back,save",
			);
			
			$this->loadScaffold('form', $params);
			$this->scaffold->loadForm($queryType,$this->controller."/form/$queryType/".$clean['id']);
			
			if ($queryType === "update")
			{
				$this->m[$this->modelName]->form->entry["id_p"]->options = $this->m[$this->modelName]->buildSelect($clean['id']);
			}
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);

			if (isset($_GET["insert"]))
			{
				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
			}
			$data['scaffold'] = $this->scaffold->render();
			
			$data['menu'] = $this->scaffold->html['menu'];
			$data['main'] = $mainContent = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
			
			$this->append($data);
			$this->load('menu_form');
		}
	}
	
	public function ordina()
	{
		parent::ordinaGerarchico();
	}
	
}
