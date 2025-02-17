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

TraduzioniModel::getInstance()->ottieniTraduzioni();

Form_Entry::$defaultLabelClass = "uk-form-label";
Form_Entry::$defaultWrap = array('<div class="uk-margin">',null,'<div class="uk-form-controls">','</div>',"</div>");
Form_Entry::$defaultInputClasses["input"] = "uk-input";
Form_Entry::$defaultInputClasses["select"] = "uk-select";

Helper_List::$defaultFilterAttributes["input"] = array(
	"class"	=>	"uk-input uk-form-width-medium uk-form-small uk-margin-small-right",
);

Helper_List::$defaultFilterAttributes["select"] = array(
	"class"	=>	"uk-select uk-form-width-medium uk-form-small uk-margin-small-right",
);

Helper_List::$bulkActionsSelectAdditionalClass = "uk-select uk-form-width-medium uk-form-small";

Helper_Menu::$htmlLinks = array(
	"back" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'class="uk-button uk-button-primary uk-button-small"',
		'text'	=>	gtext("Torna"),
		"classIconBefore"	=>	'<span uk-icon="icon: arrow-left"></span>',
	),
	"add" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'class="uk-button uk-button-primary uk-button-small"',
		'text'	=>	gtext("Nuovo"),
		"classIconBefore"	=>	'<span uk-icon="icon: plus"></span>',
	),
	'save'	=>	array(
		'title'	=>	gtext("Salva"),
		'text'	=>	gtext("Salva"),
		'url'	=>	'main',
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="uk-button uk-button-default uk-button-small save_button_frontend menu_btn"',
		"classIconBefore"	=>	'<span uk-icon="icon: check"></span>',
	),
	"copia" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'role="button" class="uk-button uk-button-default uk-button-small"',
		'text'	=>	gtext("Duplica"),
		"classIconBefore"	=>	'<span uk-icon="icon: copy"></span>',
	),
);

Form_Form::$defaultEntryAttributes["submitClass"] = "uk-button uk-button-secondary submit_entry";

Helper_List::$tableAttributes = array('class'=>'uk-table uk-table-divider uk-table-striped uk-table-hover uk-table-small table-scaffolding','cellspacing'=>'0');

Helper_List::$actionsLayout = array(
	"edit"	=>	array(
		"text"	=>	'<span uk-icon="pencil"></span>',
		"attributes"	=>	array(
			"title"	=>	gtext("Modifica"),
			"class"	=>	'action_edit',
		),
	),
	"del"	=>	array(
		"attributes"	=>	array(
			"title"	=>	gtext("Metti nel cestino"),
		),
		"text"	=>	'<span class="uk-text-danger" uk-icon="trash"></span>',
	),
);

Helper_List::$filtersFormLayout = array(
	"form"	=>	array(
		"attributes"	=>	array(
			"class"	=>	"form-inline list_filter_form",
		)
	),
	"clear"	=>	"",
	"submit"	=>	array(
		"text"	=>	'<span class="uk-margin-small-right" uk-icon="icon: search"></span>'.gtext("Filtra"),
		"attributes"	=>	array(
			"class"	=>	"uk-button uk-button-small uk-button-secondary"
		),
	),
);
