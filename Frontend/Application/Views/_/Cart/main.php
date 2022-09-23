<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($pageView,"partial") !== 0) { ?><div id="main" class="cart_container"><?php } ?>
<?php if (count($pages) > 0) {
	$numeroGiftCardInCarrello = CartModel::numeroGifCartInCarrello();
?>
	<div class="uk-grid-medium uk-grid main_cart" uk-grid="">
		<div class="uk-width-1-1 uk-width-expand@m uk-first-column">
			<?php if (!checkQtaCartFull()) { ?>
			<div class="<?php echo v("alert_error_class");?>"><?php echo gtext("Attenzione, alcune righe nel tuo carrello hanno una quantità maggiore di quella presente a magazzino.")?></div>
			<?php } ?>
			<?php if ($numeroGiftCardInCarrello > v("numero_massimo_gift_card")) { ?>
			<div class="<?php echo v("alert_error_class");?>"><?php echo str_replace("[N]",v("numero_massimo_gift_card"),gtext("Attenzione, non è possibile inserire nel carrello più di [N] gift card"));?></div>
			<?php } ?>
			<?php if (!User::$isMobile) { ?>
			<div class="uk-visible@m">
				<div class="uk-text-meta uk-grid-small uk-child-width-1-1 uk-child-width-1-5 uk-flex-middle uk-grid" uk-grid="">
					<div class="uk-first-column">
						
					</div>
					<div class="uk-width-expand">
						<?php include(tpf("Cart/main_testata_campi_right.php"));?>
					</div>
				</div>
			</div>
			<?php } ?>
			<hr>
			<?php foreach ($pages as $p) {
				$prezzoUnitario = p($p["cart"],$p["cart"]["price"]);
				$backColor = checkGiacenza($p["cart"]["id_cart"], $p["cart"]["quantity"]) ? v("input_ok_back_color") : "red";
			?>
			<div>
				<div class="cart_item_row uk-grid-small uk-child-width-1-1@m uk-child-width-1-2 uk-child-width-1-5@m uk-child-width-2-4 <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="" rel="<?php echo $p["cart"]["id_cart"];?>">
					<div class="uk-first-column">
						<div class="uk-hidden@m uk-text-left">
							<a class="uk-text-danger remove cart_item_delete_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#" uk-icon="icon: close"></a>
						</div>
						<?php if ($p["cart"]["immagine"]) { ?>
						<?php if (!$p["cart"]["id_p"]) { ?><a href="<?php echo $this->baseUrl."/".getUrlAlias($p["cart"]["id_page"]);?>"><?php } ?>
							<img width="100px" src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" />
						<?php if (!$p["cart"]["id_p"]) { ?></a><?php } ?>
						<?php } ?>
					</div>
					<div class="uk-width-expand">
						<?php include(tpf("Cart/main_campi_right.php"));?>
					</div>
				</div>
			</div>
			<?php include(tpf("Cart/main_elementi_riga.php"));?>
			<hr>
			<?php } ?>
			<div class="uk-grid-small uk-child-width-expand@s uk-grid" uk-grid="">
				<div>
					<?php if (!hasActiveCoupon()) { ?>
					<form action="<?php echo $this->baseUrl."/carrello/vedi";?>" method="POST">
						<div class="uk-grid-small uk-child-width-expand@s uk-grid" uk-grid="">
							<div>
								<input type="text" name="il_coupon" class="uk-input uk-form-width-medium@m input-text" id="coupon_code" value="" placeholder="<?php echo gtext("Codice promozione", false);?>" />
							</div>
							<div>
								<button type="submit" class="uk-button uk-width-1-1@s uk-button-secondary" name="invia_coupon" value="<?php echo gtext("Invia codice promozione", false);?>"><?php echo gtext("Invia codice");?></button>
							</div>
						</div>
					</form>
					<?php } ?>
				</div>
				<div class="uk-visible@m">
					<?php if (!v("carrello_monoprodotto")) { ?>
						<div>
							<div class="uk-align-right uk-button uk-button-default spinner uk-hidden" uk-spinner="ratio: .70"></div>
							<a type="submit" class="btn_submit_form uk-align-right uk-button uk-button-default cart_button_aggiorna_carrello" name="update_cart" value="<?php echo gtext("Aggiorna carrello");?>"><?php echo gtext("Aggiorna carrello");?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="uk-width-1-1 tm-aside-column uk-width-1-4@m uk-text-left">
			<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 100;bottom: true;"<?php } ?>>
<!-- 			<div> -->
				<h3><?php echo gtext("Totali carrello");?></h3>
				
				<?php include(tpf("/Ordini/totali.php"));?>
				
				<div class="uk-margin uk-text-small"><?php echo testo("Nota"); ?></div>

				<div class="uk-margin">
					<div class="uk-button uk-button-secondary uk-width-1-1 spinner uk-hidden" uk-spinner="ratio: .70"></div>
					<?php if ($this->controller == "cart" && $numeroGiftCardInCarrello > 0) { ?>
					<div class="vai_la_checkout btn_submit_form uk-button uk-button-secondary uk-width-1-1""><?php echo gtext("PROCEDI ALL'ACQUISTO");?></div>
					<?php } else { ?>
					<a class="btn_submit_form uk-button uk-button-secondary uk-width-1-1" href="<?php echo $this->baseUrl."/checkout"?>"><?php echo gtext("PROCEDI ALL'ACQUISTO");?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<p style="width:100%;text-align:center;"><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
	<div style="width:100%;text-align:center;"><a style="text-align:center;" class="checkout-button button alt wc-forward torna_al_negozio" href="<?php echo $this->baseUrl;?>"><?php echo gtext("Torna al negozio");?></a></div>
<?php } ?>
<?php if (strcmp($pageView,"partial") !== 0) { ?>
</div>
<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
