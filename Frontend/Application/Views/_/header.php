<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="<?php echo Params::$lang;?><?php if (Params::$country) { ?>-<?php echo strtoupper(Params::$country);?><?php } ?>">
   <head>
		<?php include(tpf("/Elementi/header_head.php"));?>
   </head>
   <body>
		<?php include(tpf("/Elementi/header_body_top.php"));?>
		
		<div class="uk-offcanvas-content">
			<?php include(tpf("/Elementi/header_html.php"));?>
