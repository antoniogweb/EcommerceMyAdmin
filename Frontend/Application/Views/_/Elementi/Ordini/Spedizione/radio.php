<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
	<?php if (!$islogged) { ?>
		
		<div class="uk-margin-medium">
			<?php include(tpf("Ordini/scelta_spedizione_fatturazione.php"));?>
		</div>
		
	<?php } else if ($islogged) { ?>
		
		<?php if (count($tendinaIndirizzi) > 0) { ?>
		
			<div class="blocco_checkout">
				
				<div class="uk-grid uk-grid-small" uk-grid>
					<?php foreach ($elencoIndirizzi as $indirizzo) { ?>
					<div class="uk-width-1-1 uk-width-1-2@m">
						<div class="spedizione_box spedizione_box_select uk-padding-small uk-flex uk-flex-middle <?php if ($values["id_spedizione"] == $indirizzo["id_spedizione"]) { ?>spedizione_selezionata<?php } ?>">
							<div>
								<?php echo Html_Form::radio("id_spedizione_radio",$values["id_spedizione"],$indirizzo["id_spedizione"],"radio_spedizione","none");?>
							</div>
							<div class="uk-margin-left uk-text-small">
								<span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <span class="uk-text-bold"></span> <?php echo $indirizzo["indirizzo_spedizione"];?>, <?php echo $indirizzo["cap_spedizione"];?><br /> <?php echo $indirizzo["citta_spedizione"];?> (<?php echo $indirizzo["provincia_spedizione"];?>), <?php echo nomeNazione($indirizzo["nazione_spedizione"]);?>
								<?php if (trim($indirizzo["telefono_spedizione"])) { ?>
								<br /><span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo $indirizzo["telefono_spedizione"];?>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="spedizione_box">
					<div class="uk-width-1-1 uk-width-1-2@m <?php if ($values["id_spedizione"] == 0) { ?>spedizione_selezionata<?php } ?> uk-padding-small uk-flex uk-flex-middle spedizione_box_select">
						<div>
							<?php echo Html_Form::radio("id_spedizione_radio",$values["id_spedizione"],"0","radio_spedizione","none");?> 
						</div>
						<div class="uk-margin-left uk-text-small">
							<?php echo gtext("Aggiungi un nuovo indirizzo di spedizione");?>
						</div>
					</div>
					<div class="campi_nuovo_indirizzo">
						<?php include(tpf("Regusers/form_dati_spedizione.php"));?>
					</div>
				</div>
				<?php echo Html_Form::hidden("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"]);?> 
				<?php echo Html_Form::hidden("id_spedizione",$values["id_spedizione"]);?> 
				
				<?php if (false) { ?>
				<div class="blocco_scelta_indirizzo">
					<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"N","imposta_seleziona","none");?> <?php echo gtext("Seleziona un indirizzo di spedizione esistente");?>
				</div>
				
				<div class="blocco_scelta_indirizzo">
					<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"Y","imposta_aggiungi","none");?> <?php echo gtext("Aggiungi un nuovo indirizzo di spedizione");?>
				</div>
				
				
				<div class="uk-margin blocco_tendina_scelta_indirizzo">
					<label class="uk-form-label"><?php echo gtext("Indirizzo");?> *</label>
					<div class="uk-form-controls">
						<?php echo Html_Form::select("id_spedizione",$values["id_spedizione"],$tendinaIndirizzi,"uk-select tendina_scelta_indirizzo",null,"yes");?>
					</div>
				</div>
				<?php } ?>
			</div>
		
		<?php } else { ?>
			
			<?php include(tpf("Ordini/scelta_spedizione_fatturazione.php"));?>
		
			<input type="hidden" name="id_spedizione" value="0" />
		<?php } ?>
		
	<?php } ?>
<?php } ?>
