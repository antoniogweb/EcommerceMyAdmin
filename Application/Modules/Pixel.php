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

class Pixel
{
	use Modulo;
	
	public static $eventoInviato = [];
	
	// restituisce l'output del metodo Geastionale::infoOrdine($idOrdine)
	public function infoOrdine($idOrdine)
	{
		require_once(LIBRARY."/Application/Modules/Gestionale.php");
		
		$g = new Gestionale(array());
		
		return $g->infoOrdine($idOrdine);
	}
	
	public function salvaEvento($evento, $idElemento, $tabellaElemento, $codiceEvento = "")
	{
		return PixeleventiModel::g(false)->aggiungi($this->params["id_pixel"], $evento, $idElemento, $tabellaElemento, $codiceEvento);
	}
	
	public function aggiornaEvento($evento, $idElemento, $tabellaElemento, $values)
	{
		return PixeleventiModel::g(false)->aggiorna($this->params["id_pixel"], $evento, $idElemento, $tabellaElemento, $values);
	}
	
	public function getEvento($evento, $idElemento, $tabellaElemento, $tutti = false)
	{
		return PixeleventiModel::g(false)->getEvento($this->params["id_pixel"], $evento, $idElemento, $tabellaElemento, $tutti);
	}
	
	public function checkData($elemento)
	{
		if (strtotime($elemento["data_creazione"]) >= strtotime($this->params["data_creazione"]))
			return true;
		
		return false;
	}
}
