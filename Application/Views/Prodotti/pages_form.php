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
			
			<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id_page".$this->viewStatus;?>">
				<div class='row'>
					<div class='col-md-8'>
						<div class="box">
							<div class="box-header with-border main">
								<?php echo $notice;?>

								<!-- show the table -->
								<div class='scaffold_form'>

									<?php echo $form["title"];?>
									<?php echo $form["alias"];?>
									<?php echo $form["sottotitolo"];?>
									
									<?php if (isset($form["price"]) && isset($form["id_iva"])) { ?>
									<div class='row'>
										<div class='col-lg-6'>
											<?php echo $form["price"];?>
										</div>
										<div class='col-lg-6'>
											<?php echo $form["id_iva"];?>
										</div>
									</div>
									<?php } ?>
									
									<?php if (isset($form["codice"])) { ?>
									<?php echo $form["codice"];?>
									<?php } ?>
									
									<?php if (isset($form["peso"])) { ?>
									<?php echo $form["peso"];?>
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
					</div>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Visibilità
							</div>
							<div class="panel-body">
								<?php echo $form["attivo"];?>
								<?php echo $form["in_evidenza"];?>
								
								<?php include($this->viewPath("pages_link"));?>
							</div>
						</div>
					</div>
					<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Traduzioni
							</div>
							<div class="panel-body">
								<?php include($this->viewPath("pages_traduzioni"));?>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class='col-md-4'>
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
					</div>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Immagine principale
							</div>
							<div class="panel-body image_panel">
								<div class="preview_image"></div>
								<?php echo $form["immagine"];?>
								<div class="cancella_immagine_box"><a title="cancella immagine" class="cancella_immagine" href="#"><span class="glyphicon glyphicon-remove"></span></a></div>
								<span class="btn btn-success fileinput-button">
									<i class="fa fa-plus"></i>
									<span>SELEZIONA IMMAGINE</span>
									<!-- The file input field used as target for the file upload widget -->
									<input id="userfile" type="file" name="Filedata">
								</span>
								<div style="display:none;margin-top:10px;" id="progress" class="progress">
									<div class="progress-bar progress-bar-success"></div>
								</div>
								<div class="alert-fileupload"></div>
							</div>
						</div>
					</div>
					<?php if (v("accessori_in_prodotti") && v("ecommerce_attivo")) { ?>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Accessorio
							</div>
							<div class="panel-body">
								<?php if (isset($form["acquistabile"])) { ?>
								<?php echo $form["acquistabile"];?>
								<?php } ?>
								
								<?php if (isset($form["aggiungi_sempre_come_accessorio"])) { ?>
								<?php echo $form["aggiungi_sempre_come_accessorio"];?>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
				
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
