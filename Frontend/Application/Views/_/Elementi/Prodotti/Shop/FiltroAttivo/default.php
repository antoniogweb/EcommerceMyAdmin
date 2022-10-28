<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $nomeDaAlias = GenericModel::getNomeDaAlias($carV);

if (!$nomeDaAlias && isset($carV) && isset($car) && $car == AltriFiltri::$altriFiltriTipi["fascia-prezzo"])
	$nomeDaAlias = sanitizeHtml(str_replace("-"," ",$carV)."€");
?>
<?php if ($nomeDaAlias) { ?>
<span class="uk-button uk-button-default uk-button-small"><?php echo $nomeDaAlias;?>
	<a title="<?php echo gtext("Elimina filtro");?>" href="<?php echo $filtroSelezionatoUrl;?>">
		<span uk-icon="icon: close;ratio: 0.7"></span>
	</a>
</span>
<?php } ?> 
