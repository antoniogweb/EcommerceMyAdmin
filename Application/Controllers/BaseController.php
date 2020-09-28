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

class BaseController extends Controller
{
	public static $traduzioni = array();
	
	protected $_posizioni = array();
	
	protected $_topMenuClasses = array(
		"categorie"		=>	array("",""),
		"prodotti"		=>	array("",""),
		"ordini"		=>	array("",""),
		"promozioni"	=>	array("",""),
		"notizie"		=>	array("",""),
		"clienti"		=>	array("",""),
		"menu1"			=>	array("",""),
		"menu2"			=>	array("",""),
		"utenti"		=>	array("",""),
		"testi"			=>	array("",""),
		"classisconto"	=>	array("",""),
		"corrieri"		=>	array("",""),
		"impostazioni"	=>	array("",""),
		"blog"			=>	array("",""),
		"slide"			=>	array("",""),
		"home"			=>	array("",""),
		"iva"			=>	array("",""),
		"marchi"		=>	array("",""),
		"pagine"		=>	array("",""),
		"slidesotto"	=>	array("",""),
		"traduzioni"	=>	array("",""),
		"tipicontenuto"	=>	array("",""),
		"referenze"		=>	array("",""),
		"team"			=>	array("",""),
		"tipitipidocumento"	=>	array("",""),
		"download"		=>	array("",""),
		"tag"			=>	array("",""),
	);
	
	public $id = 0;
	
	public $id_name = "";
	
	public $parentRoot = null;
	
	public $formAction = null;
	
	public $formView = "form";
	
	public $mainView = "main";
	
	public $formFields = null;
	
	public $disabledFields = null;
	
	public $menuLinksStruct = array();
	
	public $menuLinks = "back,save";
	
	public $menuLinksReport = "stampa";
	
	public $menuLinksInsert = "back,save";
	
	public $insertSubmitText = "Continua";
	
	public $updateRedirect = false;
	
	public $insertRedirect = true;
	
	public $insertRedirectUrl = null;
	
	public $formMethod = null;
	
	public $mainButtons = 'ldel,ledit';
	
	public $mainFields = array();
	
	public $mainCsvFields = null;
	
	public $mainHead = "";
	
	public $mainCsvHead = null;
	
	public $addBulkActions = true;
	
	public $bulkActions = null;
	
	public $queryActions = "del";
	
	public $queryActionsAfter = "";
	
	public $bulkQueryActions = "del";
	
	public $nullQueryValue = "tutti";
	
	public $tabella = null;
	
