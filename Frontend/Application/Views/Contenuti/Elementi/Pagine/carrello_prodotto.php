<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $prezzoProdotto = $p["pages"]["price"]; ?>
<h1 class="product_title entry-title"><?php echo field($p, "title");?></h1>

<?php if (count($lista_valori_attributi) > 0) { ?>
<p class="price"><span class="amount">
	<?php echo gtext("Da");?>
	
	<?php if (inPromozioneTot($p["pages"]["id_page"])) { echo "<del class=''>€ ".setPriceReverse(calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo))."</del> € <span class=''>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo))."</span>"; } else { echo "€ <span class=''>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo))."</span>";}?></span>
	
	<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
	<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
	<?php } ?>
</p>
<?php } ?>

<div class="woocommerce-product-details__short-description">
	<?php echo htmlentitydecode(attivaModuli($p["pages"]["descrizione_breve"]));?>
</div>

<?php if (count($scaglioni) > 0) { ?>
<div class="sconto_quantita">
	<b>Sconto quantità:</b>
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th>Quantità</th>
				<th>Sconto</th>
			</tr>
		</thead>
		<?php foreach ($scaglioni as $q => $sconto) { ?>
		<tr>
			<td>da <?php echo $q;?> unità</td>
			<td><?php echo $sconto." %";?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<?php } ?>

<?php include(ROOT."/Application/Views/Contenuti/Elementi/Pagine/accessori.php");?>

<?php if (acquistabile($p["pages"]["id_page"])) { ?>
	<?php if (count($lista_valori_attributi) > 0 || count($personalizzazioni) > 0) { ?>
	<div class="box_accessorio accessorio_principale">
		<script>var sconto = <?php echo $p["pages"]["prezzo_promozione"];?></script>
		<?php if (count($lista_valori_attributi) > 0) { ?>
		<div class="lista_attributi_prodotto">
			<table class="variations">
				<?php foreach ($lista_valori_attributi as $col => $valori_attributo) { ?>
				<tr>
					<td class="label" style="width:40%;"><label class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl($lista_attributi[$col]);?>"><?php echo $lista_attributi[$col];?></label></td>
					<td>
						<?php if (isset($valori_attributo[0])) { ?>
							<?php echo Html_Form::select($col,getAttributoDaCarrello($col),$valori_attributo,"form_select_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]),null,"yes","style='width:100%;' col='".$col."' rel='".$lista_attributi[$col]."'");?>
						<?php } else { ?>
							<?php echo Html_Form::radio($col,getAttributoDaCarrello($col),$valori_attributo,"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]), "after", null, "yes", "col='".$col."' rel='".$lista_attributi[$col]."'");?>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
			
			<?php
			$el = $p;
			include(ROOT."/Application/Views/Contenuti/Elementi/Pagine/dati_variante.php");
			?>
		</div>
		<?php } ?>
		
		<?php if (count($personalizzazioni) > 0) { ?>
		<div class="lista_personalizzazioni_prodotto">
			<table class="variations">
				<?php foreach ($personalizzazioni as $pers) { ?>
				<tr>
					<td class="label" style="width:40%;"><label class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl(persfield($pers, "titolo"));?>"><?php echo persfield($pers, "titolo");?></label></td>
					<td>
						<?php
						$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
						echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"]),"form_input_personalizzazione",null,"style='width:100%;' $maxLength rel='".persfield($pers, "titolo")."'");?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
		
		<div class="errore_combinazione"></div>
	</div>
	<?php } ?>

	<div class="blocco-prezzo">
		<p class="price"><span class="amount">
			<?php if (inPromozioneTot($p["pages"]["id_page"])) { echo "<del>€ <span class='price_full'>".setPriceReverse(calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoProdotto))."</span></del> € <span class='price_value'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoProdotto))."</span>"; } else { echo "€ <span class='price_value'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoProdotto))."</span>";}?></span>
			<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
			<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
			<?php } ?>
		</p>
		<p class="giacenza">
			<?php $qtaAcc = giacenzaPrincipale($p["pages"]["id_page"]);?>
			<span class="valore_giacenza"><?php echo $qtaAcc;?></span>
			<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo rimasto", false);?></span>
			<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi rimasti", false);?></span>
		</p>
	</div>

		
	<form class="cart" action="<?php echo $this->baseUrl."/".$urlAlias;?>" method="post" enctype='multipart/form-data'>
	<div class="quantity">
		<label class="screen-reader-text" for="quantity_5d9af32c655fa"><?php echo gtext("Quantità"); ?></label>
		<input
			type="number"
			id="quantity_5d9af32c655fa"
			class="input-text qty text quantita_input"
			step="1"
			min="1"
			max=""
			name="quantita"
			value="<?php echo getQtaDaCarrello();?>"
			title="Quantità"
			size="4"
			inputmode="numeric" />
	</div>

	<button type="submit" name="add-to-cart" id-cart="<?php echo isset($_GET["id_cart"]) ? (int)$_GET["id_cart"] : 0;?>" rel="<?php echo $p["pages"]["id_page"];?>"  class="aggiungi_al_carrello pulsante_carrello single_add_to_cart_button button alt"><span>
		<?php if (idCarrelloEsistente()) { ?>
		<?php echo gtext("Modifica prodotto nel carrello", false); ?>
		<?php } else { ?>
		<?php echo gtext("Aggiungi al carrello", false); ?>
		<?php } ?>
		</span></button>
	</form>

	<div class="yith-wcwl-add-to-wishlist add-to-wishlist-1118 blocco_wishlist">
		<div class="not_in_wishlist yith-wcwl-add-button hide" style="display: <?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>none<?php } ?>;">
			<a title='<?php echo gtext("Aggiungi alla lista dei desideri", false);?>' href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>" rel="nofollow" data-product-id="1118" data-product-type="simple" class="azione_wishlist">
			<?php echo gtext("Aggiungi alla lista dei desideri"); ?></a>
			<img src="<?php echo $this->baseUrlSrc."/Public/Tema/"?>/plugins/yith-woocommerce-wishlist/assets/images/wpspin_light.gif" class="ajax-loading" alt="loading" width="16" height="16" style="visibility: hidden;">
		</div>
		<div class="in_wishlist yith-wcwl-wishlistaddedbrowse show" style="display:<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>none<?php } ?>;">
			<a class="" href="<?php echo $this->baseUrl."/wishlist/vedi";?>" rel="nofollow">
				<?php echo gtext("Sfoglia la lista dei desideri"); ?>
			</a>
		</div>
		<div style="clear:both"></div>
		<div class="yith-wcwl-wishlistaddresponse"></div>
	</div>
<?php } ?>

<div class="product_meta">
<span class="sku_wrapper">SKU: <span class="sku codice_value"><?php echo $p["pages"]["codice"];?></span></span>
<span class="posted_in"><?php echo gtext("Categoria"); ?>: <a href="<?php echo $this->baseUrl."/$urlAliasCategoria"?>" rel="tag"><?php echo $p["categories"]["title"];?></a></span>
<?php if ($p["marchi"]["titolo"]) { ?>
<span class="posted_in"><?php echo gtext("Famiglia"); ?>: <a href="<?php echo $this->baseUrl."/".encodeUrl($p["marchi"]["titolo"])."/".$categoriaShop["alias"].".html";?>" rel="tag"><?php echo $p["marchi"]["titolo"];?></a></span>
<?php } ?>
</div>

<div class="pbr-social-share">
<?php echo gtext("Condividi"); ?>:
<a class="bo-social-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $this->baseUrl."/$urlAlias";?>&title=<?php echo $p["pages"]["title"];?>" target="_blank" title="Condividi su facebook">
	<i class="fa fa-facebook"></i>
</a>
<a class="bo-social-twitter" href="http://twitter.com/home?status=<?php echo $p["pages"]["title"];?> <?php echo $this->baseUrl."/$urlAlias";?>" target="_blank" title="Condividi su on Twitter">
	<i class="fa fa-twitter"></i>
</a>
<a class="bo-social-tumblr" href="http://www.tumblr.com/share/link?url=
<?php echo $this->baseUrl."/$urlAlias";?>&name=<?php echo $p["pages"]["title"];?>" target="_blank" title="Condividi su Tumblr">
	<i class="fa fa-tumblr"></i>
</a>
</div>
