<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (User::$isMobile) { ?>
<div class="uk-offcanvas-bar uk-padding-remove">
	<div class="uk-card uk-card-default uk-card-small uk-height-1-1">
		<div class="uk-card-header">
			<?php echo gtext("Menù area riservata");?>
			<button style="margin-top:-7px;" class="uk-offcanvas-close" type="button" uk-close></button>
		</div>
		<div class="uk-card-body">
<?php } ?>
	<nav>
		<ul class="uk-nav uk-nav-default uk-nav-parent-icon uk-list-divider" uk-nav>
			<?php if ($islogged) { ?>
			<li class="<?php if ($attiva == "dashboard") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
			</li>
			<?php if (v("attiva_biblioteca_documenti")) { ?>
			<li class="<?php if ($attiva == "documenti") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/biblioteca-documenti/";?>" title="<?php echo gtext("Biblioteca documenti", false);?>"><?php echo gtext("Biblioteca documenti");?></a>
			</li>
			<?php } ?>
			<li class="<?php if ($attiva == "ordini") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
			</li>
			<?php if (v("attiva_agenti") && User::$isAgente) { ?>
			<li class="<?php if ($attiva == "promozioni") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/promozioni/elenco/";?>" title="<?php echo gtext("Codici coupon", false);?>"><?php echo gtext("Codici coupon");?></a>
			</li>
			<li class="<?php if ($attiva == "ordinicollegati") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/ordini-collegati/";?>" title="<?php echo gtext("Ordini collegati", false);?>"><?php echo gtext("Ordini collegati");?></a>
			</li>
			<?php } ?>
			<?php if (v("attiva_liste_regalo")) { ?>
			<li class="<?php if ($attiva == "listeregalo") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/liste-regalo/";?>" title="<?php echo gtext("Liste nascita / regalo", false);?>"><?php echo gtext("Liste nascita / regalo");?></a>
			</li>
			<?php } ?>
			<?php
			if (v("abilita_feedback") && v("feedback_visualizza_in_area_riservata")) {
				$user_feedback = FeedbackModel::get(0,0);
				if (count($user_feedback) > 0) { ?>
				<li class="<?php if ($attiva == "feedback") { ?>uk-active<?php } ?>">
					<a href="<?php echo $this->baseUrl."/riservata/feedback";?>" title="<?php echo gtext("Le mie valutazioni", false);?>"><?php echo gtext("Le mie valutazioni");?></a>
				</li>
				<?php } ?>
			<?php } ?>
			<li class="<?php if ($attiva == "account") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("I miei dati", false);?>"><?php echo gtext("I miei dati");?></a>
			</li>
			<?php if (v("attiva_spedizione_area_riservata")) { ?>
			<li class="<?php if ($attiva == "indirizzi") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Spedizione", false);?>"><?php echo gtext("Spedizione");?></a>
			</li>
			<?php } ?>
			<?php if (v("attiva_gestione_immagine_utente")) { ?>
			<li class="<?php if ($attiva == "immagine") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/immagine-profilo";?>" title="<?php echo gtext("Immagine profilo", false);?>"><?php echo gtext("Immagine profilo");?></a>
			</li>
			<?php } ?>
			<li class="<?php if ($attiva == "password") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
			</li>
			<li class=" <?php if ($attiva == "privacy") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
			</li>
			<?php if (v("attiva_gestiobe_ticket")) { ?>
			<li class=" <?php if ($attiva == "ticket") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/ticket/";?>" title="<?php echo gtext("Assistenza", false);?>"><?php echo gtext("Assistenza");?></a>
			</li>
			<?php } ?>
			<li class="">
				<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
			</li>
			<?php } else { ?>
			<li class="uk-active">
				<a href="<?php echo $this->baseUrl."/crea-account";?>" title="<?php echo gtext("Inserisci dati fatturazione", false);?>"><?php echo gtext("Inserisci dati fatturazione");?></a>
			</li>
			<?php } ?>
		</ul>
	</nav>
<?php if (User::$isMobile) { ?>
		</div>
	</div>
</div>
<?php } ?>
