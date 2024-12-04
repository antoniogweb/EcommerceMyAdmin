<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
	<?php if (!$islogged) { ?>
		
		<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large"><?php echo gtext("Indirizzo di spedizione");?></h2>
		
		<?php include(tpf("Ordini/scelta_spedizione_fatturazione.php"));?>
		
	<?php } else if ($islogged) { ?>
		
		<?php if (count($tendinaIndirizzi) > 0) { ?>
		
		<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large"><?php echo gtext("Indirizzo di spedizione");?></h2>
		
		<div class="blocco_checkout">
			<?php if (v("permetti_modifica_account")) { ?>
			<div class="blocco_scelta_indirizzo">
				<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"Y","imposta_aggiungi","none");?> <?php echo gtext("Aggiungi un nuovo indirizzo di spedizione");?>
			</div>
			<?php } ?>
			<div class="blocco_scelta_indirizzo">
				<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"N","imposta_seleziona","none");?> <?php echo gtext("Seleziona un indirizzo di spedizione esistente");?>
			</div>
			
			<div class="uk-margin blocco_tendina_scelta_indirizzo">
				<label class="uk-form-label"><?php echo gtext("Indirizzo");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::select("id_spedizione",$values["id_spedizione"],$tendinaIndirizzi,"uk-select tendina_scelta_indirizzo",null,"yes");?>
				</div>
			</div>
			
			<?php include(tpf("Regusers/form_dati_spedizione.php"));?>
		</div>
		
		<?php } else { ?>
		
		<?php include(tpf("Ordini/scelta_spedizione_fatturazione.php"));?>
		
		<input type="hidden" name="id_spedizione" value="0" />
		<?php } ?>
		
	<?php } ?>
<?php } ?>
