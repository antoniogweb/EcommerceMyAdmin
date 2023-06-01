<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class Tests
{
	public static function getTitolo($elemento = "prodotto")
	{
		return "Test $elemento PHPUnit ".date("d/m/Y H:i:s");
	}
	
	public static function creaPagina(string $section = "prodotti", array $valori = array()) : int
	{
		$idC = CategoriesModel::g(false)->getIdFromSection($section);
		
		$p = new PagesModel();
		
		$p->sValues(array(
			"title"	=>	self::getTitolo(),
			"alias"	=>	"",
			"id_c"	=>	$idC,
		));
		
		foreach ($valori as $k => $v)
		{
			$p->setvalue($k, $v);
		}
		
		$p->insert();
		
		return $p->lId;
	}
	
	public static function creaAttributi($numeroAttributi = 2, $numeroVariantiPerAttributo = 3)
	{
		$a = new AttributiModel();
		$av = new AttributivaloriModel();
		
		$idsA = [];
		
		for ($i = 0; $i < $numeroAttributi; $i++)
		{
			$a->sValues(array(
				"titolo"	=>	self::getTitolo("attributo $i"),
			));
			
			$a->insert();
			
			$idsA[] = $a->lId;
			
			for ($j = 0; $j < $numeroVariantiPerAttributo; $j++)
			{
				$av->sValues(array(
					"titolo"	=>	self::getTitolo("attributo valore $j"),
					"id_a"		=>	$a->lId,
					"alias"		=>	$i."_".$j,
				));
				
				$av->insert();
			}
		}
		
		return $idsA;
	}
	
	public static function eliminaAttributi(array $idsA)
	{
		$a = new AttributiModel();
		$av = new AttributivaloriModel();
		
		foreach ($idsA as $idA)
		{
			$idsAvs = $av->clear()->where(array(
				"id_a"	=>	(int)$idA,
			))->toList("id_av")->send();
			
			foreach ($idsAvs as $idAv)
			{
				$av->del($idAv);
			}
			
			$a->del($idA);
		}
	}
	
	public static function numeroLingueAttive()
	{
		return count(LingueModel::getValoriAttivi());
	}
}

