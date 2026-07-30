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

if (!defined('EG')) die('Direct access not allowed!');

require_once(LIBRARY . '/External/libs/vendor/autoload.php');

class Purifier
{
	public static $purifier;
	
	public static function purify(string $html): string
	{
		if (!isset(self::$purifier))
		{
			$config = HTMLPurifier_Config::createDefault();

			$config->set('Core.Encoding', 'UTF-8');

			$config->set(
				'HTML.Allowed',
				implode(',', [
					'p',
					'br',
					'strong',
					'b',
					'em',
					'i',
					'u',
					's',
					'blockquote',
					'h2',
					'h3',
					'h4',
					'ul',
					'ol',
					'li',
					'a[href|title|target|rel]',
					'img[src|alt|title|width|height|loading]',
					'figure',
					'figcaption',
					'table',
					'thead',
					'tbody',
					'tr',
					'th',
					'td',
					'div[class]',
					'span[class]',
				])
			);

			$config->set(
				'Attr.AllowedFrameTargets',
				['_blank']
			);

			$config->set(
				'URI.AllowedSchemes',
				[
					'http'   => true,
					'https'  => true,
					'mailto' => true,
					'tel'    => true,
				]
			);

			createFolderFull('/Logs/htmlpurifier', LIBRARY);
			
			$config->set(
				'Cache.SerializerPath',
				LIBRARY . '/Logs/htmlpurifier'
			);

			self::$purifier = new HTMLPurifier($config);
		}
		
		return self::$purifier->purify($html);
	}
}
