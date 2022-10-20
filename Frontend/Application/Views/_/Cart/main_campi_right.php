<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-middle uk-flex-center uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center@m uk-text-left uk-grid" uk-grid="">
	<div class="uk-first-column">
		<?php if (!$p["cart"]["id_p"]) { ?>
			<a class="uk-link-heading <?php if (User::$isMobile) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>">
			<?php } ?>
				<?php echo field($p,"title");?>
			<?php if (!$p["cart"]["id_p"]) { ?>
			</a>
			<?php } ?>
			<?php if ($p["cart"]["attributi"]) { echo "<br />".$p["cart"]["attributi"]; } ?>
			
			<?php if ($p["cart"]["attributi"] && !$p["cart"]["id_p"] && !VariabiliModel::combinazioniLinkVeri()) { ?>
			<div class="uk-margin">
				<a class="uk-text-meta" href="<?php echo $this->baseUrl."/".$urlAliasProdotto."?id_cart=".$p["cart"]["id_cart"];?>"><?php echo gtext("Modifica");?></a>
			</div>
		<?php } ?>
	</div>
	<?php if (v("mostra_codice_in_carrello")) { ?>
	<div>
		<?php if ($p["cart"]["codice"]) { ?>
		<span class="uk-hidden@m"><?php echo gtext("COD");?>:</span></span><?php echo $p["cart"]["codice"];?>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="uk-visible@m">
		<?php echo setPriceReverse($prezzoUnitario);?> €
		<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del class='uk-text-small uk-text-muted'>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_intero"]))." €</del>"; } ?>
	</div>
	<div>
		<?php if (!v("carrello_monoprodotto")) { ?>
			<?php if (User::$isMobile) { ?>
				<div class="select_box cart_select_box" back-color="<?php echo $backColor;?>"><?php echo Html_Form::select("quantity", $p["cart"]["quantity"], array_combine(range(1,30),range(1,30)),"uk-select item_quantity cart_item_row_mobile", null, "yes", "rel='".$p["cart"]["id_cart"]."' style='background-color:$backColor; !important'");?></div>
			<?php } else { ?>
				<?php if (!v("mostra_piu_meno_modifica_quantita")) { ?>
				<input rel="<?php echo $p["cart"]["id_cart"];?>" class="uk-input item_quantity" name="quantity" type="number" value="<?php echo $p["cart"]["quantity"];?>" min="1" 	style="background-color:<?php echo $backColor;?> !important" />
				<?php } else { ?>
				<div class="uk-flex uk-flex-middle uk-flex-center box_quantity uk-border-rounded">
					<input rel="<?php echo $p["cart"]["id_cart"];?>" disabled class="uk-padding-remove uk-form-width-xsmall uk-input item_quantity" name="quantity" type="text" value="<?php echo $p["cart"]["quantity"];?>" min="1" style="max-width:25px;border:none;background-color:<?php echo $backColor;?> !important" />
					<div class="uk-text-center">
						<a style="display:block" class="uk-text-meta cart_item_quantity_increase" href=""><span uk-icon="icon: chevron-up;ratio: 1"></span></a>
						<a style="display:block" class="uk-text-meta cart_item_quantity_decrease" href=""><span uk-icon="icon: chevron-down;ratio: 1"></span></a>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<?php if (User::$isMobile) { echo gtext("Qta").":"; } ?> <?php echo $p["cart"]["quantity"];?>
		<?php } ?>
	</div>
	<div>
		<?php echo setPriceReverse($p["cart"]["quantity"] * $prezzoUnitario);?> €
	</div>
	<div class="uk-visible@m">
		<a class="uk-text-danger remove cart_item_delete_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#" uk-icon="icon: close"></a>
	</div>
</div>
