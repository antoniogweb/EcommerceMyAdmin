<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="table_dati_spedizione">
	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<div class="first_of_grid uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Indirizzo");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("indirizzo_spedizione",$values['indirizzo_spedizione'],"uk-input class_indirizzo_spedizione",null,"placeholder='".gtext("Indirizzo", false)."'");?>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Cap");?> <span class="nascondi_fuori_italia_inline_spedizione">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("cap_spedizione",$values['cap_spedizione'],"uk-input class_cap_spedizione",null,"placeholder='".gtext("Cap", false)."'");?>
			</div>
		</div>
		
		<?php if (count($selectNazioniSpedizione) > 2) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Nazione");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::select("nazione_spedizione",$values['nazione_spedizione'],$selectNazioniSpedizione,"uk-select class_nazione_spedizione",null,"yes");?>
			</div>
		</div>
		<?php } else { ?>
			<?php echo Html_Form::hidden("nazione_spedizione",$values['nazione_spedizione']);?>
		<?php } ?>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Provincia");?> *</label>
			<div class="uk-form-controls">
				<div class="select_id_provincia_spedizione">
					<?php echo Html_Form::select("provincia_spedizione",$values['provincia_spedizione'],$province,"uk-select class_provincia_spedizione",null,"yes");?>
				</div>
				<?php echo Html_Form::input("dprovincia_spedizione",$values['dprovincia_spedizione'],"uk-input class_dprovincia_spedizione",null,"placeholder='".gtext("Provincia", false)."'");?>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Città");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("citta_spedizione",$values['citta_spedizione'],"uk-input class_citta_spedizione",null,"placeholder='".gtext("Città", false)."'");?>
			</div>
		</div>
		
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Telefono");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("telefono_spedizione",$values['telefono_spedizione'],"uk-input class_telefono_spedizione",null,"placeholder='".gtext("Telefono", false)."'");?>
			</div>
		</div>
		
		<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione")) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Destinatario");?> </label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("destinatario_spedizione",$values['destinatario_spedizione'],"uk-input class_destinatario_spedizione",null,"placeholder='".gtext("Destinatario", false)."'");?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
