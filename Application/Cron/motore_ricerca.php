#!/usr/bin/php
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

define('APP_CONSOLE', true);

$options = getopt(null, array(
	"lingua::",
	"operazione::"
));

$default = array(
	"lingua"		=>	"it",
	"operazione"	=>	"cerca",
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

Files_Log::$logFolder = LIBRARY."/Logs";
$log = Files_Log::getInstance("motori_ricerca");

if (MotoriricercaModel::getModulo()->isAttivo())
{
// 	echo "attivo";
}

