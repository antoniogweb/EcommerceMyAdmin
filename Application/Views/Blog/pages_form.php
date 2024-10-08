<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/pages_form_js.php");?>

<?php include($this->viewPath("pages_top"));?>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>
			
			<?php include($this->viewPath("steps"));?>
			
			<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id_page".$this->viewStatus;?>" enctype="multipart/form-data">
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
									
									<?php echo $form["alias"];?>
									
									<?php echo $form["sottotitolo"];?>
									
									<?php echo $form["data_news"];?>
									
									<?php echo isset($form["autore"]) ? $form["autore"] : "";?>
									
									<?php echo $form["description"];?>
									
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
						
						<?php if (v("attiva_richieste_ai")) { ?>
							<?php
							$labelIdEl = "id_page";
							include($this->viewPath("box_assistente_ai"));?>
						<?php } ?>

						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Video");?>
							</div>
							<div class="panel-body">
								<div class='row'>
									<div class='col-md-6'>
										<?php echo $form["video_thumb"];?>
									</div>
									<div class='col-md-6'>
										<?php echo $form["video"];?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Visibilità");?>
							</div>
							<div class="panel-body">
								<?php echo $form["attivo"];?>
								
								<?php if (isset($form["in_evidenza"])) { ?>
								<?php echo $form["in_evidenza"];?>
								<?php } ?>
								
								<?php include($this->viewPath("pages_link"));?>
							</div>
						</div>
					</div>
					<div class='col-md-4'>
						<?php include($this->viewPath("pages_form_categorie"));?>
					</div>
					<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<div class="panel-heading">
								<?php echo gtext("Traduzioni");?>
							</div>
							<div class="panel-body">
								<?php include($this->viewPath("pages_traduzioni"));?>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class='col-md-4'>
						<div class="panel panel-info">
							<?php include($this->viewPath("pages_form_immagine"));?>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
