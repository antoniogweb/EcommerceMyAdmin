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

use PHPUnit\Framework\TestCase;

define('APP_CONSOLE', true);

require_once(dirname(__FILE__) . "/../../index.php");
require_once(LIBRARY . '/External/libs/vendor/autoload.php');

Params::$lang = "it";
ImpostazioniModel::init();

final class PagesModelTest extends TestCase
{
	public function testCampoCerca(): void
    {
		$idShop = CategoriesModel::g(false)->getShopCategoryId();
		
		$p = new PagesModel();
		
		$p->sValues(array(
			"title"	=>	"Test",
			"alias"	=>	"",
			"id_c"	=>	$idShop,
		));
		
		$p->insert();
		
		$lId = $p->lId;
		
		$record = $p->clear()->selectId((int)$lId);
		
		// Controllo la creazione della pagina
		$this->assertNotEmpty($record, "Check creazione pagina");
		
		if (v("mostra_filtro_ricerca_libera_in_magazzino"))
			$this->assertTrue((string)$record["campo_cerca"] !== "", "check creazione campo cerca");
		
		$p->del((int)$lId);
		
		$numero = $p->clear()->whereId((int)$lId)->rowNumber();
		
		// Controllo l'eliminazione della pagina
		$this->assertTrue((int)$numero === 0, "check eliminazione pagina");
    }
}

