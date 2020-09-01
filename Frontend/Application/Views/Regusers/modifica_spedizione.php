<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<?php if ($id === 0) { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>"><?php echo gtext("Indirizzi di spedizione");?></a> » <?php echo gtext("Aggiungi un indirizzo di spedizione");?></p>
					<?php } else { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>"><?php echo gtext("Indirizzi di spedizione");?></a> » <?php echo gtext("Modifica l'indirizzo di spedizione");?></p>
					<?php } ?>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<?php if ($id === 0) { ?>
					<h1 class="page-title"><?php echo gtext("Aggiungi un indirizzo di spedizione");?></h1>
					<?php } else { ?>
					<h1 class="page-title"><?php echo gtext("Modifica l'indirizzo di spedizione");?></h1>
					<?php } ?>
				</div>
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
							<div class="woocommerce">
								<?php
								$attiva = "indirizzi";
								include(ROOT."/Application/Views/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<?php echo $notice; ?>
									<form action="<?php echo $this->baseUrl.$action;?>" method="POST">
										
										<?php include(ROOT."/Application/Views/Regusers/form_dati_spedizione.php");?>

										<br />
										<?php if ($id === 0) { ?>
										<p><input class="button" type="submit" name="insertAction" value="<?php echo gtext("Salva", false);?>" /></p>
										<?php } else { ?>
										<p><input class="button" type="submit" name="updateAction" value="<?php echo gtext("Salva", false);?>" /></p>
										<?php } ?>
										
									</form>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
