<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_agenti") && isset($utente) && !empty($utente)) { ?>
<div class="callout callout-info">
	<?php echo gtext("Questo coupon è assegnato all'agente");?>
	<?php if (!partial()) { ?><a class="iframe" href="<?php echo $this->baseUrl."/regusers/form/update/".$utente["id_user"]."?partial=Y&nobuttons=Y&agente=1";?>"><?php } ?>
		<b><?php echo RegusersModel::getNominativo($utente);?></b>.
	<?php if (!partial()) { ?></a><?php } ?>
</div>
<?php } ?>

<?php echo $main;?>
