<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['spedizioni'];?>><a class="help_spedizioni" href="<?php echo $this->baseUrl."/".$this->controller."/spedizioni/$id".$this->viewStatus;?>"><?php echo gtext("Indirizzi di spedizione");?></a></li>
	<?php if (v("ecommerce_attivo")) { ?>
	<li <?php echo $posizioni['ordini'];?>><a class="help_ordini" href="<?php echo $this->baseUrl."/".$this->controller."/ordini/$id".$this->viewStatus;?>"><?php echo gtext("Ordini effettuati");?></a></li>
	<?php } ?>
	<?php if (v("attiva_gruppi_utenti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>"><?php echo gtext("Gruppi");?></a></li>
	<?php } ?>
	<?php if (v("attiva_agenti") && RegusersModel::g()->whereId((int)$id)->field("agente")) { ?>
	<li <?php echo $posizioni['ordinicollegati'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/ordinicollegati/$id".$this->viewStatus;?>"><?php echo gtext("Ordini collegati");?></a></li>
	<li <?php echo $posizioni['promozioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/promozioni/$id".$this->viewStatus;?>"><?php echo gtext("Coupon");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
