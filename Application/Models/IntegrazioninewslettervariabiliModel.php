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

class IntegrazioninewslettervariabiliModel extends GenericModel {

	public function __construct() {
		$this->_tables='integrazioni_newsletter_variabili';
		$this->_idFields='id_integrazione_newsletter_variabile';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public static function getCampi($codice = null)
	{
		$inv = new IntegrazioninewslettervariabiliModel();
		
		$inv->clear()->orderBy("id_order");
		
		if ($codice)
			$inv->where(array(
				"codice_integrazione_newsletter"	=>	sanitizeAll($codice),
			))->toList("codice_campo", "nome_campo");
		else
			$inv->toList("nome_campo", "nome_campo");
		
		return $inv->send();
	}
	
	public static function mergeCampiAggiuntivi($strutturaFinale, $valori, $codice = null)
	{
		$campiAggiuntivi = IntegrazioninewslettervariabiliModel::getCampi($codice);
		
// 		print_r($valori);
// 		print_r($strutturaFinale);
// 		print_r($campiAggiuntivi);
		
		if (count($campiAggiuntivi) > 0)
		{
			foreach ($campiAggiuntivi as $codice => $campo)
			{
				if (isset($valori[$campo]))
					$strutturaFinale[$codice] = $valori[$campo];
			}
		}
		
// 		print_r($strutturaFinale);
		
		return $strutturaFinale;
	}
}
