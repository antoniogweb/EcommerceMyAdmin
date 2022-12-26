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

class FattureInCloud extends Gestionale
{
	public function gCampiForm()
	{
		return 'titolo,attivo,param_1,param_2';
	}
	
	public function gParam1Label()
	{
		return "ID azienda";
	}
	
	public function gParam2Label()
	{
		return "Accesso Token";
	}
	
	public function isAttiva()
	{
		if (trim($this->params["param_1"]) && trim($this->params["param_2"]))
			return true;
		
		return false;
	}
	
	public function descOrdineInviato($ordine)
	{
		$f = new FattureModel();
		
		$numero = $f->clear()->where(array(
			"id_o"	=>	(int)$ordine["id_o"]
		))->field("numero");
		
		if ($numero)
			return "<span class='text text-success text-bold'>".sprintf(gtext("Fattura %s inviata a"), $numero)." ".$this->titolo()."</span>";
		else
			return "<span class='text text-danger text-bold'>".sprintf(gtext("Fattura assente nel gestionale ma segnata come inviata a"))." ".$this->titolo()."?!?</span>";
	}
	
	public function inviaOrdine($idO)
	{
		$ordine = $this->infoOrdine((int)$idO);
		
// 		print_r($ordine);
	}
}
