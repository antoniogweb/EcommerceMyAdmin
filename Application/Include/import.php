<?php
// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

require_once(ROOT."/../Application/Include/import_contenuti.php");

class Import
{
	public static function prodotti()
	{
		ImportContenuti::prodotti();
	}
	
	public static function utenti()
	{
		ImportContenuti::utenti();
	}
	
	public static function news()
	{
		ImportContenuti::news();
	}
	
	public static function contenuti()
	{
		if (method_exists("ImportContenuti", "contenuti"))
			ImportContenuti::contenuti();
	}
}
