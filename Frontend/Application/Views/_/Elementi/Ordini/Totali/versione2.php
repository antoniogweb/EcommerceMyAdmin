<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<h2 class="<?php echo v("classi_titoli_checkout");?>">
	<span uk-icon="icon:cart;ratio:1.2" class="uk-margin-right uk-hidden@m"></span><?php echo gtext("Totali carrello");?>
</h2>

<div class="blocco_totale_merce">
	<?php include(tpf("/Ordini/totale_merce.php")); ?>
</div>
