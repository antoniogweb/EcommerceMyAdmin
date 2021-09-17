<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

$(document).ready(function(){

	
});

</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
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
				
			<div class="box">
				<div class="box-header with-border main">
					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
					
						<?php echo Html_Form::select("id_corr","",$listaProdotti,"correlati_combobox","combobox","yes");?>
						
						<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
						
					</form>
					<br />

					<!-- show the table -->
					<div class='recordsBox'>
						<?php if ($numeroCorrelati > 0) { ?>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">Non è stato associato alcun prodotto</span>
						<?php } ?>
					</div>

                </div>
			</div>
		</div>
	</div>
</section>
