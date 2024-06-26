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

if (!defined('EG')) die('Direct access not allowed!');

class GDPR
{
// 	public static $cookies = array(
// 		"ok_gmaps"	=>	false,
// 	);
	
	public static function checkCookie()
	{
		if (isset($_COOKIE["ok_cookie_terzi"]))
			return true;
		
		return false;
	}
	
// 	public static function sCookie($cookie)
// 	{
// 		$time = time() + v("durata_carrello_wishlist_coupon");
// 		Cookie::set($cookie, "OK", $time, "/", true, "Lax");
// 		
// 		self::$cookies[$cookie] = true;
// 	}
	
	public static function filtra($html)
	{
		if (v("filtra_html_in_cerca_di_servizi_da_disattivare") && !self::checkCookie())
		{
			$html = preg_replace_callback('/\<script(.*?)(googleapis)(.*?)<\/script\>/', array('GDPR', 'gmaps') ,$html);
			
			$html = preg_replace_callback('/\<iframe(.*?)(youtube.com|youtube-nocookie.com)(.*?)<\/iframe\>/', array('GDPR', 'youtube') ,$html);
			
			$html = preg_replace_callback('/\<iframe(.*?)(maps\.google\.com)(.*?)<\/iframe\>/', array('GDPR', 'gmapsEmbed') ,$html);
		}
		
		return $html;
	}
	
	public static function gmaps($matches)
	{
		ob_start();
		include tpf("Elementi/GDPR/Maps/google.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public static function youtube($matches)
	{
		preg_match('/src="([^"]+)"/', $matches[0], $match);
		
		if (isset($match[1]))
		{
			$urlServizio = $match[1];
			
			ob_start();
			include tpf("Elementi/GDPR/Video/youtube.php");
			$output = ob_get_clean();
			
			return $output;
		}
		
		return "";
	}
	
	public static function gmapsEmbed($matches)
	{
		preg_match('/src="([^"]+)"/', $matches[0], $match);
		
		if (isset($match[1]))
		{
			$urlServizio = "";
			
			$urlArray = parse_url($match[1]);
			
			if (isset($urlArray["query"]))
			{
				parse_str($urlArray["query"], $output);
				
				$q = $output["q"] ?? "";
				$z = $output["z"] ?? "";
				
				if ($q)
					$urlServizio = "https://www.google.com/maps?q=$q&z=$z";
			}
			
			ob_start();
			include tpf("Elementi/GDPR/Maps/google_embed.php");
			$output = ob_get_clean();
			
			return $output;
		}
	}
}
