<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$prezzoProdotto = $prezzoMinimo;
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
?>

<div class="info-product">
	<h1><?php echo field($p, "title");?></h1>
	<div class="">
		<?php if (count($lista_valori_attributi) > 0) { ?><div class="blocco-prezzo"><?php } ?>
		<div class="uk-flex uk-flex-left uk-margin-small">
			<div class="uk-text-bold uk-margin-small-right"><span class="price_value"><?php echo setPriceReverse($prezzoFinaleIvato);?></span>€</div>
			<?php if (inPromozioneTot($p["pages"]["id_page"])) { ?>
			<div class="uk-text-muted uk-margin-small-right" style="text-decoration:line-through;"><span class="price_full"><?php echo setPriceReverse($prezzoPienoIvato);?></span>€</div>
			<?php } ?>
			
			<?php if (getPercSconto($prezzoPienoIvato, $prezzoFinaleIvato) > 0) { ?>
			<div class="uk-margin-small-right uk-text-bold"><?php echo getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);?>%</div>
			<?php } ?>
		</div>
		<?php if (count($lista_valori_attributi) > 0) { ?></div><?php } ?>
		
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
		$arrayInfo[ucfirst(gtext("codice"))] = array($p["pages"]["codice"],"codice_value", "");
	
// 	if ($p["pages"]["peso"] > 0)
		$arrayInfo[ucfirst(gtext("peso"))] = array((setPriceReverse($p["pages"]["peso"])),"peso_value", " kg");
	
	if (isset($marchioCorrente) && count($marchioCorrente) > 0)
		$arrayInfo[ucfirst(gtext("marchio"))] = array("<a href='".$this->baseUrl."/".getMarchioUrlAlias($marchioCorrente["marchi"]["id_marchio"])."'>".mfield($marchioCorrente,"titolo")."</a>","", "");
	?>
	
	<div class="uk-margin">
		<ul class="uk-list uk-text-small uk-margin-remove">
			<?php foreach ($arrayInfo as $k => $v) { ?>
			<li><span class="uk-text-muted"><?php echo $k;?>: </span><span class="<?php echo $v[1];?>"><?php echo $v[0];?></span><?php echo $v[2];?></li>
			<?php } ?>
			<li <?php if (count($lista_valori_attributi) > 0) { ?>class="blocco-prezzo"<?php } ?>>
				<?php $qtaAcc = giacenzaPrincipale($p["pages"]["id_page"]);?>
				<span class="uk-text-muted"><?php echo gtext("Disponibilità");?>: </span><span class="valore_giacenza"><?php echo $qtaAcc;?></span>
				<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo", false);?></span>
				<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi", false);?></span>
			</li>
		</ul>
	</div>
	
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
		<?php if (!User::$isMobile) { ?>
		<div class="uk-width-1-1 uk-width-2-3@m">
			<div class="uk-grid-small uk-text-right uk-flex uk-flex-middle" uk-grid>
				<div class="uk-width-3-4 uk-text-small">
					<?php echo gtext("Quantità");?>:
				</div>
				<div class="uk-width-1-4">
					<input name="quantita" class="uk-input uk-form-width-xsmall quantita_input" type="number" value="<?php echo getQtaDaCarrello();?>" min="1" style="font-size: 14px;">
				</div>
			</div>
		</div>
		<?php } else { ?>
			<input name="quantita" class="uk-input quantita_input" type="hidden" value="<?php echo getQtaDaCarrello();?>" min="1" style="font-size: 14px;">
		<?php } ?>
		
		<?php if (count($lista_valori_attributi) > 0 || (isset($personalizzazioni) && count($personalizzazioni) > 0)) { ?>
		<div class="box_accessorio accessorio_principale">
			<?php include(tpf(ElementitemaModel::p("VARIANTI")));?>
			
			<?php include(tpf(ElementitemaModel::p("PERSONALIZZAZIONI")));?>
			
			<div class="uk-margin-small uk-text-small uk-text-danger errore_combinazione"></div>
		</div>
		<?php } ?>
		
		<div class="uk-text-small uk-text-danger errore_giacenza"></div>
		
		<div class="uk-margin-small uk-width-1-1 uk-width-2-3@m">
			<div class="uk-width-1-1 uk-button uk-button-default spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<a name="add-to-cart" id-cart="<?php echo isset($_GET["id_cart"]) ? (int)$_GET["id_cart"] : 0;?>" rel="<?php echo $p["pages"]["id_page"];?>" class="uk-width-1-1 uk-button uk-button-default aggiungi_al_carrello pulsante_carrello single_add_to_cart_button" href="#">
				<span>
					<?php if (idCarrelloEsistente()) { ?>
					<?php echo gtext("Aggiorna carrello", false); ?>
					<?php } else { ?>
					<?php echo gtext("Aggiungi al carrello", false); ?>
					<?php } ?>
				</span>
			</a>
		</div>
		
		<?php if (!idCarrelloEsistente()) { ?>
		<div class="uk-width-1-1 uk-width-2-3@m">
			<div class="uk-width-1-1 uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<button id="acquista" class="uk-width-1-1 uk-button uk-button-secondary acquista_prodotto"><?php echo gtext("Acquista ora");?></button>
		</div>
		<?php } ?>
		
		<?php include(tpf(ElementitemaModel::p("WISHLIST_DETTAGLIO")));?>
	<?php } ?>
	
	<hr class="uk-margin-medium-top">
	
	<?php include(tpf(ElementitemaModel::p("CONDIVIDI_DETTAGLIO")));?>
</div>

