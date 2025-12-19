<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

Params::$actionArray = "REQUEST";
Params::$rewriteStatusVariables = false;

trait BaseCrudController
{
	protected $_posizioni = array();
	
	public static $azioniPermesse = array("insert","update");
	public static $traduzioni = array();
	
	public $menuLinks = "back,save";
	public $menuLinksInsert = "back,save";
	public $formAction = null;
	public $formFields = null;
	public $formDefaultValues = array();
	public $functionsIfFromDb = array();
	public $formView = "form";
	public $mainView = "main";
	public $mainViewAssociati = "associati";
	public $insertRedirect = true;
	public $updateRedirect = false;
	public $updateRedirectUrl = null;
	public $id = 0;
	public $nullQueryValue = "tutti";
	public $ordinaAction = "ordina";
	public $queryActions = "del";
	public $bulkQueryActions = "del";
	public $scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'add');
	public $addBulkActions = true;
	public $bulkActions = null;
	public $addIntegrazioniInMain = true;
	public $mainButtons = 'ldel,ledit';
	public $queryActionsAfter = "";
	public $colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
	public $rowAttributes = array();
	public $inverseColProperties = array();
	public $filters = array();
	public $aggregateFilters = true;
	public $showFilters = false;
	public $menuVariable = "menu";
	public $mainShift = 0;
	
	protected function getStringaErroreValidazione()
	{
		return "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>";
	}
	
	protected function setStatusVariables()
	{
		if (isset($this->argKeys))
			$this->baseArgsKeys = array_merge($this->baseArgsKeys, $this->argKeys);
		
		$this->setArgKeys($this->baseArgsKeys);
	}
	
	protected function redirectAfterInsertUpdate($queryType = 'insert', $id = 0, $frontend = false, $queryString = "")
	{
		$clean["id"] = (int)$id;
		
		$notice = $this->m[$this->modelName]->notice;
		
		if ($frontend && !$this->m[$this->modelName]->queryResult)
			$notice = $this->getStringaErroreValidazione().$notice;
		
		if (strcmp($queryType,'insert') === 0 and $this->m[$this->modelName]->queryResult and $this->insertRedirect)
		{
			if ((isset($this->viewArgs["cl_on_sv"]) && $this->viewArgs["cl_on_sv"] != "Y") || $frontend)
			{
				$lId = $this->m[$this->modelName]->lId;
				
				flash("notice",$notice);
				
				if (isset($this->insertRedirectUrl))
					$this->redirect($this->insertRedirectUrl);
				else
					$this->redirect($this->applicationUrl.$this->controller.'/form/update/'.$lId.$this->viewStatus.$queryString);
			}
		}
		
		if (strcmp($queryType,'update') === 0 and $this->m[$this->modelName]->queryResult)
		{
			flash("notice",$notice);
			
			$queryStringOk = !$frontend ? "&insert=ok" : "";
			
			if (($this->updateRedirect or isset($_POST["redirectToList"])) && !$frontend)
				$this->redirect($this->controller.'/main/'.$this->viewStatus);
			else if ($this->updateRedirectUrl)
				$this->redirect($this->updateRedirectUrl);
			else
				$this->redirect($this->applicationUrl.$this->controller.'/'.$this->action.'/update/'.$clean["id"].$this->viewStatus.$queryStringOk);
		}
	}
	
	protected function checkAccessoPagina($queryType = 'insert', $id = 0)
	{
		if (!$this->m[$this->modelName]->checkUtente($queryType, $id))
			$this->redirect("");
	}
	
	protected function main()
	{
		$this->baseMain();
	}
	
	protected function duplicaPagina($id, $alertClass = "alert alert-danger")
	{
		$clean['id'] = (int)$id;
		
		$this->clean();
		
		$lId = $this->m[$this->modelName]->duplicaPagina($clean['id'], $this->modelAssociati);
		
		if ($lId)
		{
			flash("notice",$this->m[$this->modelName]->notice);
			
			$this->redirect($this->applicationUrl.$this->controller."/form/update/".$lId.$this->viewStatus);
		}
		else
		{
			flash("notice","<div class='$alertClass'>".gtext("Attenzione, si è verificato un errore. Si prega di contattare.")."</div>");
			
			$this->redirect($this->applicationUrl.$this->controller."/form/update/".$clean['id'].$this->viewStatus);
		}
	}
	
	protected function baseMain()
	{
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->shift($this->mainShift);
		
		if ($this->id !== 0)
		{
			$clean['id'] = $data['id'] = (int)$this->id;
			$this->mainView = $this->mainViewAssociati;
		}
		
		$data['posizioni'] = $this->_posizioni;
		
		Params::$nullQueryValue = $this->nullQueryValue;
		
		$primaryKey = $this->m[$this->modelName]->getPrimaryKey();
		$table = $this->m[$this->modelName]->table();
		
		$data["tabella"] = isset($this->tabella) ? $this->tabella : $table;
		
		$data["ordinaAction"] = $this->ordinaAction;
		
		$data['type'] = $data['queryType'] = "main";

		$data["title"] = "Gestione " . $data["tabella"];
		
		$this->m[$this->modelName]->updateTable($this->queryActions);
		
		$this->m[$this->modelName]->bulkAction($this->bulkQueryActions);
		
		// Chiudo anticipatamente se è una richiesta AJAX che non richiede output
		if (isset($_POST["ajax_no_return_html"]))
		{
			if (v("usa_transactions"))
				$this->m[$this->modelName]->db->commit();
			
			$this->clean();
			return;
		}
		
		$this->m[$this->modelName]->setFilters();
		$this->loadScaffold('main',$this->scaffoldParams);

		if ($this->addBulkActions)
		{
			$this->mainFields = array_merge(array("[[checkbox]];$table.$primaryKey;"),$this->mainFields);
			$this->mainHead = "[[bulkselect:checkbox_".$table."_".$primaryKey."]],".$this->mainHead;
		}
		
		if ($this->addIntegrazioniInMain)
			$this->aggiungiintegrazioni();
		
		if ($this->m[$this->modelName]->traduzione && $this->addTraduzioniInMain)
		{
			foreach (self::$traduzioni as $codiceLingua)
			{
				$this->mainFields[] = "link".str_replace("-","",$codiceLingua);
				$this->mainHead .= ",".strtoupper($codiceLingua);
			}
		}
		
		if (isset($_GET["esporta"]) || isset($_GET["esporta_xls"]))
		{
			if (isset($this->mainCsvFields) && isset($this->mainCsvHead))
			{
				$this->mainFields = $this->mainCsvFields;
				$this->mainHead = $this->mainCsvHead;
			}
		}
		
		$this->scaffold->loadMain($this->mainFields,$table.'.'.$primaryKey,$this->mainButtons);
		
		$this->scaffold->setHead($this->mainHead);

		if ($this->addBulkActions)
		{
			if (!isset($this->bulkActions) or !is_array($this->bulkActions))
			{
				$this->scaffold->itemList->setBulkActions(array(
					"checkbox_".$table."_".$primaryKey	=>	array("del",gtext("Elimina selezionati"),"confirm"),
				));
			}
			else
			{
				$this->scaffold->itemList->setBulkActions($this->bulkActions);
			}
		}
		
		$formAction = isset($this->formMethod) ? $this->formMethod : "form";
		
// 		$this->scaffold->mainMenu->links['esporta']['url'] = "main";
		
		$this->scaffold->mainMenu->links['add']['url'] = $formAction.'/insert/0';
		$this->scaffold->mainMenu->links['add']['title'] = gtext('inserisci un nuovo elemento');
		
		if (isset($this->scaffold->mainMenu->links['elimina']) and $this->id !== 0)
			$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="'.$this->id_name.'" id="'.$clean['id'].'"';
		
		if (isset($this->scaffold->mainMenu->links['copia']) and isset($clean["id"]))
			$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean["id"];
		
		if (isset($this->scaffold->mainMenu->links['pulisci']) and isset($clean["id"]))
			$this->scaffold->mainMenu->links['pulisci']['url'] = $this->scaffold->mainMenu->links['pulisci']['url'].'/'.$clean["id"];

		$this->scaffold->model->clear()->restore();
		
		if (App::$isFrontend)
			$this->scaffold->model->aWhere(array(
				"id_user"	=>	User::$id,
			));
		
		$this->m[$this->modelName]->updateTable($this->queryActionsAfter);
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
		
		if ($this->m[$this->modelName]->queryResult)
		{
			flash("notice",$this->m[$this->modelName]->notice);
			
			$urlId = (int)$this->id ? "/".$this->id : "";
			
			$this->redirect($this->applicationUrl.$this->controller.'/'.$this->action.$urlId.$this->viewStatus);
		}
		
		$this->scaffold->fields = $this->scaffold->model->select;

		$this->scaffold->itemList->colProperties = $this->colProperties;
		$this->scaffold->itemList->inverseColProperties = $this->inverseColProperties;
		$this->scaffold->itemList->rowAttributes = $this->rowAttributes;
		
		$this->scaffold->itemList->setFilters($this->filters);
		
		if ($this->aggregateFilters)
		{
			$this->scaffold->itemList->aggregateFilters();
		}
		
		if (!$this->showFilters)
		{
			$this->scaffold->itemList->showFilters = false;
		}

		if (isset($_GET["esporta"]))
		{
			ini_set("memory_limit","256M");
			
			$this->scaffold->itemList->renderToCsv = true;
			$this->scaffold->itemList->csvColumnsSeparator = ";";
			
			$this->scaffold->params["recordPerPage"] = 10000000000;
			$this->scaffold->params['pageList'] = false;
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['main'] = $this->scaffold->html['main'];
			
			$this->clean();
			
			header('Content-disposition: attachment; filename='.date("Y-m-d_H_i_s")."_esportazione_".encodeUrl($data["tabella"]).".csv");
			header('Content-Type: application/vnd.ms-excel');
			
			echo "\xEF\xBB\xBF"; // UTF-8 BOM
			echo $data['main'];
		}
		else if (isset($_GET["esporta_xls"]))
		{
			ini_set("memory_limit","256M");
			
			$this->scaffold->params["recordPerPage"] = 10000000000;
			$this->scaffold->params['pageList'] = false;
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['main'] = $this->scaffold->html['main'];
			
			$this->clean();
			
			if (v("esporta_xls_PhpOffice"))
			{
				ob_start();
				echo "\xEF\xBB\xBF"; // UTF-8 BOM
				echo $data['main'];
				$html = ob_get_clean();
				
				HtmlToXlsx::download($html, date("Y-m-d_H_i_s")."_esportazione_".encodeUrl($data["tabella"]).".xls");
			}
			else
			{
				header('Content-disposition: attachment; filename='.date("Y-m-d_H_i_s")."_esportazione_".encodeUrl($data["tabella"]).".xls");
				header('Content-Type: application/vnd.ms-excel; charset=utf-8');
				
				echo "\xEF\xBB\xBF"; // UTF-8 BOM
				echo $data['main'];
			}
		}
		else if (isset($_GET["esporta_json"]))
		{
			$this->esportaJson();
		}
		else
		{
			$data['scaffold'] = $this->scaffold->render();

			$data['numeroElementi'] = $this->scaffold->model->rowNumber();
			
			$data[$this->menuVariable] = $this->scaffold->html['menu'];
			$data['popup'] = $this->scaffold->html['popup'];
			$data['main'] = $this->scaffold->html['main'];
			$data['pageList'] = $this->scaffold->html['pageList'];
			$data['notice'] = $this->scaffold->model->notice;
			
			$data['recordPerPage'] = $this->scaffold->params["recordPerPage"];
			$data["filtri"] = $this->scaffold->itemList->createFilters();
			
			$this->load($this->mainView);
		}
		
		$this->append($data);
	}
	
	protected function esportaJson()
	{
		header('Content-type: application/json; charset=utf-8');
		
		$this->clean();
		
		$records = $this->scaffold->model->send();
		$tableName = $this->scaffold->model->table();
		
		if (isset($_GET["formato_json"]))
		{
			$campoTitolo = $this->scaffold->model->campoTitolo;
			$campoValore = $this->scaffold->model->campoValore;
			$metodoPerTitolo = $this->scaffold->model->metodoPerTitolo;
			
			if ($_GET["formato_json"] == "select2")
			{
				$struct = array(
					"results"	=>	array(),
				);
				
				foreach ($records as $r)
				{
					if (isset($metodoPerTitolo) && isset($r[$tableName][$campoValore]))
						$struct["results"][] = array(
							"id"	=>	$r[$tableName][$campoValore],
							"text"	=>	call_user_func(array($this->scaffold->model, $metodoPerTitolo), (int)$r[$tableName][$campoValore]),
						);
					else if (isset($r[$tableName][$campoValore]) && isset($r[$tableName][$campoTitolo]))
						$struct["results"][] = array(
							"id"	=>	$r[$tableName][$campoValore],
							"text"	=>	$r[$tableName][$campoTitolo],
						);
				}
				
				$records = $struct;
			}
		}
		else
		{
			$struct = array();
			
			foreach ($records as $r)
			{
				if (isset($r[$tableName]["password"]))
					unset($r[$tableName]["password"]);
				
				$struct[] = $r;
			}
			
			$records = $struct;
		}
		
		echo json_encode($records);
	}
	
	protected function aggiungiintegrazioni()
	{
		$elencoIntegrazioni = IntegrazioniModel::getElencoIntegrazioni($this->controller);
		
		foreach ($elencoIntegrazioni as $i)
		{
			require_once(LIBRARY."/Application/Modules/Integrazioni/".$i["integrazioni"]["classe"].".php");
			
			call_user_func(array($i["integrazioni"]["classe"], "setIdSezione"), $i["integrazioni_sezioni"]["id_integrazione_sezione"]);
			call_user_func(array($i["integrazioni"]["classe"], "setIdIntegrazione"), $i["integrazioni"]["id_integrazione"]);
			
			$this->mainFields[] = $i["integrazioni"]["classe"].'::checkInElenco|orders.id_o';
			$this->mainHead .= ','.$i["integrazioni"]["titolo"];
		}
	}
	
	protected function getNomeMenu($voce = "prodotti")
	{
		return isset($this->voceMenu) ? $this->voceMenu : $voce;
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		
	}
}
