<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($pageView,"partial") !== 0) { ?><div id="main" class="cart_container"><?php } ?>
	<div class="site-content-contain">
		<div id="content" class="site-content">
			<div class="wrap">
				<div id="primary" class="content-area">
					<main id="main" class="site-main">
						<article id="post-8" class="post-8 page type-page status-publish hentry">
							<div class="entry-content">
								<div class="woocommerce">
									<div class="woocommerce-notices-wrapper"></div>
									<?php if (count($pages) > 0) { ?>
										<div class="col-lg-8 col-md-12">
											<table class="shop_table cart_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
												<thead>
													<tr class="cart_head">
														<th class="product-remove elimina_prodotto">&nbsp;</th>
														<th class="product-thumbnail thumbnail_prodotto">&nbsp;</th>
														<th class=" product-name nome_prodotto"><?php echo gtext("Prodotto");?></th>
														<th class="product-price nome_prodotto"><?php echo gtext("Codice");?></th>
														<th class="product-price prezzo_prodotto"><?php echo gtext("Prezzo (Iva esclusa)");?></th>
														<th class="product-quantity quantita_prodotto"><span class="desk"><?php echo gtext("Quantità");?></span></th>
														<th class="product-subtotal subtotal_prodotto"><?php echo gtext("Totale (Iva esclusa)");?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($pages as $p) { ?>
													<tr class="cart_item cart_item_row <?php if (!$p["cart"]["id_p"]) { ?>cart_item_row_main<?php } else { ?>cart_item_row_accessorio<?php } ?>" rel="<?php echo $p["cart"]["id_cart"];?>">
														<td class="product-remove cart_item_delete"><a class="remove cart_item_delete_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#">x</a></td>
														<td class="product-thumbnail cart_item_thumb">
															<?php if ($p["cart"]["immagine"]) { ?>
															<a href="<?php echo $this->baseUrl."/".getUrlAlias($p["cart"]["id_page"]);?>"><img src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" /></a>
															<?php } ?>
														</td>
														<td class="cart_item_product" data-title="Prodotto">
															<?php if (!$p["cart"]["id_p"]) { ?>
															<a href="<?php echo $this->baseUrl."/".getUrlAlias($p["cart"]["id_page"]);?>">
															<?php } ?>
																<?php echo field($p,"title");?>
															<?php if (!$p["cart"]["id_p"]) { ?>
															</a>
															<?php } ?>
															<?php if (strcmp($p["cart"]["id_c"],0) !== 0) { echo "<br />".$p["cart"]["attributi"]; } ?>
															<?php if (!$p["cart"]["id_p"]) { ?>
																<br /><a href="<?php echo $this->baseUrl."/".getUrlAlias($p["cart"]["id_page"])."?id_cart=".$p["cart"]["id_cart"];?>"><?php echo gtext("Modifica");?></a>
															<?php } ?>
														</td>
														<td class="product-name cart_item_product" data-title="Codice"><?php echo $p["cart"]["codice"];?></td>
														<td class="product-price cart_item_price" data-title="Prezzo">
															<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse($p["cart"]["prezzo_intero"])."</del>"; } ?> € <span class="item_price_single" rel="<?php echo $p["cart"]["price"];?>"><?php echo setPriceReverse($p["cart"]["price"]);?></span>
															<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
															<div class="scritta_iva_carrello"><?php echo gtext("Iva");?>: <?php echo setPriceReverse($p["cart"]["iva"]);?> %</div>
															<?php } ?>
														</td>
														<td class="product-quantity cart_item_quantity" data-title="Quantità">
															<div class="quantity">
																<label class="screen-reader-text" for="quantity_5d986ec501abf"></label>
																<input
																type="number"
																id="quantity_5d986ec501abf"
																class="item_quantity input-text qty text"
																step="1"
																min="0"
																max=""
																name="quantity"
																value="<?php echo $p["cart"]["quantity"];?>"
																title="Qty"
																size="4"
																inputmode="numeric" />
															</div>
														</td>
														<td class="cart_item_subtotal" data-title="Subtotale">€ <span class="item_price_subtotal"><?php echo setPriceReverse($p["cart"]["quantity"] * $p["cart"]["price"]);?></span></td>
													</tr>
													<?php } ?>
													<tr class="cart_item_row_main_promo">
														<td colspan="7" class="actions">
															<?php if (!hasActiveCoupon()) { ?>
															<form action="<?php echo $this->baseUrl."/carrello/vedi";?>" method="POST">
																<div class="coupon">
																	<label for="coupon_code"><?php echo gtext("Coupon")?>:</label>
																	<input type="text" name="il_coupon" class="input-text" id="coupon_code" value="" placeholder="<?php echo gtext("Codice promozione", false);?>" />
																	<button type="submit" class="button" name="invia_coupon" value="<?php echo gtext("Invia codice promozione", false);?>"><?php echo gtext("Invia codice promozione");?></button>
																</div>
															</form>
															<?php } ?>
															<button type="submit" class="button cart_button_aggiorna_carrello" name="update_cart" value="Update cart"><?php echo gtext("Aggiorna carrello");?></button>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="columns-2"></div>
										</div>
										<div class="cart-collaterals">
											<div class="cart_totals ">
												<h2><?php echo gtext("Totali carrello");?></h2>
												<table cellspacing="0" class="shop_table">
													<tr class="cart-subtotal">
														<td>
															<?php echo gtext("Totale merce");?>: <span class="amount" style="float:right">€ <?php echo getSubTotal();?></span>
															<?php if (hasActiveCoupon()) { ?>
															<br /><?php echo gtext("Prezzo scontato");?> (<i><?php echo getNomePromozione();?></i>): <span class="amount" style="float:right">€ <?php echo getPrezzoScontato();?></span>
															<?php } ?>
															<br /><?php echo gtext("Spese spedizione");?>: <span class="amount" style="float:right">€ <?php echo getSpedizione();?></span>
															<br /><?php echo gtext("Iva");?>: <span class="amount" style="float:right">€ <?php echo getIva();?></span>
														</td>
													</tr>
													<tr class="cart-subtotal">
														<td>
															<?php echo gtext("Totale ordine");?>: <span class="amount total_amount" style="float:right">€ <?php echo getTotal();?></span>
														</td>
													</tr>
												</table>
												<p class="wc-cart-shipping-notice"><?php echo testo("Nota"); ?></p>

												<div class="wc-proceed-to-checkout">
													<a class="checkout-button button alt wc-forward" href="<?php echo $this->baseUrl."/checkout"?>"><?php echo gtext("PROCEDI ALL'ACQUISTO");?></a>
												</div>

											</div>
										</div>
									<?php } else { ?>
										<p style="width:100%;text-align:center;"><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
										<div style="width:100%;text-align:center;"><a style="text-align:center;" class="checkout-button button alt wc-forward torna_al_negozio" href="<?php echo $this->baseUrl;?>"><?php echo gtext("Torna al negozio");?></a></div>
									<?php } ?>
								</div>
							</div>
							<!-- .entry-content -->
						</article>
						<!-- #post-## -->
					</main>
					<!-- #main -->
				</div>
				<!-- #primary -->
			</div>
			<!-- .wrap -->
		</div>
		<!-- #content -->
	</div>
	<!-- .site-content-contain -->
<?php if (strcmp($pageView,"partial") !== 0) { ?></div><?php } ?>
