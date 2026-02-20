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

class AirichiestecacheModel extends GenericModel
{
	public function __construct()
	{
		$this->_tables = 'ai_richieste_cache';
		$this->_idFields = 'id_ai_richiesta_cache';
		
		parent::__construct();
		
		$this->eliminaCachePiuVecchiaDiSecondi();
	}
	
	public function eliminaCachePiuVecchiaDiSecondi($secondi = 3600)
	{
		$time = time() - (int)$secondi;
		
		$this->del(null, array(
			"time_creazione < ?",
			array($time),
		));
	}
	
	public function get(array $messaggi, string $contesto, string $istruzioni, int $idModello)
	{
		$md5Messaggio = md5(json_encode($messaggi));
		$md5Contesto = md5($contesto);
		$md5Istruzionio = md5($istruzioni);
		
		$record = $this->clear()->where(array(
			"messaggio"		=>	sanitizeAll($md5Messaggio),
			"contesto"		=>	sanitizeAll($md5Contesto),
			"istruzioni"	=>	sanitizeAll($md5Istruzionio),
			"id_modello"	=>	(int)$idModello,
		))->record();
		
		if (!empty($record))
			return htmlentitydecode($record["output"]);
		
		return "";
	}
	
	public function set(array $messaggi, string $contesto, string $istruzioni, int $idModello, string $output)
	{
		$md5Messaggio = md5(json_encode($messaggi));
		$md5Contesto = md5($contesto);
		$md5Istruzionio = md5($istruzioni);
		
		$this->sValues(array(
			"messaggio"		=>	$md5Messaggio,
			"contesto"		=>	$md5Contesto,
			"istruzioni"	=>	$md5Istruzionio,
			"id_modello"	=>	(int)$idModello,
			"output"		=>	$output,
			"time_creazione"=>	time(),
		));
		
		$this->insert();
	}
}
