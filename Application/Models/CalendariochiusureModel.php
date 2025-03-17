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

class CalendariochiusureModel extends GenericModel
{
	public $errore = "";
	
	public function __construct() {
		$this->_tables='calendario_chiusure';
		$this->_idFields='id_calendario';
		
		parent::__construct();
	}
	
	public static function arrayGiorniChiusura()
	{
		$cModel = new CalendariochiusureModel();
		
		return $cModel->clear()->where(array(
			"gte"	=>	array(
				"data_chiusura"	=>	date("Y-m-d"),
			)
		))->toList("data_chiusura")->send();
	}
	
	public static function isGiornoChiusura($date, $giorniSettimanaEsclusi = array(0))
	{
		if (in_array($date->format("Y-m-d"), self::arrayGiorniChiusura()) || in_array($date->format("w"), $giorniSettimanaEsclusi))
			return true;
		
		return false;
	}
	
	// Restituisce un array con le date nel formato "Y-m-d" => $formatLabel dei $numeroGiorni successivi alla data $dal (DateTime), escludendo i giorni di chiusura e i giorni della settimana dove date("w") è in $giorniSettimanaEsclusi
	public static function nextXDays($dal, $numeroGiorni = 1, $formatLabel = "d-m-Y", $arrayDate = array(), $giorniSettimanaEsclusi = array(0))
	{
		if ((int)$numeroGiorni === 0)
			return $arrayDate;
		
		if (!self::isGiornoChiusura($dal, $giorniSettimanaEsclusi))
		{
			$arrayDate[$dal->format("Y-m-d")] = $dal->format($formatLabel);
			
			
			$numeroGiorni--;
		}
		
		$dal->modify("+1 days");
		
		$arrayDate = self::nextXDays($dal, $numeroGiorni, $formatLabel, $arrayDate);
		
		return $arrayDate;
	}
	
	public function aggiungiDate($dal, $al)
	{
		if ($dal)
			$dal = getIsoDate($dal);
		
		if ($al)
			$al = getIsoDate($al);
		// echo $al;die();
		if (checkIsoDate($dal) && (!$al || checkIsoDate($al)))
		{
			if (!$al)
				$al = $dal;
			
			$dalObject = new DateTime($dal);
			$alObject = new DateTime($al);
			$oggi = new DateTime(date("Y-m-d"));
			
			if ($dalObject >= $oggi && $dalObject <= $alObject)
			{
				$period = new DatePeriod($dalObject, new DateInterval('P1D'), new DateTime($al.' +1 day'));
				
				if (iterator_count($period) > 120)
				{
					$this->errore = gtext("Attenzione, non è impossibile impostare un intervallo maggiore di 120 giorni");
					
					return;
				}
				
				foreach ($period as $value)
				{
					if (!$this->clear()->where(array(
						"data_chiusura"	=>	sanitizeAll($value->format("Y-m-d"))
					))->rowNumber())
					{
						$this->sValues(array(
							"data_chiusura"	=>	$value->format("Y-m-d")
						));
						
						$this->insert();
					}
				}
			}
			else
				$this->errore = gtext("Attenzione, controllare che la data di inizio non sia nel passato e che sia precedente alla data di fine");
		}
		else
			$this->errore = gtext("Attenzione, controllare le date di inizio e fine");
	}
}
