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
				<?php include(tpf("/Elementi/Riservata/Link/main.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/biblioteca.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/documenti_riservati.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/ordini.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/agente.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/liste.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/feedback.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/account.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/spedizione.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/avatar.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/password.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/privacy.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/ticket.php"));?>
				<?php include(tpf("/Elementi/Riservata/Link/logout.php"));?>
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
