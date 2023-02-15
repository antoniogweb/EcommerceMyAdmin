<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="<?php echo Params::$lang;?>">
   <head>
		<?php
		$idPaginaPerTracking = isset($isPage) ? (int)PagesModel::$currentIdPage : 0;
		
		$stringaCacheMeta = '$idPaginaPerTracking = '.$idPaginaPerTracking.';';
		$stringaCacheMeta .= 'PagesModel::$IdCombinazione = '.(isset($isPage) ? (int)PagesModel::$IdCombinazione : 0).';';
		
		if (isset($isPage))
			$stringaCacheMeta .= '$isPage = true;';
		
		if (isset($pages))
			$pagesMeta = $pages;
		
		include(tpf("/Elementi/header_tracking_data.php", false, false, $stringaCacheMeta));?>
		
		<?php include(tpf("/Elementi/gtm.php", false, false,));?>
		
		<?php include(tpf("/Elementi/pixel.php"));?>
		
		<?php include(tpf("/Elementi/header_meta.php"));?>
		
		<?php include(tpf("/Elementi/header_css.php"));?>
		
		<?php include(tpf("/Elementi/header_js.php"));?>
		
		<?php include(tpf("/Elementi/fbk.php", false, false, $stringaCacheMeta));?>
   </head>
   <body>
		<?php include(tpf("/Elementi/tendina_caricamento.php"));?>
		
		<?php include(tpf("/Elementi/gtm_no_script.php", false, false));?>
		
		<?php include(tpf("/Elementi/pixel_no_script.php"));?>
		
		<div class="uk-offcanvas-content">
			<?php include(tpf("/Elementi/header_html.php"));?>
