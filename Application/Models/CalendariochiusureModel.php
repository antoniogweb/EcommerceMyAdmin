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
