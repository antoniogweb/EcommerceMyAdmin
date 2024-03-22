<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($messaggi) > 0) { ?>
<div class="uk-text-primary uk-text-bold uk-margin uk-margin-large-top"><?php echo gtext("Messaggi successivi");?></div>
<?php } ?>
<?php foreach ($messaggi as $m) {
	$scrittoDa = $m["ticket_messaggi"]["id_user"] ? $nominativoCliente : $m["adminusers"]["username"];
?>
<hr class="uk-divider-small">
<div id="messaggi_<?php echo $m["ticket_messaggi"]["id_ticket_messaggio"];?>">

	<?php if (!$m["ticket_messaggi"]["id_user"]) { ?><div class="uk-text-emphasis uk-text-small uk-margin-bottom"><span uk-icon="comments"></span> <?php echo gtext("Risposta del negozio:");?></div><?php } ?>
	
	<div class="uk-text-italic <?php if (!$m["ticket_messaggi"]["id_user"]) { ?>uk-background-muted uk-padding-small<?php } ?>">
		<?php if (!$m["ticket_messaggi"]["id_user"]) { ?>
		<?php echo htmlentitydecode($m["ticket_messaggi"]["descrizione"]);?>
		<?php } else { ?>
		<?php echo nl2br($m["ticket_messaggi"]["descrizione"]);?>
		<?php } ?>
	</div>
	
	<?php if ($m["ticket_messaggi"]["filename"] && TicketfileModel::fileEsistente($m["ticket_messaggi"]["filename"])) { ?>
	<div class="uk-margin">
		<?php if ($m["ticket_messaggi"]["tipo"] == "IMMAGINE") { ?>
		<a target="_blank" href="<?php echo $this->baseUrlSrc."/thumb/immagineticketfull/".$m["ticket_messaggi"]["filename"];?>"><img style="max-width:100px;" src="<?php echo $this->baseUrlSrc."/thumb/immagineticket/".$m["ticket_messaggi"]["filename"];?>" /></a>
		<?php } else {
			$daElaborare = TicketfileModel::daElaborare($m["ticket_messaggi"]["filename"]);
		?>
			<?php echo gtext("file allegato");?>:
			<?php if (!$daElaborare) { ?>
			<a target="_blank" href="<?php echo $this->baseUrlSrc."/ticket/scarica/".$m["ticket_messaggi"]["filename"];?>">
			<?php } ?>
				<span class="uk-text-small"><?php echo $m["ticket_messaggi"]["clean_filename"];?></span>
			<?php if (!$daElaborare) { ?>
			</a>
			<?php } else { ?>
			<span class="uk-text-italic uk-text-small">(<?php echo gtext("in elaborazione");?> <span uk-icon="icon: clock;ratio: 0.7"></span>)</span>
			<?php } ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div class="uk-text-muted uk-text-small uk-margin"><?php echo gtext("Scritto da") . " <span class='uk-text-secondary'>". $scrittoDa;?></span> <?php echo gtext("in data")?> <span class='uk-text-secondary'><?php echo date("d-m-Y H:i", strtotime($m["ticket_messaggi"]["data_creazione"]));?></span></div>
</div>
<?php } ?>
