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

TraduzioniModel::$contestoStatic = "back";

Params::$setValuesConditionsFromDbTableStruct = true;
Params::$automaticConversionToDbFormat = true;
Params::$automaticConversionFromDbFormat = true;
Params::$automaticallySetFormDefaultValues = true;

Helper_Menu::$htmlLinks = array(
	"back" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-default"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-circle-arrow-left"></span> '."Torna",
	),
	"add" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-plus"></span> '."Nuovo elemento",
	),
	"esporta" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info"',
		"class"	=>	"btn btn-info",
		'text'	=>	'<i class="fa fa-download"></i> '."Esporta",
		'url'	=>	'main',
		'queryString'	=>	'&esporta=Y',
	),
	"importa" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning importa_traduzioni" data-toggle="collapse" href="#box_form_import_traduzioni" role="button"',
		"class"	=>	"btn btn-info",
		'text'	=>	'<i class="fa fa-upload"></i> '."Importa",
		'url'	=>	'main',
		'queryString'	=>	'&importa=Y',
	),
	"stampa" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-info pull-right stampa_pagina"',
		"class"	=>	"btn btn-info",
		'text'	=>	'<span class="glyphicon glyphicon-download-alt"></span> '."Stampa",
		'url'	=>	'main',
	// 				'queryString'	=>	'&esporta=Y',
	),
	"pdf" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'target="_blank" role="button" class="btn btn-info pull-right"',
		"class"	=>	"btn btn-info",
		'text'	=>	'<span class="glyphicon glyphicon-download-alt"></span> '."Pdf",
		'url'	=>	'form/update',
		'queryString'	=>	'&pdf=Y&skip=Y',
	),
	"report" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-default pull-right margin-left-button iframe"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-font"></span> '."Report",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=Y&skip=Y&partial=Y&nobuttons=N',
	),
	"report_full" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'target="_blank" role="button" class="btn btn-default pull-right margin-left-button"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-resize-full"></span> '."Schermo intero",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=Y&skip=Y&partial=Y&nobuttons=Y',
	),
	"modifica" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success"',
		"class"	=>	"btn btn-success",
		'text'	=>	'<span class="fa fa-edit"></span> '."Modifica",
		'url'	=>	'form/update',
		'queryString'	=>	'&report=N&buttons=Y',
	),
	"panel" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-home"></span> '."Home",
	),
	"copia" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-repeat"></span> '."Duplica",
	),
	"torna_ordine" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-circle-arrow-left"></span> '."Torna all' ordine",
	),
	"edit" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-edit"></span> '."Modifica",
	),
	"manda_mail" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="fa fa-envelope"></span> '."Invia mail",
	),
	"refresh" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-primary"',
		"class"	=>	"btn btn-default",
		'text'	=>	'<span class="glyphicon glyphicon-repeat"></span> '."Aggiorna",
	),
	'save'	=>	array(
		'title'	=>	"salva",
		'text'	=>	'<span class="glyphicon glyphicon-ok"></span> '."Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_button menu_btn"',
	),
	'save_2'	=>	array(
		'title'	=>	"salva",
		'text'	=>	'<span class="glyphicon glyphicon-ok"></span> '."Salva",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" style="margin-left:100px;" class="btn btn-success menu_btn"',
	),
	'resetta'	=>	array(
		'title'	=>	"resetta",
		'text'	=>	'<span class="glyphicon glyphicon-adjust"></span> '."Resetta",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-warning resetta_button"',
	),
	'elimina'	=>	array(
		'title'	=>	"elimina",
		'text'	=>	'<span class="glyphicon glyphicon-remove"></span> '."Elimina",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-danger elimina_button"',
	),
	'elimina_2'	=>	array(
		'title'	=>	"elimina",
		'text'	=>	'<span class="glyphicon glyphicon-remove"></span> '."Elimina",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
	),
	'save_combinazioni'	=>	array(
		'title'	=>	"salva",
		'text'	=>	'<i class="fa fa-refresh"></i> '."Salva valori",
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="btn btn-success save_combinazioni menu_btn"',
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
				"class"	=>	"form-control",
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
			"wrap"	=>	array(
				'<div class="input-group date">','<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>'
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
