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

//in this file you can write the PHP code that will be executed at the beginning of the MvcMyLibrary execution, before super global array have been sanitizied

//this is the preferred place to create and fill log files

//you can access the whole set of classes and functions of MvcMyLibrary

Users_CheckAdmin::$usersModel = "UsersModel";
Users_CheckAdmin::$groupsModel = "GroupsModel";
Users_CheckAdmin::$sessionsModel = "SessioniModel";
Users_CheckAdmin::$accessesModel = "AccessiModel";

$mysqli = Factory_Db::getInstance(DATABASE_TYPE);

// set logger if the log of the queries is enabled
if (defined('LOG_QUERIES_ENABLED'))
{
	// set logger to development
	$mysqli->setLogger(false);
	
	if (defined('LOG_QUERIES_THRESHOLD'))
		Db_Log_Generic::$queryTimeThresholdToLogInSeconds = LOG_QUERIES_THRESHOLD;
}

$mysqli->query("set session sql_mode=''");

Url::$routes = array(
	"ordine"	=>	"ordini/vedi/%d",
);

Params::$logFunctionBeforeRedirect = array("F","checkPreparedStatement");

date_default_timezone_set('Europe/Rome');

// Carica le App
if (!defined("APPS"))
	ApplicazioniModel::carica();

// Imposto le app
if (defined("APPS"))
{
	Params::$installed = APPS;
	
	foreach (APPS as $app)
	{
		$path = ROOT."/Application/Apps/".ucfirst($app)."/Route/route.php";
		
		if (file_exists($path))
		{
			if (isset($APP_ROUTE))
				unset($APP_ROUTE);
			
			include($path);
			
			if (isset($APP_ROUTE))
				Route::$allowed = array_merge(Route::$allowed, $APP_ROUTE);
		}
		
		include(LIBRARY."/Application/Hooks/BeforeChecksVariabili.php");
	}
}

VariabiliModel::ottieniVariabili();

if (VariabiliModel::valore("usa_transactions"))
	Users_CheckAdmin::$useConcurrencyCheckInLastFailureTime = true;

VariabiliModel::$valori["alert_error_class"] = "alert";

// Imposto i pannelli dell'admin
App::setPannelli();

if (v("usa_https"))
	Params::$useHttps = true;

require(LIBRARY."/External/mobile_detect.php");

$detect = new Mobile_Detect();
User::$isMobile = $detect->isMobile();

Params::$language = "It";

if (LingueModel::permettiCambioLinguaBackend())
{
	Params::$frontEndLanguages = array_keys(LingueModel::$lingueBackend);
	
	if (isset($_COOKIE["backend_lang"]) && LingueModel::linguaPermessaBackend((string)$_COOKIE["backend_lang"]))
		Params::$defaultFrontEndLanguage = (string)$_COOKIE["backend_lang"];
	else
		Params::$defaultFrontEndLanguage = v("default_backend_language");
}

TraduzioniModel::$contestoStatic = "back";

if (v("traduzione_backend"))
	TraduzioniModel::$edit = true;

Params::$setValuesConditionsFromDbTableStruct = true;
Params::$automaticConversionToDbFormat = true;
Params::$automaticConversionFromDbFormat = true;
Params::$automaticallySetFormDefaultValues = true;

Params::$translatorFunction = "gtext";
Params::$errorStringClassName = "alert alert-danger";
Params::$infoStringClassName = "alert alert-success";

