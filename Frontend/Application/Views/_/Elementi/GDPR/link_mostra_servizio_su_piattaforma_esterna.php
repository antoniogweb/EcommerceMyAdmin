<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($urlServizio) && $urlServizio) { ?>
<div class="uk-margin uk-text-center">
	<?php echo gtextPlain("oppure");?>
</div>
<a class="uk-width-1-1 uk-button uk-button-default" target="_blank" href="<?php echo $urlServizio;?>">
	<?php echo gtextPlain("Guarda sul sito di")." ".$servizioBloccato;?>
</a>
<?php } ?>
