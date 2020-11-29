<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <?php echo gtext("Condizioni di privacy");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Gestione privacy");?></h1>
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
								$attiva = "privacy";
								include(tp()."/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<?php echo $noticecookies;?>
									<?php if (isset($_COOKIE["ok_cookie"])) { ?>
									<h2><?php echo gtext("Cookie");?></h2>
									<div class="blocco_coupon">
										<?php echo testo("Cookies");?><br />
										<a style="font-weight:bold;" href="<?php echo $this->baseUrl."/cookies.html"?>"><?php echo gtext("Vedi l'informativa sui cookie");?></a>
										<br /><br />
										<a style="font-weight:bold;" href="<?php echo $this->baseUrl."/riservata/privacy?cancella_cookies"?>"><i class="fa fa-trash"></i> <?php echo gtext("Revoca l'approvazione all'utilizzo dei cookies");?></a>
									</div>
									<br /><br />
									<?php } ?>
									
									
									<h2><?php echo gtext("Cancella account");?></h2>
									<?php echo $notice;?>
									<div class="blocco_coupon">
										<?php echo testo("Per cancellare l'account è necessario inserire la password e confermare tramite il form sottostante.");?>
										<form class="checkout woocommerce-checkout " action="<?php echo $this->baseUrl."/riservata/privacy";?>" method="POST">
											<div class="woocommerce-billing-fields">
												<?php echo Html_Form::input("password","","class_password",null,"placeholder='".gtext("Inserisci la password", false)."'");?>
												<input type="submit" class="button" style="margin-top:10px" name="cancella" value="<?php echo gtext("Cancella account", false);?>">
											</div>
										</form>
									</div>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
