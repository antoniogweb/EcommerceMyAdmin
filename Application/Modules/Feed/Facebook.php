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

require_once(LIBRARY."/Application/Modules/Feed/GoogleMerchant.php");

class Facebook extends GoogleMerchant
{
	public $isFbk = true;
	
	public function gCampiForm()
	{
		return 'titolo,attivo,link_a_combinazione,usa_token_sicurezza,token_sicurezza,query_string,tempo_cache,url_feed,default_gender,default_age_group,node_tag_name,campo_per_item_group_id';
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["campo_per_item_group_id"]["type"] = "Select";
		$model->formStruct["entries"]["campo_per_item_group_id"]["options"] = array(
			""			=>	"Non usare item_group_id",
			"codice"	=>	"Codice prodotto",
			"mpn"		=>	"MPN / Codice costruttore",
			"gtin"		=>	"GTIN / EAN",
		);
		$model->formStruct["entries"]["campo_per_item_group_id"]["reverse"] = "yes";
		$model->formStruct["entries"]["campo_per_item_group_id"]["labelString"] = "Campo da usare per item_group_id";
		
		$model->formStruct["entries"]["node_tag_name"]["labelString"] = "Tag da usare per l'elemento del feed";
		$model->formStruct["entries"]["node_tag_name"]["type"] = "Select";
		$model->formStruct["entries"]["node_tag_name"]["options"] = array(
			"item"		=>	"&lt;item&gt;",
			"entry"		=>	"&lt;entry&gt;",
		);
		$model->formStruct["entries"]["node_tag_name"]["reverse"] = "yes";
	}
}
