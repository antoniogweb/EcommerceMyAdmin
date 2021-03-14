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
		<ul class="uk-nav-default uk-nav-parent-icon uk-list-divider" uk-nav>
			<?php if ($islogged) { ?>
			<li class="<?php if ($attiva == "dashboard") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
			</li>
			<li class="<?php if ($attiva == "ordini") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
			</li>
			<li class="<?php if ($attiva == "account") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("Modifica dati fatturazione", false);?>"><?php echo gtext("Modifica dati fatturazione");?></a>
			</li>
			<li class="<?php if ($attiva == "indirizzi") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Indirizzi di spedizione", false);?>"><?php echo gtext("Indirizzi di spedizione");?></a>
			</li>
			<li class="<?php if ($attiva == "password") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
			</li>
			<li class=" <?php if ($attiva == "privacy") { ?>uk-active<?php } ?>">
				<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
			</li>
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
