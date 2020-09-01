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

class TestiController extends BaseController {

	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->helper('Menu','users','panel/main');
		$this->helper('Array');

		$this->session('admin');
		$this->model();

		$this->_topMenuClasses['testi'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = "sito";
		
		$this->setArgKeys(array('page:forceInt'=>1,'id_t:sanitizeAll'=>'tutti','chiave:sanitizeAll'=>'tutti','part:sanitizeAll'=>'tutti'));
		
		$this->append($data);
		
		$this->s['admin']->check();
		
		Params::$rewriteStatusVariables = false;
	}
	
	public function main() { //view all the users

		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
		$this->m["TestiModel"]->updateTable('del');
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>20));
		
		$this->scaffold->loadMain("[[ledit]];testi.id_t;,[[ledit]];testi.chiave;",'testi.id_t','ledit,del');
		
		$this->scaffold->itemList->setFilters(array('id_t','chiave'));
		
		$this->scaffold->setHead("ID,TITOLO");
		
		$this->scaffold->model->orderBy("id_t");
		
		$where = array(
			'id_t'		=>	$this->viewArgs['id_t'],
			"lk"		=>	array('chiave' => $this->viewArgs['chiave']),
		);
		
		$this->scaffold->model->where($where)->convert();
			
		$data['scaffold'] = $this->scaffold->render();
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		$data['notice'] = $this->scaffold->model->notice;
		
		$this->append($data);
		$this->load('main');
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->m[$this->modelName]->addSoftCondition("both",'checkNumeric',"width,height");
		
		if (strcmp($this->viewArgs['part'],"Y") === 0)
		{
			$_GET["partial"] = "Y";
		}
		
		$fields = "tipo";
		
		$data["tipo"] = "TESTO";
		
		if ($queryType == "update")
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($record))
			{
				$data["tipo"] = $record["tipo"];
				
				switch ($record["tipo"])
				{
					case "TESTO":
						$fields = "valore";
						break;
					
					case "IMMAGINE":
						$fields = "immagine,immagine_2x,width,height,crop,alt,url_link,id_contenuto,target_link,testo_link,attributi,id_categoria";
						break;
					
					case "LINK":
						$fields = "testo_link,url_link,id_contenuto,target_link,attributi,id_categoria";
						break;
					
					case "VIDEO":
						$fields = "immagine,immagine_2x,width,height,crop,alt,url_link,attributi";
						break;
				}
			}
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
// 	public function form($queryType = 'insert',$id = 0)
// 	{
// 		$this->shift(2);
// 		
// 		if (strcmp($this->viewArgs['part'],"Y") === 0)
// 		{
// 			$_GET["partial"] = "Y";
// 		}
// 		
// 		$this->_posizioni['main'] = 'class="active"';
// 		$data['posizioni'] = $this->_posizioni;
// 		
// 		$qAllowed = array("update");
// 		
// 		if (in_array($queryType,$qAllowed))
// 		{
// 			$clean['id'] = (int)$id;
// 			
// 			$data['type'] = $queryType;
// 			
// 			$this->m['TestiModel']->setFields('valore','sanitizeAll');
// 			
// 			$this->m['TestiModel']->updateTable('insert,update',$clean['id']);
// 
// 			if (strcmp($queryType,'insert') === 0 and $this->m[$this->modelName]->queryResult)
// 			{
// 				$lId = $this->m[$this->modelName]->lId;
// 				$this->redirect($this->controller.'/form/update/'.$lId.$this->viewStatus."&insert=ok");
// 			}
// 			
// 			$this->loadScaffold('form');
// 			$this->scaffold->loadForm($queryType,"testi/form/$queryType/".$clean['id']);
// 			
// 			$this->scaffold->getFormValues('sanitizeHtml',$clean['id']);
// 		
// 			$data['scaffold'] = $this->scaffold->render();
// 			
// 			if (isset($_GET["insert"]))
// 			{
// 				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
// 			}
// 				
// 			$data['menu'] = $this->scaffold->html['menu'];
// 			$data['main'] = $this->scaffold->html['main'];
// 			$data['notice'] = $this->scaffold->model->notice;
// 		
// 			$this->append($data);
// 			$this->load('form');
// 		}
// 	}

}
