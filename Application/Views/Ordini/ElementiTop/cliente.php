<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($cliente))
	$cliente = RegusersModel::g()->selectId((int)$ordine["id_user"]);
?>
<?php if ($cliente && $cliente["deleted"] == "no" && ControllersModel::checkAccessoAlController(array("regusers"))) {
	if (!isset($urlClienti))
		$urlClienti = "regusers";
?>
<tr>
	<td><?php echo gtext("Cliente");?>:</td>
	<td>
		<a title="<?php echo gtext("Apri il dettaglio del cliente dell'ordine");?>" class="pull-right iframe badge badge-info" href="<?php echo $this->baseUrl."/$urlClienti/form/update/".$cliente["id_user"]?>?partial=Y&nobuttons=Y"><i class="fa fa-user"></i> <?php echo gtext("dettagli");?></a>
		<b><?php echo OrdiniModel::getNominativo($cliente);?></b> (<?php echo $cliente["username"];?>)
	</td>
</tr>
<?php } ?>
