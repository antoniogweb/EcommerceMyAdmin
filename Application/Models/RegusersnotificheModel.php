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

class RegusersnotificheModel extends GenericModel
{
	public function __construct() {
		$this->_tables='regusers_notifiche';
		$this->_idFields='id_regusers_notifiche';
		
		parent::__construct();
	}
	
	public function daleggere($count = false)
	{
		$d = new DocumentiModel();
		$d->clear()
			->select("documenti.*,pages.title,categories.title,documenti_tradotti.titolo,pagine_tradotte.title,categorie_tradotte.title,tipi_documento.titolo")
			->inner("pages")->on("pages.id_page = documenti.id_page")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->addJoinTraduzione(null, "documenti_tradotti", false)
			->addJoinTraduzione(null, "pagine_tradotte", false, (new PagesModel()))
			->addJoinTraduzione(null, "categorie_tradotte", false, (new CategoriesModel()))
			->left(array("tipo"))
			->sWhere("documenti.id_doc not in (select id_doc from regusers_notifiche where id_user = ".(int)User::$id.")")
			->where(array(
				"gte"	=>	array(
					"data_documento"	=>	sanitizeDb(date("Y-m-d",User::$dettagli["creation_time"])),
				),
				"pages.attivo"			=>	"Y",
				"documenti.visibile"	=>	1,
				"in"	=>	array(
					"categories.id_c"	=>	CategoriesModel::getIdCategorieAccessibili(),
				),
				"ne"	=>	array(
					"documenti.filename"	=>	"",
				),
			))
			->groupBy("documenti.id_doc");
		
		if (v("attiva_gruppi_documenti"))
			$d->addAccessoGruppiWhereClase();
		
		if ($count)
			return $d->rowNumber();
		
		$revisioni = $d->orderBy("data_documento desc")->limit(5)->send();
		// echo $d->getQuery();die();
		
		return $revisioni;
	}
	
	public function aggiungiDocumento($idDoc)
	{
		$d = new DocumentiModel();
		$documento = $d->selectId((int)$idDoc);
		
		if (!empty($documento))
		{
			$numero = $this->clear()->where(array(
				"id_user"	=>	(int)User::$id,
				"id_doc"	=>	(int)$documento["id_doc"],
			))->rowNumber();
			
			if (!$numero)
			{
				$this->sValues(array(
					"id_user"	=>	(int)User::$id,
					"id_page"	=>	(int)$documento["id_page"],
					"id_doc"	=>	(int)$documento["id_doc"],
					"tipo"		=>	"DOCUMENTO",
				));
				
				$this->insert();
			}
		}
	}
}
