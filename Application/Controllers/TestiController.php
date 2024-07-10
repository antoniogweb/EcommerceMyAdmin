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

class TestiController extends BaseController {
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		$this->argKeys = array(
			'page:forceInt'=>1,
			'id_t:sanitizeAll'=>'tutti',
			'chiave:sanitizeAll'=>'tutti',
			'part:sanitizeAll'=>'tutti',
			'lingua:sanitizeAll'=>'tutti',
			'tipo_testo:sanitizeAll'=>'tutti',
			'id_cont:sanitizeAll'=>'tutti',
		);
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		Params::$rewriteStatusVariables = false;
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("thumb","testi.tipo", "testi.chiave", "lingua");
		$this->mainHead = "Thumb,Tipo,Titolo,Lingua";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'');
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'120px',
			),
		);
		
		$filtroLingua = array("tutti" => "LINGUA") + $this->m[$this->modelName]->selectLingua();
		$filtroTipo = array("tutti" => "TIPO") + array(
			"TESTO"		=>	"Testo",
			"IMMAGINE"	=>	"Immagine",
			"LINK"		=>	"Link",
			"VIDEO"		=>	"Video",
		);
		
		$this->filters = array('chiave',array("tipo_testo","",$filtroTipo), array("lingua","",$filtroLingua));
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"lk"		=>	array('chiave' => $this->viewArgs['chiave']),
					"lingua"	=>	$this->viewArgs['lingua'],
					"tipo"	=>	$this->viewArgs['tipo_testo'],
					"id_cont"	=>	$this->viewArgs['id_cont'],
				))
				->orderBy("id_t desc")->convert()->save();
		
		parent::main();
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
						$fields = "editor_visuale,valore";
						
						if (v("attiva_tag_in_testi"))
							$fields .= ",tag_elemento,attributi";
						
						break;
					
					case "IMMAGINE":
						$fields = "immagine,immagine_2x,width,height,crop,alt,url_link,id_contenuto,target_link,testo_link,attributi,id_categoria,link_id_documento";
						break;
					
					case "LINK":
						$fields = "testo_link,url_link,id_contenuto,target_link,attributi,id_categoria,link_id_documento";
						break;
					
					case "VIDEO":
						$fields = "immagine,immagine_2x,width,height,crop,alt,url_link,attributi";
						break;
				}
				
				$templateElemento = Tema::getSelectElementi("Contenuti/Elementi/Widget/".ucfirst(strtolower($record["tipo"])));
				
				if (count($templateElemento) > 0)
				{
					$fields .= ",template";
				}
			}
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		// Lo imposto come salvato manualmente
		// Salvo la data della traduzione
		$this->m[$this->modelName]->setSalvatoEDataTraduzione();
		
		parent::form($queryType, $id);
		
		$data["editor_visuale"] = $this->getUsaEditorVisuale($queryType, $id);
		
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
