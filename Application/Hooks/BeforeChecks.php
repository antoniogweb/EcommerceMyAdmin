<?php

// All MvcMyLibrary code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

//in this file you can write the PHP code that will be executed at the beginning of the MvcMyLibrary execution, before super global array have been sanitizied

//this is the preferred place to create and fill log files

//you can access the whole set of classes and functions of MvcMyLibrary

VariabiliModel::ottieniVariabili();

VariabiliModel::$valori["alert_error_class"] = "alert";

if (v("usa_https"))
	Params::$useHttps = true;

// Imposto le app
if (defined("APPS"))
{
	Params::$installed = APPS;
	
	foreach (APPS as $app)
	{
		$path = ROOT."/Application/Apps/".ucfirst($app)."/Route/route.php";
		
		if (file_exists($path))
		{
			unset($APP_ROUTE);
			
			include($path);
			
			if (isset($APP_ROUTE))
				Route::$allowed = array_merge(Route::$allowed, $APP_ROUTE);
		}
	}
}

require(LIBRARY."/External/mobile_detect.php");

$detect = new Mobile_Detect();
User::$isMobile = $detect->isMobile();

$mysqli = Db_Mysqli::getInstance();
$mysqli->query("set session sql_mode=''");

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
		"attributes" => 'role="button" class="btn btn-primary"',
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
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Modifica",
		"classIconBefore"	=>	'<i class="fa fa-pencil"></i>',
	),
	"manda_mail" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Invia mail",
		"classIconBefore"	=>	'<i class="fa fa-envelope"></i>',
	),
	"refresh" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	"Aggiorna",
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
	'save'	=>	array(
		'title'	=>	"salva",
		'text'	=>	"Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_button menu_btn"',
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
		"attributes" => 'role="button" class="btn btn-success save_combinazioni menu_btn"',
		"classIconBefore"	=>	'<i class="fa fa-refresh"></i>',
	),
);

Scaffold::$autoParams["formMenu"] = "panel,back,resetta,save,elimina";

Form_Form::$defaultEntryAttributes["className"] = "form-control";
Form_Form::$defaultEntryAttributes["submitClass"] = "btn btn-success";

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
				"placeholder"	=>	"Cerca ..",
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
				"placeholder"	=>	"Cerca ..",
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
		"id_o"	=>	array(
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
