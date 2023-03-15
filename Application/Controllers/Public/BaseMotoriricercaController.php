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

class BaseMotoriricercaController extends BaseController
{
	protected $estratiDatiGenerali = false;
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		if (!v("attiva_gestione_motori_ricerca"))
			$this->responseCode(403);
	}

	public function cerca($modulo = "")
	{
		$modulo = strtoupper((string)$modulo);
		
		$search = $this->request->get("term","","strip_tags");
		
		if (trim((string)$search) && trim($modulo) && MotoriricercaModel::g()->checkModulo($modulo, ""))
		{
			IpcheckModel::check("CERCA $modulo");
			
			if (MotoriricercaModel::getModulo($modulo)->isAttivo())
			{
				User::setPostCountryFromUrl();
				
				$jsonArray = MotoriricercaModel::getModulo($modulo)->cerca("prodotti_".Params::$lang, $search);
				
				// Salva la ricerca
				if (v("salva_ricerche"))
				{
					$this->m("RicercheModel")->sValues(array(
						"termini"	=>	sanitizeAll((string)$search),
						"cart_uid"	=>	sanitizeAll(User::$cart_uid),
					));
					
					$this->m("RicercheModel")->insert();
				}
				
// 				print_r($jsonArray);die();
				
				header('Content-type: application/json; charset=utf-8');
				
				echo json_encode($jsonArray);
			}
			else
				$this->responseCode(403);
		}
		else
			$this->responseCode(403);
	}
}
