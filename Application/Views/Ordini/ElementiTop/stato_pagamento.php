<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Stato pagamento");?>:</td>
	<td>
		<?php if ($ordine["pagato"] || StatiordineModel::g(false)->pagato($ordine["stato"])) { ?>
		<span class="label label-success"><?php echo gtext("Ordine pagato");?></span>
			<?php if ($ordine["data_pagamento"]) { ?>
			<?php echo gtext("in data");?> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_pagamento"]));?></b>
			<?php } ?>
		<?php } else { ?>
		<span class="label label-warning"><?php echo gtext("Ordine NON pagato");?></span>
		<?php } ?>
	</td>
</tr>
