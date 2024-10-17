<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a class="uk-form-icon uk-form-icon-flip mostra_nascondi_password mostra_password" href="#"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/eye-slash.svg");?></span></a>
<a class="uk-form-icon uk-form-icon-flip mostra_nascondi_password nascondi_password uk-hidden" href="#"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/eye.svg");?></span></a>

<?php if (v("attiva_controllo_robustezza_password")) { ?>
<div class="box_avvisi_password uk-hidden uk-position-absolute uk-position-bottom-out uk-background-muted uk-width-1-1" style="z-index:3;">
	<div class="uk-padding-small uk-text-small">
		<div class="uk-position-top-right uk-padding-small">
			<a title="<?php echo gtext("Chiudi avviso");?>" rel="nofollow" class="chiudi_wizard_password" href="#" uk-icon="icon: close"></a>
		</div>
		<?php echo gtext("La password deve contenere:");?>
		<span class="avviso_numero_caratteri uk-text-danger">
			<?php echo gtext("almeno 8 caratteri,");?>
		</span>
		<span class="avviso_caratteri_minuscoli uk-text-danger">
			<?php echo gtext("minuscole,");?>
		</span>
		<span class="avviso_caratteri_maiuscoli uk-text-danger">
			<?php echo gtext("maiuscole,");?>
		</span>
		<span class="avviso_caratteri_numerici uk-text-danger">
			<?php echo gtext("almeno un numero,");?>
		</span>
		<span class="avviso_caratteri_speciali uk-text-danger">
			<?php echo gtext("almeno un carattere speciale");?> (<?php echo v("password_regular_expression_caratteri_speciali");?>)
		</span>
	</div>
</div>
<?php } ?>
