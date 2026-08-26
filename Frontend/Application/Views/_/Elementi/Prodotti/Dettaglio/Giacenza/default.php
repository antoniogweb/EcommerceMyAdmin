<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php if ($haVarianti) { ?>blocco-prezzo<?php } ?> giacenza">
	<?php $qtaAcc = giacenzaPrincipale($p["pages"]["id_page"]);?>
	<span class="uk-text-muted"><?php echo gtextPlain("Disponibilità");?>: </span><span class="valore_giacenza"><?php echo $qtaAcc;?></span>
	<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtextPlain("pezzo", false);?></span>
	<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtextPlain("pezzi", false);?></span>
</li>