	public $scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'add');
	
	public $colProperties = array(
			array(
				'width'	=>	'60px',
			),
		);
	
	public $orderBy = "";
	
	public $filters = array();
	
	public $sezionePannello = "sito";
	
	public $formDefaultValues = array();
	
	public $useEditor = false;
	
	public $argKeys = null;
	
	public $formValuesToDb = null;
	
	public $aggregateFilters = true;
	
	public $showFilters = false;
	
	public $ordinaAction = "ordina";
	
	public $filtroAttivo = array("tutti"=>"Attivi / NON Attivi","Y"=>"Attivi","N"=>"NON Attivi");
	
	public $elencoLingue = array();
	
	public function __construct($model, $controller, $queryString)
	{
		parent::__construct($model, $controller, $queryString);
		
		$this->model("ContenutitradottiModel");
		
		// Estraggo le traduzioni
		$this->model("LingueModel");
		
		self::$traduzioni = $this->m["LingueModel"]->clear()->where(array(
			"principale"	=>	0,
			"attiva"		=>	1,
		))->orderBy("id_order")->toList("codice")->send();
		
		$data['elencoLingue'] = $this->elencoLingue = $this->m["LingueModel"]->clear()->where(array(
			"attiva"	=>	1,
		))->orderBy("id_order desc")->toList("codice", "descrizione")->send();
		
		$this->model('ImpostazioniModel');
		
		$this->m["ImpostazioniModel"]->getImpostazioni();
		
		// Leggi le impostazioni
		if (ImpostazioniModel::$valori)
		{
			Parametri::$useSMTP = ImpostazioniModel::$valori["usa_smtp"] == "Y" ? true : false;
			Parametri::$SMTPHost = ImpostazioniModel::$valori["smtp_host"];
			Parametri::$SMTPPort = ImpostazioniModel::$valori["smtp_port"];
			Parametri::$SMTPUsername = ImpostazioniModel::$valori["smtp_user"];
			Parametri::$SMTPPassword = ImpostazioniModel::$valori["smtp_psw"];
			Parametri::$mailFrom = ImpostazioniModel::$valori["smtp_from"];
			Parametri::$mailFromName = ImpostazioniModel::$valori["smtp_nome"];
			Parametri::$mailInvioOrdine = ImpostazioniModel::$valori["mail_invio_ordine"];
			Parametri::$mailInvioConfermaPagamento = ImpostazioniModel::$valori["mail_invio_conferma_pagamento"];
			Parametri::$nomeNegozio = ImpostazioniModel::$valori["nome_sito"];
			Parametri::$iva = ImpostazioniModel::$valori["iva"];
			Parametri::$ivaInclusa = ImpostazioniModel::$valori["iva_inclusa"] == "Y" ? true : false;
		}
		
		// Variabili
		$this->model('VariabiliModel');
		VariabiliModel::ottieniVariabili();
		
		// Traduzioni
		TraduzioniModel::checkTraduzioneAttiva();
		$this->model('TraduzioniModel');
		$this->m["TraduzioniModel"]->ottieniTraduzioni();
		
		$baseArgsKeys = array('page:forceInt'=>1,'attivo:sanitizeAll'=>'tutti','partial:sanitizeAll' => "tutti", 'nobuttons:sanitizeAll' => "tutti", 'report:sanitizeAll' => "tutti", 'skip:sanitizeAll' => "tutti");
		
		if (isset($this->argKeys))
		{
			$baseArgsKeys = array_merge($baseArgsKeys, $this->argKeys);
		}
		
		$this->setArgKeys($baseArgsKeys);
		
		$this->parentRoot = $data['parentRoot'] = Domain::$name = str_replace("/admin",null,$this->baseUrl);
		
		$this->parentRootFolder = $data['parentRootFolder'] = Domain::$parentRoot = str_replace("/admin",null,ROOT);
		
		Domain::$adminRoot = ROOT;
		Domain::$adminName = $this->baseUrlSrc;
		
		$this->session('admin');
		
		if (strcmp($controller,"users") !== 0)
		{
			$this->s['admin']->check();
		}
		
		if (class_exists($model))
		{
			$this->model($model);
		}
		
		$this->model('UsersModel');
		$this->model("FattureModel");
		
		$this->_topMenuClasses[$controller] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
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
		
		$data['logged'] = $this->s['admin']->getUsersLogged();
		
		$data['alertFatture'] = $this->m["FattureModel"]->noticeHtml;
		$data['fattureOk'] = $this->m["FattureModel"]->fattureOk;
		
		$data['tm'] = $this->_topMenuClasses;
		
		$data['queryResult'] = false;
		
		$data["orderBy"] = $this->orderBy;
		
		$data["sezionePannello"] = $this->sezionePannello;
		
		$data["title"] = "Pannello di controllo";
		
		$this->append($data);
		
		Params::$actionArray = "REQUEST";
		
		Params::$rewriteStatusVariables = false;
		
		$this->load('header_'.$this->sezionePannello);
		$this->load('footer','last');
		
		$this->generaPosizioni();
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
	
	protected function main()
	{
		$this->m[$this->modelName]->db->beginTransaction();
		
		$this->shift();
		
		if ($this->id !== 0)
		{
			$clean['id'] = $data['id'] = (int)$this->id;
			$this->mainView = "associati";
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
		
		$this->m[$this->modelName]->setFilters();
		$this->loadScaffold('main',$this->scaffoldParams);
		
		$mainFields = $this->mainFields;
		$mainHead = $this->mainHead;
		
		if ($this->addBulkActions)
		{
			$mainFields = array_merge(array("[[checkbox]];$table.$primaryKey;"),$this->mainFields);
			$mainHead = "[[bulkselect:checkbox_".$table."_".$primaryKey."]],".$this->mainHead;
		}
		
		if ($this->m[$this->modelName]->traduzione)
		{
			foreach (self::$traduzioni as $codiceLingua)
			{
				$mainFields[] = "link".$codiceLingua;
				$mainHead .= ",".strtoupper($codiceLingua);
			}
		}
		
		if (isset($_GET["esporta"]))
		{
			if (isset($this->mainCsvFields) and isset($this->mainCsvHead))
			{
				$mainFields = $this->mainCsvFields;
				$mainHead = $this->mainCsvHead;
			}
		}
		
		$this->scaffold->loadMain($mainFields,$table.'.'.$primaryKey,$this->mainButtons);
		
		$this->scaffold->setHead($mainHead);
		
		if ($this->addBulkActions)
		{
			if (!isset($this->bulkActions) or !is_array($this->bulkActions))
			{
				$this->scaffold->itemList->setBulkActions(array(
					"checkbox_".$table."_".$primaryKey	=>	array("del","Elimina selezionati","confirm"),
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
		$this->scaffold->mainMenu->links['add']['title'] = 'inserisci un nuovo elemento';
		
		if (isset($this->scaffold->mainMenu->links['elimina']) and $this->id !== 0)
		{
			$this->scaffold->mainMenu->links['elimina']['attributes'] = 'role="button" class="btn btn-danger elimina_button menu_btn" rel="'.$this->id_name.'" id="'.$clean['id'].'"';
		}
		
		if (isset($this->scaffold->mainMenu->links['copia']) and isset($clean["id"]))
		{
			$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean["id"];
		}
		
		$this->scaffold->model->clear()->restore();
		
		$this->m[$this->modelName]->updateTable($this->queryActionsAfter);
		
		$this->scaffold->fields = $this->scaffold->model->select;
		
		$this->scaffold->itemList->colProperties = $this->colProperties;
		
		$this->scaffold->itemList->setFilters($this->filters);
		
		if ($this->aggregateFilters)
		{
			$this->scaffold->itemList->aggregateFilters();
		}
		
		if (!$this->showFilters)
		{
			$this->scaffold->itemList->showFilters = false;
		}
		
		if (!isset($_GET["esporta"]))
		{
			$data['scaffold'] = $this->scaffold->render();
			
			$data['numeroElementi'] = $this->scaffold->model->rowNumber();
			
			$data['menu'] = $this->scaffold->html['menu'];
			$data['popup'] = $this->scaffold->html['popup'];
			$data['main'] = $this->scaffold->html['main'];
			$data['pageList'] = $this->scaffold->html['pageList'];
			$data['notice'] = $this->scaffold->model->notice;
			
			$data['recordPerPage'] = $this->scaffold->params["recordPerPage"];
			$data["filtri"] = $this->scaffold->itemList->createFilters();
			
			$this->load($this->mainView);
		}
		else
		{
			$this->scaffold->itemList->renderToCsv = true;
			
			$this->scaffold->params["recordPerPage"] = 10000000000;
			$this->scaffold->params['pageList'] = false;
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['main'] = $this->scaffold->html['main'];
			
			$this->clean();
			
			header('Content-disposition: attachment; filename='.date("Y-m-d_H_i_s")."_esportazione_".encodeUrl($data["tabella"]).".csv");
			header('Content-Type: application/vnd.ms-excel');
			echo $data['main'];
		}
		
		$this->append($data);
		
		$this->m[$this->modelName]->db->commit();
	}
	
	protected function form($queryType = 'insert', $id = 0)
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
		
		$qAllowed = array("insert","update");
		
		$data["useEditor"] = $this->useEditor;
		
		if (in_array($queryType,$qAllowed))
		{
			$clean["id"] = $data["id"] = (int)$id;
		
			$table = $this->m[$this->modelName]->table();
			
			$data["tabella"] = isset($this->tabella) ? $this->tabella : $table;
		
			$data["queryType"] = $data["type"] = $queryType;
			
			$this->m[$this->modelName]->updateTable('insert,update',$clean["id"]);
			
			$data["queryResult"] = $this->m[$this->modelName]->queryResult;
			
			if (isset($_POST["gAction"]))
			{
				$this->m[$this->modelName]->result = false;
			}
			
			$data["titoloRecord"] = "inserimento nuovo elemento";
			
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
			
			$formAction = isset($this->formAction) ? $this->formAction : $this->controller."/".$this->action."/$queryType/".$clean["id"].$partial;
			
			if (strcmp($queryType,'insert') === 0 and $this->m[$this->modelName]->queryResult and $this->insertRedirect)
			{
				$lId = $this->m[$this->modelName]->lId;
				
				if (isset($this->insertRedirectUrl))
				{
					$this->redirect($this->insertRedirectUrl);
				}
				else
				{
					$this->redirect($this->controller.'/form/update/'.$lId.$this->viewStatus.$partialU."insert=ok");
				}
			}
			
			if (strcmp($queryType,'update') === 0 and $this->m[$this->modelName]->queryResult and ($this->updateRedirect or isset($_POST["redirectToList"])))
			{
				$this->redirect($this->controller.'/main/'.$this->viewStatus);
			}
			
			$this->m[$this->modelName]->setFormStruct();
			
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
			
			if (isset($_GET["insert"]))
			{
				$this->scaffold->model->notice = "<div class='alert alert-success'>operazione eseguita!</div>\n";
				$data["queryResult"] = true;
			}
			
			if (isset($this->disabledFields))
			{
				$this->scaffold->model->disabilita($this->disabledFields);
			}
			
			$this->scaffold->model->fields = isset($this->formFields) ? $this->formFields : $this->scaffold->model->fields;
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean["id"],$this->formDefaultValues);
			
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
					$this->scaffold->values[$key] = nl2br(strip_tags(br2nl(htmlentitydecode($this->scaffold->values[$key]))));
					
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
			
			if (!isset($_GET["pdf"]))
			{
				$this->append($data);
				$this->load($this->formView);
			}
			else
			{
				$this->clean();
				require_once(ROOT."/External/html2pdf_v4.03/html2pdf.class.php");
				
				ob_start();
				include(ROOT."/Application/Views/pdf.php");
				$content = ob_get_clean();
				
				$html2pdf = new HTML2PDF('P','A4','it', true, 'ISO-8859-15', array("0mm", "0mm", "0mm", "0mm"));
				
				$html2pdf->setDefaultFont('Arial');
				$html2pdf->writeHTML($content);
				
				$html2pdf->Output(date("d-m-Y")."_".$data["tabella"].".pdf");
			}
		}
	}
	
	public function ordina()
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
				
				$this->m[$this->modelName]->db->commit();
			}
		}
	}
}
