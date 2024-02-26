<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<ul class="uk-nav uk-dropdown-nav">
	<li>
		<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
	</li>
	<?php if (v("attiva_biblioteca_documenti")) { ?>
	<li>
		<a href="<?php echo $this->baseUrl."/biblioteca-documenti/";?>" title="<?php echo gtext("Biblioteca documenti", false);?>"><?php echo gtext("Biblioteca documenti");?></a>
	</li>
	<?php } ?>
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
	<?php if (v("attiva_gestiobe_ticket")) { ?>
	<li>
		<a href="<?php echo $this->baseUrl."/ticket/";?>" title="<?php echo gtext("Assistenza", false);?>"><?php echo gtext("Assistenza");?></a>
	</li>
	<?php } ?>
	<li class="uk-nav-divider"></li>
	<li>
		<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
	</li>
</ul>
