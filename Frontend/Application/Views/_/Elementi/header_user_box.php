<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($ukdropdown))
	$ukdropdown = "pos: bottom-right; offset: -10; delay-hide: 200;";
?>
<div class="uk-padding-small uk-margin-remove uk-dropdown" uk-dropdown="<?php echo $ukdropdown;?>" style="min-width: 250px;">
	<?php if ($islogged) { ?>
	<ul class="uk-nav uk-dropdown-nav">
		<li>
			<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
		</li>
		<li>
			<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
		</li>
		<?php if (v("attiva_agenti") && User::$isAgente) { ?>
		<li>
			<a href="<?php echo $this->baseUrl."/promozioni/elenco/";?>" title="<?php echo gtext("Codici coupon", false);?>"><?php echo gtext("Codici coupon");?></a>
		</li>
		<li>
			<a href="<?php echo $this->baseUrl."/ordini-collegati/";?>" title="<?php echo gtext("Ordini collegati", false);?>"><?php echo gtext("Ordini collegati");?></a>
		</li>
		<?php } ?>
		<?php if (v("attiva_liste_regalo")) { ?>
		<li>
			<a href="<?php echo $this->baseUrl."/liste-regalo/";?>" title="<?php echo gtext("Liste nascita / regalo", false);?>"><?php echo gtext("Liste nascita / regalo");?></a>
		</li>
		<?php } ?>
		<?php
		if (v("abilita_feedback") && v("feedback_visualizza_in_area_riservata")) {
			$user_feedback = FeedbackModel::get(0,0);
			if (count($user_feedback) > 0) { ?>
			<li>
				<a href="<?php echo $this->baseUrl."/riservata/feedback";?>" title="<?php echo gtext("Le mie valutazioni", false);?>"><?php echo gtext("Le mie valutazioni");?></a>
			</li>
			<?php } ?>
		<?php } ?>
		<li>
			<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("I miei dati", false);?>"><?php echo gtext("I miei dati");?></a>
		</li>
		<?php if (v("attiva_spedizione_area_riservata")) { ?>
		<li>
			<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Spedizione", false);?>"><?php echo gtext("Spedizione");?></a>
		</li>
		<?php } ?>
		<?php if (v("attiva_gestione_immagine_utente")) { ?>
		<li>
			<a href="<?php echo $this->baseUrl."/immagine-profilo";?>" title="<?php echo gtext("Immagine profilo", false);?>"><?php echo gtext("Immagine profilo");?></a>
		</li>
		<?php } ?>
		<li>
			<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
		</li>
		<li>
			<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
		</li>
		<li class="uk-nav-divider"></li>
		<li>
			<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
		</li>
	</ul>
	<?php } else { ?>
	<div class="uk-dropdown-nav">
		<div class="uk-text-small uk-text-right">
			<a class="uk-text-secondary uk-text-bold" href="<?php echo $this->baseUrl."/crea-account";?>"><?php echo gtext("Crea un account")?></a>
			<hr />
		</div>
		<form autocomplete="new-password" action="<?php echo $this->baseUrl."/regusers/login";?>" data-toggle="validator" method="POST">
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label"><?php echo gtext("e-mail")?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input " autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Indirizzo e-mail", false)?>" />
					</div>
				</div>
				<div class="uk-margin">
					<label class="uk-form-label"><?php echo gtext("password")?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input " autocomplete="new-password" name="password" type="password" placeholder="<?php echo gtext("Password", false)?>" />
					</div>
				</div>
				
				<input autocomplete="new-password" class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1" type="submit" name="" value="<?php echo gtext("Accedi");?>" />
			</fieldset>
		</form>
		<br />
		<a class="uk-text-small uk-text-secondary" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
	</div>
	<?php } ?>
</div>
