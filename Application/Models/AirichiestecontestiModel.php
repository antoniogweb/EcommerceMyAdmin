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

class AirichiestecontestiModel extends GenericModel
{
	public static $controllaNumeroPagineContesto = true;

	public function __construct() {
		$this->_tables = 'ai_richieste_contesti';
		$this->_idFields = 'id_ai_richiesta_contesto';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}

	public function relations() {
		return array(
			'richiesta' => array("BELONGS_TO", 'AirichiesteModel', 'id_ai_richiesta',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
		);
    }

    public function insert()
	{
		$idRichiesta = isset($this->values["id_ai_richiesta"]) ? (int)$this->values["id_ai_richiesta"] : 0;

		if (!$idRichiesta)
			return false;

		$numeroMassimoContesti = AirichiesteModel::g(false)->numeroMassimoPagineContesto($idRichiesta);

		if (!self::$controllaNumeroPagineContesto)
			$numeroMassimoContesti = 99999999;

		$numeroContestiDellaRichiesta = $this->clear()->where(array(
			"id_ai_richiesta"	=>	(int)$idRichiesta,
		))->rowNumber();

		if ($numeroContestiDellaRichiesta < $numeroMassimoContesti)
		{
			$this->values["id_admin"] = User::$id;

			return parent::insert();
		}
		else
		{
			$this->result = false;
			$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione, il numero massimo di pagina che si possono usare come contesto è")." ".$numeroMassimoContesti."</a>";
			return false;
		}
	}

	public function bulksegnaimportante($record)
    {
		if ($record["ai_richieste_contesti"]["importante"])
			return "<i data-azione='settanonimportante' title='".gtext("Segna come NON importante")."' class='bulk_trigger help_trigger_rendi_non_importante fa fa-check text text-success'></i>";
		else
			return "<i data-azione='settaimportante' title='".gtext("Segna come importante")."' class='bulk_trigger help_trigger_rendi_importante fa fa-ban text'></i>";
    }

    public function settanonimportante($id)
	{
		$this->setValues(array(
			"importante"	=>	0
		));

		$this->update((int)$id);
	}

	public function settaimportante($id)
	{
		$this->setValues(array(
			"importante"	=>	1
		));

		$this->update((int)$id);
	}

	public function getContesto($idRichiesta)
	{
		$pagine = $this->clear()->select("ai_richieste_contesti.importante,pages.title,pages.description,pages.id_page,pages.id_c,pages.id_marchio")->inner(array("pagina"))->where(array(
			"id_ai_richiesta"	=>	(int)$idRichiesta,
		))->orderBy("ai_richieste_contesti.id_order")->send();

		$contesto = "";

		$indice = 1;

		foreach ($pagine as $p)
		{
			$importante = $p["ai_richieste_contesti"]["importante"] ? "\nesempio di prodotto" : "";
			$stringaId = "\nID PRODOTTO: ".$p["pages"]["id_page"];
			$stringaId .= "\nID MARCHIO: ".$p["pages"]["id_marchio"];
			$stringaId .= "\nID CATEGORIA: ".$p["pages"]["id_c"];

			$contesto .= "PRODOTTO $indice:\nTITOLO PRODOTTO $indice: ".htmlentitydecode($p["pages"]["title"]).$importante.$stringaId."\nCONTENUTO PRODOTTO $indice: ".strip_tags(htmlentitydecode($p["pages"]["description"]))."\n\n";

			$indice++;
		}

		return $contesto;
	}
}
