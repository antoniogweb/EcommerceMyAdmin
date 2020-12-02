<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <?php echo gtext("Area riservata");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Area riservata");?></h1>
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
						<div class="entry-content">
							<div class="woocommerce">
								<?php
								$attiva = "dashboard";
								include(tp()."/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<div class="woocommerce-notices-wrapper"></div>
										<p><?php echo gtext("Ciao")?> <strong><?php echo $nomeCliente;?></strong> (<?php echo gtext("non sei")?> <strong><?php echo $nomeCliente;?></strong>? <a href="<?php echo $this->baseUrl."/esci";?>"><?php echo gtext("Esci")?></a>)</p>

										<p><?php echo gtext("Dalla tua area riservata puoi vedere gli")?> <a href="<?php echo $this->baseUrl."/ordini-effettuati"?>"><?php echo gtext("ordini effettuati")?></a>, <?php echo gtext("gestire i tuoi")?>  <a href="<?php echo $this->baseUrl."/modifica-account"?>"><?php echo gtext("dati di fatturazione");?></a> <?php echo gtext("e i tuoi");?> <a href="<?php echo $this->baseUrl."/riservata/indirizzi"?>"><?php echo gtext("dati di spedizione");?></a>.</p>

									</div>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->


