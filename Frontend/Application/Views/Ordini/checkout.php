<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
   <div class="container">
      <div class="wrap w-100 d-flex align-items-center text-center">
         <div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
            <div class="breadcrumb mb-0 w-100 order-last">
               <p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home")?></a> » <a href="<?php echo $this->baseUrl."/carrello/vedi";?>"><?php echo gtext("Carrello")?></a> » <?php echo gtext("Checkout")?></p>
            </div>
            <div class="page-header  mb-2 w-100 order-first">
               <h1 class="page-title"><?php echo gtext("Checkout")?></h1>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="site-content-contain">
   <div id="content" class="site-content">
      <div class="wrap">
         <div id="primary" class="content-area">
            <main id="main" class="site-main">
               <article id="post-9" class="post-9 page type-page status-publish hentry">
                  <div class="form-account-utente">
                     <div class="woocommerce">
						<?php if (count($pages) > 0) { ?>
							<div class="woocommerce-notices-wrapper"></div>
							
							<?php if (!$islogged) { ?>
							<div class="">
								<div class="woocommerce-form-coupon-toggle">
									<div class="woocommerce-info">
										<?php echo gtext("Hai già un account?");?> <a class="showlogin show_form_login_checkout" href="#"><?php echo gtext("Clicca qui per accedere");?></a><br />
										<?php echo gtext("Altrimenti continua pure inserendo i tuoi dati.");?>
									</div>
								</div>
								
								
								<form class="checkout_coupon woocommerce-form-coupon" id="login" action = '<?php echo $this->baseUrl."/regusers/login?redirect=/checkout";?>' method = 'POST' style="display:none">
									<p class="form-row ">
										<?php echo gtext("E-mail");?><br />
										<input class="text_input" type='text' name='username'>
									</p>
									<p class="form-row form-row-first">
										<?php echo gtext("Password");?><br />
										<input class="text_input" type='password' name='password'>
									</p>
									<br />
									<p>
										<a href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
									</p>
									
									<input class="inputEntry_submit button" type="submit" name="" value="Accedi" title="<?php echo gtext("esegui il login",false);?>" />
									
									<div class="clear"></div>
								</form>
							</div>
							<?php } ?>
	
							<?php if (!hasActiveCoupon()) { ?>
							<div class="woocommerce-form-coupon-toggle">
								<div class="woocommerce-info">
									<?php echo gtext("Possiedi il codice di una promozione attiva?");?> <a href="#" class="showcoupon"><?php echo gtext("Aggiungi il tuo codice all'ordine");?></a>	
								</div>
							</div>
							<form id="coupon" class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none" action="<?php echo $this->baseUrl."/checkout";?>">
								<p><?php echo gtext("Se hai un codice promozione, inseriscilo sotto.");?></p>
								<p class="form-row form-row-first">
									<input type="text" name="il_coupon" class="input-text" placeholder="<?php echo gtext("Codice promozione", false);?>" id="coupon_code" value="" />
								</p>
								<p class="form-row form-row-last">
									<button type="submit" class="button" name="invia_coupon" value="<?php echo gtext("Invia codice promozione");?>"><?php echo gtext("Invia codice promozione", false);?></button>
								</p>
								<div class="clear"></div>
							</form>
							<?php } ?>
							
							<div class="woocommerce-notices-wrapper"></div>
							<?php echo $notice; ?>
							<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo $this->baseUrl."/checkout";?>#content">
							<div class="row">
								<div class="col-lg-7 col-md-12 col-sm-12">
									<div class="inner">
										<h3 style="margin-bottom:30px;"><?php echo gtext("Dettagli di fatturazione");?></h3>
				
										<div class="blocco_checkout">
											<?php include(ROOT."/Application/Views/Regusers/form_dati_cliente.php");?>
										</div>
										
										<?php if (!$islogged) { ?>
											
											<?php include(ROOT."/Application/Views/Ordini/scelta_spedizione_fatturazione.php");?>
											
										<?php } else if ($islogged) { ?>
											
											<?php if (count($tendinaIndirizzi) > 0) { ?>
											
											<h3><?php echo gtext("Indirizzo di spedizione");?></h3>
											
											<div class="blocco_checkout">
												<div class="blocco_scelta_indirizzo">
													<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"Y","imposta_aggiungi","none");?> <?php echo gtext("Aggiungi un nuovo indirizzo di spedizione");?>
												</div>
												
												<div class="blocco_scelta_indirizzo">
													<?php echo Html_Form::radio("aggiungi_nuovo_indirizzo",$values["aggiungi_nuovo_indirizzo"],"N","imposta_seleziona","none");?> <?php echo gtext("Seleziona un indirizzo di spedizione esistente");?>
												</div>
												<p class="blocco_tendina_scelta_indirizzo">Indirizzo: <?php echo Html_Form::select("id_spedizione",$values["id_spedizione"],$tendinaIndirizzi,"tendina_scelta_indirizzo",null,"yes")?></p>
												
												<?php include(ROOT."/Application/Views/Regusers/form_dati_spedizione.php");?>
											</div>
											
											<?php } else { ?>
											
											<?php include(ROOT."/Application/Views/Ordini/scelta_spedizione_fatturazione.php");?>
											
											<input type="hidden" name="id_spedizione" value="0" />
											<?php } ?>
											
										<?php } ?>
										
										<?php if (count($corrieri) > 1) { ?>
											<div class="box_corrieri">
											<h2><?php echo gtext("Seleziona il corriere");?></h2>
											
											<?php foreach ($corrieri as $corriere) { ?>
											<div class="radio_corriere corriere_<?php echo $corriere["id_corriere"];?>">
											<?php echo Html_Form::radio("id_corriere",$values["id_corriere"],$corriere["id_corriere"],"imposta_corriere","none");?> <?php echo $corriere["titolo"];?>
											</div>
											<?php } ?>
											</div>
											
										<?php } else if (count($corrieri) === 1) { ?>
											<?php foreach ($corrieri as $corriere) { ?>
											<?php echo Html_Form::hidden("id_corriere",$values["id_corriere"]);?>
											<?php } ?>
										<?php } ?>
										
										<div class="blocco_checkout">
												<div id="payment" class="woocommerce-checkout-payment">
													<h3><?php echo gtext("Metodo di pagamento");?></h3>
													<ul class="payment_methods methods modalita_pagamento class_pagamento">
														<li class="payment_method_paypal"><?php echo Html_Form::radio("pagamento",$values["pagamento"],"paypal",null,"none");?> <span><?php echo gtext("Paypal / Carta di credito.");?> <a href="https://www.paypal.com/it/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/it/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false; __gaTracker('send', 'event', 'outbound-article', 'https://www.paypal.com/it/webapps/mpp/paypal-popup', 'What is PayPal?');" title="What is PayPal?"><?php echo gtext("Cos'è PayPal?"); ?></a></span>

															
															<div class="payment_box payment_method_paypal" >
																<p><?php echo testo("Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.");?></p>
															</div>
														</li>
														
														<li class="payment_method_paypal">
															<?php echo Html_Form::radio("pagamento",$values["pagamento"],"bonifico",null,"none");?> <span><?php echo gtext("Bonifico bancario.");?></span>
															
															<div class="payment_box payment_method_bacs" style="display: block;">
