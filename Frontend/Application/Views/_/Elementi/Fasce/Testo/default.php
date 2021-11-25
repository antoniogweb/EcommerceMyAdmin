<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (trim(field($p, "description"))) { ?>
<div class="uk-section uk-text-left uk-padding-small uk-margin-medium">
	<div class="uk-container">
		<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
	</div>
</div>
<?php } ?>
