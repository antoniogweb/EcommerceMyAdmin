<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<nav class="woocommerce-MyAccount-navigation">
	<ul>
		<?php if ($islogged) { ?>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard <?php if ($attiva == "dashboard") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders <?php if ($attiva == "ordini") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account <?php if ($attiva == "account") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("Modifica dati fatturazione", false);?>"><?php echo gtext("Modifica dati fatturazione");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address <?php if ($attiva == "indirizzi") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Indirizzi di spedizione", false);?>"><?php echo gtext("Indirizzi di spedizione");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account <?php if ($attiva == "password") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link <?php if ($attiva == "privacy") { ?>is-active<?php } ?>">
			<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
			<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
		</li>
		<?php } else { ?>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account is-active">
			<a href="<?php echo $this->baseUrl."/crea-account";?>" title="<?php echo gtext("Inserisci dati fatturazione", false);?>"><?php echo gtext("Inserisci dati fatturazione");?></a>
		</li>
		<?php } ?>
	</ul>
</nav>
