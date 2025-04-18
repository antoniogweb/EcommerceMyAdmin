<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Stato pagamento");?>:</td>
	<td>
		<?php if ($ordine["pagato"] || StatiordineModel::g(false)->pagato($ordine["stato"])) { ?>
		<?php if (v("permetti_annullare_data_pagamento_e_annullamento") && OrdiniModel::g()->puoAnnullareDataPagamento((int)$ordine["id_o"])) { ?>
		<a title="<?php echo gtext("Elimina la data di pagamento");?>" class="pull-right badge bg-olive ajlink" href="<?php echo $this->baseUrl."/ordini/settanonpagato/".$ordine["id_o"];?>"><i class="fa fa-times "></i></a>
		<?php } ?>
		
		<span class="label label-success"><?php echo gtext("Ordine pagato");?></span>
			<?php if ($ordine["data_pagamento"]) { ?>
			<?php echo gtext("in data");?> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_pagamento"]));?></b>
			<?php } ?>
		<?php } else { ?>
		<span class="label label-warning"><?php echo gtext("Ordine NON pagato");?></span>
		<?php } ?>
	</td>
</tr>
<?php if (v("mostra_data_annullamento_se_presente") && $ordine["annullato"] && $ordine["data_annullamento"]) { ?>
<tr>
	<td><?php echo gtext("Data annullamento");?>:</td>
	<td>
		<?php if (v("permetti_annullare_data_pagamento_e_annullamento") && OrdiniModel::g()->puoAnnullareDataAnnullamento((int)$ordine["id_o"])) { ?>
		<a title="<?php echo gtext("Elimina la data di annullamento");?>" class="pull-right badge bg-orange ajlink" href="<?php echo $this->baseUrl."/ordini/settanonannullato/".$ordine["id_o"];?>"><i class="fa fa-times "></i></a>
		<?php } ?>
		
		<span class="label label-warning"><?php echo gtext("Ordine annullato in data");?></span> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_annullamento"]));?></b>
	</td>
</tr>
<?php } ?>
<?php if (v("mostra_data_annullamento_se_presente") && $ordine["rimborsato"] && $ordine["data_rimborso"]) { ?>
<tr>
	<td><?php echo gtext("Data rimborso");?>:</td>
	<td>
		<?php if (v("permetti_annullare_data_pagamento_e_annullamento") && OrdiniModel::g()->puoAnnullareDataRimborso((int)$ordine["id_o"])) { ?>
		<a title="<?php echo gtext("Elimina la data di rimborso");?>" style="background-color: #dd4b39;border-color: #d73925;" class="pull-right badge ajlink" href="<?php echo $this->baseUrl."/ordini/settanonrimborsato/".$ordine["id_o"];?>"><i class="fa fa-times "></i></a>
		<?php } ?>
		
		<span class="label label-danger"><?php echo gtext("Ordine rimborsato in data");?></span> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_rimborso"]));?></b>
	</td>
</tr>
<?php } ?>
