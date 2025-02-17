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

class Lang_It_ModelStrings extends Lang_ResultStrings {
	
	public $string = array(
		"error" => "<div class='alert'>Errore nella query: contatta l'amministratore!</div>\n",
		"executed" => "<div class='uk-alert uk-alert-success'>operazione eseguita!</div>\n",
		"associate" => "<div class='alert'>Problema di integrit&agrave referenziale: il record &egrave associato ad un record di una tabella figlia. Devi prima rompere l'associazione.</div>\n",
		"no-id" => "<div class='alert'>Non &egrave definito alcun id della query</div>\n",
		"not-linked" => "<div class='alert'>Il record non &egrave associato, non puoi dissociarlo</div>",
		"linked" => "<div class='alert'>Il record &egrave gi&agrave associato, non puoi associarlo un'altra volta</div>",
		"not-existing-fields" => "<div class='alert'>Alcuni campi della tabella non sono esistenti</div>\n",
		"no-fields" => "<div class='alert alert-danger'>Si prega di selezionare dei campi</div>\n",
	);
	
}
