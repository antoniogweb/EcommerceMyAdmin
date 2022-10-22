<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="box_righe_prodotti_lista">
	<?php if (!User::$isMobile) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-grid-small uk-child-width-1-1 uk-child-width-1-5 uk-flex-middle uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-center">
				<?php echo gtext("Prodotto");?>
			</div>
			<div class="uk-width-expand">
				<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center uk-grid" uk-grid="">
					<div class="uk-first-column">
						<?php echo gtext("Descrizione");?>
					</div>
					<div>
						<?php echo gtext("Prezzo");?>
					</div>
					<div>
						<?php echo gtext("Quantità desiderata");?>
					</div>
					<div>
						<?php echo gtext("Regalati");?>
					</div>
					<div>
						<?php echo gtext("Rimasti");?>
					</div>
					<div>
						<?php echo gtext("Cancella");?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
	<?php foreach ($prodotti_lista as $p) {
		PagesModel::$IdCombinazione = $p["liste_regalo_pages"]["id_c"];
		
		include(tpf(ElementitemaModel::p("PRODOTTO_VARIABILI","", array(
			"titolo"	=>	"Genera le variabili del prodotto in elenco",
			"percorso"	=>	"Elementi/Prodotti/Shop/VariabiliProdotto",
		))));
		
		$urlAliasProdotto = getUrlAlias($p["liste_regalo_pages"]["id_page"], $p["liste_regalo_pages"]["id_c"]);
		$immagine = ProdottiModel::immagineCarrello($p["liste_regalo_pages"]["id_page"], $p["liste_regalo_pages"]["id_c"]);
		$attributi = CombinazioniModel::g()->getStringa($p["liste_regalo_pages"]["id_c"], "<br />", false);
		$idListaRegalo = $p["liste_regalo_pages"]["id_lista_regalo"];
		$numeroRegalati = ListeregaloModel::numeroRegalati($idListaRegalo);
		$numeroRimastiDaRegalare = ListeregaloModel::numeroRimastiDaRegalare($idListaRegalo);
	?>
	<div>
		<div class="lista-riga uk-grid-small uk-child-width-1-1@m uk-child-width-1-2 uk-child-width-1-5@m uk-child-width-2-4 <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="" id-lista-riga="<?php echo $p["liste_regalo_pages"]["id_lista_regalo_page"];?>">
			<div class="uk-first-column">
				<div class="uk-hidden@m uk-text-left">
					<a class="uk-text-danger remove cart_item_delete_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#" uk-icon="icon: close"></a>
				</div>
				<?php if ($immagine) { ?>
				<a href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>"><img width="200px" src="<?php echo $this->baseUrl."/thumb/listaregalo/".$immagine;?>" /></a>
				<?php } ?>
			</div>
			<div class="uk-width-expand">
				<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center@m uk-text-left uk-grid" uk-grid="">
					<div class="uk-first-column">
						<a class="uk-link-heading <?php if (User::$isMobile) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>"><?php echo field($p,"title");?></a>
						<?php if ($attributi) { ?>
						<div class="uk-text-meta"><?php echo $attributi;?></div>
						<?php } ?>
					</div>
					<div class="uk-text-small">
						<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Prezzo");?>:</span> <?php if (inPromozioneTot($idPr,$p)) { echo "<del>$stringaDa € ".setPriceReverse($prezzoPienoIvato)."</del> € ".setPriceReverse($prezzoFinaleIvato); } else { echo "$stringaDa € ".setPriceReverse($prezzoFinaleIvato);}?> €
					</div>
					<div>
						<?php
						$idRigaCarrello = $p["liste_regalo_pages"]["id_lista_regalo_page"];
						$quantitaRigaCarrello = $p["liste_regalo_pages"]["quantity"];
						$backColor = "#FFF";
						$mobileCallbackClass = "prodotti_lista_item_mobile";
						$increaseCallbackClass = "prodotti_lista_item_quantity_increase";
						$decreaseCallbackClass = "prodotti_lista_item_quantity_decrease";
						
						include(tpf(ElementitemaModel::p("INPUT_QUANTITA_CARRELLO","", array(
							"titolo"	=>	"Campo input di modifica della quantità",
							"percorso"	=>	"Elementi/Generali/QuantitaCarrello",
						))));
						?>
					</div>
					<div>
						<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Regalati");?>:</span> 
					</div>
					<div>
						<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Rimasti");?>:</span> 
					</div>
					<div class="uk-visible@m">
						<a class="uk-text-danger remove lista_item_delete_link" title="<?php echo gtext("Elimina il prodotto dalla lista", false);?>" href="#" uk-icon="icon: trash"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
</div> 
