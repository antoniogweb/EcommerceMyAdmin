<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class QueryAwareContextBuilder
{
	public static function normalize(string $s): string
	{
		$s = mb_strtolower($s, 'UTF-8');
		$s = preg_replace('/[^\p{L}\p{N}\s°\-\+\/]/u', ' ', $s);
		$s = preg_replace('/\s+/u', ' ', $s);
		
		return trim($s);
	}

	public static function tokenize(string $s): array
	{
		$s = self::normalize($s);
		$parts = preg_split('/\s+/u', $s, -1, PREG_SPLIT_NO_EMPTY);

		$stop = ['il','lo','la','i','gli','le','un','una','di','da','a','in','con','per','su','e','o','che','come','del','della','dei','delle'];

		$out = [];
		foreach ($parts as $p)
		{
			if (mb_strlen($p, 'UTF-8') < 2) continue;
			if (in_array($p, $stop, true)) continue;
			$out[] = $p;
		}

		return array_values(array_unique($out));
	}

	/**
	* Espansione minima e generica:
	* - aggiunge radice parola (es: reclinabile → reclin)
	*/
	public static function expandQueryTokens(array $tokens): array
	{
		$expanded = $tokens;

		foreach ($tokens as $t)
		{
			if (mb_strlen($t, 'UTF-8') > 5)
				$expanded[] = mb_substr($t, 0, 6, 'UTF-8'); // radice semplice
		}

		return array_values(array_unique($expanded));
	}

	public static function splitSentences(string $text): array
	{
		$text = str_replace(["\r\n", "\r"], "\n", $text);
		$parts = preg_split('/(\n+|[.;!?]+|\s-\s|•|\*)/u', $text);

		$out = [];
		foreach ($parts as $p)
		{
			$p = trim($p);
			if ($p === '') continue;
			if (mb_strlen($p, 'UTF-8') < 12) continue;
			$out[] = $p;
		}

		return $out;
	}

	public static function extractRelevantSnippet(string $query, string $description, int $maxLines = 4): array
	{
		$qTokens = self::tokenize($query);
		$qTokens = self::expandQueryTokens($qTokens);

		$sentences = self::splitSentences($description);

		$scored = [];

		foreach ($sentences as $s)
		{
			$ns = self::normalize($s);
			$score = 0;

			foreach ($qTokens as $t)
			{
				if ($t === '') continue;
				if (mb_strpos($ns, $t, 0, 'UTF-8') !== false)
					$score += 2;
			}

			// penalizza frasi troppo lunghe (marketing)
			$len = mb_strlen($s, 'UTF-8');
			if ($len > 220) $score -= 1;
			if ($len > 350) $score -= 2;

			// penalizza frasi puramente decorative (semplice euristica)
			if (preg_match('/\b(luxury|design|collezione|stile|logo|cuciture)\b/i', $s))
				$score -= 2;

			if ($score > 0)
				$scored[] = ['score' => $score, 'text' => trim($s)];
		}

		usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

		$top = [];
		foreach ($scored as $row)
		{
			$key = self::normalize($row['text']);
			if (isset($top[$key])) continue;
			$top[$key] = $row['text'];
			if (count($top) >= $maxLines) break;
		}

		// fallback: primi 200 caratteri se nulla matcha
		if (empty($top))
		{
			$fallback = mb_substr(trim($description), 0, 200, 'UTF-8');
			return [$fallback];
		}

		return array_values($top);
	}
}
