<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (isset($corr))
	$p = $corr;

$idPr = getPrincipale(field($p, "id_page"));

$hasCombinations = hasCombinations($idPr);
?>
<li class="product type-product post-1064 status-publish first instock product_cat-all product_cat-stools has-post-thumbnail shipping-taxable purchasable product-type-simple">
	<?php $urlAlias = getUrlAlias($p["pages"]["id_page"]); ?>
	<div class="product-block">
		<div class="product-transition">
			<div class="product-image">
				<img width="300" height="300" src="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" class="attachment-shop_catalog size-shop_catalog" alt="<?php echo encodeUrl(field($p, "title"));?>" />
			</div>
			<div class="product-caption">
				<div class="shop-action blocco_wishlist">
					<div class="yith-wcwl-add-to-wishlist add-to-wishlist-1192">
						<div class="not_in_wishlist  yith-wcwl-add-button show" style="display:<?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>none<?php } ?>;">
							<a title='<?php echo gtext("Aggiungi alla lista dei desideri", false);?>' href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>" rel="nofollow" data-product-id="1192" data-product-type="simple" class=" azione_wishlist"></a>
							<img src="<?php echo $this->baseUrlSrc."/Public/Tema/"?>/plugins/yith-woocommerce-wishlist/assets/images/wpspin_light.gif" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden">
						</div>
						<div class="in_wishlist yith-wcwl-wishlistaddedbrowse hide" style="display:<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>none<?php } ?>;">
							<span class="feedback"><?php echo gtext("Articolo aggiunto!");?></span>
							<a class=" azione_wishlist" title='<?php echo gtext("Elimina dalla lista dei desideri", false);?>' href="<?php echo $this->baseUrl."/wishlist/elimina/".$p["pages"]["id_page"];?>" rel="nofollow"></a>
							<img src="<?php echo $this->baseUrlSrc."/Public/Tema/"?>/plugins/yith-woocommerce-wishlist/assets/images/wpspin_light.gif" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden">
						</div>
						<div style="clear:both"></div>
						<div class="yith-wcwl-wishlistaddresponse"></div>
					</div>
				</div>
			</div>
			<?php if (isProdotto($idPr) && acquistabile($idPr)) { ?>
			<a img-thumb="<?php echo $this->baseUrlSrc."/thumb/tooltip/".$p["pages"]["immagine"];?>" href="<?php echo $this->baseUrl."/".$urlAlias;?>" rel="<?php echo $idPr;?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart <?php if (!$hasCombinations) { ?>aggiungi_al_carrello_semplice<?php } ?>" aria-label="<?php echo encodeUrl(field($p, "title"));?>" rel="nofollow">
				<?php if (!$hasCombinations) {
					$stringaDa = "";
				?>
				<?php echo gtext("Aggiung al carrello", false);?>
				<?php } else {
					$stringaDa = gtext("da");
				?>
				<?php echo gtext("Acquista", false);?>
				<?php } ?>
			</a>
			<?php } ?>
			<a href="<?php echo $this->baseUrl."/".$urlAlias;?>" class=""></a>
		</div>
		<div class="caption">
			<h3 class="woocommerce-loop-product__title">
				<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo field($p, "title");?></a>
			</h3>
			<?php if (isProdotto($idPr)) { ?>
			<span class="price">
				<span class="woocommerce-Price-amount amount">
					<?php if (inPromozioneTot($idPr,$p)) { echo "<del>$stringaDa € ".setPriceReverse(calcolaPrezzoIvato($p["pages"]["id_page"], $p["pages"]["price"]))."</del> € ".prezzoPromozione($p); } else { echo "$stringaDa € ".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $p["pages"]["price"]));}?>
					<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
					<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
					<?php } ?>
				</span>
			</span>
			<?php } ?>
		</div>
	</div>
</li>

