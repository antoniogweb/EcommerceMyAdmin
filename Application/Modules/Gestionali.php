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

class Gestionali
{
	protected $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function gParam1Label()
	{
		return "Param 1";
	}
	
	public function gParam2Label()
	{
		return "Param 2";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo';
	}
	
	public function titolo()
	{
		return $this->params["titolo"];
	}
	
	public function descOrdineInviato($ordine)
	{
		return "<span class='text text-success text-bold'>".sprintf("Ordine %s inviato a", $ordine["id_o"])." ".$this->titolo()."</span>";
	}
	
	public function descOrdineErrore($ordine)
	{
		return "<span class='text text-danger text-bold'>".sprintf("Errore nell'invio dell'ordine %s inviato a", $ordine["id_o"])." verso ".$this->titolo()."</span>";
	}
	
	public function specchiettoOrdine($ordine)
	{
		$html = "";
		
		if (OrdiniModel::statoGestionale($ordine) > 0)
			$html .= $this->descOrdineInviato($ordine);
		else if (OrdiniModel::statoGestionale($ordine) < 0)
			$html .= $this->descOrdineErrore($ordine);
		
		return $html;
	}
}
