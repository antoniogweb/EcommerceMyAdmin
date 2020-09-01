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
			
			<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id_page".$this->viewStatus;?>">
				<div class='row'>
					<div class='col-md-8'>
						<div class="box">
							<div class="box-header with-border main">
								<?php echo $notice;?>

								<!-- show the table -->
								<div class='scaffold_form'>
									<?php echo $form["title"];?>
									
									<?php echo $form["sottotitolo"];?>
									
									<div class='row'>
										<div class='col-md-4'>
											<?php echo $form["link_id_page"];?>
										</div>
										<div class='col-md-4'>
											<?php echo $form["link_id_c"];?>
										</div>
										<div class='col-md-4'>
											<?php echo $form["url"];?>
										</div>
									</div>
									
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
					</div>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								Visibilità
							</div>
							<div class="panel-body">
								<?php echo $form["attivo"];?>
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
							<div class="panel-heading">
								Immagine
							</div>
							<div class="panel-body image_panel">
								<div class="preview_image"></div>
								<?php echo $form["immagine"];?>
								<div class="cancella_immagine_box">
									<a title="cancella immagine" class="cancella_immagine" href="#"><span class="glyphicon glyphicon-remove"></span></a>
								</div>
								<div class="scarica_immagine_box">
									<a target="_blank" title="scarica immagine" class="scarica_immagine" href="#"><span class="glyphicon glyphicon-download"></span></a>
								</div>
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
				</div>
			</form>
		</div>
	</div>
</section>