Helper_Menu::$htmlLinks = array(
	"back" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-default"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Torna",
		"classIconBefore"	=>	'<i class="fa fa-arrow-circle-left"></i>',
	),
	"add" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info help_nuovo_elemento"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Nuovo elemento",
		"classIconBefore"	=>	'<i class="fa fa-plus"></i>',
	),
	"esporta" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info"',
		"class"	=>	"btn btn-info",
		'text'	=>	"Esporta",
		'url'	=>	'main',
		'queryString'	=>	'&esporta=Y',
		"classIconBefore"	=>	'<i class="fa fa-download"></i>',
	),
	"esporta_xls" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info"',
		"class"	=>	"btn btn-info",
		'text'	=>	"Esporta XLS",
		'url'	=>	'main',
		'queryString'	=>	'&esporta_xls=Y',
		"classIconBefore"	=>	'<i class="fa fa-file-excel-o"></i>',
	),
	"pulisci" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning"',
		"class"	=>	"btn btn-warning",
		'text'	=>	"Pulisci",
		'url'	=>	'documenti',
		'queryString'	=>	'&pulisci_file=Y',
		"classIconBefore"	=>	'<i class="fa fa-trash"></i>',
	),
	"importa" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning importa_traduzioni" data-toggle="collapse" href="#box_form_import_traduzioni" role="button"',
		"class"	=>	"btn btn-info",
		'text'	=>	"Importa",
		'url'	=>	'main',
		'queryString'	=>	'&importa=Y',
		"classIconBefore"	=>	'<i class="fa fa-upload"></i>',
	),
	"stampa" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info pull-right stampa_pagina"',
		"class"	=>	"btn btn-info",
		'text'	=>	"Stampa",
		'url'	=>	'main',
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-download-alt"></span>',
	// 				'queryString'	=>	'&esporta=Y',
	),
	"pdf" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'target="_blank" role="button" class="btn btn-info pull-right"',
		"class"	=>	"btn btn-info",
		'text'	=>	"Pdf",
		'url'	=>	'form/update',
		'queryString'	=>	'&pdf=Y&skip=Y',
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-download-alt"></span>',
	),
	"report" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-default pull-right margin-left-button iframe"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Report",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=Y&skip=Y&partial=Y&nobuttons=N',
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-font"></span>',
	),
	"report_full" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'target="_blank" role="button" class="btn btn-default pull-right margin-left-button"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Schermo intero",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=Y&skip=Y&partial=Y&nobuttons=Y',
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-resize-full"></span>',
	),
	"modifica" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success"',
		"class"	=>	"btn btn-success",
		'text'	=>	"Modifica",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=N&buttons=Y',
		"classIconBefore"	=>	'<i class="fa fa-edit"></i>',
	),
	"panel" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Home",
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-home"></span>',
	),
	"copia" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary make_spinner"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Duplica",
		"classIconBefore"	=>	'<i class="fa fa-paste"></i>',
	),
	"torna_ordine" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Torna all' ordine",
		"classIconBefore"	=>	'<i class="fa fa-arrow-circle-left"></i>',
	),
	"edit" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary help_modifica"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Modifica",
		"classIconBefore"	=>	'<i class="fa fa-pencil"></i>',
	),
	"manda_mail" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning help_manda_mail make_spinner"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Invia mail",
		"classIconBefore"	=>	'<i class="fa fa-envelope"></i>',
	),
	"refresh" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary make_spinner"',
		"class"	=>	"btn btn-default make_spinner",
		'text'	=>	"Aggiorna",
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
	"rigenera" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning"',
		"class"	=>	"btn btn-warning",
		'text'	=>	"Rigenera",
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
	'save'	=>	array(
		'title'	=>	"salva",
		'text'	=>	"Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_button make_spinner menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-save"></i>',
	),
	'save_2'	=>	array(
		'title'	=>	"salva",
		'text'	=>	"Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" style="margin-left:100px;" class="btn btn-success menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-save"></i>',
	),
	'resetta'	=>	array(
		'title'	=>	"resetta",
		'text'	=>	"Resetta",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning resetta_button"',
		"classIconBefore"	=>	'<span class="glyphicon glyphicon-adjust"></span>',
	),
	'elimina'	=>	array(
		'title'	=>	"elimina",
		'text'	=>	"Elimina",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-danger elimina_button"',
		"classIconBefore"	=>	'<i class="fa fa-trash"></i>',
	),
	'elimina_2'	=>	array(
		'title'	=>	"elimina",
		'text'	=>	"Elimina",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"classIconBefore"	=>	'<i class="fa fa-trash"></i>',
	),
	'save_combinazioni'	=>	array(
		'title'	=>	"salva",
		'text'	=>	"Salva valori",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_combinazioni menu_btn btn_trigger_click"',
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
	'genera_redirect'	=>	array(
		'title'	=>	"Rigenera file redirect",
		'text'	=>	"Rigenera file redirect",
		'url'	=>	'rigenera',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_redirect menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
	'save_regali'	=>	array(
		'title'	=>	"Salva quantità",
		'text'	=>	"Salva quantità",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_regali menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-save"></i>',
	),
	'save_righe'	=>	array(
		'title'	=>	"Salva",
		'text'	=>	"Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_righe menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-save"></i>',
	),
	"vedi_feed" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'target="_blank" role="button" class="btn btn-info"',
		"class"	=>	"btn btn-default",
		'title'	=>	"Vedi feed",
		'text'	=>	"Vedi feed",
		'url'	=>	'main',
		"classIconBefore"	=>	'<i class="fa fa-eye"></i>',
	),
);

