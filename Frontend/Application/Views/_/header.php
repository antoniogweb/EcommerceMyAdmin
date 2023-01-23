<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="<?php echo Params::$lang;?>">
   <head>
		<?php include(tpf("/Elementi/header_tracking_data.php"));?>
		
		<?php include(tpf("/Elementi/gtm.php"));?>
		
		<?php include(tpf("/Elementi/pixel.php"));?>
		
		<?php include(tpf("/Elementi/header_meta.php"));?>
		
		<?php include(tpf("/Elementi/header_css.php"));?>
		
		<?php include(tpf("/Elementi/header_js.php"));?>
		
		<?php include(tpf("/Elementi/fbk.php"));?>
   </head>
   <body>
		<?php include(tpf("/Elementi/tendina_caricamento.php"));?>
		
		<?php include(tpf("/Elementi/gtm_no_script.php"));?>
		
		<?php include(tpf("/Elementi/pixel_no_script.php"));?>
		
		<div class="uk-offcanvas-content">
			<?php include(tpf("/Elementi/header_html.php"));?>
