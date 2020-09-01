<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<?php if (isset($_SESSION['result'])) { ?>
						<?php if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0) { ?>
							<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/regusers/login";?>">Accedi</a> » <a href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Richiesta nuova password");?></a> » <?php echo gtext("Invio mail per cambio password");?></p>
						<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
							<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/regusers/login";?>">Accedi</a> » <a href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Richiesta nuova password");?></a> » <?php echo gtext("Password cambiata");?></p>
						<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0) { ?>
							<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo gtext("Account creato");?></p>
						<?php } ?>
					<?php } else { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo gtext("Notifiche");?></p>
					<?php } ?>
				</div>
				<?php if (isset($_SESSION['result'])) { ?>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title">
						<?php if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0) { ?>
							<?php echo gtext("Impostazione nuova password");?>
						<?php } else if (strcmp($_SESSION['result'],'error') === 0) { ?>
							<?php echo gtext("Errore");?>
						<?php } else if (strcmp($_SESSION['result'],'invalid_token') === 0) { ?>
							<?php echo gtext("Link scaduto");?>
						<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
							<?php echo gtext("Password cambiata");?>
						<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0) { ?>
							<?php echo gtext("Account creato");?>
						<?php } ?>
						
					</h1>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<article id="post-10" class="post-10 page type-page status-publish hentry">
						<div class="form-account-utente">
							<div class="">
								<?php if (isset($_SESSION['result'])) { ?>
									<?php if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0) { ?>
										<p><?php echo gtext("Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password");?>.</p>
										<?php if (!isset($_GET["eFromApp"])) { ?>
										<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
										<?php } ?>
									<?php } else if (strcmp($_SESSION['result'],'error') === 0) { ?>
										<p><?php echo gtext("Si è verificato un errore durante il processo, riprovi più tardi o contatti l'amministratore del sito");?>.</p>
										<?php if (!isset($_GET["eFromApp"])) { ?>
										<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
										<?php } ?>
									<?php } else if (strcmp($_SESSION['result'],'invalid_token') === 0) { ?>
										<p><br /><?php echo gtext("Il link è scaduto");?>.</p>
										<?php if (!isset($_GET["eFromApp"])) { ?>
										<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
										<?php } ?>
									<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
										<?php if (!isset($_GET["eFromApp"])) { ?>
										<p><?php echo gtext("La password è stata correttamente impostata");?>.</p>
										<p><?php echo gtext("Vai al");?> <a href="<?php echo $this->baseUrl."/regusers/login";?>">login</a></p>
										<?php } else { ?>
										<br />
										<p><?php echo gtext("La password è stata correttamente impostata");?>.</p>
										<p><?php echo gtext("Può continuare gli acquisti tramite la APP Edilivery utilizzando la password che ha appena impostato.");?></p>
										<p><?php echo gtext("Cordiali saluti<br />Edilivery");?></p>
										<?php } ?>
									<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0) { ?>
										<p><?php echo gtext("L'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d'accesso che ha scelto");?>.</p>
										<?php if (!isset($_GET["eFromApp"])) { ?>
										<p><?php echo gtext("Vai all'");?> <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo strtolower(gtext("Area riservata"));?></a></p>
										<?php } ?>
									<?php } ?>
								<?php } else { ?>
									<?php if (!isset($_GET["eFromApp"])) { ?>
									<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
									<?php } ?>
								<?php } ?>
							</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->

<?php if ( isset($_SESSION['result']) ) unset($_SESSION['result']); ?>
