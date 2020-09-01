<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Aggiungi una categoria a: <?php echo $titoloPagina; ?></h1>
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

					<?php echo $notice; ?>

					<div class="panel panel-info">
						<div class="panel-heading">
							Scegli categoria
						</div>
						<div class="panel-body">
							<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/aggiungicategoria/$id_page".$this->viewStatus;?>' method='POST'>
								
								<?php echo Html_Form::select("id_c","",$listaCategorie,'form_select form-control',null,"yes");?>
								<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
								
							</form>
						</div>
					</div>

                </div>
			</div>
		</div>
	</div>
</section>