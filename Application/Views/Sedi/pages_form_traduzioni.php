<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$nascondiAlias = $nascondiLink = true;

if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
	<div class="panel panel-info">
		<div class="panel-heading">
			<?php echo gtext("Traduzioni");?>
		</div>
		<div class="panel-body">
			<?php include($this->viewPath("pages_traduzioni"));?>
		</div>
	</div>
<?php } ?>