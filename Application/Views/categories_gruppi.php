<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione categoria: <?php echo $titoloPagina;?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<!-- show the top menù -->
					<div class='mainMenu'>
						<?php echo $menu;?>
					</div>

					<?php include($this->viewPath("categories_steps"));?>

					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<div class="panel panel-info">
						<div class="panel-heading">
							Aggiungi un gruppo
						</div>
						<div class="panel-body">
							<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
							
								<?php echo Html_Form::select("id_group","",$listaGruppi,null,"combobox","yes");?>
								
								<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
								
							</form>
						</div>
					</div>

					<!-- show the table -->
					<div class='recordsBox'>
						<?php if ($numeroGruppi > 0) { ?>
						<p><span class="empty_list">La categoria è accessibile solo agli utenti che appartengono ad uno dei seguenti gruppi</span></p>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">La categoria è accessibile a tutti</span>
						<?php } ?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>


