<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($attiva)) $attiva = "dashboard";?>
<?php if (User::$isMobile) { ?>
	<?php if ($islogged) { ?>
	<div class="uk-margin-large-bottom">
		<a href="#filtri-categoria" class="uk-button uk-button-default uk-margin-small-right uk-margin-top" uk-toggle="target: #filtri-categoria"><span class="uk-margin-xsmall-right" uk-icon="icon: settings; ratio: .75;"></span> <?php echo gtext("Menù area riservata");?></a>
	</div>
	<?php } ?>
<?php } ?>
<div class="uk-text-left" uk-grid>
	<?php if ($islogged) { ?>
	<div id="filtri-categoria" <?php if (User::$isMobile) { ?>uk-offcanvas<?php } ?> class="uk-width-1-4 uk-padding-remove uk-margin-remove <?php if (!User::$isMobile) { ?>uk-overflow-auto<?php } ?>" style="flex-basis: auto">
		<?php
		include(tpf("/Elementi/Pagine/riservata-left.php"));?>
	</div>
	<?php } ?>
	<div class="uk-width-expand">
