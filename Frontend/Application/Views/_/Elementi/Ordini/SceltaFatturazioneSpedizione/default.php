<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin">
	<?php echo Html_Form::radio("spedisci_dati_fatturazione",$values["spedisci_dati_fatturazione"],"Y","imposta_fatt","none");?> <?php echo gtext("Utilizza gli stessi dati per fatturazione e spedizione");?>
</div>
<div class="uk-margin">
	<?php echo Html_Form::radio("spedisci_dati_fatturazione",$values["spedisci_dati_fatturazione"],"N","imposta_fatt","none");?> <?php echo gtext("Utilizza dati diversi per la spedizione");?>
</div>

<div class="blocco_spedizione_non_loggato">
	
	
	<div class="blocco_checkout">
		<?php include(tpf("Regusers/form_dati_spedizione.php"));?>
	</div>
</div>
 