Scaffold::$autoParams["formMenu"] = "panel,back,resetta,save,elimina";

Form_Form::$defaultEntryAttributes["className"] = "form-control";
Form_Form::$defaultEntryAttributes["submitClass"] = "btn btn-success make_spinner";
Form_Form::$defaultEntryAttributes["submitHtml"] = "<i class='fa fa-save'></i> Salva";

if (!showreport())
{
Form_Form::$defaultEntryAttributes["formWrap"] = array(
	'',
	'',
);
}

Helper_Pages::$pageLinkWrap = array("li");

Helper_Pages::$staticCurrentClass = "active disabled";

Helper_Pages::$staticShowFirstLast = true;

Helper_Pages::$staticFirstLastDividerHtml = "<li class='active disabled'><a href=''>...</a></li>";

// 		Form_Form::$defaultEntryAttributes["submitClass"] = array();
// 		Form_Form::$defaultEntryAttributes["submitClass"]["Salva"] = "btn btn-success";
// 		Form_Form::$defaultEntryAttributes["submitClass"]["Salva"] = "btn btn-primary";

Helper_Popup::$popupHtml["before_loop"] = "<div class='row'>";
Helper_Popup::$popupHtml["top"] = "<div class='col-md-2'><div class='btn-group'><button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'>[[name]] <span class='caret'></span></button><ul class='dropdown-menu' role='menu'>";
Helper_Popup::$popupHtml["middle"] = "</ul>\n</div>\n";
Helper_Popup::$popupHtml["bottom"] = "</div>";
Helper_Popup::$popupHtml["after_loop"] = "</div>";

Helper_List::$tableAttributes = array('class'=>'table table-striped table-scaffolding','cellspacing'=>'0');

Helper_List::$actionsLayout = array(
	"edit"	=>	array(
		"text"	=>	"<i class='text_16 verde fa fa-arrow-right'></i>",
		"attributes"	=>	array(
			"class"	=>	"action_edit",
		),
	),
	"del"	=>	array(
		"attributes"	=>	array(
			"title"	=>	"elimina",
			"class"	=>	"text text-danger del_button",
		),
		"text"	=>	"<i class='fa fa-trash-o'></i>",
	),
);

if (v("attiva_azioni_ajax"))
	Helper_List::$actionsLayout["del"]["attributes"]["class"] .= " ajlink";

