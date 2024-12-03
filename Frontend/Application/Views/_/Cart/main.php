<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($pageView,"partial") !== 0) { ?><div id="main" class="cart_container"><?php } ?>
<?php if (count($pages) > 0) { ?>
	<div class="uk-grid-medium uk-grid main_cart" uk-grid="">
		<div class="uk-width-1-1 uk-width-expand@m uk-first-column">
			<?php include(tpf("Cart/main_avvisi_superiori.php"));?>
			
			<?php if (!User::$isMobile) { ?>
			<div class="uk-visible@m cart_head_row">
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
			<?php foreach ($pages as $p) { ?>
				<?php include(tpf("Cart/main_riga.php"));?>
			<?php } ?>
			<?php include(tpf("Cart/main_avvisi_inferiori.php"));?>
			<div class="uk-grid-small uk-child-width-expand@s uk-grid" uk-grid="">
				<div>
					<?php include(tpf("Cart/main_form_coupon.php"));?>
				</div>
				<?php include(tpf("Cart/main_pulsante_aggiorna_carrello.php"));?>
			</div>
		</div>
		<div class="uk-width-1-1 tm-aside-column uk-width-1-4@m uk-text-left">
			<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: <?php echo v("cart_sticky_top_offeset");?>;bottom: true;"<?php } ?>>
				<h3><?php echo gtext("Totali carrello");?></h3>
				
				<?php include(tpf("/Ordini/totali.php"));?>
				
				<div class="uk-margin uk-text-small"><?php echo testo("Nota"); ?></div>
				
				<?php include(tpf("Cart/main_pulsante_checkout.php"));?>
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
