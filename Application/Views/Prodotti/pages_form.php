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
									
									<?php if (isset($form["price"]) || isset($form["price_ivato"])) { ?>
									<div class='row'>
										<div class='col-lg-6'>
											<?php if (isset($form["peso"])) { ?>
											<?php echo $form["peso"];?>
											<?php } ?>
										</div>
										<div class='col-lg-6'>
											<?php if (isset($form["giacenza"])) { ?>
											<?php echo $form["giacenza"];?>
											<?php } ?>
										</div>
									</div>
									<?php } ?>
									
									<?php if (isset($form["in_promozione"])) { ?>
									<?php echo $form["in_promozione"];?>
									<?php } ?>
									
									<?php if (isset($form["prezzo_promozione"])) { ?>
									<?php echo $form["prezzo_promozione"];?>
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
									
									<?php include($this->viewPath("pages_campi_aggiuntivi"));?>
									
									<?php if ($type === "update") { ?>
									<input class="varchar_input form-control" type="hidden" value="<?php echo $id_page;?>" name="id_page">
									<?php } ?>
									
									<div class="submit_entry">
										<span class="submit_entry_Salva">
											<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
											<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<?php if ((isset($form["acquisto_diretto"]) || v("accessori_in_prodotti")) && v("ecommerce_attivo")) { ?>
							<div class="panel panel-info help_accessori">
								<div class="panel-heading">
									Opzioni acquisto
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
								Visibilità
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
								Traduzioni
							</div>
							<div class="panel-body">
								<?php include($this->viewPath("pages_traduzioni"));?>
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
								
								<?php if (isset($form["codice_categoria_prodotto_google"])) { ?>
								<?php echo $form["codice_categoria_prodotto_google"];?>
								<?php } ?>
							</div>
						</div>
					
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
