<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count($invii_codice) > 0) { ?>
	<div class="uk-margin-bottom uk-text-small uk-text-emphasis"><?php echo gtext("Persone a cui hai inviato il codice");?>:</div>
	
	<?php foreach ($invii_codice as $invio) { ?>
	<div class="uk-grid uk-grid-medium uk-width-1-1 uk-width-2-3@s uk-margin-small-top uk-text-small uk-flex uk-flex-middle" uk-grid>
		<div class="uk-width-1-1 uk-width-1-3@s"><span uk-icon="chevron-right"></span> <?php echo $invio["promozioni_invii"]["nome"];?> <?php echo $invio["promozioni_invii"]["cognome"];?></div>
		<div class="uk-width-1-1 uk-width-2-3@s uk-margin-remove-top">
			<div class="uk-grid uk-width-1-1" uk-grid>
				<div class="uk-width-2-3"><?php echo $invio["promozioni_invii"]["email"];?></div>
				<div class="uk-margin-remove-top uk-width-1-3">
					<div class="uk-grid uk-grid-small" uk-grid>
						<div>
							<?php if ($invio["promozioni_invii"]["inviato"]) { ?>
							<span title="<?php echo gtext("Il codice è stato inviato correttamente.");?>" class="uk-text-success" uk-icon="check"></span>
							<?php } else { ?>
							<span title="<?php echo gtext("Errore nell'invio del codice, si prega di riprovare.");?>" class="uk-text-danger" uk-icon="ban"></span>
							<?php } ?>
						</div>
						<div class="uk-margin-remove-top">
							<?php if ($invio["promozioni_invii"]["numero_tentativi"] < v("numero_massimo_tentativi_invio_link")) { ?>
							<span class="spinner uk-hidden" uk-spinner="ratio: .70"></span>
							<a class="invia_nuovamente_codice btn_submit_form" href="<?php echo $this->baseUrl."/promozioni/invianuovamentecodice/".$invio["promozioni_invii"]["id_promozione_invio"];?>"><span title="<?php echo gtext("Invia nuovamente il codice");?>" class="uk-margin-small-left" uk-icon="mail"></span></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
<?php } else { ?>
	<span class="uk-text-small"><?php echo gtext("Non hai ancora inviato il codice ad alcun indirizzo e-mail")?></span>
<?php } ?>
