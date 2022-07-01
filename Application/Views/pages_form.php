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
									<?php include($this->viewPath("pages_form_left"));?>
									
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
						
						<?php include($this->viewPath("pages_form_left_bottom"));?>
					</div>
					<div class='col-md-4'>
						<?php include($this->viewPath("pages_form_visibility"));?>
						
						<?php include($this->viewPath("pages_form_categorie"));?>
						
						<?php include($this->viewPath("pages_form_traduzioni"));?>
						
						<?php include($this->viewPath("pages_form_immagini"));?>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
