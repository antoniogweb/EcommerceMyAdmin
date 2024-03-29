<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-width-1-1 uk-flex uk-flex-top uk-grid uk-margin-medium-bottom" uk-grid>
    <div class="uk-width-1-1 uk-width-1-3@m uk-text-small">
		<?php echo gtext("ID Ticket");?>: <span class="uk-text-bold uk-text-primary"><?php echo $idTicket;?></span><br />
		<?php echo gtext("Stato del Ticket");?>: <span class="uk-label" style="<?php echo TicketModel::getStile($ticket["stato"]);?>"><?php echo TicketModel::getTitoloStato($ticket["stato"]);?></span><br />
		<?php if ($ticket["stato"] == "B") { ?>
		<?php echo gtext("Data creazione");?>: <b class="uk-text-primary"><?php echo smartDate($ticket["data_creazione"]);?></b>
		<?php } else { ?>
		<?php echo gtext("Data invio");?>: <b class="uk-text-primary"><?php echo smartDate($ticket["data_invio"]);?></b>
		<?php } ?>
		<?php if ($ticket["stato"] == "C") { ?>
		<br /><?php echo gtext("Data chiusura");?>: <b class="uk-text-primary"><?php echo smartDate($ticket["data_chiusura"]);?></b>
		<?php } ?>
		<?php if ($ticket["id_admin"]) { ?>
		<br /><?php echo gtext("Questo ticket è stato creato dal negozio.");?>
		<br /><?php echo gtext("Utente negozio:")?> <b class="uk-text-primary"><?php echo UsersModel::getUsername($ticket["id_admin"]);?></b>
		<?php } ?>
    </div>
    <div class="uk-width-1-1 uk-width-2-3@m">
		
    </div>
</div>

<?php echo flash("notice");?>

<?php
if ($ticket["stato"] == "B")
	include(tpf("Ticket/form.php"));
else
	include(tpf("Ticket/dettaglio.php"));
?>
