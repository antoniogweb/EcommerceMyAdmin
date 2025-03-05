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

class BaseController extends Controller
{
	use BaseCrudController;
	use InitController;
	use JsonController;
	
	protected $_topMenuClasses = array();
	
	public $id_name = "";
	public $parentRoot = null;
	public $disabledFields = null;
	public $menuLinksStruct = array();
	public $menuLinksReport = "stampa";
	public $mainMenuAssociati = "back,copia";
	public $insertSubmitText = "Continua";
	public $insertRedirectUrl = null;
	public $formMethod = null;
	public $mainFields = array();
	public $mainCsvFields = null;
	public $mainHead = "";
	public $mainCsvHead = null;
	public $tabella = null;
	public $orderBy = "";
	public $sezionePannello = "sito";
	public $useEditor = false;
	public $useEditorVisuale = true;
	public $argKeys = null;
	public $formValuesToDb = null;
	public $filtroAttivo = array("tutti"=>"Attivi / NON Attivi","Y"=>"Attivi","N"=>"NON Attivi");
	public $elencoLingue = array();
	public $mainMenu = "add";
	public $closeModal = false;
	public $addTraduzioniInMain = true;
	public $tabViewFields = array();
	public $campiVariabiliDaModificare = "";
	public $loginController = "users";
	public $formQueryActions = "insert,update";
	public $documentiInPagina = true;
	
	public $baseArgsKeys = array(
		'page:forceInt'=>1,
		'attivo:sanitizeAll'=>'tutti',
		'partial:sanitizeAll' => "tutti",
		'nobuttons:sanitizeAll' => "tutti",
		'report:sanitizeAll' => "tutti",
		'skip:sanitizeAll' => "tutti",
		'nofiltri:sanitizeAll' => "tutti",
		'cl_on_sv:sanitizeAll' => "tutti",
		'page_fgl:forceInt'=>1,
	);
	
	public $modelAssociati = array(); // Da caricare
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if( !session_id() )
			session_start();
		
		$this->creaSessioneAdmin();
		
		$data['token'] = null;
		
		$this->s['admin']->checkStatus();
		if ( strcmp($this->s['admin']->status['status'],'logged') === 0 ) { //check if already logged
			User::$logged = true;
			User::$id = (int)$this->s['admin']->status['id_user'];
			
			User::$name = $this->s['admin']->status['user'];
			
			User::$groups = $this->s['admin']->status['groups'];
			
			$token = User::$token = $this->s['admin']->status['token'];
			$data['token'] = $token;
		}
		
		if (strcmp($controller, $this->loginController) !== 0)
		{
			$this->s['admin']->check();
			
			if (!ControllersModel::checkAccessoAlController(array($controller)))
				$this->responseCode(403);
			
			// Hook dopo login
			if (v("hook_after_login_admin"))
				callFunction(v("hook_after_login_admin"), $this, v("hook_after_login_admin"));
		}
		
		$this->init();
		
		// Help wizard
		$this->model("HelpModel");
		$data["helpDaVedere"] = $this->m["HelpModel"]->daVedere();
		$data["helpDaVedereTutti"] = $this->m["HelpModel"]->daVedere(false);
		
		$this->setStatusVariables();
		
// 		if (isset($this->argKeys))
// 			$this->baseArgsKeys = array_merge($this->baseArgsKeys, $this->argKeys);
// 		
// 		$this->setArgKeys($this->baseArgsKeys);
		
		if (class_exists($model))
			$this->model($model);
		
		$this->model('UsersModel');
		$this->model("FattureModel");
		
		// Carico i model associati
		foreach ($this->modelAssociati as $modelAssociato => $modelParams)
		{
			$this->model($modelAssociato);
		}
		
		$this->setMenuClass($controller);
		
		MenuadminModel::$currentAction = $action;
		
		$data['logged'] = $this->s['admin']->getUsersLogged();
		
		$data['alertFatture'] = $this->m["FattureModel"]->noticeHtml;
		$data['fattureOk'] = $this->m["FattureModel"]->fattureOk;
		
		$data['queryResult'] = $data['closeModal'] = false;
		