Helper_List::$filtersFormLayout = array(
	"form"	=>	array(
		"attributes"	=>	array(
			"class"	=>	"form-inline list_filter_form",
		)
// 		"innerWrap"	=>	array(
// 			"<div class='filters_form'>","</div>"
// 		)
	),
	"filters"	=>	array(
		"titolo"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control help_cerca",
				"placeholder"	=>	"Cerca ..",
			),
// 			"wrap"	=>	array(
// 				"<div class='col-md-2'>","</div>"
// 			)
		),
		"attivo"	=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control"
			),
// 			"wrap"	=>	array(
// 				"<div class='col-md-2'>","</div>"
// 			),
		),
		"username"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Email ..",
			),
		),
		"codice_fiscale"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"CF / P.IVA ..",
			),
		),
		"dal"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control data_field",
				"placeholder"	=>	"Dal",
			),
			"wrap"	=>	array(
				'<div class="input-group date">','<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>'
			),
		),
		"al"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control data_field",
				"placeholder"	=>	"Al",
			),
			"wrap"	=>	array(
				'<div class="input-group date">','<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>'
			),
		),
		"p_iva"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"P.IVA ..",
			),
		),
		"title"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca ..",
			),
		),
		"titolo_contenuto"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca ..",
			),
		),
		"lingua"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"tipocontenuto"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"prodotto"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Prodotto..",
			),
		),
		"categoria"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Categoria..",
			),
		),
		"codice"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Codice..",
			),
		),
		"email"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Email..",
			),
		),
		"id_ordine"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Ordine..",
			),
		),
		"valore"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca IT..",
			),
		),
		"lingua_doc"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"lingua_page"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"lingua_page_escl"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"titolo_documento"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca ..",
			),
		),
		"id_tipo_doc"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"tradotta"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"tipo"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"tipo_cliente"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"stato"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"attiva"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"attiva_spedizione"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_tag"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"in_promozione"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"in_evidenza"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"nuovo"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"nazione_utente"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"st_giac"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"chiave"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca ..",
			),
		),
		"imm_1"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Cerca ..",
			),
		),
		"id_tipologia_caratteristica"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_car_f"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control help_filtro_caratteristica",
			),
		),
		"tipo_testo"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_nazione"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"tipo_carrello"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"fonte"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_c"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"-id_marchio"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_naz"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"style"	=>	"max-width:150px;",
			),
		),
		"id_reg"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"style"	=>	"max-width:150px;",
			),
		),
		"verificato"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"id_ruolo"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"vecchio_url"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Vecchio URL ..",
			),
		),
		"nuovo_url"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Nuovo URL ..",
			),
		),
		"codice_app"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"deleted"		=>	array(
			"type"	=>	"select",
			"attributes"	=>	array(
				"class"	=>	"form-control",
			),
		),
		"token_eliminazione"	=>	array(
			"attributes"	=>	array(
				"class"	=>	"form-control",
				"placeholder"	=>	"Codice eliminazione",
			),
		),
	),
	"clear"	=>	"",
	"submit"	=>	array(
		"text"	=>	"Filtra",
		"attributes"	=>	array(
			"class"	=>	"btn btn-success"
		),
// 		"wrap"	=>	array(
// 			"<div class='col-md-2'>","</div>"
// 		)
	),
);

if (defined("FILTRI_AGGIUNTIVI") && is_array(FILTRI_AGGIUNTIVI))
{
	foreach (FILTRI_AGGIUNTIVI as $asta => $filtro)
	{
		Helper_List::$filtersFormLayout["filters"][$asta] = $filtro;
	}
}


$tempFilters = array();

foreach (Helper_List::$filtersFormLayout["filters"] as $key => $filter)
{
	$filter["attributes"]["autocomplete"] = "off";
	
	$tempFilters[$key] = $filter;
}

Helper_List::$filtersFormLayout["filters"] = $tempFilters;

if (defined("APPS")) {
	foreach (APPS as $app)
	{
		$path = ROOT."/Application/Apps/".ucfirst($app)."/Hooks/BeforeChecks.php";
		
		if (file_exists($path))
			include($path);
	}
}
