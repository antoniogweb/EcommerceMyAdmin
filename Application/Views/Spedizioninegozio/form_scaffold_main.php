<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/anagrafiche_js.php")?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-12'>
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-truck"></i> <?php echo gtext("Caratteristiche spedizione");?></h4>
			
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["data_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["id_spedizioniere"];?>
				</div>
			</div>
			
			<?php if ($type === "update") { ?>
			<h4 class="text-bold" style="padding-top:10px;padding-bottom:10px;"><i class="fa fa-map-marker"></i> <?php echo gtext("Indirizzo spedizione");?></h4>
			
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["ragione_sociale"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["ragione_sociale_2"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["indirizzo"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["cap"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["nazione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["provincia"];?>
					<?php echo $form["dprovincia"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["citta"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["telefono"];?>
				</div>
			</div>
			
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<?php
			if ($type === "update")
				include($this->viewPath("form_submit_button"));
			else
				include($this->viewPath("form_submit_button_continua"));
			?>
		</div>
	</form>
</div>
