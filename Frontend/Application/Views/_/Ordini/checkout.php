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
						<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large" style="margin-bottom:30px;"><?php echo gtext("Dati di fatturazione");?></h2>

						<div class="blocco_checkout">
							<?php include(tpf("Regusers/form_dati_cliente.php"));?>
						</div>
					</div>
					
					<?php include(tpf("Ordini/checkout_spedizione.php"));?>
					
					<?php include(tpf("Ordini/checkout_corrieri.php"));?>
					
					<?php if (count(OrdiniModel::$pagamenti) > 1) { ?>
					<div class="uk-container uk-margin-medium">
						<div id="payment" class="">
							<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large"><?php echo gtext("Metodo di pagamento");?></h2>
							<ul class="uk-list payment_methods modalita_pagamento class_pagamento">
								<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) {
									if (file_exists(tpf("Elementi/Pagamenti/$codPag.php")))
										include(tpf("Elementi/Pagamenti/$codPag.php"));
									else
										include(tpf("Elementi/Pagamenti/pagamento_generico.php"));
								} ?>
							</ul>
						</div>
					</div>
					<?php } else { ?>
					<div class="uk-container uk-margin class_pagamento">
						<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) {
							echo Html_Form::hidden("pagamento",$codPag,$codPag);
						} ?>
					</div>
					<?php } ?>
					
					<?php include(tpf("Ordini/note_acquisto.php"));?>
					
					<?php if (!$islogged && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"]) { ?>
					<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?> <?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></div> 
					<?php } ?>
					
					<div class="uk-margin">
						<?php $idCondizioni = PagineModel::gTipoPagina("CONDIZIONI"); ?>
						<?php if ($idCondizioni) { ?>
						<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small"><?php echo gtext("Ho letto e accettato i");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idCondizioni);?>"><?php echo gtext("termini e condizioni di vendita");?></a></div>
						<?php } else { ?>
						<div class="uk-alert uk-alert-danger"><?php echo gtext("Attenzione, definire le condizioni di vendita");?></div>
						<?php } ?>
						
						<div class="class_accetto">
							<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
						</div>
					</div>
					
					<?php if (v("piattaforma_di_demo")) { ?>
					<div class="uk-text-center uk-alert-danger uk-margin-remove" uk-alert>
						<?php echo gtext("Attenzione, questa è una piattaforma di demo e non è possibile completare l'acquisto.");?>
						<button class="uk-alert-close" type="button" uk-close></button>
					</div>
					<?php } else { ?>
					<div class="uk-margin-medium uk-margin-large-bottom">
						<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
						<input class="uk-button uk-button-secondary btn_completa_acquisto" type="submit" name="invia" value="<?php echo gtext("Completa acquisto", false);?>" />
					</div>
					<?php } ?>
					
					<?php
					if (isset($_POST['invia']))
						echo Html_Form::hidden("post_error",2);
					?>
				</div>
				<div class="uk-width-1-1 tm-aside-column uk-width-1-3@m uk-text-left <?php if (v("resoconto_ordine_top_carrello")) { ?>uk-flex-first uk-flex-last@s<?php } ?>">
					<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 100;bottom: true;"<?php } ?>>
						<?php include(tpf("/Ordini/checkout_totali.php")); ?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php } else { ?>
	<p><?php echo gtext("Non ci sono prodotti nel carrello");?></p>
<?php } ?>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
