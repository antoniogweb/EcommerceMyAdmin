<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
	<?php include(tpf("/Elementi/Ordini/checkout_top.php"));?>
	
	<?php include(tpf("/Elementi/Ordini/resoconto_login.php"));?>
	
	<?php include(tpf("/Elementi/Ordini/resoconto_coupon.php"));?>
	
	<div class="uk-section">
		<form name="checkout" method="post" action="<?php echo $this->baseUrl."/checkout";?>#content" autocomplete="new-password">
			<div class="uk-grid-medium uk-grid main_cart uk-text-left" uk-grid>
				<div class="uk-width-1-1 uk-width-expand@m uk-first-column">
					<?php
					include(tpf(ElementitemaModel::p("AVVISO_LISTA_SELEZIONATA","", array(
						"titolo"	=>	"Avviso quando hai una lista selezionata",
						"percorso"	=>	"Elementi/ListaRegalo/AvvisoCarrelloCheckout",
					))));
					?>
					<div style="position:relative;top:-150px;" id="content"></div>
					<div class="uk-text-center">
						<?php echo $notice; ?>
					</div>
					
					<div class="uk-container">
						<h2 class="<?php echo v("classi_titoli_checkout");?>" style="margin-bottom:30px;"><?php echo gtext("Dati di fatturazione");?></h2>

						<div class="blocco_checkout">
							<?php include(tpf("Regusers/form_dati_cliente.php"));?>
						</div>
					</div>
					
					<?php include(tpf("Ordini/checkout_spedizione.php"));?>
					
					<?php include(tpf("Ordini/checkout_corrieri.php"));?>
					
					<?php
					include(tpf(ElementitemaModel::p("CHECKOUT_PAGAMENTI","", array(
						"titolo"	=>	"Scelta del metodo di pagamento",
						"percorso"	=>	"Elementi/Ordini/Pagamenti",
					))));
					?>
					
					<?php
					include(tpf(ElementitemaModel::p("CHECKOUT_BOTTOM","", array(
						"titolo"	=>	"Parte inferiore del checkout",
						"percorso"	=>	"Elementi/Ordini/CheckoutBottom",
					))));
					?>
					
					<?php
					if (isset($_POST['invia']))
						echo Html_Form::hidden("post_error",2);
					?>
				</div>
				<div class="uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left <?php if (v("resoconto_ordine_top_carrello")) { ?>uk-flex-first uk-flex-last@s<?php } ?>">
					<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 10;bottom: true;"<?php } ?>>
						<?php include(tpf("/Ordini/checkout_totali.php")); ?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>
