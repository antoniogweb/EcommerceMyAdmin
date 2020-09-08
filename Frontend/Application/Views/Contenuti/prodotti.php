<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
  <div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>
				</div>
				<div class="page-header mb-2 w-100 order-first">
					<h2 class="page-title"><?php echo cfield($datiCategoria, "title");?></h2>
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
					<header class="woocommerce-products-header"></header>
					
					<?php if ($fasce) { ?>
						<?php echo $fasce;?>
					<?php } else { ?>
						<?php include(ROOT."/Application/Views/Contenuti/Elementi/Categorie/blocco_prodotti.php");?>
					<?php } ?>
				</main>
				<!-- #main -->
			</div>
			<!-- #primary -->
			<aside id="secondary" class="widget-area" role="complementary">
				<div class="inner">
					<section id="woocommerce_product_categories-2" class="widget woocommerce widget_product_categories">
						<h2 class="widget-title"><?php echo gtext("Famiglia")?></h2>
						<ul class="product-categories">
							<li class="cat-item cat-item-49 <?php if ($idMarchio == 0) { ?>current-cat<?php } ?>">
								<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($datiCategoria["categories"]["id_c"]);?>"><?php echo gtext("Tutti");?></a>
							</li>
							<?php foreach ($elencoMarchi as $idM => $titolo) { ?>
							<li class="cat-item <?php if ($idM == $idMarchio) { ?>current-cat<?php } ?>">
								<a href="<?php echo $this->baseUrl."/".getMarchioUrlAlias($idM);?>">
									<?php echo $titolo;?>
								</a>
							</li>
							<?php } ?>
						</ul>
						<br /><br />
						<h2 class="widget-title"><?php echo gtext("Categoria")?></h2>
						<ul class="product-categories">
							<li class="cat-item cat-item-49 <?php if ($datiCategoria["categories"]["id_c"] == $idShop) { ?>current-cat<?php } ?>">
								<a href="<?php echo $this->baseUrl."/".$categoriaShop["alias"].".html";?>"><?php echo gtext("Tutti");?></a>
							</li>
							<?php foreach ($alberoCategorieProdotti as $c) {
								$cat = fullcategory($c["id_c"]);
							?>
							<li class="cat-item <?php if ($datiCategoria["categories"]["id_c"] == $cat["categories"]["id_c"]) { ?>current-cat<?php } ?> <?php if (isset($c["figli"])) { ?>cat-parent closed<?php } ?>">
								<a href="<?php echo $this->baseUrl."/".$aliasMarchioCorrente.getCategoryUrlAlias($cat["categories"]["id_c"]);?>">
									<?php echo cfield($cat, "title");?>
								</a>
								<?php if (isset($c["figli"])) { ?>
								<ul class='children'>
									<?php foreach ($c["figli"] as $fg) {
										$fgCat = fullcategory($fg["id_c"]);
									?>
									<li class="cat-child cat-item cat-item-54 <?php if ($datiCategoria["categories"]["id_c"] == $fgCat["categories"]["id_c"]) { ?>current-cat<?php } ?>">
										<a href="<?php echo $this->baseUrl."/".$aliasMarchioCorrente.getCategoryUrlAlias($fgCat["categories"]["id_c"]);?>">
											<?php echo cfield($fgCat, "title");?>
										</a>
									</li>
									<?php } ?>
								</ul>
								<i class="open_close_sub closed fa fa-chevron-down"></i>
								<?php } ?>
							</li>
							<?php } ?>
						</ul>
						
						<div style="margin-top:30px;"><?php echo testo("testo_spedizione_shop")?></div>
					</section>
				</div>
			</aside>
			<!-- #secondary -->
		</div>
	</div>
	<!-- #content -->
</div>
