<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin uk-width-1-1">
	<div class="uk-grid uk-grid-collapse" uk-grid>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo OrdiniModel::getNominativo(User::$dettagli);?></span>
			<?php if (User::$dettagli["completo"]) { ?>
				<?php if (User::$dettagli["indirizzo"]) { ?>
				<br /><span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <?php echo User::$dettagli["indirizzo"];?>
				<?php } ?>
				<?php if (User::$dettagli["cap"] || User::$dettagli["citta"] || User::$dettagli["provincia"]) { ?>
				<br /><?php echo User::$dettagli["cap"];?>, <?php echo User::$dettagli["citta"];?> (<?php echo User::$dettagli["nazione"] == "IT" ? User::$dettagli["provincia"] : User::$dettagli["dprovincia"];?>)
				<?php } ?>
			<?php } ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione(User::$dettagli["nazione"]);?>
			<?php if (User::$dettagli["tipo_cliente"] != "azienda" && User::$dettagli["codice_fiscale"]) { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("C.F.");?>:</span>  <?php echo User::$dettagli["codice_fiscale"];?>
			<?php } ?>
			<?php if (User::$dettagli["tipo_cliente"] != "privato") { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("P.IVA");?>:</span>  <?php echo User::$dettagli["p_iva"];?>
			<?php } ?>
		</div>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<?php if (User::$dettagli["completo"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo User::$dettagli["telefono"];?><br />
			<?php } ?>
			<span class="uk-text-emphasis"><?php echo gtext("Email");?>:</span> <?php echo User::$dettagli["username"];?><br />
			<?php if (User::$dettagli["pec"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Pec");?>:</span>  <?php echo User::$dettagli["pec"];?><br />
			<?php } ?>
			<?php if (User::$dettagli["codice_destinatario"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Codice destinatario");?>:</span>  <?php echo User::$dettagli["codice_destinatario"];?><br />
			<?php } ?>
			<?php if (User::$dettagli["completo"] && User::$dettagli["tipo_cliente"] == "privato") { ?>
			<?php echo User::$dettagli["fattura"] ? gtext("Voglio ricevere la fattura") : gtext("Voglio ricevere lo scontrino fiscale");?>
			<?php } ?>
			<?php if (User::$dettagli["completo"] && v("permetti_modifica_account")) { ?>
			<div class="uk-margin-small-top"><a href="<?php echo $this->baseUrl."/".Url::routeToUrl("modifica-account")."?redirect=checkout"?>" class="<?php echo $classePulsanteModificaDati;?>"><span class="uk-margin-small-right" uk-icon="icon: pencil"></span><?php echo gtext("Modifica dati")?></a></div>
			<?php } ?>
		</div>
	</div>
</div>