<!-- 																<p><?php echo testo("bonifico");?></p> -->
															</div>
														</li>

														<!--<li class="payment_method_paypal">
															<?php echo Html_Form::radio("pagamento",$values["pagamento"],"contrassegno",null,"none");?> <span><?php echo gtext("Contrassegno.");?></span>
															
															<div class="payment_box payment_method_bacs" style="display: block;">
																<p><?php echo testo("contrassegno");?></p>
															</div>
														</li>-->
													</ul>

												</div>
											</div>
											
											<?php if (!$islogged) { ?>
 											<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?> <?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></div> 
											<?php } ?>
											
											<div class="blocco_checkout">
												<p class="testo_privacy"><?php echo gtext("Ho letto e accettato i");?> <a href="<?php echo $this->baseUrl."/condizioni-generali-di-vendita.html";?>"><?php echo gtext("termini e condizioni di vendita");?></a></p>
												
												<div class="class_accetto">
													<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
												</div>
											</div>
											
											<p>
												<div class="clear"></div>
											<input class="button button_submit_desktop" type="submit" name="invia" value="<?php echo gtext("Completa acquisto", false);?>" /></p>
									</div>
								</div>
								<?php include(ROOT."/Application/Views/Ordini/checkout_totali.php"); ?>
							</form>
						<?php } else { ?>
							<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
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

<script type="text/javascript">
	$(function() {
		$(".showcoupon").click(function(e){
			e.preventDefault();
			
			$("#coupon").slideToggle();
		});
	});
</script>

<script type="text/javascript">
	$(function() {
		$(".showlogin").click(function(e){
			e.preventDefault();
			
			$("#login").slideToggle();
		});
	});
</script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Js/"?>jquery.sticky.js'></script>

<script>
$(document).ready(function(){
	if ($(window).width() >= 992 && $(window).height() >= ($(".sticker").height() - 100))
	{
		$(".sticker").sticky({
			topSpacing:0,
			bottomSpacing: 700
		});
	}
});
</script>
