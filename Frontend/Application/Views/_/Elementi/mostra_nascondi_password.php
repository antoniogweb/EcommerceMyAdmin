<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a class="uk-form-icon uk-form-icon-flip mostra_nascondi_password mostra_password" href="#"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/eye-slash.svg");?></span></a>
<a class="uk-form-icon uk-form-icon-flip mostra_nascondi_password nascondi_password uk-hidden" href="#"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/eye.svg");?></span></a>

<?php if (v("attiva_controllo_robustezza_password")) { ?>
<div class="box_avvisi_password uk-hidden uk-position-absolute uk-position-bottom-out uk-background-muted uk-width-1-1" style="z-index:3;">
	<div class="uk-padding-small uk-text-small uk-text-danger">
		<div class="avviso_numero_caratteri">
			<li><?php echo gtext("La password deve essere lunga almeno 8 caratteri");?></li>
		</div>
		<div class="avviso_caratteri_minuscoli">
			<li><?php echo gtext("La password deve contenere almeno un carattere minuscolo [a - z]");?></li>
		</div>
		<div class="avviso_caratteri_maiuscoli">
			<li><?php echo gtext("La password deve contenere almeno un carattere maiuscolo [A - Z]");?></li>
		</div>
		<div class="avviso_caratteri_numerici">
			<li><?php echo gtext("La password deve contenere almeno un carattere numerico [0 - 9]");?></li>
		</div>
		<div class="avviso_caratteri_speciali">
			<li><?php echo gtext("La password deve contenere almeno un carattere speciale tra i seguenti:");?> <?php echo v("password_regular_expression_caratteri_speciali");?></li>
		</div>
	</div>
</div>
<?php } ?>
