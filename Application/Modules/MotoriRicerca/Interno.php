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

class Interno extends MotoreRicerca
{
	public function gCampiForm()
	{
		return 'titolo,attivo';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"])
			return true;
		
		return false;
	}
	
	public function cerca($indice, $search)
	{
		$search = trim($search);
		
		$searchArray = explode(" ", preg_quote($search));
		$pattern = implode("|", $searchArray);
		
		$p = new PagesModel();
		$p->addWhereSearch($search);
		
		$pRicerca = new PagesricercaModel();
		
		$res = $this->ottieniOggetti(0, $p);
		
// 		print_r($res);die();
		
		$ids = [];
		
		foreach ($res as $r)
		{
			$ids[] = $r["id_page"];
		}
		
		$oggettiRicerca = $pRicerca->getStructFromIdsOfPages($ids);
		
// 		print_r($oggettiRicerca);die();
		
		$risultatiRicerca = array(
			"hits"	=>	array(),
		);
		
		foreach ($oggettiRicerca as $id_page => $r)
		{
			$temp = array(
				"objectID"			=>	(int)$id_page,
				"_highlightResult"	=>	array(),
			);
			
			foreach ($r as $campo => $valore)
			{
				$valore = $this->pulisciXss($valore);
				$label = preg_replace("/($pattern)/i","<b>$0</b>",$valore, 6, $numero);
				
// 				echo $label."<br />\n";
// 				echo $numero."<br />\n";
				
				$innerTemp = array(
					"value"			=>	$label,
					"matchLevel"	=>	$numero > 0 ? "partial" : "none",
					"matchedWords"	=>	($numero > 0) ? range(0, ($numero - 1)) : array(),
				);
				
				$temp["_highlightResult"][$campo] = $innerTemp;
			}
			
			$risultatiRicerca["hits"][] = $temp;
		}
		
// 		print_r($risultatiRicerca);die();
		
		return $this->elaboraOutput($search, $risultatiRicerca);
	}
}
