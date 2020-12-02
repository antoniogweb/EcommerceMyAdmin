<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li>
	<div class="widget woocommerce widget_shopping_cart">
		<div class="widget_shopping_cart_content">
			<?php if (count($carrello) > 0) { ?>
			<ul class="woocommerce-mini-cart cart_list product_list_widget ">
				<?php foreach ($carrello as $p) { ?>
				<li class="woocommerce-mini-cart-item mini_cart_item">
					<?php if (!$p["cart"]["id_p"]) { ?>
					<a href="<?php echo $this->baseUrl."/".getUrlAlias($p["cart"]["id_page"]);?>">
					<?php } ?>
						<?php if ($p["cart"]["immagine"]) { ?><img width="300" height="300" src="<?php echo $this->baseUrl."/thumb/carrelloajax/".$p["cart"]["immagine"];?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" /><?php } ?><?php echo field($p, "title");?><br /><?php echo $p["cart"]["attributi"];?>
					<?php if (!$p["cart"]["id_p"]) { ?>
					</a>
					<?php } else { ?>
					<br />
					<?php } ?>
					<span class="quantity"><?php echo $p["cart"]["quantity"];?> × 
						<span class="woocommerce-Price-amount amount"><?php echo setPriceReverse($p["cart"]["quantity"] * $p["cart"]["price"]);?>
							<span class="woocommerce-Price-currencySymbol">€</span>
						</span>
					</span>
				</li>
				<?php } ?>
			</ul>
			
			<p class="woocommerce-mini-cart__total total">
				<strong><?php echo gtext("Subtotale");?>:</strong>
				<span class="woocommerce-Price-amount amount"><?php echo getSubTotal();?>
					<span class="woocommerce-Price-currencySymbol">€</span>
				</span>
			</p>
			<p class="woocommerce-mini-cart__buttons buttons">
				<a href="<?php echo $this->baseUrl."/carrello/vedi"?>" class="button wc-forward"><?php echo gtext("VAI AL CARRELLO");?></a>
				<a href="<?php echo $this->baseUrl."/checkout"?>" class="button checkout wc-forward"><?php echo gtext("CONCLUDI ACQUISTO");?></a>
			</p>
			<?php } else { ?>
			<p class="woocommerce-mini-cart__total total"><?php echo gtext("Il carrello è vuoto")?></p>
			<?php } ?>
			
			<div style="display:none;" class="ajax_cart_num_prod"><?php echo $prodInCart;?></div>
		</div>
	</div>
</li>