		$data["orderBy"] = $this->orderBy;
		
		$data["sezionePannello"] = $this->sezionePannello;
		
		$data["title"] = "Pannello di controllo";
		
		$data["tabella"] = isset($this->tabella) ? $this->tabella : "";
		
		$this->append($data);
		
		if (!isset($_GET["ajax_partial_load"]))
		{
			$this->load('header_'.$this->sezionePannello);
			$this->load('footer','last');
		}
		
		$this->generaPosizioni();
		
		// Controlla che tutti i prodotti abbiano la combinazione canonical
		if (v("ecommerce_attivo") && VariabiliModel::combinazioniLinkVeri())
			CombinazioniModel::g(false)->checkCanonicalAll();
	}
	
	protected function creaSessioneAdmin()
	{
		if (!isset($this->s["admin"]))
		{
			$twoFactorModel = null;
			
			if (v("attiva_autenticazione_due_fattori_admin"))
				$twoFactorModel = new SessionitwoModel("uidt", v("autenticazione_due_fattori_admin_durata_cookie"), "/", v("autenticazione_due_fattori_durata_verifica_admin"));
			
			$this->session('admin', array(
				new UsersModel(),
				new SessioniModel(),
				new AccessiModel(),
				new GroupsModel(),
			), $twoFactorModel);
		}
	}
	
	protected function generaPosizioni()
	{
		$metodi = get_class_methods($this);
		
		foreach ($metodi as $m)
		{
			$this->_posizioni[$m] = null;
		}
	}
	
	protected function thumb($field = "", $id = 0)
	{
		$this->clean();
		
		$clean["id"] = (int)$id;
		
		if (isset($this->m[$this->modelName]->uploadFields[$field]))
		{
			$params = $this->m[$this->modelName]->uploadFields[$field];
			$path = $params["path"];
			$folder = Domain::$parentRoot."/".trim($path,"/");
			
			$record = $this->m[$this->modelName]->selectId($clean["id"]);
			
			if (strcmp($record[$field],"") !== 0 and file_exists($folder."/".$record[$field]))
			{
				$p = array(
					'imgWidth'		=>	400,
					'imgHeight'		=>	400,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				);
				
				if (isset($params["thumb"])) $p = $params["thumb"];
				
				$thumb = new Image_Gd_Thumbnail($folder,$p);
				$thumb->render($record[$field]);
			}
		}
	}
	
	protected function documento($field = "", $id = 0)
	{
		$this->clean();
		
		$clean["id"] = (int)$id;
		
		if (isset($this->m[$this->modelName]->uploadFields[$field]))
		{
			$params = $this->m[$this->modelName]->uploadFields[$field];
			$path = $params["path"];
			$folder = Domain::$parentRoot."/".trim($path,"/");
			
			$record = $this->m[$this->modelName]->selectId($clean["id"]);
			
			if (strcmp($record[$field],"") !== 0 and file_exists($folder."/".$record[$field]))
			{
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$MIMEtype = finfo_file($finfo, $folder."/".$record[$field]);
				finfo_close($finfo);
				
				$fileName = isset($params["clean_field"]) ? $record[$params["clean_field"]] : $record[$field];
				
				$cd = isset($params["Content-Disposition"]) ? $params["Content-Disposition"] : "attachment";
				
				header('Content-type: '.$MIMEtype);
				header('Content-Disposition: '.$cd.'; filename='.$fileName);
				readfile($folder."/".$record[$field]);
			}
		}
	}
	
	protected function form($queryType = 'insert', $id = 0)
	{
		$this->baseForm($queryType, $id);
	}
	
