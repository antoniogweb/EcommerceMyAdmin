<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

if (!defined("FRONT"))
	define('FRONT', ROOT);

class BaseThumbController extends Controller {
	
	public function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);
		
		// Variabili
		$this->model('VariabiliModel');
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
	}
	
	public function contenuto($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	150,
			'imgHeight'		=>	130,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function layer($idLayer)
	{
		$this->model("LayerModel");
		
		$this->clean();
		
		$layer = $this->m["LayerModel"]->clear()->where(array(
			"id_layer"	=>	(int)$idLayer,
		))->record(false);
		
		if (!empty($layer))
		{
			$fileName = $layer["immagine"];
			
			$this->clean();
		
			$params = array(
				'imgWidth'		=>	$layer["larghezza"] ? $layer["larghezza"] : 320,
				'imgHeight'		=>	$layer["altezza"] ? $layer["altezza"] : 320,
				'defaultImage'	=>  null,
	// 			'backgroundColor' => "#FFF",
			);
			
			if (accepted($fileName))
			{
				if (strcmp($fileName,'') !== 0)
				{
					$thumb = new Image_Gd_Thumbnail(FRONT.'/images/layer',$params);
					$thumb->render($fileName);
				}
			}
			else
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
				$thumb->render('nofound.jpeg');
			}
		}
	}
	
	public function slidethumb($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	320,
			'imgHeight'		=>	320,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'useCache'		=>	true,
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function slide($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	1920,
			'imgHeight'		=>	700,
			'defaultImage'	=>  null,
// 			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function slidelayer($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	700,
			'imgHeight'		=>	700,
			'defaultImage'	=>  null,
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/contenuti',$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function slidesotto($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	1710,
			'imgHeight'		=>	700,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function slidesottothumb($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	400,
			'imgHeight'		=>	400,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function dettaglio($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	500,
			'imgHeight'		=>	500,
			'defaultImage'	=>  null,
// 			'useCache'		=>	true,
// 			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function blog($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	600,
			'imgHeight'		=>	390,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function blogdetail($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	1360,
			'imgHeight'		=>	953,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
// 			'cropImage'		=>	'yes',
// 			'horizAlign'	=>	'center',
// 			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function dettaglioapp($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	350,
			'imgHeight'		=>	500,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
// 			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function dettagliobig($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	600,
			'imgHeight'		=>	600,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
// 			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function team($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	263,
			'imgHeight'		=>	364,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function referenza($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	993,
			'imgHeight'		=>	993,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function dettagliobigapp($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	500,
			'imgHeight'		=>	500,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function home($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	1920,
			'imgHeight'		=>	1363,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
// 			'backgroundColor' => "#FFF",
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function famiglia($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	835,
			'imgHeight'		=>	400,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'useCache'		=>	true,
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/marchi',$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function categoria($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	550,
			'imgHeight'		=>	600,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'useCache'		=>	true,
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/categorie',$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function tagbig($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	800,
			'imgHeight'		=>	500,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'useCache'		=>	true,
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/tag_2',$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function carrelloajax($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	v("thumb_ajax_w"),
			'imgHeight'		=>	v("thumb_ajax_h"),
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
// 			'useCache'		=>	true,
// 				'cropImage'		=>	'yes',
// 				'horizAlign'	=>	'center',
// 				'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function tooltip($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	70,
			'imgHeight'		=>	70,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'useCache'		=>	true,
// 				'cropImage'		=>	'yes',
// 				'horizAlign'	=>	'center',
// 				'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function carrello($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	300,
			'imgHeight'		=>	300,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function valoreattributo($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	100,
			'imgHeight'		=>	100,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/valori_attributi',$params);
				$thumb->render($fileName);
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function widget($id, $immagine, $field = "immagine")
	{
		$this->widgetg($id, $immagine, "immagine");
	}
	
	public function widget2x($id, $immagine, $field = "immagine")
	{
		$this->widgetg($id, $immagine, "immagine_2x");
	}
	
	private function widgetg($id, $immagine, $field = "immagine")
	{
		$this->clean();
		
		$this->model("TestiModel");
		$testo = $this->m["TestiModel"]->selectId((int)$id);
		
		if (!empty($testo) && $testo[$field])
		{
			$fileName = $testo[$field];
			
			$params = array(
				'defaultImage'	=>  null,
				'useCache'		=>	true,
			);
			
			$moltiplicatore = 1;
			
			if ($field == "immagine_2x")
				$moltiplicatore = 2;
			
			if ($testo["width"])
				$params["imgWidth"] = $moltiplicatore * $testo["width"];
			
			if ($testo["height"])
				$params["imgHeight"] = $moltiplicatore * $testo["height"];
			
			if ($testo["crop"] == "Y")
			{
				$params["cropImage"] = "yes";
				$params["horizAlign"] = "center";
				$params["vertAlign"] = "center";
			}
			
			if (accepted($fileName))
			{
				if (strcmp($fileName,'') !== 0)
				{
					$thumb = new Image_Gd_Thumbnail(FRONT.'/images/widgets',$params);
					$thumb->render($fileName);
				}
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
// 	public function news($fileName)
// 	{
// 		$this->clean();
// 		
// 		$params = array(
// 			'imgWidth'		=>	150,
// 			'imgHeight'		=>	150,
// 			'defaultImage'	=>  null,
// 			'backgroundColor' => "#FFF",
// // 			'cropImage'		=>	'yes',
// // 			'horizAlign'	=>	'center',
// // 			'vertAlign'		=>	'center',
// 		);
// 		
// 		if (accepted($fileName))
// 		{
// 			if (strcmp($fileName,'') !== 0)
// 			{
// 				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiNews,$params);
// 				$thumb->render($fileName);
// 			}
// 		}
// 		else
// 		{
// 			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
// 			$thumb->render('nofound.jpeg');
// 		}
// 	}
// 	
// 	public function dettaglionews($fileName)
// 	{
// 		$this->clean();
// 		
// 		$params = array(
// 			'imgWidth'		=>	298,
// 			'imgHeight'		=>	298,
// 			'defaultImage'	=>  null,
// 			'backgroundColor' => "#FFF",
// // 			'cropImage'		=>	'yes',
// // 			'horizAlign'	=>	'center',
// // 			'vertAlign'		=>	'center',
// 		);
// 		
// 		if (accepted($fileName))
// 		{
// 			if (strcmp($fileName,'') !== 0)
// 			{
// 				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiNews,$params);
// 				$thumb->render($fileName);
// 			}
// 		}
// 		else
// 		{
// 			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
// 			$thumb->render('nofound.jpeg');
// 		}
// 	}
}
