<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Tipo ordine");?>:</td>
	<td><b><?php echo OrdiniModel::getLabelTipoOrdine($ordine["tipo_ordine"]);?></b></td>
</tr>
