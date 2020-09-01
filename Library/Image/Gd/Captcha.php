<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

//class to create a captcha
//you have to call session_start() before to initialize a captcha object
class Image_Gd_Captcha
{

	private $params = array(); //parameters of the object
	private $string = null; //the text string of the captcha
	
	public function __construct($params = null)
	{
		$here = realpath('.');
		
		$defaultParams = array(
			'boxWidth'		=>	150,
			'boxHeight'		=>	100,
			'charNumber'	=>	6,
			'fontPath'		=>	$here.'/External/Fonts/FreeFont/FreeMono.ttf',
			'undulation'	=>	true,
			'align'			=>	false,
			'charHeight'	=>	28,
			'sessionKey'	=>	'captchaString',
		);

		//set the $this->scaffold->params array
		if (is_array($params))
		{
			foreach ($params as $key => $value)
			{
				$defaultParams[$key] = $value;
			}
		}
		$this->params = $defaultParams;
		
		$this->string = generateString($this->params['charNumber']);
	}

	public function render()
	{
		//space among characters
		$space = $this->params['boxWidth'] / ($this->params['charNumber']+1);
		//create the image box
		$img = imagecreatetruecolor($this->params['boxWidth'],$this->params['boxHeight']);
		
		$background = imagecolorallocate($img,255,255,255);
		$border = imagecolorallocate($img,0,0,0);
		$colors[] = imagecolorallocate($img,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
		$colors[] = imagecolorallocate($img,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
		$colors[] = imagecolorallocate($img,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
		
		//create the background
		imagefilledrectangle($img,1,1,$this->params['boxWidth']-2,$this->params['boxHeight']-2,$background);
		imagerectangle($img,0,0,$this->params['boxWidth']-1,$this->params['boxHeight']-2,$border);
		
		//set the text
		for ($i=0; $i< $this->params['charNumber']; $i++)
		{
			$color = $colors[$i % count($colors)];
			$char = substr($this->string,$i,1);
			$fontPath = $this->params['fontPath'];
			$angle = $this->params['undulation'] === false ? 0 : -20+rand(0,40);
			$yposFixed = (int)(($this->params['boxHeight'])/2);
			$ypos = $this->params['align'] === true ? $yposFixed : $yposFixed + mt_rand(0,10);
			$charHeight = $this->params['charHeight'];
			imagettftext($img,$charHeight + rand(0,8),$angle,($i+0.3)*$space,$ypos,$color,$fontPath,$char);
		}

		$noiseColor = imagecolorallocate($img, mt_rand(125,255), mt_rand(125,255), mt_rand(125,255));
		/* generate random dots in background */
		for( $i=0; $i<($this->params['boxWidth'] * $this->params['boxHeight'])/7; $i++ ) {
			imagefilledellipse($img, mt_rand(0,$this->params['boxWidth']), mt_rand(0,$this->params['boxHeight']), 1, 1, $noiseColor);
		}
		
		$_SESSION[$this->params['sessionKey']] = $this->string;
		header('Content-Type: image/png');
		imagepng($img);
		imagedestroy($img);
	}
	
}
