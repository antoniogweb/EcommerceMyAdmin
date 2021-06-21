<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) { ?>
<?php
$titoloPagina = mfield($p, "title");
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php echo htmlentitydecode(attivaModuli(mfield($p, "description")));?>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
<?php } ?>
