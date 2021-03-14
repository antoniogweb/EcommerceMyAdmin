<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) { ?>
<?php
$titoloPagina = field($p, "title");
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
	<?php echo $fasce;?>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
<?php } ?>
