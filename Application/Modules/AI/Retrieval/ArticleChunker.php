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

class ArticleChunker
{
	public static function normalizeText(string $html): string
	{
		$html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		// heading markers
		$html = preg_replace('#<h1[^>]*>(.*?)</h1>#is', "\n\n[[H1]] $1 [[/H1]]\n\n", $html);
		$html = preg_replace('#<h2[^>]*>(.*?)</h2>#is', "\n\n[[H2]] $1 [[/H2]]\n\n", $html);
		$html = preg_replace('#<h3[^>]*>(.*?)</h3>#is', "\n\n[[H3]] $1 [[/H3]]\n\n", $html);

		// paragrafi
		$html = preg_replace('#<p[^>]*>(.*?)</p>#is', "\n\n$1\n\n", $html);

		// liste
		$html = preg_replace('#<li[^>]*>#i', "\n- ", $html);
		$html = preg_replace('#</li>#i', "\n", $html);

		// br
		$html = preg_replace('#<br\s*/?>#i', "\n", $html);

		// rimuovi tag residui
		$text = strip_tags($html);

		$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		// normalizza newline
		$text = preg_replace("/\r\n|\r/u", "\n", $text);

		// spazi multipli
		$text = preg_replace("/[ \t]+/u", " ", $text);

		// troppi newline
		$text = preg_replace("/\n{3,}/u", "\n\n", $text);

		return trim($text);
	}


	public static function textToBlocks(string $text): array
	{
		$parts = preg_split("/\n{2,}/u", $text);
		$blocks = [];

		foreach ($parts as $part)
		{

			$part = trim($part);

			if ($part === '')
				continue;

			if (preg_match('/^\[\[H([1-6])\]\]\s*(.*?)\s*\[\[\/H\1\]\]$/u', $part, $m))
			{
				$blocks[] = [
					'type' => 'heading',
					'level' => (int)$m[1],
					'text' => trim($m[2])
				];
			}
			else
			{
				$blocks[] = [
					'type' => 'paragraph',
					'text' => preg_replace("/\n+/u", " ", $part)
				];
			}
		}

		return $blocks;
	}


	public static function blocksToSections(array $blocks): array
	{
		$sections = [];

		$currentH1 = "";
		$currentH2 = "";
		$currentH3 = "";

		$buffer = [];

		$flush = function () use (&$sections, &$buffer, &$currentH1, &$currentH2, &$currentH3)
		{
			$text = trim(implode("\n\n", $buffer));

			if ($text === '')
				return;

			$sections[] = [
				'title' => $currentH1,
				'section' => $currentH2,
				'subsection' => $currentH3,
				'text' => $text
			];

			$buffer = [];
		};


		foreach ($blocks as $block)
		{

			if ($block['type'] === 'heading')
			{
				$flush();

				$level = $block['level'];

				if ($level === 1)
				{
					$currentH1 = $block['text'];
					$currentH2 = "";
					$currentH3 = "";
				}
				elseif ($level === 2)
				{
					$currentH2 = $block['text'];
					$currentH3 = "";
				}
				elseif ($level === 3)
				{
					$currentH3 = $block['text'];
				}

			}
			else
			{
				$buffer[] = $block['text'];
			}
		}

		$flush();

		return $sections;
	}


	public static function splitLongSections(array $sections, int $maxLen = 1400, int $overlap = 200): array
	{
		$result = [];

		foreach ($sections as $section)
		{
			$text = $section['text'];
			// echo mb_strlen($text)."\n";
			if (mb_strlen($text) <= $maxLen)
			{
				$result[] = $section;
				continue;
			}

			$start = 0;
			$len = mb_strlen($text);

			// echo "LEN:".$len."\n";
			while ($start < $len)
			{
				$chunkText = mb_substr($text, $start, $maxLen);

				// echo $chunkText."\n-----";
				$lastDot = max(
					mb_strrpos($chunkText, '.'),
					mb_strrpos($chunkText, '!'),
					mb_strrpos($chunkText, '?')
				);
    
				if ($lastDot !== false && $lastDot > ($maxLen * 0.6))
					$chunkText = mb_substr($chunkText, 0, $lastDot + 1);

				$lastChunk = false;
				// echo "LEN CHUNK:".mb_strlen($chunkText)."\n";
				if (($len - ($start + mb_strlen($chunkText))) <= $overlap)
				{
					$lastChunk = true;
					// echo "\n\naa\n\n";
					// die();
					$chunkText = mb_substr($text, $start, $len);
				}
				
				$result[] = [
					'title' => $section['title'],
					'section' => $section['section'],
					'subsection' => $section['subsection'],
					'text' => trim($chunkText)
				];

				$advance = mb_strlen($chunkText);
				// echo $advance."\n";
				if ($advance <= 0 || $lastChunk)
					break;

				// echo "ADVANCE:".$advance."\n";
				if ($advance > $overlap)
					$start += $advance - $overlap;
				else
					$start += $advance;
				// echo "START:".$start."\n";
			}
		}

		return $result;
	}
	
	public static function getChunks($html, int $maxLen = 1400, int $overlap = 200): array
	{
		$normalized = self::normalizeText($html);
		
		$blocks = self::textToBlocks($normalized);
		
		$sections = self::blocksToSections($blocks);
		
		return self::splitLongSections($sections, $maxLen, $overlap);
	}
	
	public static function getChunksTextsForEmbeddings($html, int $maxLen = 1400, int $overlap = 200, $categoria = ""): array
	{
		$chunks = self::getChunks($html, $maxLen, $overlap);
		
		$textArray = array();
		
		foreach ($chunks as $chunk)
		{
			$text = "";
			
			if (trim($categoria))
				$text .= '"'.$categoria.'"'."\n\n";
			
			$text .= '"'.$chunk["title"].'"'.".\n";
			
			if ($chunk["section"])
				$text .= "Section: ".$chunk["section"].".\n";
			
			if ($chunk["subsection"])
				$text .= "Subsection: ".$chunk["subsection"].".\n";
			
			if ($chunk["text"])
				$text .= "\n".$chunk["text"];
			
			$textArray[] = array(
				"title"	=>	$chunk["title"],
				"full"	=>	$text,
				"text"	=>	$chunk["text"]
			);
		}
		
		return $textArray;
	}
}
