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

require_once(LIBRARY."/Application/Controllers/Public/BaseThumbController.php");

class ThumbController extends BaseThumbController {
	
	public $publicRoot;
	
	public function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);
		
		$this->session('admin');
		$this->s['admin']->check();

		$this->model('ImmaginiModel');
		
		$this->publicRoot = str_replace("/admin","",LIBRARY);
	}

	public function contenuto($image)
	{
		$this->clean();
		
		if (accepted($image))
		{
			$image = sanitizeAll($image);
			$params = array(
				'imgWidth'		=>	250,
				'imgHeight'		=>	250,
				'defaultImage'	=>  null,
				'cropImage'		=>	'no',
// 				'useCache'		=>	true,
			);

			$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/'.Parametri::$cartellaImmaginiContenuti,$params);
			$thumb->render($image);
		}
	}
	
	public function mainimage($image)
	{
		$this->clean();
		
		if (accepted($image))
		{
			$image = sanitizeAll($image);
			$params = array(
				'imgWidth'		=>	400,
				'imgHeight'		=>	400,
				'defaultImage'	=>  null,
				'cropImage'		=>	'no',
			);

			$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/'.Parametri::$cartellaImmaginiContenuti,$params);
			$thumb->render($image);
		}
	}
	
	public function immagineinlistaprodotti($id_page, $fileName = null)
	{
		$clean["id_page"] = (int)$id_page;
		
		$this->clean();
		
		if (!isset($fileName))
		{
			$fileName = $this->m["ImmaginiModel"]->getFirstImage($clean["id_page"]);
		}
		
		if (accepted($fileName) or strcmp($fileName,'') === 0)
		{
			$params = array(
				'imgWidth'		=>	80,
				'imgHeight'		=>	80,
				'defaultImage'	=>  null,
				'cropImage'		=>	'yes',
				'horizAlign'	=>	'center',
				'vertAlign'		=>	'center',
			);
			
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica($id_page));
			}
			else
			{
				$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/Public/Img',$params);
				$thumb->render('nofound.jpeg');
			}
		}
	}
	
	public function news($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	100,
			'imgHeight'		=>	150,
			'defaultImage'	=>  null
		);
		
		if (strcmp($fileName,'') !== 0)
		{
			$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/'.Parametri::$cartellaImmaginiNews,$params);
			$thumb->render($fileName);
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(ROOT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function immagineticket($image)
	{
		$this->clean();
		
		if (accepted($image))
		{
			$image = sanitizeAll($image);
			$params = array(
				'imgWidth'		=>	800,
				'imgHeight'		=>	800,
				'defaultImage'	=>  null,
				'cropImage'		=>	'no',
			);

			$thumb = new Image_Gd_Thumbnail($this->publicRoot.'/images/ticket_immagini',$params);
			$thumb->render($image);
		}
	}
}
