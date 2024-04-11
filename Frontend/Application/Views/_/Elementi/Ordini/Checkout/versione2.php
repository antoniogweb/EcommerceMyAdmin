<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
	<?php include(tpf("/Elementi/Ordini/checkout_top.php"));?>
	
	<div class="">
		<?php if (!$islogged || User::$isMobile || true) {
			$percentuale = User::$isMobile ? 0 : 0;
			$textClassCheckout = "uk-text-secondary";
			$classBadgeCheckout = "uk-light uk-background-secondary";
			include(tpf("/Elementi/Ordini/checkout_steps.php"));
		?>
		<?php ?>
		<?php } ?>
		<form class="box_form_evidenzia" name="checkout" method="post" action="<?php echo $this->baseUrl."/checkout";?>#content" autocomplete="new-password">
			<div class="uk-grid-medium uk-grid main_cart uk-text-left" uk-grid>
				<div class="uk-width-1-1 uk-width-expand@m uk-first-column uk-text-small">
					<?php
					include(tpf(ElementitemaModel::p("CHECKOUT_COLONNA_SINISTRA","", array(
						"titolo"	=>	"Colonna sinistra checkout",
						"percorso"	=>	"Elementi/Ordini/CheckoutColonne/Sinistra",
					))));
					?>
				</div>
				<div class="uk-margin-remove-top uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left <?php if (v("resoconto_ordine_top_carrello")) { ?>uk-flex-first uk-flex-last@s<?php } ?>">
					<?php
					include(tpf(ElementitemaModel::p("CHECKOUT_COLONNA_DESTRA","", array(
						"titolo"	=>	"Colonna destra checkout",
						"percorso"	=>	"Elementi/Ordini/CheckoutColonne/Destra",
					))));
					?>
				</div>
			</div>
		</form>
		<?php if (!$islogged) { ?>

		<?php } ?>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>
