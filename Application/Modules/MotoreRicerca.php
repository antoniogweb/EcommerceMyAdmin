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

require_once(LIBRARY."/Application/Modules/Feed.php");

class MotoreRicerca
{
	use Modulo;
	
	public function ottieniOggetti($idPage = 0)
	{
		$strutturaProdotti = FeedModel::getModuloPadre()->strutturaFeedProdotti(null, (int)$idPage, 0, false);
		
		return $strutturaProdotti;
	}
	
	protected function getNomeCampoId()
	{
		return "objectID";
	}
	
	protected function getLogPath()
	{
		return $this->cacheAbsolutePath."/".trim($this->params["codice"])."/motori_ricerca_".trim($this->params["codice"])."_last_sent.log";
	}
	
	protected function leggiDatiInviati()
	{
		$path = $this->getLogPath();
		
		if (@is_file($path))
			return unserialize(file_get_contents($path));
		
		return array();
	}
	
	protected function salvaDatiInviati($data)
	{
		$path = $this->getLogPath();
		
		FilePutContentsAtomic($path, serialize($data));
	}
}
