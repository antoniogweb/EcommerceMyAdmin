<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
  <div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home")?></a> » <?php echo gtext("Wishlist")?></p>
				</div>
				<div class="page-header mb-2 w-100 order-first">
					<h2 class="page-title"><?php echo gtext("La tua lista dei desideri")?></h2>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<div class="columns-4 in-pagina-wishlist">
						<?php if (count($pages) > 0) { ?>
							<ul class="products columns-4">
								<?php include(ROOT."/Application/Views/Contenuti/Elementi/Categorie/prodotti.php");?>
							</ul>
						<?php } else { ?>
							<p><?php echo gtext("Non ci sono prodotti nella lista dei desideri")?></p>
							<a style="text-align:center;" class="checkout-button button alt wc-forward torna_al_negozio" href="<?php echo $this->baseUrl;?>"><?php echo gtext("Torna al negozio")?></a>
						<?php } ?>
					</div>
				</main>
				<!-- #main -->
			</div>
			
		</div>
	</div>
	<!-- #content -->
</div>
