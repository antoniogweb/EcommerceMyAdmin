<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-middle uk-flex-center uk-grid-small uk-child-width-1-1 uk-child-width-expand@m uk-text-center@m uk-text-left uk-grid" uk-grid="">
	<div class="uk-first-column descrizione_prodotto_carrello <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php if (!$p["cart"]["id_p"]) { ?>
			<a class="uk-link-heading <?php if (User::$isMobile) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>">
		<?php } ?>
		<?php echo field($p,"title");?>
		<?php if (!$p["cart"]["id_p"]) { ?>
		</a>
		<?php } ?>
		<?php if ($p["cart"]["attributi"]) { echo "<br /><span class='uk-text-small'>".$p["cart"]["attributi"]."</span>"; } ?>
		
		<?php if ($p["cart"]["attributi"] && !$p["cart"]["id_p"] && !VariabiliModel::combinazioniLinkVeri() && v("mostra_pulsante_modifica_se_ha_combinazioni")) { ?>
			<div class="uk-margin">
				<a class="uk-text-meta" href="<?php echo $this->baseUrl."/".$urlAliasProdotto."?id_cart=".$p["cart"]["id_cart"];?>"><?php echo gtext("Modifica");?></a>
			</div>
		<?php } ?>
		<?php include(tpf("Cart/main_testo_disponibilita.php"));?>
	</div>
	<?php if (v("mostra_codice_in_carrello")) { ?>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php if ($p["cart"]["codice"]) { ?>
		<span class="uk-hidden@m"><?php echo gtext("COD");?>:</span></span><?php echo $p["cart"]["codice"];?>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if (v("attiva_prezzo_fisso")) { ?>
	<div class="uk-visible@m <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php echo setPriceReverse($prezzoUnitarioFisso);?> €
		<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0 && $p["cart"]["prezzo_fisso_intero"] > 0){ echo "<del class='uk-text-small uk-text-muted'>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_fisso_intero"]))." €</del>"; } ?>
		<?php if (!v("prezzi_ivati_in_carrello")) { ?>
		<div class="uk-text-meta"><?php echo gtext("Iva")?>: <?php echo setPriceReverse($p["cart"]["iva"]);?>%</div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="uk-visible@m <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php echo setPriceReverse($prezzoUnitario);?> €
		<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del class='uk-text-small uk-text-muted'>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_intero"]))." €</del>"; } ?>
		<?php if (!v("prezzi_ivati_in_carrello")) { ?>
		<div class="uk-text-meta"><?php echo gtext("Iva")?>: <?php echo setPriceReverse($p["cart"]["iva"]);?>%</div>
		<?php } ?>
	</div>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php if (!v("carrello_monoprodotto")) { ?>
			<?php
			$idRigaCarrello = $p["cart"]["id_cart"];
			$quantitaRigaCarrello = $p["cart"]["quantity"];
			include(tpf(ElementitemaModel::p("INPUT_QUANTITA_CARRELLO","", array(
				"titolo"	=>	"Campo input di modifica della quantità",
				"percorso"	=>	"Elementi/Generali/QuantitaCarrello",
			))));
			?>
		<?php } else { ?>
			<?php if (User::$isMobile) { echo gtext("Qta").":"; } ?> <?php echo $p["cart"]["quantity"];?>
		<?php } ?>
	</div>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php echo setPriceReverse(($p["cart"]["quantity"] * $prezzoUnitario) + $prezzoUnitarioFisso);?> €
	</div>
	<div class="uk-visible@m">
		<a class="uk-text-danger remove cart_item_delete_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/close.svg");?></span></a>
	</div>
</div>
