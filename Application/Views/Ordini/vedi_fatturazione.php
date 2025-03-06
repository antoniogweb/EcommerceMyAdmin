<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<table class="table table-striped">
	<?php if ($cliente && $cliente["deleted"] == "no" && ControllersModel::checkAccessoAlController(array("regusers"))) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Account cliente");?></td>
		<td><a class="iframe label label-success" href="<?php echo $this->baseUrl."/regusers/form/update/".$cliente["id_user"]?>?partial=Y"><?php echo $cliente["username"];?></a></td>
	</tr>
	<?php } ?>
	<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Nome");?></td>
		<td><?php echo $ordine["nome"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Cognome");?></td>
		<td><?php echo $ordine["cognome"];?></td>
	</tr>
	<?php } ?>
	<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Ragione sociale");?></td>
		<td><?php echo $ordine["ragione_sociale"];?></td>
	</tr>
	<?php } ?>
	<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Partita IVA");?></td>
		<td><?php echo $ordine["p_iva"];?></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="first_column"><?php echo gtext("Codice fiscale");?></td>
		<td><?php echo $ordine["codice_fiscale"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Indirizzo");?></td>
		<td><?php echo $ordine["indirizzo"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Cap");?></td>
		<td><?php echo $ordine["cap"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Nazione");?></td>
		<td><?php echo nomeNazione($ordine["nazione"]);?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Provincia");?></td>
		<td><?php echo $ordine["provincia"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Città");?></td>
		<td><?php echo $ordine["citta"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Telefono");?></td>
		<td><?php echo $ordine["telefono"];?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Email");?></td>
		<td><?php echo $ordine["email"];?></td>
	</tr>
	<?php if (VariabiliModel::attivaCodiceGestionale()) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Codice gestionale");?></td>
		<td><?php echo $ordine["codice_gestionale_cliente"];?></td>
	</tr>
	<?php } ?>
	<?php if (strcmp($tipoOutput,"web") !== 0 and $sendPassword ) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("PASSWORD");?></td>
		<td><?php echo $password;?></td>
	</tr>
	<?php } ?>
</table>
<br />
