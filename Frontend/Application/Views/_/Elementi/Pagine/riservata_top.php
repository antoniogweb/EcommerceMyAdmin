<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($attiva)) $attiva = "dashboard";?>

<div class="uk-text-left" uk-grid>
	<?php if ($islogged) { ?>
	<div id="filtri-categoria" <?php if (User::$isMobile) { ?>uk-offcanvas<?php } ?> class="uk-width-1-4 uk-padding-remove uk-margin-remove <?php if (!User::$isMobile) { ?>uk-overflow-auto<?php } ?>" style="flex-basis: auto">
		<?php
		include(tpf("/Elementi/Pagine/riservata-left.php"));?>
	</div>
	<?php } ?>
	<div class="uk-width-expand">
