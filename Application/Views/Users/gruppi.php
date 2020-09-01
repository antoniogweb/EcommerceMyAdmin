<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

$(document).ready(function(){

	
});

</script>

<section class="content-header">
	<h1>Gestione amministratore: <?php echo $titoloPagina; ?></h1>
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

					<?php include(ROOT."/Application/Views/Users/steps.php");?>

					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<div class="panel panel-info">
						<div class="panel-heading">
							Aggiungi un gruppo
						</div>
						<div class="panel-body">
							<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/users/gruppi/$id_user".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
							
								<?php echo Html_Form::select("id_group","",$listaGruppi,null,"combobox","yes");?>
								
								<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
								
							</form>
						</div>
					</div>

					<!-- show the table -->
					<div class='recordsBox'>
						<?php if ($numeroGruppi > 0) { ?>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">Questo utente amministratore non è associato ad alcun gruppo</span>
						<?php } ?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>