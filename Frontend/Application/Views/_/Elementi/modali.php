<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($modali_frontend) && (v("stile_popup_cookie") != "cookie_stile_modale" || isset($_COOKIE["ok_cookie"]))) { ?>
	<?php foreach ($modali_frontend as $p) { ?>
		<?php if (!isset($_COOKIE["modale_".$p["pages"]["id_page"]])) { ?>
			<?php 
			$pathModale = tpf("/Elementi/Modali/".$p["pages"]["template_modale"]);
			
			if (!file_exists($pathModale))
				continue;
			
			include($pathModale);?>
		
			<script>
			setTimeout(function(){
				UIkit.modal("#modale_<?php echo $p["pages"]["id_page"];?>").show();
			}, <?php echo (int)$p["pages"]["apri_dopo_secondi"] * 1000;?>);
			</script>
		<?php } ?>
	<?php } ?>
<?php } ?>
