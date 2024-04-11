<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
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
				<?php
				include(tpf(ElementitemaModel::p("CHECKOUT_FATTURAZIONE","", array(
					"titolo"	=>	"Sezione fatturazione in checkout",
					"percorso"	=>	"Elementi/Ordini/Fatturazione",
				))));
				?>
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

<?php if (count(OrdiniModel::$pagamenti) > 1 || (v("attiva_spedizione") && count($corrieri) > 1)) { ?>
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
<?php } else {
	include(tpf(ElementitemaModel::p("CHECKOUT_PAGAMENTI","", array(
		"titolo"	=>	"Scelta del metodo di pagamento",
		"percorso"	=>	"Elementi/Ordini/Pagamenti",
	))));
	
	include(tpf("Ordini/checkout_corrieri.php"));
} ?>

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
