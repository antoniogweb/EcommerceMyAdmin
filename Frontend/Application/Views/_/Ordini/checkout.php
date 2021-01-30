<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Carrello") => $this->baseUrl."/carrello/vedi",
	gtext("Checkout") => "",
);

$titoloPagina = gtext("Checkout");
$noFiltri = true;
$noNumeroProdotti = true;

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<?php if (count($pages) > 0) { ?>
	<?php if (!$islogged) { ?>
	<div class="">
		<div class="uk-margin">
			<div class="uk-text-small">
				<?php echo gtext("Hai già un account?");?> <a class="showlogin show_form_login_checkout" href="#"><?php echo gtext("Clicca qui per accedere");?></a><br />
				<?php echo gtext("Altrimenti continua pure inserendo i tuoi dati.");?>
			</div>
		</div>
		
		<div id="login" style="display:none;">
			<?php
			$noLoginNotice = $noLoginRegistrati = true;
			$action = $this->baseUrl."/regusers/login?redirect=/checkout";
			include(tp()."/Regusers/login_form.php");?>
			<br />
		</div>
	</div>
	<?php } ?>

	<?php if (!hasActiveCoupon()) { ?>
	<div class="uk-margin">
		<div class="uk-text-small">
			<?php echo gtext("Possiedi il codice di una promozione attiva?");?> <a href="#" class="showcoupon"><?php echo gtext("Aggiungi il tuo codice all'ordine");?></a>	
		</div>
	</div>
	
	<div id="coupon" class="uk-child-width-1-3@m uk-text-center" uk-grid style="display:none">
		<div></div>
		<div>
			<form class="checkout_coupon" method="post" action="<?php echo $this->baseUrl."/checkout";?>">
				<p class="uk-text-small uk-text-muted"><?php echo gtext("Se hai un codice promozione, inseriscilo sotto.");?></p>
				
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Codice promozione");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="il_coupon" type="text" placeholder="<?php echo gtext("Codice promozione", false)?>" />
					</div>
				</div>
				
				<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m" type="submit" name="invia_coupon" value="<?php echo gtext("Invia codice");?>" />
			</form>
		</div>
		<div></div>
	</div>
	<?php } ?>
	
	<div class="uk-section">
		<form name="checkout" method="post" action="<?php echo $this->baseUrl."/checkout";?>#content">
			<div class="uk-grid-medium uk-grid main_cart uk-text-left" uk-grid>
				<div class="uk-width-1-1 uk-width-expand@m uk-first-column">
					<div class="uk-text-center">
						<?php echo $notice; ?>
					</div>
					
					<div class="uk-container">
						<h2 style="margin-bottom:30px;"><?php echo gtext("Dati di fatturazione");?></h2>

						<div class="blocco_checkout">
							<?php include(tp()."/Regusers/form_dati_cliente.php");?>
						</div>
					</div>
					
					<?php if (!$islogged) { ?>
						
						<h3><?php echo gtext("Indirizzo di spedizione");?></h3>
						
						<?php include(tp()."/Ordini/scelta_spedizione_fatturazione.php");?>
						
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
							
							<div class="uk-margin blocco_tendina_scelta_indirizzo">
								<label class="uk-form-label"><?php echo gtext("Indirizzo");?> *</label>
								<div class="uk-form-controls">
									<?php echo Html_Form::select("id_spedizione",$values["id_spedizione"],$tendinaIndirizzi,"uk-select tendina_scelta_indirizzo",null,"yes");?>
								</div>
							</div>
							
							<?php include(tp()."/Regusers/form_dati_spedizione.php");?>
						</div>
						
						<?php } else { ?>
						
						<?php include(tp()."/Ordini/scelta_spedizione_fatturazione.php");?>
						
						<input type="hidden" name="id_spedizione" value="0" />
						<?php } ?>
						
					<?php } ?>
					
					<div class="uk-container uk-margin-medium">
						<?php if (count($corrieri) > 1) { ?>
							<div class="box_corrieri">
							<h3><?php echo gtext("Seleziona il corriere");?></h3>
							
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
					</div>
					
					<div class="uk-container uk-margin">
						<div id="payment" class="">
							<h3><?php echo gtext("Metodo di pagamento");?></h3>
							<ul class="uk-list payment_methods modalita_pagamento class_pagamento">
								<li class="payment_method_paypal"><?php echo Html_Form::radio("pagamento",$values["pagamento"],"paypal",null,"none");?> <span><?php echo gtext("Paypal / Carta di credito.");?> <a href="https://www.paypal.com/it/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/it/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false; __gaTracker('send', 'event', 'outbound-article', 'https://www.paypal.com/it/webapps/mpp/paypal-popup', 'What is PayPal?');" title="What is PayPal?"><?php echo gtext("Cos'è PayPal?"); ?></a></span>
									<div class="payment_box payment_method_paypal uk-margin" >
										<div class="uk-text-small uk-text-muted"><?php echo testo("Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.");?></div>
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
					
					<div class="uk-margin">
						<h3><?php echo gtext("Note d'acquisto")?></h3>
						
						<div class="blocco_checkout">
							<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea",null,"placeholder='".gtext("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
						</div>
					</div>
					
					<?php if (!$islogged && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"]) { ?>
					<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?> <?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></div> 
					<?php } ?>
					
					<div class="uk-margin">
						<?php $idCondizioni = PagineModel::gTipoPagina("CONDIZIONI"); ?>
						<?php if ($idCondizioni) { ?>
						<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small"><?php echo gtext("Ho letto e accettato i");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idCondizioni);?>"><?php echo gtext("termini e condizioni di vendita");?></a></div>
						<?php } else { ?>
						<div class="uk-alert uk-alert-danger"><?php echo gtext("Attenzione, definire le condizioni di vendita");?></div>
						<?php } ?>
						
						<div class="class_accetto">
							<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
						</div>
					</div>
					
					<div class="uk-margin">
						<input class="uk-button uk-button-secondary" type="submit" name="invia" value="<?php echo gtext("Completa acquisto", false);?>" />
					</div>
					
					<?php
					if (isset($_POST['invia']))
						echo Html_Form::hidden("post_error",2);
					?>
				</div>
				<div class="uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left">
					<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 100;bottom: true;"<?php } ?>>
						<?php include(tp()."/Ordini/checkout_totali.php"); ?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>

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
