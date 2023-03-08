<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (VariabiliModel::mostraAvvisiGiacenzaCarrello()) { ?>
	<?php if ($p["cart"]["disponibile"]) { ?>
		<div class="uk-text-success"><?php echo gtext(v("testo_disponibilita_immediata"));?></div>
	<?php } else { ?>
		<div class="uk-text-secondary"><?php echo gtext(v("testo_disponibilita_non_immediata"));?></div>
	<?php } ?>
<?php } ?>
