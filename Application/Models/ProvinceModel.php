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

class ProvinceModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'province';
		$this->_idFields = 'id_prov';

		parent::__construct();
	}
	
	public function selectTendina($campoPostNazione = "", $mostraElementoVuoto = true)
	{
		$this->orderBy("provincia")->toList("codice_provincia","provincia");
		
		if (v("mostra_solo_province_attive") && App::$isFrontend)
			$this->aWhere(array(
				"attiva"	=>	1,
			));
		
		if (v("gestisci_province_estere"))
		{
			if ($campoPostNazione && isset($_POST["$campoPostNazione"]) && $_POST["$campoPostNazione"] && NazioniModel::esistente(sanitizeAll($_POST["$campoPostNazione"])))
				$this->aWhere(array(
					"nazione"	=>	sanitizeAll($_POST["$campoPostNazione"]),
				));
		}
		else
			$this->aWhere(array(
				"nazione"	=>	v("nazione_default"),
			));
		
		if (App::$isFrontend)
			$this->sWhere("nazione in (select iso_country_code from nazioni where attiva = 1 or attiva_spedizione = 1)");
		
		if ($mostraElementoVuoto)
			return array(""=>gtext("Seleziona")) + $this->send();
		else
			return $this->send();
	}
	
	public static function sFindTitoloDaCodice($codice)
	{
		return self::g()->findTitoloDaCodice($codice);
	}
	
	public function findTitoloDaCodice($codice)
	{
		return $this->clear()->select("codice_provincia")->where(array(
			"codice_provincia"	=>	sanitizeAll($codice),
		))->field("provincia");
	}
	
	public function findDaCodice($codice)
	{
		$record = $this->clear()->where(array(
			"codice_provincia"	=>	sanitizeAll($codice),
		))->record();
		
		if (!empty($record))
			return $record["id_prov"];
		
		return 0;
	}
	
	public static function recuperaCodice($testo)
	{
		$pModel = new ProvinceModel();
		
		$codice = $pModel->clear()->select("codice_provincia")->where(array(
			"OR"	=>	array(
				"codice_provincia"	=>	sanitizeAll($testo),
				"provincia"	=>	sanitizeAll($testo),
			),
		))->field("codice_provincia");
		
		if ($codice)
			return $codice;
		
		return $testo;
	}
	
	public function selectArray($isSpedizione = false)
	{
		$whereArray = array();
		
		if ($isSpedizione)
		{
			$whereArray = array(
				"visibile_spedizione"	=>	1
			);
		}
		
		$res = $this->where($whereArray)->orderBy("provincia")->send(false);
		
		$selectArray = array(
			array(
				"codice_provincia"	=>	"",
				"provincia"	=>	"Seleziona",
			)
		);
		
		foreach ($res as $r)
		{
			$selectArray[] = array(
				"codice_provincia"	=>	$r["codice_provincia"],
				"provincia"	=>	htmlentitydecode($r["provincia"]),
			);
		}
		
		return $selectArray;
	}
}
