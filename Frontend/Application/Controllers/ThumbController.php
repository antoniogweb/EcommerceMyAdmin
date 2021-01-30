<?php

if (!defined('EG')) die('Direct access not allowed!');

require_once(LIBRARY."/Application/Controllers/Public/BaseThumbController.php");

class ThumbController extends BaseThumbController
{
	public function dettagliobig($fileName)
	{
		$this->clean();
		
		$params = array(
			'imgWidth'		=>	600,
			'imgHeight'		=>	600,
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
}
