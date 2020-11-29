<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<table class="table_dati_spedizione">
	<tr>
		<td class="first_column"><?php echo gtext("Indirizzo");?> *</td>
		<td><?php echo Html_Form::input("indirizzo_spedizione",$values['indirizzo_spedizione'],"text_input class_indirizzo_spedizione");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Cap");?> <span class="nascondi_fuori_italia_inline_spedizione">*</span></td>
		<td><?php echo Html_Form::input("cap_spedizione",$values['cap_spedizione'],"text_input class_cap_spedizione");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Nazione");?> *</td>
		<td><?php echo Html_Form::select("nazione_spedizione",$values['nazione_spedizione'],$selectNazioniSpedizione,"text_input class_nazione_spedizione",null,"yes");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Provincia");?> *</td>
		<td>
			<?php echo Html_Form::select("provincia_spedizione",$values['provincia_spedizione'],$province,"text_input class_provincia_spedizione",null,"yes");?>
			
			<?php echo Html_Form::input("dprovincia_spedizione",$values['dprovincia_spedizione'],"text_input class_dprovincia_spedizione");?>
		</td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Città");?> *</td>
		<td><?php echo Html_Form::input("citta_spedizione",$values['citta_spedizione'],"text_input class_citta_spedizione");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Telefono");?> *</td>
		<td><?php echo Html_Form::input("telefono_spedizione",$values['telefono_spedizione'],"text_input class_telefono_spedizione");?></td>
	</tr>
</table>
