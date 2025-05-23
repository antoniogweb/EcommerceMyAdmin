<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="card-cart-ajax uk-card uk-card-default uk-card-small uk-flex uk-flex-column" style="min-height:100%;">
	<header class="uk-card-header">
		<div class="uk-grid-small uk-flex-1" uk-grid>
			<div class="uk-width-expand ajax-cart-title">
				<?php echo gtext("Carrello");?>
			</div>
			<button style="margin-top:-7px;" class="uk-offcanvas-close" type="button" uk-close></button>
		</div>
	</header>
	<div class="uk-card-body uk-overflow-auto">
		<?php
		if (!isset($carrello))
			$carrello = CartModel::g()->getProdotti();
		
		if (count($carrello) > 0) { ?>
		<ul class="uk-list uk-list-divider">
			<?php foreach ($carrello as $p) { 
				$cartUrlAlias = getUrlAlias($p["cart"]["id_page"], $p["cart"]["id_c"]);
			?>
			<li class="uk-visible-toggle cart_item_row" rel="<?php echo $p["cart"]["id_cart"];?>">
				<article>
					<div class="uk-grid uk-grid-small" uk-grid>
						<div class="uk-width-1-4">
							<?php if ($p["cart"]["immagine"]) { ?>
							<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?><a class="" href="<?php echo $this->baseUrl."/".$cartUrlAlias;?>"><?php } ?>
								<figure class="tm-media-box-wrap">
									<img src="<?php echo $this->baseUrlSrc."/thumb/carrelloajax/".$p["cart"]["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>">
								</figure>
							<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?></a><?php } ?>
							<?php } ?>
						</div>
						<div class="uk-width-expand uk-margin-remove-top">
							<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?><a class="uk-text-small" href="<?php echo $this->baseUrl."/".$cartUrlAlias;?>"><?php } else { ?><span class="uk-link uk-text-small"><?php } ?>
								<?php echo field($p, "title");?>11
							<?php if (!$p["cart"]["id_p"] && $p["cart"]["prodotto_attivo"]) { ?></a><?php } else { ?></span><?php } ?>
							<?php if ($p["cart"]["attributi"]) { ?>
							<br />
							<?php echo $p["cart"]["attributi"];?>
							<?php } ?>
							<?php include(tpf("/Elementi/Carrello/ajax_cart_codice.php"));?>
							<div class="uk-grid uk-margin-xsmall uk-grid-small uk-flex-<?php echo v("attiva_prezzo_fisso") ? "bottom" : "middle";?>" uk-grid>
								<div class="uk-text-bolder uk-text-small">
									<?php if (v("attiva_prezzo_fisso") && $p["cart"]["prezzo_fisso"] > 0) { ?>
									<?php echo setPriceReverse(p($p["cart"],$p["cart"]["prezzo_fisso"]));?> € + <br />
									<?php } ?>
									<?php echo setPriceReverse($p["cart"]["quantity"] * p($p["cart"],$p["cart"]["price"]));?> €
								</div>
								<div class="uk-margin-remove-top uk-text-meta uk-text-xsmall"><?php echo $p["cart"]["quantity"];?> × <?php echo setPriceReverse(p($p["cart"],$p["cart"]["price"]));?> €</div>
							</div>
						</div>
						<div><a class="uk-icon-link uk-text-danger uk-invisible-hover cart_item_delete_link" href="#" uk-icon="icon: close; ratio: .75" uk-tooltip=""></a></div>
					</div>
				</article>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<p><?php echo gtext("Il carrello è vuoto")?></p>
		<?php } ?>
		<div style="display:none;" class="ajax_cart_num_prod"><?php echo $prodInCart;?></div>
	</div>
	<?php if (count($carrello) > 0) {
		$haErrori = CartelementiModel::haErrori();
	?>
	<footer class="uk-card-footer">
		<div class="uk-grid-small uk-grid" uk-grid>
			<div class="uk-width-expand uk-text-muted uk-h4"><?php echo gtext("Subtotale");?></div>
			<div class="uk-margin-remove-top uk-h4 uk-text-bolder"><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
		</div>
		<div class="uk-grid-small uk-child-width-1-1 <?php if (!$haErrori) { ?>uk-child-width-1-2@m<?php } ?> uk-margin-small" uk-grid>
			<?php if (!$haErrori) { ?>
				<div><a class="<?php echo v("classe_pulsanti_carrello");?> uk-margin-small uk-width-1-1" href="<?php echo $this->baseUrl."/carrello/vedi"?>"><?php echo gtext("Carrello");?></a></div>
				<div><a class="<?php echo v("classe_pulsanti_submit");?> uk-margin-small uk-width-1-1" href="<?php echo $this->baseUrl."/".VariabiliModel::paginaAutenticazione();?>"><?php echo gtext("Checkout");?></a></div>
			<?php } else { ?>
				<div><a class="<?php echo v("classe_pulsanti_carrello");?> uk-margin-small uk-width-1-1" href="<?php echo $this->baseUrl."/carrello/vedi"?>"><?php echo gtext("Vai al carrello");?> <span uk-icon="arrow-right"></span></a></div>
			<?php } ?>
		</div>
	</footer>
	<?php } ?>
	<?php include(tpf("/Elementi/Carrello/ajax_cart_bottom.php"));?>
</div>
