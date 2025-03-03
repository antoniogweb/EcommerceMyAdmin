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
<?php if ($ordine["annullato"] && $ordine["data_annullamento"]) { ?>
<tr>
	<td><?php echo gtext("Data annullamento / rimborso");?>:</td>
	<td>
		<?php if (false && date("Y-m-d", strtotime($ordine["data_annullamento"])) == date("Y-m-d")) { ?>
		<a title="<?php echo gtext("Segna come non annullato");?>" class="pull-right badge bg-maroon ajlink" href="<?php echo $this->baseUrl."/ordini/settanonannullato/".$ordine["id_o"];?>"><i class="fa fa-times "></i></a>
		<?php } ?>
		
		<span class="label bg-maroon"><?php echo gtext("Ordine annullato in data");?></span> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_annullamento"]));?></b>
	</td>
</tr>
<?php } ?>
