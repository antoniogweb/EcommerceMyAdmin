<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<h2 class="<?php echo v("classi_titoli_checkout");?>"><?php echo gtext("Totali carrello");?></h2>

<div class="blocco_totale_merce">
	<?php include(tpf("/Ordini/totale_merce.php")); ?>
</div>
