<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="table_dati_spedizione">
	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<div class="first_of_grid uk-margin uk-margin-remove-bottom">
			<label for="indirizzo_spedizione" class="uk-form-label"><?php echo gtextPlain("Indirizzo");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("indirizzo_spedizione",$values['indirizzo_spedizione'],"uk-input class_indirizzo_spedizione","indirizzo_spedizione","placeholder='".gtextAttr("Indirizzo", false)."' aria-required='true'");?>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label for="cap_spedizione" class="uk-form-label"><?php echo gtextPlain("Cap");?> <span class="nascondi_fuori_italia_inline_spedizione" aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("cap_spedizione",$values['cap_spedizione'],"uk-input class_cap_spedizione","cap_spedizione","placeholder='".gtextAttr("Cap", false)."' aria-required='true'");?>
			</div>
		</div>
		
		<?php if (count($selectNazioniSpedizione) > 2) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label for="nazione_spedizione" class="uk-form-label"><?php echo gtextPlain("Nazione");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::select("nazione_spedizione",$values['nazione_spedizione'],$selectNazioniSpedizione,"uk-select class_nazione_spedizione","nazione_spedizione","yes","aria-required='true'");?>
			</div>
		</div>
		<?php } else { ?>
			<?php echo Html_Form::hidden("nazione_spedizione",$values['nazione_spedizione']);?>
		<?php } ?>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<div class="uk-form-controls select_id_provincia_spedizione">
				<div class="box_select_provincia_spedizione">
					<label for="provincia_spedizione" class="uk-form-label"><?php echo gtextPlain("Provincia");?> <span aria-hidden="true">*</span></label>
					<?php echo Html_Form::select("provincia_spedizione",$values['provincia_spedizione'],$provinceSpedizione,"uk-select class_provincia_spedizione","provincia_spedizione","yes","aria-required='true'");?>
				</div>
				<div class="box_select_dprovincia_spedizione">
					<label for="dprovincia_spedizione" class="uk-form-label"><?php echo gtextPlain("Provincia");?> <span aria-hidden="true">*</span></label>
					<?php echo Html_Form::input("dprovincia_spedizione",$values['dprovincia_spedizione'],"uk-input class_dprovincia_spedizione","dprovincia_spedizione","placeholder='".gtextAttr("Provincia", false)."' aria-required='true'");?>
				</div>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label for="citta_spedizione" class="uk-form-label"><?php echo gtextPlain("Città");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("citta_spedizione",$values['citta_spedizione'],"uk-input class_citta_spedizione","citta_spedizione","placeholder='".gtextAttr("Città", false)."' aria-required='true'");?>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label for="telefono_spedizione" class="uk-form-label"><?php echo gtextPlain("Telefono");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("telefono_spedizione",$values['telefono_spedizione'],"uk-input class_telefono_spedizione","telefono_spedizione","placeholder='".gtextAttr("Telefono", false)."' aria-required='true'");?>
			</div>
		</div>
		
		<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione")) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label for="destinatario_spedizione" class="uk-form-label"><?php echo gtextPlain("Destinatario");?> </label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("destinatario_spedizione",$values['destinatario_spedizione'],"uk-input class_destinatario_spedizione","destinatario_spedizione","placeholder='".gtextAttr("Destinatario", false)."'");?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
