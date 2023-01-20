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

class PixeleventiModel extends GenericModel
{
	public function __construct() {
		$this->_tables='pixel_eventi';
		$this->_idFields='id_pixel_evento';
		
		parent::__construct();
	}
	
	public function aggiungi($idPixel, $evento, $idElemento, $tabellaElemento)
	{
		$this->sValues(array(
			"id_pixel"		=>	(int)$idPixel,
			"evento"		=>	$evento,
			"tabella_elemento"	=>	$tabellaElemento,
			"id_elemento"	=>	(int)$idElemento,
		));
		
		return $this->insert();
	}
	
	public function getEvento($idPixel, $evento, $idElemento, $tabellaElemento)
	{
		return $this->clear()->where(array(
			"id_pixel"		=>	(int)$idPixel,
			"evento"		=>	$evento,
			"tabella_elemento"	=>	sanitizeAll($tabellaElemento),
			"id_elemento"	=>	(int)$idElemento,
		))->record();
	}
}
