<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count($link_lista) > 0) { ?>
	<div class="uk-margin-bottom uk-text-small uk-text-emphasis"><?php echo gtext("Persone a cui hai inviato il link");?>:</div>
	
	<?php foreach ($link_lista as $link) { ?>
	<div class="uk-grid uk-grid-medium uk-width-1-1 uk-width-2-3@s uk-margin-small-top uk-text-small uk-flex uk-flex-middle" uk-grid>
		<div class="uk-width-1-1 uk-width-1-3@s"><span uk-icon="chevron-right"></span> <?php echo $link["liste_regalo_link"]["nome"];?> <?php echo $link["liste_regalo_link"]["cognome"];?></div>
		<div class="uk-width-1-1 uk-width-2-3@s uk-margin-remove-top">
			<div class="uk-grid uk-width-1-1" uk-grid>
				<div class="uk-width-2-3"><?php echo $link["liste_regalo_link"]["email"];?></div>
				<div class="uk-margin-remove-top uk-width-1-3">
					<div class="uk-grid uk-grid-small" uk-grid>
						<div>
							<?php if ($link["liste_regalo_link"]["inviato"]) { ?>
							<span title="<?php echo gtext("Il link è stato inviato correttamente.");?>" class="uk-text-success" uk-icon="check"></span>
							<?php } else { ?>
							<span title="<?php echo gtext("Errore nell'invio del link, si prega di riprovare.");?>" class="uk-text-danger" uk-icon="ban"></span>
							<?php } ?>
						</div>
						<div class="uk-margin-remove-top">
							<?php if ($link["liste_regalo_link"]["numero_tentativi"] < v("numero_massimo_tentativi_invio_link")) { ?>
							<span class="spinner uk-hidden" uk-spinner="ratio: .70"></span>
							<a class="invia_nuovamente_link btn_submit_form" href="<?php echo $this->baseUrl."/listeregalo/invianuovamentelink/".$link["liste_regalo_link"]["id_lista_regalo_link"];?>"><span title="<?php echo gtext("Invia nuovamente il link");?>" class="uk-margin-small-left" uk-icon="mail"></span></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
<?php } else { ?>
	<span class="uk-text-small"><?php echo gtext("Non hai ancora inviato il link ad alcun indirizzo e-mail")?></span>
<?php } ?>
