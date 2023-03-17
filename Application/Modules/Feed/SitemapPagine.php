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

class SitemapPagine extends Feed
{
	public function feedProdotti($p = null, $outputFile = null)
	{
		$nodi = SitemapModel::getNodiFrontend();
		
		$xmlArray = array(
			"url"	=>	array(),
		);
		
		foreach ($nodi as $n)
		{
			if ($n["id_page"]) {
				$url = Url::getRoot().getUrlAlias($n["id_page"]);
			} else if ($n["id_c"]) {
				$url = Url::getRoot().getCategoryUrlAlias($n["id_c"]);
			} else if ($n["url"]) {
				$url = $n["url"];
			} else {
				$url = Url::getRoot();
			}
			
			$temp = array(
				"loc"		=>	$url,
				"lastmod"	=>	date('c', strtotime($n["ultima_modifica"])),
				"priority"	=>	number_format($n["priorita"],2,".",""),
			);
			
			$xmlArray["url"][] = $temp;
		}
		
		$xml = aToX($xmlArray);
		
		F::xml($xml, array(
			"urlset"	=>	array(
				"xmlns"		=>	"http://www.sitemaps.org/schemas/sitemap/0.9",
				"xmlns:xsi"	=>	"http://www.w3.org/2001/XMLSchema-instance",
				"xsi:schemaLocation"	=>	"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd",
			),
		), $outputFile);
	}
}
