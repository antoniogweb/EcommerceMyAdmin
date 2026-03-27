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

class Vector
{
	/**
	* Calcola il prodotto scalare tra due vettori numerici.
	*/
	public static function dotProduct(array $a, array $b): float
	{
		$sum = 0.0;
		$len = min(count($a), count($b)); // sicurezza: in caso di lunghezze diverse
		for ($i = 0; $i < $len; $i++)
		{
			$sum += (float)$a[$i] * (float)$b[$i];
		}
		
		return $sum;
	}
	
	/**
	* Norma L2 di un vettore.
	*/
	public static function l2Norm(array $v): float
	{
		$sum = 0.0;
		$n = count($v);
		for ($i = 0; $i < $n; $i++)
		{
			$x = (float)$v[$i];
			$sum += $x * $x;
		}
		
		return sqrt($sum);
	}

	/**
	* Cosine similarity tra un vettore e un secondo vettore di cui si conosce gia' la norma.
	* Esegue un'unica passata sul primo vettore per calcolare insieme prodotto scalare e norma.
	*/
	public static function cosineSimilarityWithKnownNorm(array $a, array $b, float $normB): float
	{
		$len = count($a);
		
		if ($len === 0 || $len !== count($b) || $normB <= 0.0)
			return 0.0;
		
		$dot = 0.0;
		$sumSquaresA = 0.0;
		
		for ($i = 0; $i < $len; $i++)
		{
			$av = (float)$a[$i];
			$bv = (float)$b[$i];
			
			$dot += $av * $bv;
			$sumSquaresA += $av * $av;
		}
		
		if ($sumSquaresA <= 0.0)
			return 0.0;
		
		return $dot / (sqrt($sumSquaresA) * $normB);
	}
	
	/**
	* Cosine similarity tra due vettori.
	* Ritorna 0.0 se una norma è zero o le dimensioni non corrispondono.
	*/
	public static function cosineSimilarity(array $a, array $b): float
	{
		return self::cosineSimilarityWithKnownNorm($a, $b, self::l2Norm($b));
	}
}
