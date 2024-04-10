<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (CartelementiModel::evidenzia($pageView) && CartelementiModel::haErrori()) { ?>
<div class="uk-grid uk-grid-small uk-child-width-expand@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?>" uk-grid="">
	<div class="uk-first-column uk-width-1-1 uk-width-1-5@m">
	</div>
	<div class="uk-width-expand uk-text-right uk-text-small uk-text-danger">
		<?php echo gtext("Si prega di verificare i campi evidenziati");?>
	</div>
</div>
<?php } ?>
