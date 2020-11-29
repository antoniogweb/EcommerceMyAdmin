<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="tipo_cliente class_tipo_cliente">
<?php
$tipoCliente = array(
	"<span style='margin-left:8px;'></span>".gtext("Privato")."<span style='margin-right:20px;'></span>"=>"privato",
	"<span style='margin-left:8px;'></span>".gtext("Azienda")."<span style='margin-right:20px;'></span>"=>"azienda",
	"<span style='margin-left:8px;'></span>".gtext("Libero professionista")=>"libero_professionista",
);

echo Html_Form::radio("tipo_cliente",$values['tipo_cliente'],$tipoCliente,"radio_cliente");?>
</div>
			
<table class="table table_dati_cliente">
	<tr class="tr_ragione_sociale">
		<td class="first_column"><?php echo gtext("Ragione sociale");?> *</td>
		<td><?php echo Html_Form::input("ragione_sociale",$values['ragione_sociale'],"text_input class_ragione_sociale");?></td>
	</tr>
	<tr class="tr_nome">
		<td class="first_column"><?php echo gtext("Nome");?> *</td>
		<td><?php echo Html_Form::input("nome",$values['nome'],"text_input class_nome");?></td>
	</tr>
	<tr class="tr_cognome">
		<td class="first_column"><?php echo gtext("Cognome");?> *</td>
		<td><?php echo Html_Form::input("cognome",$values['cognome'],"text_input class_cognome");?></td>
	</tr>
	
	<tr class="nascondi_fuori_italia">
		<td class="first_column"><?php echo gtext("Codice fiscale");?> *</td>
		<td><?php echo Html_Form::input("codice_fiscale",$values['codice_fiscale'],"text_input class_codice_fiscale");?></td>
	</tr>
	<tr class="tr_p_iva box_p_iva">
		<td class="first_column"><?php echo gtext("Partita iva");?> *</td>
		<td><?php echo Html_Form::input("p_iva",$values['p_iva'],"text_input class_p_iva");?></td>
	</tr>
	
	
	<tr>
		<td class="first_column"><?php echo gtext("Nazione");?> *</td>
		<td><?php echo Html_Form::select("nazione",$values['nazione'],$selectNazioni,"text_input class_nazione",null,"yes");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Provincia");?> *</td>
		<td>
			<?php echo Html_Form::select("provincia",$values['provincia'],$province,"text_input class_provincia",null,"yes");?>
			<?php echo Html_Form::input("dprovincia",$values['dprovincia'],"text_input class_dprovincia");?>
		</td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Città");?> *</td>
		<td><?php echo Html_Form::input("citta",$values['citta'],"text_input class_citta");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Indirizzo");?> *</td>
		<td><?php echo Html_Form::input("indirizzo",$values['indirizzo'],"text_input class_indirizzo");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Cap");?> <span class="nascondi_fuori_italia_inline">*</span></td>
		<td><?php echo Html_Form::input("cap",$values['cap'],"text_input class_cap");?></td>
	</tr>
	
	<tr>
		<td class="first_column"><?php echo gtext("Telefono");?> *</td>
		<td><?php echo Html_Form::input("telefono",$values['telefono'],"text_input class_telefono");?></td>
	</tr>
	<tr class="t">
		<td class="first_column">TESSERA</td>
		<td><?php echo Html_Form::input("tessera","","text_input");?></td>
	</tr>

<!-- registrazione o modificadati -->
<?php if (strcmp($this->controller,"regusers") === 0) { ?>

	<tr>
		<td class="first_column"><?php echo gtext("Email");?> *</td>
		<td><?php echo Html_Form::input("username",$values['username'],"text_input class_username");?></td>
	</tr>
	<?php if (strcmp($this->action,"add") === 0 or strcmp($this->controller,"ordini") === 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Conferma email");?> *</td>
		<td><?php echo Html_Form::input("conferma_username",$values['conferma_username'],"text_input class_conferma_username");?></td>
	</tr>
	
	<?php } ?>
	<?php if (strcmp($this->action,"add") === 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Password");?> *</td>
		<td><?php echo Html_Form::password("password",$values['password'],"text_input class_password");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Conferma password");?> *</td>
		<td><?php echo Html_Form::password("confirmation",$values['confirmation'],"text_input class_confirmation");?></td>
	</tr>
	<?php } ?>
</table>

<!-- checkout ordine -->
<?php } else if (strcmp($this->controller,"ordini") === 0) { ?>

	<tr>
		<td class="first_column"><?php echo gtext("Email");?> *</td>
		<td><?php echo Html_Form::input("email",$values['email'],"text_input class_email");?></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Conferma email");?> *</td>
		<td><?php echo Html_Form::input("conferma_email",$values['conferma_email'],"text_input class_conferma_email");?></td>
	</tr>
</table>
	
	<?php if (!$islogged) { ?>
	<div class="utente_registrato class_registrato">
		<div><?php echo Html_Form::radio("registrato",$values["registrato"],"N","radio_registrato","none");?> <?php echo gtext("Continua come utente ospite");?></div>
		<div style="margin-top:10px;"><?php echo Html_Form::radio("registrato",$values["registrato"],"Y","radio_registrato","none");?> <?php echo gtext("Crea account");?></div>
	<?php
	/*$registrato = array(
		gtext("Continua come utente ospite")=>"N",
		gtext("Crea account")=>"Y",
	);
	
	echo Html_Form::radio("registrato",$values['registrato'],$registrato,"radio_registrato");*/?>
	</div>
	
	<table class="table table_password">
		<tr>
			<td class="first_column"><?php echo gtext("Password");?> *</td>
			<td><?php echo Html_Form::password("password",$regusers_values['password'],"text_input class_password");?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Conferma password");?> *</td>
			<td><?php echo Html_Form::password("confirmation",$regusers_values['confirmation'],"text_input class_confirmation");?></td>
		</tr>
	</table>
	
	<?php } ?>

<?php } ?>

<div class="blocco_fatturazione_elettronica">
	<h3 style="margin:20px 0;"><b><?php echo gtext("Dati per la fatturazione elettronica",false)?></b></h3>
	
	<?php echo testo("testo_fatt_elettronica")?>

	<table class="table table_dati_cliente">
		<tr>
			<td class="first_column"><?php echo gtext("Pec");?></td>
			<td><?php echo Html_Form::input("pec",$values['pec'],"text_input class_pec");?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Codice destinatario");?></td>
			<td><?php echo Html_Form::input("codice_destinatario",$values['codice_destinatario'],"text_input class_codice_destinatario");?></td>
		</tr>
	</table>
</div>
