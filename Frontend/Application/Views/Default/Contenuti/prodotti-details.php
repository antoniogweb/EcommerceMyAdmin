<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
  <div id="page-title-bar" class="page-title-bar">
	<div class="container">
<!-- 		<div class="wrap w-100 d-flex align-items-center text-center"> -->
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>
				</div>
			</div>
<!-- 		</div> -->
	</div>
</div>
<?php foreach ($pages as $p) {
	$urlAlias = getUrlAlias($p["pages"]["id_page"]);
	$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
?>
<div id="page-title-bar" class="page-title-bar">
</div>
<div class="site-content-contain">
   <div id="content" class="site-content">
      <div class="wrap">
         <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
				<div class="woocommerce-notices-wrapper">
					<div class="woocommerce-message hidden-notice" role="alert">
						<a href="<?php echo $this->baseUrl."/carrello/vedi";?>" tabindex="1" class="button wc-forward">Vai al carrello</a> <?php echo field($p, "title");?> è stato aggiunto al carrello.
					</div>
				</div>
				<div id="product-1022" class="product type-product post-1022 status-publish first instock product_cat-uncategorized has-post-thumbnail shipping-taxable purchasable product-type-simple">
					<?php /*echo $fasce;*/?>
					<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-6 images" data-columns="6" style="opacity: 0; transition: opacity .25s ease-in-out;">
						<?php include(tp()."/Contenuti/Elementi/Pagine/slide_prodotto.php");?>
					</div>
					<div class="summary entry-summary">
						<div class="inner">
							<?php include(tp()."/Contenuti/Elementi/Pagine/carrello_prodotto.php");?>
						</div>
					</div>
					<div class="woocommerce-tabs wc-tabs-wrapper">
						<ul class="tabs wc-tabs" role="tablist">
							<li class="description_tab" id="tab-title-description" role="tab" aria-controls="tab-description">
							<a href="#tab-description"><?php echo gtext("Descrizione"); ?></a>
							</li>
							<?php if (count($lista_caratteristiche) > 0) { ?>
							<li class="additional_information_tab" id="tab-title-additional_information" role="tab" aria-controls="tab-additional_information">
							<a href="#tab-additional_information">Informazioni aggiuntive</a>
							</li>
							<?php } ?>
						</ul>
						<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
							<h2><?php echo gtext("Descrizione"); ?></h2>
							<?php echo htmlentitydecode(attivaModuli($p["pages"]["description"]));?>
						</div>
						<?php if (count($lista_caratteristiche) > 0) { ?>
						<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information">
							<h2>Informazioni aggiuntive</h2>
							<table class="woocommerce-product-attributes shop_attributes">
								<?php foreach ($lista_caratteristiche as $caratt) { ?>
								<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_handle-height-ground-to-handle">
									<th class="woocommerce-product-attributes-item__label"><?php echo $caratt["caratteristiche"]["titolo"];?></th>
									<td class="woocommerce-product-attributes-item__value">
										<p><?php echo $caratt["caratteristiche_valori"]["titolo"];?></p>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php } ?>
					</div>
					<?php if (count($prodotti_correlati) > 0) { ?>
					<div class="columns-4">
						<section class="related products">
							<h2><?php echo gtext("Prodotti correlati"); ?></h2>
							<ul class="products columns-4">
								<?php foreach ($prodotti_correlati as $corr) {
									include(tp()."/Contenuti/Elementi/Categorie/prodotto.php");
								?>
								<?php } ?>
							</ul>
						</section>
					</div>
					<?php } ?>
				</div>
            </main>
            <!-- #main -->
         </div>
         <!-- #primary -->
      </div>
   </div>
   <!-- #content -->
</div>
<!-- .site-content-contain -->
<?php } ?>
