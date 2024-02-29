<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($eliminaButton))
	$eliminaButton = true;
?>
<div class="box_prodotti_inner">
	<?php if ($mostra_tendina_prodotti && $numeroProdotti < v("numero_massimo_prodotti_ticket")) { ?>
	<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Seleziona e aggiugni un prodotto per cui chiedi assistenza");?></div>
	<div class="uk-form-controls">
		<div class="uk-grid-collapse uk-grid uk-flex uk-flex-bottom" uk-grid>
			<div class="uk-width-3-4">
				<label class="uk-form-label"><?php echo gtext("Seleziona il prodotto");?> *</label>
				<div class="uk-form-controls uk-margin-bottom"><?php echo Html_Form::select("id_page",0,$prodotti,"uk-select class_id_page",null,"yes");?></div>
				
				<label class="uk-form-label"><?php echo gtext("Scrivi il numero seriale del prodotto");?></label>
				<div class="uk-form-controls"><?php echo Html_Form::input("numero_seriale","","uk-input class_numero_seriale",null,"placeholder='".gtext("Numero seriale", false)."'");?></div>
			</div>
			<div class="uk-width-1-4 uk-text-right">
				<div class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<a href="<?php echo $this->baseUrl."/ticket/aggiungiprodotto/$idTicket/$ticketUid"?>" title="<?php echo gtext("Aggiungi il prodotto al ticket")?>" class="uk-button uk-button-primary aggiungi_al_ticket"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span></a>
			</div>
		</div>
	</div>
	<hr />
	<?php } ?>

	<?php if (count($prodottiInseriti) > 0) { ?>
	<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Prodotti aggiunti al ticket");?></div>
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-divider uk-table-hover uk-table-small" cellspacing="0">
			<?php foreach ($prodottiInseriti as $p) { ?>
			<tr class="ordini_table_row uk-text-small">
				<td><img style="max-width:50px;" src="<?php echo $this->baseUrlSrc."/thumb/carrello/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>"></td>
				<td><?php echo field($p, "title");?><br /><?php echo gtext("N.Seriale");?>: <b><?php echo $p["ticket_pages"]["numero_seriale"] ? $p["ticket_pages"]["numero_seriale"] : "--" ;?></b></td>
				<?php if ($eliminaButton) { ?>
				<td class="uk-text-right">
					<div class="uk-margin-left uk-text-bold td_edit uk-text-danger spinner uk-hidden" uk-spinner="ratio: .70"></div>
					<a id-page="<?php echo (int)$p["pages"]["id_page"];?>" class="btn_submit_form elimina_dal_tiket uk-margin-left uk-text-bold td_edit uk-text-danger" title="<?php echo gtext("Elimina il prodotto dal ticket",false);?>" href="<?php echo $this->baseUrl."/ticket/rimuoviprodotto/$idTicket/$ticketUid";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/trash.svg");?></span></a>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>
	</div>
	<?php } ?>
</div>
