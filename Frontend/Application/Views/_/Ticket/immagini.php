<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-grid-match uk-grid-column-small <?php if ($tipo == "VIDEO") { ?>uk-child-width-1-<?php if ($isBozza) { ?>2<?php } else { ?>1<?php } ?>@s uk-child-width-1-1<?php } else { ?>uk-child-width-1-4@s uk-child-width-1-2<?php } ?> uk-grid" uk-grid>
	<?php foreach ($files as $file) {
		$ticket = $file["ticket"];
		$file = $file["ticket_file"];
		
		$idFile = (int)$file["id_ticket_file"];
		$idTicket = (int)$ticket["id_ticket"];
		$ticketUid = $ticket["ticket_uid"];
	?>
	<div class="uk-margin-small-top uk-position-relative">
		<div class="uk-inline <?php if ($file["tipo"] == "VIDEO") { ?>uk-background-muted"<?php } ?>">
			<?php if ($file["tipo"] == "IMMAGINE" || $file["tipo"] == "SCONTRINO") { ?>
			<a target="_blank" href="<?php echo $this->baseUrlSrc."/thumb/immagineticketfull/".$file["filename"];?>"><img src="<?php echo $this->baseUrlSrc."/thumb/immagineticket/".$file["filename"];?>" /></a>
			<?php } else { ?>
			<div style="padding:5px;"><span uk-icon="icon: play; ratio: 1.5"></span>
				<?php $daElaborare = TicketfileModel::daElaborare($file["filename"]);?>
				<?php if (!$daElaborare) { ?>
				<a target="_blank" href="<?php echo $this->baseUrlSrc."/images/ticket_immagini/".$file["filename"];?>">
				<?php } ?>
					<span class="uk-text-small"><?php echo $file["clean_filename"];?></span>
				<?php if (!$daElaborare) { ?>
				</a>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ($isBozza) { ?>
			<div class="uk-position-top-right" style="background-color:#FFF;padding:5px;">
				<span class="uk-text-danger spinner uk-hidden" uk-spinner="ratio: .70"></span>
				<a tipo="<?php echo strtolower($file["tipo"]);?>" title="<?php echo gtext("Elimina il file");?>" class="btn_submit_form elimina_immagine_ticket uk-text-danger" href="<?php echo $this->baseUrl."/ticket/eliminafile/$idFile/$idTicket/$ticketUid" ?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/close.svg");?></span></a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
</div>

<?php if ($isBozza && count($files) < TicketfileModel::$maxNumero[strtolower($tipo)]) { ?>
<div class="uk-margin upload_ticket_box" uk-margin>
	<div class="upload_ticket_alert"></div>
	<div uk-form-custom="target: true" class="uk-margin-remove">
		<input type="file" aria-label="Custom controls" name="<?php echo strtolower($tipo);?>">
		<input class="uk-input uk-form-width-medium" type="text" placeholder="<?php echo gtext("Seleziona il file");?>" aria-label="Custom controls" disabled>
	</div>
	<span>
		<span class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></span>
		<a href="#" class="uk-button uk-button-primary upload_immagine_ticket"><?php echo gtext("Carica");?></a>
	</span>
</div>
<?php } ?>
