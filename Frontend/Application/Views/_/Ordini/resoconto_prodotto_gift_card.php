<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($p["righe"]["gift_card"]) {
	$elementiRiga = RigheelementiModel::getElementiRiga($p["righe"]["id_r"]);
	
	if (count($elementiRiga) > 0) { ?>
		<table width="100%" class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-justify" cellspacing="0">
			<tr>
				<th style="text-align:left;font-size:13px;"><?php echo gtext("Da inviare a");?></th>
				<th style="text-align:left;font-size:13px;"><?php echo gtext("Dedica e firma");?></th>
			</tr>
		<?php foreach ($elementiRiga as $el) { ?>
		<tr>
			<td style="text-align:left;font-size:13px;">
				<?php echo $el["email"];?>
			</td>
			<td style="text-align:left;font-size:13px;">
				<?php echo nl2br($el["testo"]);?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php } ?>
	
	<?php $promozioni = PromozioniModel::getPromoRigaOrdine($p["righe"]["id_r"]);
	
	if (count($promozioni) > 0) { ?>
		<table width="100%" class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-justify" cellspacing="0">
			<tr>
				<th style="text-align:left;"><?php echo gtext("Codice Gift Card");?></th>
				<th style="text-align:left;"><?php echo gtext("Stato");?></th>
			</tr>
			<?php foreach ($promozioni as $promo) { ?>
			<tr>
				<td>
					<span class="uk-text uk-text-primary"><?php echo $promo["codice"];?></span>
					<?php $inviataA = EventiretargetingelementiModel::getElemento($promo["id_p"], "promozioni"); ?>
					<?php if (!empty($inviataA)) { ?>
					<br /><span class="uk-text-meta"><?php echo gtext("Inviato a");?>:</span> <span class="uk-text-small"><?php echo $inviataA["email"];?></span>
					<?php } ?>
				</td>
				<td>
					<?php echo PromozioniModel::g()->isActiveCoupon($promo["codice"],null,false) ? "<span class='uk-label uk-label-success'>".gtext("Attivo")."</span>" : "<span class='uk-label uk-label-warning'>".gtext("Non attivo")."</span>";?>
					<?php if (PromozioniModel::gNumeroEuroRimasti($promo["id_p"]) <= 0) { ?>
					<br /><span class="uk-text-meta"><?php echo gtext("Credito esaurito");?></span>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</table>
	<?php } ?>
	
	
<?php } ?>
