<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($modali_frontend)) { ?>
	<?php foreach ($modali_frontend as $p) { ?>
		<?php 
		$pathModale = tpf("/Elementi/Modali/".$p["pages"]["template_modale"]);
		
		if (!file_exists($pathModale))
			continue;
		
		include($pathModale);?>
		
		<?php if (!isset($_COOKIE["modale_".$p["pages"]["id_page"]])) { ?>
			<script>
			UIkit.modal("#modale_<?php echo $p["pages"]["id_page"];?>").show();
			</script>
		<?php } ?>
	<?php } ?>
<?php } ?>
