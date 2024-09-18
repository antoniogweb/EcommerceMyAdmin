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

class LingueModel extends GenericModel
{
	public static $valori = null;
	public static $valoriAttivi = null;
	public static $codici = null;
	public static $lingueBackend = array();
	public static $principaleFrontend = null;
	public $campoTitolo = "descrizione";

	public function __construct() {
		$this->_tables = 'lingue';
		$this->_idFields = 'id_lingua';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function getValoriAttivi()
	{
		$l = new LingueModel();
		
		if (!isset(self::$valoriAttivi))
		{
			$l->clear()->orderBy("id_order")->toList("codice","descrizione");
			
			$l->where(array(
				"attiva"	=>	1,
			));
			
			self::$valoriAttivi = $l->send();
		}
		
		return self::$valoriAttivi;
	}
	
	// Controlla che la lingua esista e sia attiva
	public static function checkLinguaAttiva($lingua)
	{
		$lingue = self::getValoriAttivi();
		
		if (isset($lingue[$lingua]))
			return true;
		
		return false;
	}
	
	public static function getPrincipaleFrontend()
	{
		$l = new LingueModel();
		
		if (!isset(self::$principaleFrontend))
		{
			$l->clear()->orderBy("id_order")->where(array(
				"principale"	=>	1,
				"attiva"		=>	1,
			));
			
			$codice = $l->field("codice");
			
			if ($codice)
				self::$principaleFrontend = $codice;
		}
		
		return self::$principaleFrontend;
	}
	
	public static function getValori($soloAttive = false)
	{
		$l = new LingueModel();
		
		if (!isset(self::$valori))
		{
			$l->clear()->orderBy("id_order")->toList("codice","descrizione");
			
			self::$valori = $l->send();
		}
		
		return self::$valori;
	}
	
	public static function getCodici()
	{
		$l = new LingueModel();
		
		if (!isset(self::$codici))
			self::$codici = $l->clear()->orderBy("id_order")->toList("codice_clean","codice")->send();
		
		return self::$codici;
	}
	
	public static function getPrincipale()
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"principale"	=>	1
		))->field("codice");
	}
	
	public static function getLingueBackend()
	{
		self::$lingueBackend = self::getLingue(true);
	}
	
	public static function linguaPermessaBackend($lang)
	{
		if (isset(self::$lingueBackend[$lang]))
			return true;
		
		return false;
	}
	
	public static function getLingue($soloBackend = false)
	{
		$l = new LingueModel();
		
		$l->clear()->orderBy("id_order")->toList("codice", "descrizione");
		
		if ($soloBackend)
			$l->aWhere(array(
				"backend"	=>	1,
			));
		else
			$l->aWhere(array(
				"attiva"	=>	1,
			));
		
		return $l->send();
	}
	
	public static function titoloLinguaCorrente()
	{
		if (isset(self::$lingueBackend[Params::$lang]))
			return self::$lingueBackend[Params::$lang];
		
		return "";
	}
	
	public static function permettiCambioLinguaBackend()
	{
		self::getLingueBackend();
		
		if (!empty(self::$lingueBackend) && v("permetti_cambio_lingua"))
			return true;
		
		return false;
	}
	
	public static function getIdDaCodice($codice)
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"codice"	=>	sanitizeAll($codice),
		))->field("id_lingua");
	}
	
	public static function getLingueNonPrincipali()
	{
		$l = new LingueModel();
		
		return $l->clear()->where(array(
			"principale"	=>	0,
			"attiva"		=>	1,
		))->orderBy("id_order")->toList("codice")->send();
	}

	// Attiva la lingua e genera tutte le traduzioni
	public function attivaLingua($lingua)
	{
		$record = $this->clear()->where(array(
			"codice"	=>	sanitizeAll($lingua),
			"attiva"	=>	0
		))->record();

		if (!empty($record))
		{
			$this->sValues(array(
				"attiva"	=>	1,
			));

			if ($this->update($record["id_lingua"]))
			{
				// Genero tutte le traduzioni
				ContenutitradottiModel::rigeneraTraduzioni();

				// Rigenero tutti gli alias
				CombinazioniModel::g()->aggiornaAlias();
			}
		}
	}

	public function principaleCrud($record)
	{
		if ($record["lingue"]["principale"])
			return "<i class='fa fa-check text-success'></i>";

		return "";
	}

	public function attivaCrud($record)
	{
		if ($record["lingue"]["attiva"])
			return "<i class='fa fa-check text-success'></i>";

		return "";
	}
}
