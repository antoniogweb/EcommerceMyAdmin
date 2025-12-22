<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("imposta_la_nazione_dell_utente_a_quella_nell_url")) { ?>
<div id="modal-listini" class="<?php if (isset($_SESSION["carrello_ricalcolato"])) { ?>modal-listini-open<?php } ?>" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title"><?php echo gtext("Carrello aggiornato");?></h2>
        <p><?php echo gtext("Il carrello è stato aggiornato secondo il mercato di riferimento selezionato.");?></p>
    </div>
</div>
<?php if ( isset($_SESSION['carrello_ricalcolato']) ) unset($_SESSION['carrello_ricalcolato']);?>
<?php } ?> 
