<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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
	
	public static $genericParams = array(
		'imgWidth'		=>	600,
		'imgHeight'		=>	600,
		'defaultImage'	=>  null,
		'useCache'		=>	true,
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		// Variabili
		$this->model('VariabiliModel');
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
	}
	
	protected function caricaParametri($params)
	{
		$path = FRONT . "/Application/Layout/".v("theme_folder")."/layout.php";
		
		if (!file_exists($path))
			$path = FRONT . "/Application/Views/".v("theme_folder")."/layout.php";
		
		if (file_exists($path))
		{
			$action = $this->action;
			
			require_once($path);
			
			if (isset(Layout::$thumb[$action]))
				$params = Layout::$thumb[$action];
		}
		
		if (v("attiva_cache_immagini"))
			$params["useCache"] = false;
		
		return $params;
	}
	
	protected function parametriRender($fileName)
	{
		$usaCacheDinamica = v("attiva_cache_immagini") ? false : true;
		
		return array($fileName, null, $this->percorsoCartellaCacheFisica(), $usaCacheDinamica);
	}
	
	protected function percorsoCartellaCacheFisica($id = null)
	{
		if (!v("attiva_cache_immagini"))
			return null;
		
		$path = "thumb/".$this->action;
		
		if ($id && is_numeric($id))
			$path .= "/".(int)basename($id);
		
		return $path;
	}
	
	protected function genericthumb($fileName, $params, $folder)
	{
		$this->clean();
		
		$folder = rtrim(ltrim($folder, "/"),"/");
		
		$params = $this->caricaParametri($params);
		
// 		if (file_exists(FRONT.'/Public/Img/nofound.jpeg'))
// 		{
// 			$params["defaultImage"] = FRONT.'/Public/Img/nofound.jpeg';
// 			
// 			if (!$fileName)
// 				$fileName = "nofound.jpeg";
// 		}
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.$folder,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
					$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 					call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function slidemobile($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	700,
			'imgHeight'		=>	700,
			'defaultImage'	=>  null,
// 			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/contenuti',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function blogfirst($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	800,
			'imgHeight'		=>	700,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'backgroundColor' => "#FFF",
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		$this->genericthumb($fileName, self::$genericParams, Parametri::$cartellaImmaginiContenuti);
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$this->genericthumb($fileName, $params, "images/marchi");
	}
	
	public function famigliabig($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	2880,
			'imgHeight'		=>	1146,
			'defaultImage'	=>  null,
// 			'backgroundColor' => "#FFF",
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'useCache'		=>	true,
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/marchi',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function categoria2x($fileName)
	{
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
		
		$this->genericthumb($fileName, $params, "images/categorie_2");
	}
	
	public function categoria($fileName)
	{
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
		
		$this->genericthumb($fileName, $params, "images/categorie");
	}
	
	public function gallery($fileName)
	{
		$this->genericthumb($fileName, self::$genericParams, Parametri::$cartellaImmaginiContenuti);
	}
	
	public function sfondocategoria($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	1920,
			'imgHeight'		=>	600,
			'defaultImage'	=>  null,
			'useCache'		=>	true,
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/categorie',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function categoriamenu($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	71,
			'imgHeight'		=>	218,
			'defaultImage'	=>  null,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
			'useCache'		=>	true,
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/categorie_2',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function tag($fileName)
	{
		$this->genericthumb($fileName, self::$genericParams, 'images/tag');
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/tag_2',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
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
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/images/valori_attributi',$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
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
			
			if (v("attiva_cache_immagini"))
				$params["useCache"] = false;
			
			if (accepted($fileName))
			{
				if (strcmp($fileName,'') !== 0)
				{
					$thumb = new Image_Gd_Thumbnail(FRONT.'/images/widgets',$params);
					$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica((int)$id));
				}
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
	
	public function testimonial($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	28,
			'imgHeight'		=>	28,
			'defaultImage'	=>  null,
// 			'useCache'		=>	true,
			'backgroundColor' => "#FFF",
			'cropImage'		=>	'yes',
			'horizAlign'	=>	'center',
			'vertAlign'		=>	'center',
		);
		
		$params = $this->caricaParametri($params);
		
		if (accepted($fileName))
		{
			if (strcmp($fileName,'') !== 0)
			{
				$thumb = new Image_Gd_Thumbnail(FRONT.'/'.Parametri::$cartellaImmaginiContenuti,$params);
				$thumb->render($fileName,null,$this->percorsoCartellaCacheFisica());
// 				call_user_func_array(array($thumb, "render"),$this->parametriRender($fileName));
			}
		}
		else
		{
			$thumb = new Image_Gd_Thumbnail(FRONT.'/Public/Img',$params);
			$thumb->render('nofound.jpeg');
		}
	}
}
