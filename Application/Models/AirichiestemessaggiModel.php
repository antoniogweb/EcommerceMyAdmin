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

class AirichiestemessaggiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ai_richieste_messaggi';
		$this->_idFields = 'id_ai_richiesta_messaggio';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}

	public function relations() {
		return array(
			'richiesta' => array("BELONGS_TO", 'AirichiesteModel', 'id_ai_richiesta',null,"CASCADE"),
		);
    }

    public function getMessaggi($idRichiesta, $numero = false)
	{
		$this->clear()->where(array(
			"id_ai_richiesta"	=>	(int)$idRichiesta,
		));

		return $numero ? $this->rowNumber() : $this->orderBy("id_order")->send(false);
	}

	public function getMessaggioDefault($idRichiesta)
	{
		$testoDefault = "";

		$airModel = new AirichiesteModel();

		$richiesta = $airModel->selectId((int)$idRichiesta);

		$numero = $this->getMessaggi($idRichiesta, true);

		if (!empty($richiesta) && $numero <= 0 && v("default_primo_messaggio_ai"))
		{
			$numeroContesti = $airModel->numeroContesti($idRichiesta);

			if ($numeroContesti <= 1)
				return "";

			$testoDefault = v("default_primo_messaggio_ai");

			$testoDefault = str_replace("[INDIRIZZO SITO WEB]", str_replace("/admin","",DOMAIN_NAME), $testoDefault);

			$nomeCategoria = CategoriesModel::g(false)->select("title")->whereId((int)$richiesta["id_c"])->field("title");
			$nomeMarchio = MarchiModel::g(false)->select("titolo")->whereId((int)$richiesta["id_marchio"])->field("titolo");
			$nomePagina =PagesModel::g(false)->select("title")->whereId((int)$richiesta["id_page"])->field("title");

			$contesto = "su ...";

			if ($nomeCategoria && $nomeMarchio)
				$contesto = 'sulla categoria "'.$nomeCategoria.'" del marchio "'.$nomeMarchio.'"';
			else if ($nomeCategoria)
				$contesto = 'sulla categoria "'.$nomeCategoria.'"';
			else if ($nomeMarchio)
				$contesto = 'sul marchio "'.$nomeMarchio.'"';
			else if ($nomePagina)
				$contesto = 'sul prodotto "'.$contesto.'"';

			$testoDefault = str_replace("[CONTESTO]", $contesto, $testoDefault);
		}

		return $testoDefault;
	}
}
