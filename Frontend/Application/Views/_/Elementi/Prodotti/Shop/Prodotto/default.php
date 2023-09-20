<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
include(tpf(ElementitemaModel::p("PRODOTTO_VARIABILI","", array(
	"titolo"	=>	"Genera le variabili del prodotto in elenco",
	"percorso"	=>	"Elementi/Prodotti/Shop/VariabiliProdotto",
))));
?>
<article class="uk-transition-toggle">
	<div class="uk-inline-clip " tabindex="0">
		<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><img src="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>" /></a>
		
		<div class="uk-position-top-right blocco_wishlist">
			<div class="uk-transition-fade">
				<div class="not_in_wishlist  show" style="<?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
					<a data-link="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>" title='<?php echo gtext("Aggiungi alla lista dei desideri", false);?>' href="#" rel="nofollow" class="uk-text-secondary azione_wishlist"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/cuore.svg");?></span></a>
				</div>
			</div>
			<div class="in_wishlist  hide" style="<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
				<a data-link="<?php echo $this->baseUrl."/wishlist/elimina/".$p["pages"]["id_page"];?>" class="uk-text-danger azione_wishlist" title='<?php echo gtext("Elimina dalla lista dei desideri", false);?>' href="#" rel="nofollow"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/cuore.svg");?></span></a>
			</div>
		</div>
		<div class="uk-transition-slide-bottom uk-position-bottom uk-overlay uk-overlay-default uk-light uk-background-secondary">
			<?php if ($isProdotto && acquistabile($idPr)) { ?>
			<div class="uk-margin-remove">
				<div class="spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<a href="<?php echo $this->baseUrl."/".$urlAlias;?>" rel="<?php echo $idPr;?>" class="uk-text-small add_to_cart_button ajax_add_to_cart <?php if (!$hasCombinations) { ?>aggiungi_al_carrello_semplice<?php } ?>">
					<span uk-icon="icon: plus; ratio: .75;"></span>
					<?php if (!$hasCombinations) { ?>
					<?php echo gtext("Acquista", false);?>
					<?php } else { ?>
					<?php echo gtext("Acquista", false);?>
					<?php } ?>
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
	
	<div class="uk-margin-top">
		<h2 class="uk-text-small uk-text-bold uk-margin-remove">
			<a class="uk-text-secondary" href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo field($p, "title");?></a>
		</h2>
		<?php if ($isProdotto) { ?>
		<span class="price">
			<span class="uk-text-small">
				<?php
				$strPrezzoFissoIvato = (isset($prezzoFissoIvato) && $prezzoFissoIvato > 0) ? setPriceReverse($prezzoFissoIvato)." + " : "";
				$strPrezzoFissoFinaleIvato = (isset($prezzoFissoFinaleIvato) && $prezzoFissoFinaleIvato > 0) ? setPriceReverse($prezzoFissoFinaleIvato)." + " : "";
				?>
				<?php if ($percentualeSconto > 0 && inPromozioneTot($idPr,$p)) { echo "<del>$stringaDa € ".$strPrezzoFissoIvato.setPriceReverse($prezzoPienoIvato)."</del> € ".$strPrezzoFissoFinaleIvato.setPriceReverse($prezzoFinaleIvato); } else { echo "$stringaDa € ".$strPrezzoFissoFinaleIvato.setPriceReverse($prezzoFinaleIvato);}?>
				
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
				<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
				<?php } ?>
			</span>
		</span>
		<?php } ?>
	</div>
</article>
