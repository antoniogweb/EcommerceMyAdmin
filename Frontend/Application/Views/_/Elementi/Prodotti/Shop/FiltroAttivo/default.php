<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $nomeDaAlias = GenericModel::getNomeDaAlias($carV);

if (!$nomeDaAlias && isset($carV) && isset($car) && $car == AltriFiltri::$altriFiltriTipi["fascia-prezzo"])
	$nomeDaAlias = sanitizeHtml(str_replace("-"," ",$carV)."€");
?>
<?php if ($nomeDaAlias) { ?>
<span class="uk-button uk-button-default uk-button-small"><?php echo $nomeDaAlias;?>
	<a title="<?php echo gtext("Elimina filtro");?>" href="<?php echo $filtroSelezionatoUrl;?>">
		<img style="height:15px;" src="<?php echo tpf("Elementi/Icone/Svg/close.svg", true);?>" />
	</a>
</span>
<?php } ?> 
