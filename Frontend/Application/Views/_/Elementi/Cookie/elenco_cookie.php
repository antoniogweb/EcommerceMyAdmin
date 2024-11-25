<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php $cookieTecnici = App::getCookieTecnici();?>

<h3><?php echo gtext("Elenco dei cookie tecnici (necessari)");?></h3>

<div class="uk-overflow-auto">
	<table class="uk-table uk-table-striped">
		<thead>
			<tr>
				<td><?php echo gtext("Nome");?></td>
				<td><?php echo gtext("Durata");?></td>
				<td><?php echo gtext("Proprietario");?></td>
				<td><?php echo gtext("Descrizione");?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cookieTecnici as $c => $struct) {
					if (!isset($struct["usato"]) || !$struct["usato"])
						continue;
			?>
			<tr>
				<td><?php echo $c;?></td>
				<td><?php echo CookiearchivioModel::durata(time() + $struct["Durata"]);?></td>
				<td><?php echo $struct["Fornitore"];?></td>
				<td><?php echo $struct["Descrizione"];?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<h3><?php echo gtext("Elenco dei cookie di profilazione a fini statistici e di marketing");?></h3>

<div class="uk-overflow-auto">
	<table class="uk-table uk-table-striped">
		<thead>
			<tr>
				<td><?php echo gtext("Nome");?></td>
				<td><?php echo gtext("Durata");?></td>
				<td><?php echo gtext("Proprietario");?></td>
				<td><?php echo gtext("Descrizione");?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cookies as $c) { ?>
			<tr>
				<td><?php echo $c["titolo"];?></td>
				<td><?php echo gtext($c["durata"]);?></td>
				<td><?php echo CookiearchivioModel::getProprietario($c["servizio"]);?></td>
				<td><?php echo gtext($c["note"]);?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

