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
					
					<div id="fragment-checkout-fatturazione" class="uk-container uk-margin-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span class="uk-icon uk-icon-button <?php echo v("classi_icona_checkout")?>"><?php include tpf("Elementi/Icone/Svg/user.svg");?></span>
							</div>
							<div class="uk-width-1-1">
								<h2 class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>">
									<span class="uk-icon uk-margin-right uk-hidden@m <?php echo v("classi_icona_checkout")?>"><?php include tpf("Elementi/Icone/Svg/user.svg");?></span><?php echo gtext("Dati di fatturazione");?>
								</h2>

								<div class="blocco_checkout">
									<?php if ($islogged && !$mostraCampiFatturazione) {
										if (!empty($erroriInvioOrdine))
											User::$dettagli = array_merge(User::$dettagli, $values);
									?>
									<div class="uk-margin uk-width-1-1">
										<div class="uk-grid uk-grid-collapse" uk-grid>
											<div class="uk-width-1-1 uk-width-1-2@m">
												<span class="uk-text-emphasis"><?php echo OrdiniModel::getNominativo(User::$dettagli);?></span>
												<?php if (User::$dettagli["completo"]) { ?>
													<?php if (User::$dettagli["indirizzo"]) { ?>
													<br /><span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <?php echo User::$dettagli["indirizzo"];?>
													<?php } ?>
													<?php if (User::$dettagli["cap"] || User::$dettagli["citta"] || User::$dettagli["provincia"]) { ?>
													<br /><?php echo User::$dettagli["cap"];?>, <?php echo User::$dettagli["citta"];?> (<?php echo User::$dettagli["nazione"] == "IT" ? User::$dettagli["provincia"] : User::$dettagli["dprovincia"];?>)
													<?php } ?>
												<?php } ?>
												<br /><span class="uk-text-emphasis"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione(User::$dettagli["nazione"]);?>
												<?php if (User::$dettagli["tipo_cliente"] != "azienda" && User::$dettagli["codice_fiscale"]) { ?>
												<br /><span class="uk-text-emphasis"><?php echo gtext("C.F.");?>:</span>  <?php echo User::$dettagli["codice_fiscale"];?>
												<?php } ?>
												<?php if (User::$dettagli["tipo_cliente"] != "privato") { ?>
												<br /><span class="uk-text-emphasis"><?php echo gtext("P.IVA");?>:</span>  <?php echo User::$dettagli["p_iva"];?>
												<?php } ?>
											</div>
											<div class="uk-width-1-1 uk-width-1-2@m">
												<?php if (User::$dettagli["completo"]) { ?>
												<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo User::$dettagli["telefono"];?><br />
												<?php } ?>
												<span class="uk-text-emphasis"><?php echo gtext("Email");?>:</span> <?php echo User::$dettagli["username"];?><br />
												<?php if (User::$dettagli["pec"]) { ?>
												<span class="uk-text-emphasis"><?php echo gtext("Pec");?>:</span>  <?php echo User::$dettagli["pec"];?><br />
												<?php } ?>
												<?php if (User::$dettagli["codice_destinatario"]) { ?>
												<span class="uk-text-emphasis"><?php echo gtext("Codice destinatario");?>:</span>  <?php echo User::$dettagli["codice_destinatario"];?><br />
												<?php } ?>
												<?php if (User::$dettagli["completo"] && User::$dettagli["tipo_cliente"] == "privato") { ?>
												<?php echo User::$dettagli["fattura"] ? gtext("Voglio ricevere la fattura") : gtext("Voglio ricevere lo scontrino fiscale");?>
												<?php } ?>
												<?php if (User::$dettagli["completo"]) { ?>
												<div class="uk-margin-small-top"><a href="<?php echo $this->baseUrl."/modifica-account?redirect=checkout"?>" class="uk-button uk-button-primary uk-button-small"><span class="uk-margin-small-right" uk-icon="icon: pencil"></span><?php echo gtext("Modifica dati")?></a></div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="<?php if (!User::$dettagli["completo"]) { ?>mostra_solo_dati_incompleti<?php } else { ?>uk-hidden<?php } ?>">
										<?php if (!User::$dettagli["completo"]) { ?><span class="uk-text-primary"><?php echo gtext("Si prega di completare i dati di fatturazione");?></span><?php } ?>
										<?php include(tpf("Regusers/form_dati_cliente.php"));?>
									</div>
									<?php } else { ?>
										<?php include(tpf("Regusers/form_dati_cliente.php"));?>
									<?php } ?>
								</div>
								
								<hr class="uk-divider-icon uk-margin-medium-top ">
							</div>
						</div>
					</div>
					
					<?php if (v("attiva_spedizione")) { ?>
					<div id="fragment-checkout-spedizione" class="uk-container uk-margin-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span uk-icon="icon:location;ratio:1" class="uk-icon-button <?php echo v("classi_icona_checkout")?>"></span>
							</div>
							<div class="uk-width-expand">
								<h2 class="<?php echo v("classi_titoli_checkout_spedizione");?>">
									<span uk-icon="icon:location;ratio:1" class="uk-margin-right uk-hidden@m <?php echo v("classi_icona_checkout")?>"></span><?php echo gtext("Indirizzo di spedizione");?>
								</h2>
								
								<div class="blocco_checkout">
									<?php include(tpf("Ordini/checkout_spedizione.php"));?>
								</div>
								
								<hr class="uk-divider-icon uk-margin-medium-top ">
							</div>
						</div>
					</div>
					<?php } ?>
					
					<div class="uk-container uk-margin-medium-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right uk-visible@m">
								<span uk-icon="icon:credit-card;ratio:1" class="uk-icon-button <?php echo v("classi_icona_checkout")?>"></span>
							</div>
							<div class="uk-width-expand">
								<div class="uk-grid-medium uk-grid" uk-grid>
									<div class="uk-width-1-1 <?php if (count($corrieri) > 1) { ?>uk-width-1-2@m<?php } ?>" id="fragment-checkout-pagamento">
										<div class="">
											<?php
											$htmlIcona = '<span uk-icon="icon:credit-card;ratio:1.3" class="uk-margin-right uk-hidden@m '.v("classi_icona_checkout").'"></span>';
											include(tpf(ElementitemaModel::p("CHECKOUT_PAGAMENTI","", array(
												"titolo"	=>	"Scelta del metodo di pagamento",
												"percorso"	=>	"Elementi/Ordini/Pagamenti",
											))));
											?>
										</div>
									</div>
									<?php if (v("attiva_spedizione") && count($corrieri) > 1) { ?>
									<div class="uk-width-1-1 uk-width-1-2@m <?php echo User::$isMobile ? "uk-margin-large-top" : "";?>" id="fragment-checkout-consegna">
										<div class="">
											<?php include(tpf("Ordini/checkout_corrieri.php"));?>
										</div>
									</div>
									<?php } ?>
								</div>
								
								<?php if (v("attiva_spedizione") && count($corrieri) <= 1) { ?>
								<?php include(tpf("Ordini/checkout_corrieri.php"));?>
								<?php } ?>
								
								<hr class="uk-divider-icon uk-margin-medium-top uk-margin-remove-bottom">
							</div>
						</div>
					</div>
					
					<?php if (!User::$isMobile) { ?>
					<div class="uk-container uk-margin-large-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-margin-right">
								<span uk-icon="icon:check;ratio:1" class="uk-icon-button <?php echo v("classi_icona_checkout")?>"></span>
							</div>
							<div class="uk-width-expand">
								<h2 id="fragment-checkout-conferma" class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>"><?php echo gtext("Note e conferma acquisto");?></h2>
								
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
					
					<?php if (User::$isMobile) { ?>
					<div class="uk-background-muted uk-width-1-1 checkout_bottom_bar">
						<div class="uk-padding-small">
							<div class="uk-grid-small uk-flex uk-flex-middle" uk-grid>
								<div class="uk-width-2-3">
									<div class="">
										<div class="uk-width-1-1 uk-width-auto@s uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
										<input class="uk-width-1-1 uk-width-auto@s uk-button uk-button-primary btn_completa_acquisto" type="submit" name="invia" value="<?php echo gtext("Conferma e paga", false);?>" />
									</div>
								</div>
								<div class="uk-width-expand uk-text-right">
									<div class="uk-text-lead uk-text-bolder"><span class="prezzo_bottom"><?php echo getTotal(true);?></span> €</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="uk-margin-remove-top uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left <?php if (v("resoconto_ordine_top_carrello")) { ?>uk-flex-first uk-flex-last@s<?php } ?>">
					<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 10;bottom: true;"<?php } ?> id="fragment-checkout-carrello">
						<?php include(tpf("/Ordini/checkout_totali.php")); ?>
						
						<?php if (v("attiva_coupon_checkout") && !hasActiveCoupon()) { ?>
						<div class="box_coupon uk-margin-medium-top">
							<?php
							include(tpf(ElementitemaModel::p("CHECKOUT_COUPON","", array(
								"titolo"	=>	"Form coupon al checkout",
								"percorso"	=>	"Elementi/Ordini/Coupon",
							))));
							?>
						</div>
						<?php } ?>
					</div>
					
					<?php if (User::$isMobile) { ?>
					<div class="uk-container uk-margin-large-bottom">
						<div class="uk-flex uk-flex-top">
							<div class="uk-width-expand">
								<hr class="uk-divider-icon uk-margin-medium-top uk-margin-medium-bottom">
								<h2 id="fragment-checkout-conferma" class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>">
									<span uk-icon="icon:check;ratio:1" class="uk-margin-right uk-hidden@m <?php echo v("classi_icona_checkout")?>"></span><?php echo gtext("Conferma acquisto");?>
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
