<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
	<?php include(tpf("/Elementi/Ordini/checkout_top.php"));?>
	
	<div class="">
		<?php if (!$islogged) {
			$percentuale = 75;
			$textClassCheckout = "uk-text-secondary";
			include(tpf("/Elementi/Ordini/checkout_steps.php"));
		?>
		<div class="uk-text-center uk-text-small uk-margin-large-bottom"><span uk-icon="pencil"></span> <?php echo gtext("Continua inserendo i tuoi dati");?></div>
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
					
					<div class="uk-container uk-margin-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span uk-icon="icon:bookmark;ratio:1.2" class="uk-icon-button"></span>
							</div>
							<div class="uk-width-1-1">
								<h2 class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>">
									<span uk-icon="icon:bookmark;ratio:1.2" class="uk-margin-right uk-icon-button uk-hidden@m"></span><?php echo gtext("Informazioni personali");?>
								</h2>

								<div class="blocco_checkout">
									<?php if ($islogged) { ?>
									<div class="uk-margin uk-width-1-1">
										<div class="uk-grid uk-grid-large" uk-grid>
											<div class="uk-width-1-1 uk-width-1-2@m">
												<span class="uk-text-emphasis"><?php echo OrdiniModel::getNominativo(User::$dettagli);?></span>
												<br /><span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <?php echo User::$dettagli["indirizzo"];?>
												<br /><?php echo User::$dettagli["cap"];?>, <?php echo User::$dettagli["citta"];?> (<?php echo User::$dettagli["nazione"] == "IT" ? User::$dettagli["provincia"] : User::$dettagli["dprovincia"];?>)
												<br /><span class="uk-text-emphasis"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione(User::$dettagli["nazione"]);?>
											</div>
											<div class="uk-width-1-1 uk-width-1-2@m">
												<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo User::$dettagli["telefono"];?><br />
												<span class="uk-text-emphasis"><?php echo gtext("Email");?>:</span> <?php echo User::$dettagli["username"];?><br />
												<?php echo User::$dettagli["fattura"] ? gtext("Voglio ricevere la fattura") : gtext("Voglio ricevere lo scontrino fiscale");?>
											</div>
										</div>
									</div>
									<?php } ?>
									<div <?php if ($islogged) { ?>class="uk-hidden"<?php } ?>>
										<?php include(tpf("Regusers/form_dati_cliente.php"));?>
									</div>
								</div>
								
								<hr class="uk-divider-icon uk-margin-medium-top">
							</div>
						</div>
					</div>
					
					<div class="uk-container uk-margin-medium-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span uk-icon="icon:location;ratio:1.2" class="uk-icon-button"></span>
							</div>
							<div class="uk-width-expand">
								<h2 class="<?php echo v("classi_titoli_checkout_spedizione");?>">
									<span uk-icon="icon:location;ratio:1.2" class="uk-margin-right uk-icon-button uk-hidden@m"></span><?php echo gtext("Indirizzo di spedizione");?>
								</h2>
								
								<?php include(tpf("Ordini/checkout_spedizione.php"));?>
								
								<hr class="uk-divider-icon uk-margin-medium-top">
							</div>
						</div>
					</div>
					
					<div class="uk-container uk-margin-medium-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span uk-icon="icon:credit-card;ratio:1.2" class="uk-icon-button"></span>
							</div>
							<div class="uk-width-expand">
								<div class="uk-grid-large" uk-grid>
									<div class="uk-width-1-1 uk-width-1-2@m">
										<div class="">
											<?php
											$htmlIcona = '<span uk-icon="icon:credit-card;ratio:1.2" class="uk-margin-right uk-icon-button uk-hidden@m"></span>';
											include(tpf(ElementitemaModel::p("CHECKOUT_PAGAMENTI","", array(
												"titolo"	=>	"Scelta del metodo di pagamento",
												"percorso"	=>	"Elementi/Ordini/Pagamenti",
											))));
											?>
										</div>
									</div>
									<div class="uk-width-1-1 uk-width-1-2@m <?php echo User::$isPhone ? "uk-margin-large-top" : "";?>">
										<div class="<?php echo User::$isPhone ? "" : "";?>">
											<?php include(tpf("Ordini/checkout_corrieri.php"));?>
										</div>
									</div>
								</div>
								
								<hr class="uk-divider-icon uk-margin-medium-top">
							</div>
						</div>
					</div>
					
					<?php if (!User::$isPhone) { ?>
					<div class="uk-container uk-margin-large-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right">
								<span uk-icon="icon:check;ratio:1.2" class="uk-icon-button"></span>
							</div>
							<div class="uk-width-expand">
								<h2 class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>"><?php echo gtext("Verifica e conferma acquisto");?></h2>
								
								<?php include(tpf(ElementitemaModel::p("CHECKOUT_BOTTOM","", array(
										"titolo"	=>	"Parte inferiore del checkout",
										"percorso"	=>	"Elementi/Ordini/CheckoutBottom",
									))));
								?>
							</div>
						</div>
					</div>
					<?php } ?>
					
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
					
					<?php if (User::$isPhone) { ?>
					<div class="uk-container uk-margin-large-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-width-expand">
								<h2 class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>">
									<span uk-icon="icon:check;ratio:1.2" class="uk-margin-right uk-icon-button uk-hidden@m"></span><?php echo gtext("Conferma acquisto");?>
								</h2>
								
								<?php include(tpf(ElementitemaModel::p("CHECKOUT_BOTTOM","", array(
										"titolo"	=>	"Parte inferiore del checkout",
										"percorso"	=>	"Elementi/Ordini/CheckoutBottom",
									))));
								?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</form>
		<?php if (!$islogged) { ?>

		<?php } ?>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>
