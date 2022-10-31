<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
	<?php include(tpf("/Elementi/Ordini/checkout_top.php"));?>
	
	<div class="">
		<?php if (!$islogged) { ?>
		<ul class="uk-subnav uk-subnav-pill uk-margin-medium-bottom" uk-switcher>
			<li><a href="#"><span class="uk-margin-small-right uk-visible@s" uk-icon="icon: check; ratio: 0.9"></span><?php echo gtext("Non sono registrato")?></a></li>
			<li><a href="#" style="vertical-align:middle"><span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.9"></span><?php echo gtext("Login");?></a></li>
		</ul>
		
		<div class="uk-switcher">
			<div>
		<?php } ?>
				<form name="checkout" method="post" action="<?php echo $this->baseUrl."/checkout";?>#content" autocomplete="new-password">
					<div class="uk-grid-medium uk-grid main_cart uk-text-left" uk-grid>
						<div class="uk-width-1-1 uk-width-expand@m uk-first-column uk-text-small">
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
							
							<div class="uk-container uk-margin-small-bottom">
								<h2 class="<?php echo v("classi_titoli_checkout");?>" style="margin-bottom:30px;"><?php echo gtext("Dati di fatturazione");?></h2>

								<div class="blocco_checkout">
									<?php include(tpf("Regusers/form_dati_cliente.php"));?>
								</div>
							</div>
							
							<?php include(tpf("Ordini/checkout_spedizione.php"));?>
							
							<div class="uk-margin-large-top uk-margin-large-bottom uk-grid-collapse" uk-grid>
								<div class="uk-width-1-1 uk-width-1-2@m">
									<div class="uk-padding uk-background-muted">
										<?php
										include(tpf(ElementitemaModel::p("CHECKOUT_PAGAMENTI","", array(
											"titolo"	=>	"Scelta del metodo di pagamento",
											"percorso"	=>	"Elementi/Ordini/Pagamenti",
										))));
										?>
									</div>
								</div>
								<div class="uk-width-1-1 uk-width-1-2@m <?php echo User::$isPhone ? "uk-margin-large-top" : "";?>">
									<div class="<?php echo User::$isPhone ? "" : "uk-padding";?>">
										<?php include(tpf("Ordini/checkout_corrieri.php"));?>
									</div>
								</div>
							</div>
							
							<?php
							if (!User::$isPhone)
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
						<div class="uk-margin-remove-top uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left <?php if (v("resoconto_ordine_top_carrello")) { ?>uk-flex-first uk-flex-last@s<?php } ?>">
							<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 100;bottom: true;"<?php } ?>>
								<?php include(tpf("/Ordini/checkout_totali.php")); ?>
								
								<?php if (v("attiva_coupon_checkout") && !hasActiveCoupon()) { ?>
								<div class="box_coupon uk-margin-medium">
									<?php
									include(tpf(ElementitemaModel::p("CHECKOUT_COUPON","", array(
										"titolo"	=>	"Form coupon al checkout",
										"percorso"	=>	"Elementi/Ordini/Coupon",
									))));
									?>
								</div>
								<?php } ?>
							</div>
							
							<?php
							if (User::$isPhone)
								include(tpf(ElementitemaModel::p("CHECKOUT_BOTTOM","", array(
									"titolo"	=>	"Parte inferiore del checkout",
									"percorso"	=>	"Elementi/Ordini/CheckoutBottom",
								))));
							?>
						</div>
					</div>
				</form>
		<?php if (!$islogged) { ?>
			</div>
			<div class="">
				<?php
				include(tpf(ElementitemaModel::p("CHECKOUT_LOGIN","", array(
					"titolo"	=>	"Form login al checkout",
					"percorso"	=>	"Elementi/Ordini/Login",
				))));
				?>
			</div>
		</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>
