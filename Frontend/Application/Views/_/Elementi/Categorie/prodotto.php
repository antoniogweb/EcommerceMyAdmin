<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (isset($corr))
	$p = $corr;

$idPr = getPrincipale(field($p, "id_page"));
$hasCombinations = hasCombinations($idPr);
$hasSoloCombinations = hasCombinations($idPr, false);
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$prezzoMinimo = prezzoMinimo($idPr);
$stringaDa = !$hasSoloCombinations ? "" : gtext("da");
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
$percSconto = getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);
?>
<article class="uk-transition-toggle">
	<div class="uk-inline-clip " tabindex="0">
		<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><img src="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>" /></a>
		
		<div class="uk-position-top-right blocco_wishlist">
			<div class="uk-transition-fade">
				<div class="not_in_wishlist  show" style="<?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
					<a title='<?php echo gtext("Aggiungi alla lista dei desideri", false);?>' href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>" rel="nofollow" class="uk-text-secondary azione_wishlist"><span uk-icon="icon: heart; ratio: 1;"></span></a>
				</div>
			</div>
			<div class="in_wishlist  hide" style="<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
				<a class="uk-text-danger azione_wishlist" title='<?php echo gtext("Elimina dalla lista dei desideri", false);?>' href="<?php echo $this->baseUrl."/wishlist/elimina/".$p["pages"]["id_page"];?>" rel="nofollow"><span uk-icon="icon: heart; ratio: 1;"></span></a>
			</div>
		</div>
		<div class="uk-transition-slide-bottom uk-position-bottom uk-overlay uk-overlay-default uk-light uk-background-secondary">
			<?php if (isProdotto($idPr) && acquistabile($idPr)) { ?>
			<div class="uk-margin-remove">
				<div class="spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<a href="<?php echo $this->baseUrl."/".$urlAlias;?>" rel="<?php echo $idPr;?>" class="uk-text-small add_to_cart_button ajax_add_to_cart <?php if (!$hasCombinations) { ?>aggiungi_al_carrello_semplice<?php } ?>" rel="nofollow">
					<span uk-icon="icon: plus; ratio: .75;"></span>
					<?php if (!$hasCombinations) { ?>
					<?php echo gtext("Aggiungi al carrello", false);?>
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
		<?php if (isProdotto($idPr)) { ?>
		<span class="price">
			<span class="uk-text-small">
				<?php if (inPromozioneTot($idPr,$p)) { echo "<del>$stringaDa € ".setPriceReverse($prezzoPienoIvato)."</del> € ".setPriceReverse($prezzoFinaleIvato); } else { echo "$stringaDa € ".setPriceReverse($prezzoFinaleIvato);}?>
				
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
				<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
				<?php } ?>
			</span>
		</span>
		<?php } ?>
	</div>
</article>

