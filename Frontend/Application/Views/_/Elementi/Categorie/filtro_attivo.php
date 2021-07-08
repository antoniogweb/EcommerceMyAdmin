<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $nomeDaAlias = GenericModel::getNomeDaAlias($carV);?>
<?php if ($nomeDaAlias) { ?>
<a class="uk-button uk-button-default uk-button-small" href="<?php echo $filtroSelezionatoUrl;?>">
	<?php echo $nomeDaAlias;?>
	<span uk-icon="icon: close;ratio: 0.6"></span>
</a>
<?php } ?>
