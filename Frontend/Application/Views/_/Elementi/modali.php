<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($modali_frontend as $p) { ?>
	<?php include(tpf("/Elementi/Modali/modale_stile_1.php"));?>
	
	<?php if (!isset($_COOKIE["modale_".$p["pages"]["id_page"]])) { ?>
		<script>
		UIkit.modal("#modale_<?php echo $p["pages"]["id_page"];?>").show();
		</script>
	<?php } ?>
<?php } ?>
