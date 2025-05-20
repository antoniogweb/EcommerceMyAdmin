<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($prodotti_lista) > 0) { ?>
	<div class="">
		<?php if (!User::$isMobile) { ?>
		<div class="uk-visible@m">
			<div class="uk-text-meta uk-grid-small uk-child-width-1-5 uk-flex-middle uk-grid" uk-grid="">
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
							<?php echo gtext("Quantità da acquistare");?>
						</div>
						<div>
							<?php echo gtext("Quantità desiderata");?>
						</div>
						<div>
							<?php echo gtext("Regalati");?>
						</div>
						<div>
							<?php echo gtext("Regala");?>
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
			$numeroDesiderati = ListeregaloModel::numeroProdotti($idListaRegalo, $p["liste_regalo_pages"]["id_c"]);
			$numeroRegalati = ListeregaloModel::numeroRegalati($idListaRegalo, $p["liste_regalo_pages"]["id_c"]);
			$numeroRimastiDaRegalare = ListeregaloModel::numeroRimastiDaRegalare($idListaRegalo, $p["liste_regalo_pages"]["id_c"]);
			$acquistabile = (acquistabile($p["liste_regalo_pages"]["id_page"]) && $p["pages"]["attivo"] == "Y");
		?>
		<div class="accessorio_principale">
			<div class="id_combinazione uk-hidden"><?php echo $p["liste_regalo_pages"]["id_c"];?></div>
			<div class="lista-riga uk-grid-small uk-child-width-1-2 uk-child-width-1-5@m <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="" id-lista-riga="<?php echo $p["liste_regalo_pages"]["id_lista_regalo_page"];?>">
				<div class="uk-first-column">
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
							<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Prezzo");?>:</span> <?php if ($prezzoPienoIvato != $prezzoFinaleIvato) { echo "<del>$stringaDa € ".setPriceReverse($prezzoPienoIvato)."</del> € ".setPriceReverse($prezzoFinaleIvato); } else { echo "$stringaDa € ".setPriceReverse($prezzoFinaleIvato);}?> €
						</div>
						<div class="uk-text-small">
							<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Quantita da acquistare");?>:</span>
							<?php
							$idRigaCarrello = $p["liste_regalo_pages"]["id_lista_regalo_page"];
							$quantitaRigaCarrello = ($numeroRimastiDaRegalare > 0) ? 1 : 0;
							$backColor = "#FFF";
							$mobileCallbackClass = "generic_item_mobile";
							$increaseCallbackClass = "generic_item_quantity_increase";
							$decreaseCallbackClass = "generic_lista_item_quantity_decrease";
							$qtaMax = $qtaMaxSelect = $numeroRimastiDaRegalare;
							
							include(tpf(ElementitemaModel::p("INPUT_QUANTITA_CARRELLO","", array(
								"titolo"	=>	"Campo input di modifica della quantità",
								"percorso"	=>	"Elementi/Generali/QuantitaCarrello",
							))));
							?>
						</div>
						<div class="uk-text-small">
							<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Quantita desiderata");?>:</span> <?php echo $numeroDesiderati;?>
						</div>
						<div class="uk-text-small">
							<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Regalati");?>:</span> <?php echo $numeroRegalati;?>
						</div>
						<div>
							<?php if ($numeroRimastiDaRegalare > 0) { ?>
								<?php if (CombinazioniModel::acquistabile($p["liste_regalo_pages"]["id_c"]) && $acquistabile) { ?>
								<div class="uk-button uk-button-primary uk-button-small spinner uk-hidden" uk-spinner="ratio: .70"></div>
								<a id-lista="<?php echo $idListaRegalo;?>" rel="<?php echo $p["liste_regalo_pages"]["id_page"];?>" class="uk-button uk-button-primary uk-button-small aggiungi_al_carrello_lista" title="<?php echo gtext("Acquista il prodotto", false);?>" href="#"><?php echo gtext("Acquista", false);?></a>
								<?php } else { ?>
									<span class="uk-text-secondary uk-text-small uk-text-bold"><?php echo gtext("Il prodotto non è più acquistabile");?></span>
								<?php } ?>
							<?php } else { ?>
								<span class="uk-text-secondary uk-text-small uk-text-bold"><?php echo gtext("Già regalato");?></span>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="uk-text-center"><?php echo gtext("Questa lista non contiene alcun prodotto da regalare.");?></div>
<?php } ?>
