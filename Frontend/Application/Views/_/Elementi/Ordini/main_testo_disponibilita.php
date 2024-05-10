<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (VariabiliModel::mostraAvvisiGiacenzaCarrello() && !$p["righe"]["prodotto_generico"]) { ?>
	<?php if ($p["righe"]["disponibile"]) { ?>
		<div class="uk-text-success uk-text-small"><?php echo gtext(v("testo_disponibilita_immediata"));?></div>
	<?php } else { ?>
		<div class="uk-text-secondary uk-text-small"><?php echo gtext(v("testo_disponibilita_non_immediata"));?></div>
	<?php } ?>
<?php } ?>
