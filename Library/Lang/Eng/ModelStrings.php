<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Lang_Eng_ModelStrings extends Lang_ResultStrings {
	
	public $string = array(
		"error" => "<div class='alert'>Query error: Contact the administrator!</div>\n",
		"executed" => "<div class='executed'>Operation executed!</div>\n",
		"associate" => "<div class='alert'>Referential integrity problem: record associated to some other record in a child table. Break the association before.</div>\n",
		"no-id" => "<div class='alert'>Alert: record identifier not defined!</div>\n",
		"not-linked" => "<div class='alert'>The Item is not associated : you can't dissociate it</div>",
		"linked" => "<div class='alert'>The Item is already associated: you can't associate it another time</div>",
		"not-existing-fields" => "<div class='alert'>Some fields in the query do not exist</div>\n",
	);
	
}
