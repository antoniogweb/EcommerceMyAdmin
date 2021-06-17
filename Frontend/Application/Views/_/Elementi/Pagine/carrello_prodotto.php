<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $prezzoProdotto = $prezzoMinimo; ?>

<div class="info-product">
	<h1><?php echo field($p, "title");?></h1>
	<div class="">
		<?php if (count($lista_valori_attributi) > 0) { ?><?php echo gtext("Da");?><?php } else { ?><?php } ?>
		<?php if (inPromozioneTot($p["pages"]["id_page"])) { echo "<del class='uk-text-muted'>".setPriceReverse(calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo))." €</del><br /> <strong class='uk-text-large'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo))."</strong> €"; } else { echo "<strong class='uk-text-large'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo))."</span> €";}?></strong>
		
		<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
		<span class="uk-text-muted"><?php echo gtext("Iva inclusa");?></span>
		<?php } ?>
		
		<?php if (count($lista_valori_attributi) === 0) { ?>
		<div class="giacenza uk-text-small">
			<?php $qtaAcc = giacenzaPrincipale($p["pages"]["id_page"]);?>
			<?php echo gtext("Disponibilità");?>: <span class="valore_giacenza"><?php echo $qtaAcc;?></span>
			<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo", false);?></span>
			<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi", false);?></span>
		</div>
		<?php } ?>
	</div>
	
	<div class="uk-margin-medium">
		<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
	</div>
	
	<?php
	$arrayInfo = array();
	
	if ($p["pages"]["codice"])
		$arrayInfo[ucfirst(gtext("codice"))] = array($p["pages"]["codice"],"codice_value");
	
	if ($p["pages"]["peso"] > 0)
		$arrayInfo[ucfirst(gtext("peso"))] = array((setPriceReverse($p["pages"]["peso"]))."kg","peso_value");
	
	if (isset($marchioCorrente) && count($marchioCorrente) > 0)
		$arrayInfo[ucfirst(gtext("marchio"))] = array("<a href='".$this->baseUrl."/".getMarchioUrlAlias($marchioCorrente["marchi"]["id_marchio"])."'>".mfield($marchioCorrente,"titolo")."</a>","");
	?>
	<?php if (count($arrayInfo) > 0) { ?>
	<div class="uk-margin">
		<ul class="uk-list uk-text-small uk-margin-remove">
			<?php foreach ($arrayInfo as $k => $v) { ?>
			<li><span class="uk-text-muted"><?php echo $k;?>: </span><span class="<?php echo $v[1];?>"><?php echo $v[0];?></span></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	
	<?php if (count($scaglioni) > 0) { ?>
	<div class="sconto_quantita">
		<table class="table table_2 table_responsive" width="100%">
			<thead>
				<tr>
					<th><?php echo gtext("Quantità");?></th>
					<th><?php echo gtext("Sconto");?></th>
				</tr>
			</thead>
			<?php foreach ($scaglioni as $q => $sconto) { ?>
			<tr>
				<td>da <?php echo $q;?> <?php echo gtext("unità");?></td>
				<td><?php echo $sconto." %";?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<?php } ?>

	<?php include(tpf("/Elementi/Pagine/accessori.php"));?>
	
	<?php if (acquistabile($p["pages"]["id_page"])) { ?>
		<?php if (count($lista_valori_attributi) > 0 || (isset($personalizzazioni) && count($personalizzazioni) > 0)) { ?>
		<div class="box_accessorio accessorio_principale">
			<script>var sconto = <?php echo $p["pages"]["prezzo_promozione"];?></script>
			<?php if (count($lista_valori_attributi) > 0) { ?>
			<div class="lista_attributi_prodotto">

					<?php foreach ($lista_valori_attributi as $col => $valori_attributo) { ?>
						
						<label style="display:none;" class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl($lista_attributi[$col]);?>"><?php echo $lista_attributi[$col];?></label>
						<?php if (PagesModel::isRadioAttributo($p["pages"]["id_page"], $col)) { ?>
							<?php echo Html_Form::radio($col,getAttributoDaCarrello($col),$valori_attributo,"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]), "after", null, "yes", "col='".$col."' rel='".$lista_attributi[$col]."'");?>
						<?php } else if (PagesModel::isAttributoTipo($p["pages"]["id_page"], $col, "IMMAGINE")) { ?>
							<div class="uk-text-small uk-text-bold"><?php echo $lista_attributi[$col];?></div>
							<select class="image-picker uk-select form_select_attributo form_select_attributo_<?php echo encodeUrl($lista_attributi[$col]);?>" name="<?php echo $col;?>" col="<?php echo $col;?>" rel="<?php echo $lista_attributi[$col];?>">
								<?php
								$indice = 0;
								foreach ($valori_attributo as $v => $i) {
									if (!v("primo_attributo_selezionato") && $indice == 0)
									{
										$indice++;
										continue;
									}
								?>
								<option data-img-src="<?php echo $this->baseUrlSrc."/thumb/valoreattributo/".$i;?>" <?php if (getAttributoDaCarrello($col) == $v) { ?>selected="selected"<?php } ?> value="<?php echo $v;?>"><?php echo $i;?></option>
								<?php } ?>
							</select>
						<?php } else { ?>
							<div class="uk-margin uk-width-1-2@m">
								<?php echo Html_Form::select($col,getAttributoDaCarrello($col),$valori_attributo,"uk-select form_select_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]),null,"yes","col='".$col."' rel='".$lista_attributi[$col]."'");?>
							</div>
						<?php } ?>
						
					<?php } ?>
				
				<?php
				$el = $p;
				include(tpf("/Elementi/Pagine/dati_variante.php"));
				?>
			</div>
			<?php } ?>
			
			<?php if (isset($personalizzazioni) && count($personalizzazioni) > 0) { ?>
			<div class="lista_personalizzazioni_prodotto">
				<?php foreach ($personalizzazioni as $pers) { ?>
				<div class="uk-margin uk-width-1-2@m">
					<?php
					$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
					echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"]),"uk-input form_input_personalizzazione",null,"$maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			
			<div class="uk-margin uk-text-small uk-text-danger errore_combinazione"></div>
		</div>
		<?php } ?>

		<?php if (count($lista_valori_attributi) > 0) { ?>
		<div class="blocco-prezzo">
			<h5>
				<div class="price uk-text-small">
					<?php echo gtext("Prezzo");?>: <span class="amount">
					<?php if (inPromozioneTot($p["pages"]["id_page"])) { echo "<del><span class='price_full'>".setPriceReverse(calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoProdotto))."</span> </del> <strong class='price_value'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoProdotto))."</strong> €"; } else { echo "<strong class='price_value'>".setPriceReverse(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoProdotto))."</strong> €";}?></span>
					<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
					<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
					<?php } ?>
				</div>
				<div class="giacenza uk-text-small">
					<?php $qtaAcc = giacenzaPrincipale($p["pages"]["id_page"]);?>
					<?php echo gtext("Disponibilità");?>: <span class="valore_giacenza"><?php echo $qtaAcc;?></span>
					<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo", false);?></span>
					<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi", false);?></span>
				</div>
			</h5>
		</div>
		<?php } ?>
		
		<div class="uk-margin-remove">
			<input name="quantita" class="uk-input uk-form-width-xsmall quantita_input" type="<?php if (User::$isMobile) { ?>hidden<?php } else { ?>number<?php } ?>" value="<?php echo getQtaDaCarrello();?>" min="1" style="font-size: 14px;">
			<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<a name="add-to-cart" id-cart="<?php echo isset($_GET["id_cart"]) ? (int)$_GET["id_cart"] : 0;?>" rel="<?php echo $p["pages"]["id_page"];?>" class="uk-button uk-button-secondary aggiungi_al_carrello pulsante_carrello single_add_to_cart_button" href="#">
				<span>
					<?php if (idCarrelloEsistente()) { ?>
					<?php echo gtext("Aggiorna carrello", false); ?>
					<?php } else { ?>
					<?php echo gtext("Aggiungi al carrello", false); ?>
					<?php } ?>
				</span>
			</a>
		</div>
		
		<div id="whishlist" class="uk-margin blocco_wishlist">
			<div class="not_in_wishlist relative" style="<?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
				<a class="wishlist azione_wishlist uk-text-small uk-text-muted" href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>">
					<span uk-icon="icon: heart"></span> <?php echo gtext("Aggiungi alla lista dei desideri", false);?></span>
				</a>
			</div>
			
			<div class="in_wishlist relative" style="<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
				<a class="uk-text-small uk-text-muted" href="<?php echo $this->baseUrl."/wishlist/vedi";?>" rel="nofollow">
					<span class="uk-text-primary"><span uk-icon="icon: heart"></span> <?php echo gtext("Sfoglia la lista dei desideri"); ?></span>
				</a>
			</div>
		</div>
	<?php } ?>
	
	<hr class="uk-margin-medium-top">
	
	<?php echo gtext("Condividi")?>:
	<a class="bo-social-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $this->baseUrl."/$urlAlias";?>&title=<?php echo $p["pages"]["title"];?>" target="_blank" title="<?php echo gtext("Condividi su facebook");?>">
		<span uk-icon="icon: facebook"></span>
	</a>
	<a class="bo-social-twitter" href="http://twitter.com/home?status=<?php echo $p["pages"]["title"];?> <?php echo $this->baseUrl."/$urlAlias";?>" target="_blank" title="<?php echo gtext("Condividi su on Twitter");?>">
		<span uk-icon="icon: twitter"></span>
	</a>
	<a class="bo-social-tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $this->baseUrl."/$urlAlias";?>&name=<?php echo $p["pages"]["title"];?>" target="_blank" title="<?php echo gtext("Condividi su Tumblr");?>">
		<span uk-icon="icon: tumblr"></span>
	</a>
</div>

