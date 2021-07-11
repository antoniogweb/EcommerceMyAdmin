<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$titoloPagina = field($p, "title");
	$noNumeroProdotti = true;
	include(tpf("/Elementi/Pagine/page_top.php"));
?>
	<?php if (trim(field($p, "description"))) { ?>
	<div class="uk-section uk-text-left uk-padding-small">
		<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
	</div>
	<?php }

	echo $fasce;

	include(tpf("/Elementi/Pagine/page_bottom.php"));
}
