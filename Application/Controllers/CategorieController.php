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

class CategorieController extends CategoriesController {

	public $voceMenu = "categorie";
	public $sezionePannello = "ecommerce";
	
	public $queryFields = "title,alias,sottotitolo,id_p,mostra_in_home,description,immagine";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (v("attiva_mostra_in_menu"))
			$this->queryFields .= ",mostra_in_menu";
		
		if (v("scegli_il_corriere_dalla_categoria_dei_prodotti"))
			$this->queryFields .= ",id_corriere";
		
		if (v("attiva_strumenti_merchant_google"))
			$this->queryFields .= ",codice_categoria_prodotto_google";
		
		if (v("attiva_margine_in_prodotti"))
			$this->queryFields .= ",margine";
		
		if (v("mostra_seconda_immagine_categoria_prodotti"))
			$this->queryFields .= ",immagine_2";
		
		if (v("mostra_colore_testo"))
			$this->queryFields .= ",colore_testo_in_slide";
		
		if (v("attiva_immagine_sfondo"))
			$this->queryFields .= ",immagine_sfondo";
			
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
	}

}
