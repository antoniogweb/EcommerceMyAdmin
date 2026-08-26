<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($ordini_coupon) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Ordine");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Data");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Cliente");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Stato");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Totale");?>
			</div>
		</div>
	</div>
	<hr>
	<?php foreach ($ordini_coupon as $ordine) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Ordine");?>:</span> #<?php echo $ordine["id_o"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Data");?>:</span> <?php echo smartDate($ordine["data_creazione"]);?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Cliente");?>:</span> <?php echo OrdiniModel::getNominativo($ordine);?><br /><?php echo $ordine["email"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Stato");?>:</span> <?php echo statoOrdine($ordine["stato"]);?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Totale");?>:</span> <?php echo setPriceReverse($ordine["total"]);?>€
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
	<?php echo gtextPlain("Questo codice coupon non ha ancora alcun ordine associato.");?>
<?php } ?>