// 	protected function salvacampo($id = 0, $campo = "")
// 	{
// 		if (in_array($campo, $this->m[$this->modelName]->campiEditabiliDaMain))
// 		{
// 			$this->m[$this->modelName]->setValuesFromPost($campo);
// 			$this->formQueryActions = "update";
// 			
// 			$this->baseForm("update", (int)$id);
// 		}
// 	}
	
	protected function baseForm($queryType = 'insert', $id = 0)
	{
		if (isset($this->formValuesToDb))
		{
			$this->m[$this->modelName]->setFields($this->formValuesToDb,'sanitizeAll');
		}
		
		$this->shift(2);
		
		if (isset($_GET["pdf"]))
		{
			$_GET["report"] = "Y";
			$_GET["partial"] = "Y";
			$_GET["buttons"] = "N";
		}
		
		$data['posizioni'] = $this->_posizioni;
		
		$qAllowed = BaseController::$azioniPermesse;
		
		$data["useEditor"] = $this->useEditor;
		$data["useEditorVisuale"] = $this->useEditorVisuale;
		
		if (in_array($queryType,$qAllowed))
		{
			$clean["id"] = $data["id"] = (int)$id;
			
			// controllo che il record esista
			if ($queryType == "update")
			{
				$recordDaModificare = $this->m[$this->modelName]->selectId($clean["id"]);
				
				if (empty($recordDaModificare))
					$this->responseCode(403);
			}
			
			$table = $this->m[$this->modelName]->table();
			
			$data["tabella"] = isset($this->tabella) ? $this->tabella : $table;
		
			$data["queryType"] = $data["type"] = $queryType;
			
			$this->m[$this->modelName]->updateTable($this->formQueryActions,$clean["id"]);
			
			$data["queryResult"] = $this->m[$this->modelName]->queryResult;
			
			if (isset($this->viewArgs["cl_on_sv"]) && $this->viewArgs["cl_on_sv"] == "Y" && $this->m[$this->modelName]->queryResult)
				$data["closeModal"] = $this->closeModal = true;
			
			if (isset($_POST["gAction"]))
			{
				$this->m[$this->modelName]->result = false;
			}
			
			$data["titoloRecord"] = gtext("inserimento nuovo elemento");
			
			if (strcmp($queryType,'update') === 0)
			{
				$data["titoloRecord"] = $this->m[$this->modelName]->titolo($clean["id"]);
				
				if ($this->m[$this->modelName]->traduzione)
				{
					$data["contenutiTradotti"] = $this->m["ContenutitradottiModel"]->clear()->where(array(
						$this->m[$this->modelName]->getPrimaryKey()	=>	$clean["id"],
						"in"	=>	array(
							"lingua"	=>	self::$traduzioni,
						),
					))->send(false);
				}
			}
			
			if (strcmp($queryType,'insert') === 0)
			{
				$this->menuLinks = $this->menuLinksInsert;
			}
			
			$queryStringChar = Params::$rewriteStatusVariables ? "?" : "&";
			
			$partial = isset($_GET["partial"]) ? $queryStringChar."partial=Y" : null;
			$partialU = isset($_GET["partial"]) ? $queryStringChar."partial=Y&" : $queryStringChar;
			
			if (isset($this->viewArgs["partial"]))
			{
				$partial = null;
				$partialU = $queryStringChar;
			}
			
			$formAction = isset($this->formAction) ? $this->formAction : $this->applicationUrl.$this->controller."/".$this->action."/$queryType/".$clean["id"].$partial;
			
			$this->redirectAfterInsertUpdate($queryType, $clean["id"], false, $partialU);
			
			$this->m[$this->modelName]->setFormStruct($clean["id"]);
			
			$this->m[$this->modelName]->setUploadForms($clean["id"]);
			
			if (strcmp($queryType,'update') === 0 and showreport())
			{
				$this->menuLinks = $this->menuLinksReport;
			}
			
			$params = array(
				'formMenu'=>$this->menuLinks,
			);
			
			$this->loadScaffold('form',$params);
			$this->scaffold->loadForm($queryType,$formAction);
			
			if (isset($this->scaffold->mainMenu->links['pdf']))
			{
				$this->scaffold->mainMenu->links['pdf']['url'] = 'form/update/'.$clean["id"];
			}
			
			if (isset($this->scaffold->mainMenu->links['report']))
			{
				$this->scaffold->mainMenu->links['report']['url'] = 'form/update/'.$clean["id"];
			}
			
			if (isset($this->scaffold->mainMenu->links['modifica']))
			{
				$this->scaffold->mainMenu->links['modifica']['url'] = 'form/update/'.$clean["id"];
			}
			
			if (isset($this->scaffold->mainMenu->links['report_full']))
			{
				$this->scaffold->mainMenu->links['report_full']['url'] = 'form/update/'.$clean["id"];
			}
			
			if (isset($this->scaffold->mainMenu->links['torna_ordine']))
			{
				$this->scaffold->mainMenu->links['torna_ordine']['url'] = 'vedi/'.$clean['id'];
// 				$this->scaffold->mainMenu->links['torna_ordine']['queryString'] = '?n=y';
				$this->scaffold->mainMenu->links['torna_ordine']['title'] = "Torna alla pagina di dettaglio dell'ordine";
			}
			
			if (isset($this->scaffold->mainMenu->links['invia_link_recupero_password']))
				$this->scaffold->mainMenu->links['invia_link_recupero_password']['url'] = 'inviamailrecuperopassword/'.$clean["id"];
			
			$this->aggiungiUrlmenuScaffold($clean["id"]);
			
			if (isset($_GET["insert"]))
			{
				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
				$data["queryResult"] = true;
			}
			
			if (isset($this->disabledFields))
				$this->scaffold->model->disabilita($this->disabledFields);
			
			$this->scaffold->model->fields = isset($this->formFields) ? $this->formFields : $this->scaffold->model->fields;
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean["id"],$this->formDefaultValues, $this->functionsIfFromDb);
			
			if (count($this->menuLinksStruct) > 0)
			{
				foreach ($this->menuLinksStruct as $k => $v)
				{
					$this->scaffold->mainMenu->links[$k] = $v;
				}
			}
			
			if (showreport())
			{
				$this->scaffold->form->setReport(skipIfEmpty());
			}
			
			if (isset($_GET["pdf"]) or showreport())
			{
				foreach ($this->scaffold->values as $key => $value)
				{
					$this->scaffold->values[$key] = nl2br(sanitizeHtml(strip_tags(br2nl(htmlentitydecode($this->scaffold->values[$key])))));
					
					if (isset($this->scaffold->model->uploadFields[$key]))
					{
						$params = $this->scaffold->model->uploadFields[$key];
						
						if (strcmp($this->scaffold->values[$key],"") !== 0 and file_exists(Domain::$parentRoot."/".trim($params["path"],"/")."/".$this->scaffold->values[$key]))
						{
							if (strcmp($params["type"],"image") === 0)
							{
								if (!isset($_GET["pdf"]))
								{
									$src = Domain::$name."/".$params["path"]."/".$value;
								}
								else
								{
									$src = Domain::$parentRoot."/".$params["path"]."/".$value;
								}
								
								if (isset($params["clean_field"]) and !isset($_GET["pdf"]))
								{
									$src = Url::getRoot().$this->controller."/thumb/".$key."/".$clean["id"];
								}
								
								$style = isset($_GET["pdf"]) ? "style='width:300px'" : null;
								
								$this->scaffold->values[$key] = "<img $style src='".$src."' />";
							}
							else if (strcmp($params["type"],"file") === 0)
							{
								$linkText = $value;
								
								if (isset($params["clean_field"]))
								{
									$record = $this->m[$this->modelName]->selectId($clean["id"]);
									
									if (isset($record[$params["clean_field"]]))
									{
										$linkText = $record[$params["clean_field"]];
									}
								}
								
								$href = Domain::$name."/".$params["path"]."/".$value;
								
								if (isset($params["clean_field"]))
								{
									$href = Url::getRoot().$this->controller."/documento/".$key."/".$clean["id"];
								}
								
								$this->scaffold->values[$key] = "<a href='".$href."'>$linkText</a>";
							}
						}
					}
				}
			}
			
			$data["form"] = array();
			
			foreach ($this->scaffold->values as $key => $value)
			{
				$data["form"][$key] = $this->scaffold->model->form->entry[$key]->render($value);
			}
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['menu'] = $this->scaffold->html['menu'];
			$data['main'] = $mainContent = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
			
			$stringaTitolo = (!showreport()) ? "Gestione" : "Visualizzazione";
			$data["title"] = $stringaTitolo . " " . $data["tabella"] . ": " . $data["titoloRecord"];
			
			if (isset($_GET["pdf"]) && v("permetti_generazione_pdf_pagine_backend"))
			{
				$this->clean();
				
				Pdf::output(LIBRARY."/Application/Views/pdf.php", date("d-m-Y")."_".encodeUrl($data["title"]).".pdf", array(
					"mainContent"	=>	$mainContent,
				));
			}
			else if (isset($_GET["esporta_json"]) && v("permetti_generazione_json_pagine_backend"))
			{
				header('Content-type: application/json; charset=utf-8');
				
				$this->clean();
				
				$jsonArray = $this->scaffold->values;
				
				if (isset($jsonArray["username"]))
					$jsonArray["email"] = $jsonArray["username"];
				
				if (isset($jsonArray["password"]))
					unset($jsonArray["password"]);
				
				echo json_encode($jsonArray);
			}
			else
			{
				$this->append($data);
				$this->load($this->formView);
			}
		}
	}
	
	public function ordina()
	{
		$this->ordinaGeneric();
	}
	
	public function ordinaGeneric()
	{
		$this->s['admin']->check();
		
		$this->clean();
		
		if (strstr($this->orderBy, "id_order"))
		{
			if (isset($_POST["ordinaPagine"]))
			{
				$clean["order"] = $this->request->post("order","","sanitizeAll");
			
				$orderArray = explode(",",$clean["order"]);
				
				$orderClean = array();
				
				foreach ($orderArray as $id_table)
				{
					if ((int)$id_table !== 0)
					{
						$orderClean[] = (int)$id_table;
					}
				}
				
// 				$where = "in(".implode(",",$orderClean).")";
				
				$idOrderArray = $this->m[$this->modelName]->where(array(
					"in" => array($this->m[$this->modelName]->getPrimaryKey() => $orderClean),
				))->toList("id_order")->send();
				
// 				if ($this->orderBy === "pages.id_order")
				if (!strstr(strtolower($this->orderBy), "desc"))
				{
					sort($idOrderArray);
				}
				else
				{
					rsort($idOrderArray);
				}
				
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->beginTransaction();
				
				for ($i=0; $i<count($orderClean); $i++)
				{
					if (isset($idOrderArray[$i]))
					{
						$this->m[$this->modelName]->values = array(
							"id_order" => (int)$idOrderArray[$i],
						);
						$this->m[$this->modelName]->pUpdate((int)$orderClean[$i]);
					}
				}
				
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
			}
		}
	}
	
	protected function ordinaGerarchico()
	{
		$this->ordinaGeneric();
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->m[$this->modelName]->callRebuildTree();
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
	
	protected function scaricaFile($filePath, $fileName, $cd = "attachment")
	{
		if (file_exists($filePath))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$MIMEtype = finfo_file($finfo, $filePath);
			finfo_close($finfo);
			
// 			$cd = "attachment";
			
			header('Content-type: '.$MIMEtype);
			header('Content-Disposition: '.$cd.'; filename='.$fileName);
			readfile($filePath);
		}
	}
	
	protected function setMenuClass($controller)
	{
		$this->_topMenuClasses[$controller] = MenuadminModel::$classiVociMenu[$controller] = MenuadminModel::$activeClass;
		$data['tm'] = $this->_topMenuClasses;
		$this->append($data);
	}
	
	protected function getUsaEditorVisuale($queryType, $id)
	{
		$editorVisuale = "1";
		
		if (strcmp($queryType,'update') === 0)
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (count($record) > 0)
				$editorVisuale = $record["editor_visuale"];
		}
		
		$editorVisuale = (isset($_POST["editor_visuale"]) and in_array($_POST["editor_visuale"],array("1","0"))) ? sanitizeAll($_POST["editor_visuale"]) : $editorVisuale;
		
		return $editorVisuale;
	}
	
	protected function integrazioni($id = 0)
	{
		$this->model("IntegrazionisezioniinviiModel");
		
		$this->_posizioni['integrazioni'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_corriere";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "IntegrazionisezioniinviiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("integrazioni.titolo", "cleanDateTime", "integrazioni_sezioni_invii.codice_piattaforma");
		$this->mainHead = "Piattaforma esterna,Data / ora invio,ID elemento nella piattaforma esterna";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'torna_ordine','mainAction'=>"integrazioni/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("integrazione"))->orderBy("integrazioni_sezioni_invii.data_creazione")->where(array(
			"id_elemento"	=>	$clean['id'],
			"sezione"		=>	$this->controller,
		))->convert()->save();
		
		$this->tabella = "integrazioni ".$this->tabella;
	}
	
	public function variabili($id = 0)
	{
		$this->model("VariabiliModel");
		
		$this->_posizioni[$this->action] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = (int)$id;
		
		$data["notice"] = null;
		
// 		$listaVariabiliGestibili = $this->campiVariabiliDaModificare ? $this->campiVariabiliDaModificare : v("lista_variabili_gestibili");
		
		if ($this->controller == "impostazioni" && $this->action == "variabili")
			$listaVariabiliGestibili = implode(",",array_diff(explode(",", v("lista_variabili_gestibili")), explode(",", v("lista_variabili_opzioni_google")), explode(",", v("lista_variabili_funzionamento_ecommerce"))));
		else if ($this->controller == "applicazioni")
			$listaVariabiliGestibili = ApplicazioniModel::variabiliGestibili($id);
// 		else if ($this->controller == "gestionali")
// 			$listaVariabiliGestibili = GestionaliModel::variabiliGestibili($id);
		else
			$listaVariabiliGestibili = $this->campiVariabiliDaModificare;
			
		if ($listaVariabiliGestibili)
		{
			$variabili = explode(",", $listaVariabiliGestibili);
			
			if (isset($_POST["updateAction"]))
			{
				foreach ($variabili as $v)
				{
					if (isset($_POST[$v]))
						VariabiliModel::setValore($v, $_POST[$v]);
				}
				
				$data["notice"] = "<div class='alert alert-success'>operazione eseguita!</div>";
			}
		}
		
		$mainMenu = 'back,save';
		
		if ($this->controller == "impostazioni")
			$mainMenu = 'save';
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$mainMenu,'mainAction'=>"variabili".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->mainView = "variabili";
		
		$form = new Form_Form($this->controller."/".$this->action."/".$clean['id'],array("updateAction"=>"Salva"), "POST");
		
		$entries = array();
		$values = array();
		
		VariabiliModel::ottieniVariabili();
		
		if ($listaVariabiliGestibili)
		{
			$struct = $this->m["VariabiliModel"]->strutturaForm();
			
			foreach ($variabili as $v)
			{
				if (isset($struct[$v]))
					$entries[$v] = $struct[$v];
				else
					$entries[$v] = array();
				
				$entries[$v]["className"] = "form-control";
				
				$values[$v] = v($v);
			}
		}
		
		$form->setEntries($entries);
		
		$data["formVariabili"] = $form->render($values);
		
		$this->pMain();
		
		$data["titoloRecord"] = "Varibili";
		
		$this->append($data);
	}
	
	public function getTabViewFields($tab)
	{
		if (isset($this->tabViewFields[$tab]))
		{
			foreach ($this->tabViewFields[$tab] as $k => $v)
			{
				$this->{$k} = $this->tabViewFields[$tab][$k];
			}
		}
		
		if (isset($this->tabViewFields[$tab."-append"]))
		{
			foreach ($this->tabViewFields[$tab."-append"] as $k => $v)
			{
				if (isset($this->{$k}))
				{
					if (is_array($v))
					{
						foreach ($v as $va)
						{
							$this->{$k}[] = $va;
						}
					}
					else if (is_string($v))
						$this->{$k} .= $v;
				}
			}
		}
	}
	
	protected function aggiungiCodiceGestionale()
	{
		if (v("attiva_collegamento_gestionali"))
		{
			$this->mainFields[] = $this->m($this->modelName)->table().".codice_gestionale";
			$this->mainHead .= ",Codice gestionale";
		}
	}
}
