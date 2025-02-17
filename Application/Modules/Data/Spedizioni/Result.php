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

class Data_Spedizioni_Result
{
	private $numeroSpedizione = "";
	private $erroreSpedizione = "";
	private $warningSpedizione = "";
	
	public function __construct($numeroSpedizione = "", $erroreSpedizione = "", $warningSpedizione = "")
	{
		$this->numeroSpedizione = $numeroSpedizione;
		$this->erroreSpedizione = $erroreSpedizione;
		$this->warningSpedizione = $warningSpedizione;
	}
	
	public function instradato()
	{
		return $this->numeroSpedizione ? true : false;
	}
	
	public function toArray($numeroSpedizione = true)
	{
		$res = array(
			"errore_invio"		=>	$this->erroreSpedizione,
			"warning_invio"		=>	$this->warningSpedizione,
		);
		
		if ($numeroSpedizione)
			$res["numero_spedizione"] = $this->numeroSpedizione;
		
		return $res;
	}
	
	public function getErrore()
	{
		return $this->erroreSpedizione;
	}
}
