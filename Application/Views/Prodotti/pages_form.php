<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/pages_form_js.php");?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php if (strcmp($type,"update") === 0) { echo $titoloPagina; } else { echo "inserimento nuovo prodotto";}?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>

			<?php include($this->viewPath("steps"));?>
			
			<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/$type/$id_page".$this->viewStatus;?>">
				<div class='row'>
					<div class='col-md-8'>
						<div class="box">
							<div class="box-header with-border main">
								<?php $flash = flash("notice");?>
								<?php echo $flash;?>
								<?php if (!$flash) echo $notice;?>

								<?php echo $avviso_combinazioni; ?>
								
								<!-- show the table -->
								<div class='scaffold_form'>

									<?php echo $form["title"];?>
									<?php echo $form["alias"];?>
									<?php echo $form["sottotitolo"];?>
									
									<?php if ((isset($form["price"]) || isset($form["price_ivato"])) && isset($form["id_iva"])) { ?>
									<div class='row'>
										<div class='col-lg-6'>
											<?php if (v("prezzi_ivati_in_prodotti")) { ?>
											<?php echo $form["price_ivato"];?>
											<?php } else { ?>
											<?php echo $form["price"];?>
											<?php } ?>
										</div>
										<div class='col-lg-6'>
											<?php echo $form["id_iva"];?>
										</div>
									</div>
									<?php } else if (isset($form["id_iva"])) { ?>
									<?php echo $form["id_iva"];?>
									<?php } ?>
									
									<?php if (isset($form["codice"])) { ?>
									<?php echo $form["codice"];?>
									<?php } ?>
									
									<?php echo $form[$campo_prezzo_fisso] ?? "";?>
									
									<?php if (isset($form["price"]) || isset($form["price_ivato"])) { ?>
									<div class='row'>
										<div class='col-lg-4'>
											<?php if (isset($form["peso"])) { ?>
											<?php echo $form["peso"];?>
											<?php } ?>
										</div>
										<div class='col-lg-4'>
											<?php if (isset($form["giacenza"])) { ?>
											<?php echo $form["giacenza"];?>
											<?php } ?>
										</div>
										<div class='col-lg-4'>
											<?php if (isset($form["gift_card"])) { ?>
											<?php echo $form["gift_card"];?>
											<?php } ?>
										</div>
									</div>
									<?php } else { ?>
									<div class='row'>
										<div class='col-lg-12'>
											<?php if (isset($form["gift_card"])) { ?>
											<?php echo $form["gift_card"];?>
											<?php } ?>
										</div>
									</div>
									<?php } ?>
									
									<?php if (isset($form["in_promozione"])) { ?>
									<?php echo $form["in_promozione"];?>
									<?php } ?>
									
									<?php if (isset($form["tipo_sconto"])) { ?>
									<?php echo $form["tipo_sconto"];?>
									<?php } ?>
									
									<?php if (isset($form["prezzo_promozione"])) { ?>
									<?php echo $form["prezzo_promozione"];?>
									<?php } ?>
									
									<?php if (isset($form[$campoPriceSconto])) { ?>
									<?php echo $form[$campoPriceSconto];?>
									<?php } ?>
									
									<?php if (isset($form["dal"])) { ?>
									<?php echo $form["dal"];?>
									<?php } ?>
									
									<?php if (isset($form["al"])) { ?>
									<?php echo $form["al"];?>
									<?php } ?>
									
									<?php if (isset($form["description"])) { ?>
										<?php echo $form["use_editor"];?>
										<?php echo $form["description"];?>
									<?php } ?>
									
									<?php echo isset($form["descrizione_2"]) ? $form["descrizione_2"] : ""; ?>
									
									<?php echo isset($form["descrizione_3"]) ? $form["descrizione_3"] : ""; ?>
									
									<?php echo isset($form["descrizione_4"]) ? $form["descrizione_4"] : ""; ?>
									
									<?php include($this->viewPath("pages_campi_aggiuntivi"));?>
									
									<?php if ($type === "update") { ?>
									<input class="varchar_input form-control" type="hidden" value="<?php echo $id_page;?>" name="id_page">
									<?php } ?>
									
									<div class="submit_entry">
										<span class="submit_entry_Salva">
											<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva");?></button>
											<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<?php if ((isset($form["acquisto_diretto"]) || v("accessori_in_prodotti")) && v("ecommerce_attivo")) { ?>
							<div class="panel panel-info help_accessori">
								<div class="panel-heading">
									<?php echo gtext("Opzioni acquisto");?>
								</div>
								<div class="panel-body">
									<?php if (isset($form["acquistabile"])) { ?>
									<?php echo $form["acquistabile"];?>
									<?php } ?>
									
									<?php if (isset($form["acquisto_diretto"])) { ?>
									<?php echo $form["acquisto_diretto"];?>
									<?php } ?>
									
									<?php if (isset($form["aggiungi_sempre_come_accessorio"])) { ?>
									<?php echo $form["aggiungi_sempre_come_accessorio"];?>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Visibilità");?>
							</div>
							<div class="panel-body">
								<?php echo $form["attivo"];?>
								<?php echo $form["in_evidenza"];?>
								
								<?php if (isset($form["test"])) { ?>
								<?php echo $form["test"];?>
								<?php } ?>
								
								<?php if (isset($form["nuovo"])) { ?>
								<?php echo $form["nuovo"];?>
								<?php } ?>
								
								<?php if (isset($form["id_p"])) { ?>
								<?php echo $form["id_p"];?>
								<?php } ?>
								
								<?php include($this->viewPath("pages_link"));?>
							</div>
						</div>
						
						<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
					
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Traduzioni");?>
							</div>
							<div class="panel-body">
								<?php include($this->viewPath("pages_traduzioni"));?>
							</div>
						</div>
					
						<?php } ?>
						
						<?php if (v("attiva_prodotti_digitali") || v("attiva_crediti")) { ?>
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Prodotti digitali");?>
							</div>
							<div class="panel-body">
								<?php echo $form["prodotto_digitale"] ?? "";?>
								
								<?php if (v("attiva_crediti")) { ?>
								<?php echo $form["prodotto_crediti"];?>
								<?php echo $form["numero_crediti"];?>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						
						<div class="panel panel-info">
							<div class="panel-heading">
								Categoria<?php if (v("usa_marchi")) { ?> / <?php echo gtext("famiglie",true,"ucfirst");?><?php } ?>
							</div>
							<div class="panel-body">
								<?php echo $form["id_c"];?>
								
								<?php if (v("usa_marchi")) { ?>
								<?php echo $form["id_marchio"];?>
								<?php } ?>
							</div>
						</div>
						
						<?php if (v("attiva_strumenti_merchant_google")) { ?>
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Informazioni per adv (Google / Facebook)")?>
							</div>
							<div class="panel-body">
								<?php echo $form["codice_categoria_prodotto_google"];?>
								
								<?php echo $form["gtin"];?>
								
								<?php echo $form["stampa_gtin_nel_feed"] ?? "";?>
								
								<?php echo $form["mpn"];?>
								
								<?php echo $form["identifier_exists"];?>
								
								<?php echo isset($form["margine"]) ? $form["margine"] : "";?>
								
								<p>
									<?php $linguaNazioneUrl = v("attiva_nazione_nell_url") ? "it_it" : "it"; ?>
									
									<?php
									$linkFeedGoogle = Domain::$name."/$linguaNazioneUrl/home/xmlprodotti?id_page=$id_page&".v("token_feed_google_facebook");
									
									if (FeedModel::getModulo("GOOGLEMERCHANT")->isAttivo())
										$linkFeedGoogle = Domain::$name . "/$linguaNazioneUrl/". FeedModel::getModulo("GOOGLEMERCHANT")->getFeedUrl()."?id_page=$id_page";
									
									$linkFeedFacebook = Domain::$name."/$linguaNazioneUrl/home/xmlprodotti?fbk&id_page=$id_page&".v("token_feed_google_facebook");
									
									if (FeedModel::getModulo("FACEBOOK", true)->isAttivo())
										$linkFeedFacebook = Domain::$name . "/$linguaNazioneUrl/". FeedModel::getModulo("FACEBOOK")->getFeedUrl()."?id_page=$id_page";
									?>
									
									<a class="label label-info" title="<?php echo gtext("Controlla il feed Facebook");?>" target="_blank" href="<?php echo $linkFeedFacebook;?>"><i class="fa fa-facebook"></i> <?php echo gtext("Facebook feed del prodotto");?></a>
									
									<a class="label label-info" title="<?php echo gtext("Controlla il feed Google");?>" target="_blank" href="<?php echo $linkFeedGoogle;?>"><i class="fa fa-google"></i> <?php echo gtext("Google feed del prodotto");?></a>
								</p>
							</div>
						</div>
						<?php } ?>
						
						<div class="panel panel-info">
							<?php include($this->viewPath("pages_form_immagine"));?>
						</div>
					</div>
					<?php include($this->viewPath("pages_form_app_box"));?>
					
					<?php if ($type === "update") { ?>
					<!--<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Categorie secondarie
							</div>
							<div class="panel-body">
								<?php if (count($altreCategorie) === 0) { ?>
								<p>La pagina non è inserita in alcuna categoria secondaria</p>
								<?php } ?>
								
								<p class="text-right"><a title="aggiungi categoria secondaria" class="iframe" href="<?php echo $this->baseUrl."/".$this->controller."/aggiungicategoria/$id_page";?>"><span class='glyphicon glyphicon-plus-sign'></span> Aggiungi</a></p>
							
								<?php if (count($altreCategorie) > 0) { ?>
								<table class="table">
									<?php foreach ($altreCategorie as $c) { ?>
									<tr><td><?php echo getCatNameForFilters($c["categories"]["id_c"]);?> ( <a rel="<?php echo $c["pages"]["id_page"];?>" class="elimina_categoria_associata" title="elimina" href="#"><span class="glyphicon glyphicon-remove"></span></a> )</td></tr>
									<?php } ?>
								</table>
								<?php }?>
							</div>
						</div>
					</div>-->
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</section>
