<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php echo gtext("Gestione")." $tabella";?>: <?php echo $titoloPagina;?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if (!nobuttons()) { ?>
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>
			<?php } ?>
			
			<?php include($this->viewPath("categories_steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<?php if ($numeroGruppi > 0) { ?>
					<div class="callout callout-warning"><?php echo gtext("La categoria è accessibile solo agli utenti che appartengono ad uno dei seguenti gruppi.");?></div>
					
					<?php } else {  ?>
					<div class="callout callout-info"><?php echo gtext("La categoria è accessibile a tutti.");?></div>
					<?php } ?>
							
					<?php echo $notice;?>

					<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
					
						<span select2="">
							<?php echo Html_Form::select("id_group","",$listaGruppi,null,null,"yes");?>
						</span>
						
						<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
						<input type="hidden" name="insertAction" value="Aggiungi" />
					</form>
					<br />
					<div class="scaffold_form">
						<!-- show the table -->
						<div class='recordsBox'>
							<?php echo $main;?>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>


