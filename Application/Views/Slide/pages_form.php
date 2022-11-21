<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/pages_form_js.php");?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php if (strcmp($type,"update") === 0) { echo $titoloPagina; } else { echo "inserimento nuovo elemento";}?></h1>
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

								<!-- show the table -->
								<div class='scaffold_form'>
									<?php echo $form["title"];?>
									
									<?php echo $form["sottotitolo"];?>
									
									<?php echo isset($form["id_opzione"]) ? $form["id_opzione"] : "";?>
									
									<?php echo isset($form["description"]) ? $form["description"] : "";?>
									
									<?php if ($type === "update") { ?>
									<input class="varchar_input form-control" type="hidden" value="<?php echo $id_page;?>" name="id_page">
									<?php } ?>
									
									<div class="submit_entry" style="display:none;">
										<span class="submit_entry_Salva">
											<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
											<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Pulsante nella slide (call to action)")?>
							</div>
							<div class="panel-body">
								<div class='row'>
									<div class='col-md-4'>
										<?php echo $form["link_id_page"];?>
									</div>
									<div class='col-md-4'>
										<?php echo $form["link_id_c"];?>
									</div>
									<?php if (v("usa_marchi")) { ?>
									<div class='col-md-4'>
										<?php echo $form["link_id_marchio"];?>
									</div>
									<?php } ?>
									<?php if (v("usa_tag")) { ?>
									<div class='col-md-4'>
										<?php echo $form["link_id_tag"];?>
									</div>
									<?php } ?>
									<?php if (v("attiva_link_documenti")) { ?>
									<div class='col-md-4'>
										<?php echo $form["link_id_documento"];?>
									</div>
									<?php } ?>
									<div class='col-md-4'>
										<?php echo $form["url"];?>
									</div>
									<div class='col-md-4'>
										<?php echo $form["target"];?>
									</div>
									<div class='col-md-4'>
										<?php echo $form["testo_link"];?>
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
								
								<?php if (v("attiva_in_evidenza_slide")) { ?>
								<?php echo $form["in_evidenza"];?>
								<?php } ?>
							</div>
						</div>
						
						<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
							<div class="panel panel-info">
								<div class="panel-heading">
									Traduzioni
								</div>
								<div class="panel-body">
									<?php
									$nascondiAlias = true;
									$nascondiLink = true;
									include($this->viewPath("pages_traduzioni"));?>
								</div>
							</div>
						<?php } ?>
						
						<div class="panel panel-info">
							<?php
							$labelBlocco = gtext("Immagine");
							include($this->viewPath("pages_form_immagine"));?>
						</div>
						
						<?php if (v("immagine_2_in_slide")) { ?>
						<div class="panel panel-info">
							<?php
							$labelBlocco = gtext("Immagine per mobile");
							$numeroImmagine = "2";
							include($this->viewPath("pages_form_immagine"));?>
						</div>
						<?php } ?>
						
						<?php if (v("immagine_3_in_slide")) { ?>
						<div class="panel panel-info">
							<?php
							$labelBlocco = gtext("Immagine 3");
							$numeroImmagine = "3";
							include($this->viewPath("pages_form_immagine"));?>
						</div>
						<?php } ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
