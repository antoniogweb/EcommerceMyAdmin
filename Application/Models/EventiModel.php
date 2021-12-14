<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class EventiModel extends PagesModel {
	
	public $hModelName = "EventicatModel";
	
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
			'id_c'	=>	'id_c',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
			'id_c'	=>	'CATEGORIA',
			'in_evidenza'	=>	'IN EVIDENZA?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
			'id_c'	=>	'getCatNameForFilters',
		);
		
		if (isset($this->hModel->section))
			$this->_popupWhere["id_c"] = $this->hModel->getChildrenFilterWhere();
	}
	
	public static function estremiEvento($id)
	{
		$ev = new EventiModel();
		
		$record = $ev->selectId((int)$id);
		
		if (!empty($record))
		{
			if (checkIsoDate($record["data_inizio_evento"]) && checkIsoDate($record["data_fine_evento"]))
			{
				$start = DateTime::createFromFormat("Y-m-d H:i:s", $record["data_inizio_evento"]." ".$record["ora_inizio_evento"]);
				
				$end = DateTime::createFromFormat("Y-m-d H:i:s", $record["data_fine_evento"]." ".$record["ora_fine_evento"]);
				
				return array($start, $end);
			}
		}
		
		return array(null, null);
	}
	
	public static function getElementiFascia($numero = 0)
	{
		$e = new EventiModel();
		
		if (!$numero)
			$numero = v("numero_eventi_home");
		
		return $e->clear()->select("*")
			->addJoinTraduzionePagina()
			->where(array(
				"attivo"	=>	"Y",
				"id_c"		=>	(int)CategoriesModel::getIdCategoriaDaSezione("eventi"),
			))->limit($numero)->orderBy("pages.data_inizio_evento desc, pages.ora_inizio_evento, pages.data_fine_evento desc, pages.ora_fine_evento,pages.data_news desc")->send();
	}
}
