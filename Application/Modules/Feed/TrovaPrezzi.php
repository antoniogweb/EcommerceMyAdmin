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

class TrovaPrezzi extends Feed
{
	public function feedProdotti($p = null)
	{
		$strutturaFeedProdotti = $this->strutturaFeedProdotti($p, 0, 0, null, (int)$this->params["tempo_cache"]);
		
		$xmlArray = array(
			"Offers"	=>	array(),
		);
		
		$outOfStock = v("attiva_giacenza") ? "disponibile" : "disponibile";
		
		foreach ($strutturaFeedProdotti as $r)
		{
			$temp = array(
				"Name"	=>	F::alt($r["titolo"]),
				"Code"	=>	$r["codice"],
				"Description"	=>	F::alt($r["descrizione"]),
				"Categories"	=>	count($r["categorie"]) > 0 ? implode(",",$r["categorie"][0]) : "",
				"Image"	=>	Url::getFileRoot()."thumb/dettagliofeed/".$r["immagine_principale"],
				"Link"	=>	$r["link"].$this->getQueryString(),
				"Price"	=>	$r["prezzo_scontato"],
				"ShippingCost"	=>	$r["spese_spedizione"],
				"Brand"	=>	$r["marchio"],
				"Weight"	=>	$r["peso"],
				"Disponibilita"	=>	$r["giacenza"] > 0 ? "disponibile" : $outOfStock,
// 				"Stock"	=>	$r["spese_spedizione"],
			);
			
			if ($r["prezzo_pieno"] != $r["prezzo_scontato"])
				$temp["OriginalPrice"]	= $r["prezzo_pieno"];
			
			if ($r["gtin"])
				$temp["EanCode"] = F::alt($r["gtin"]);
			
			if ($r["mpn"])
				$temp["PartNumber"] = F::alt($r["mpn"]);
			
			$indice = 2;
			
			foreach ($r["altre_immagini"] as $i)
			{
				$temp["Image".$indice] = Url::getFileRoot()."thumb/dettagliofeed/".$i["immagine"];
				
				$indice++;
			}
			
			if (isset($r["attributi"]))
				$temp = $this->elaboraNodiAttributi($temp, $r["attributi"]);
			
			$xmlArray["Offer"][] = $temp;
		}
		
		$xml = aToX($xmlArray);
		
		F::xml($xml, array(
			"Products"	=>	null,
		));
	}
	
	public function tagNameColore()
	{
		return "Colore";
	}
}
