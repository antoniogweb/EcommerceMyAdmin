<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_csrf_form") && isset($_SESSION['csrf_token'])) { ?>
	<input type="hidden" name="csrf_token" value="<?php echo sanitizeHtml($_SESSION['csrf_token']); ?>">
<?php } ?>

