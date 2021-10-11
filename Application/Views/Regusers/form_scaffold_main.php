<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/anagrafiche_js.php")?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data" autocomplete="new-password">
		<div class='col-md-8'>
			<?php echo $form["tipo_cliente"];?>
			<?php if (isset($form["id_ruolo"])) { ?>
			<?php echo $form["id_ruolo"];?>
			<?php } ?>
			<?php if (isset($form["id_tipo_azienda"])) { ?>
			<?php echo $form["id_tipo_azienda"];?>
			<?php } ?>
			<?php echo $form["ragione_sociale"];?>
			<?php echo $form["nome"];?>
			<?php echo $form["cognome"];?>
			<?php echo $form["p_iva"];?>
			<?php echo $form["codice_fiscale"];?>
			<?php echo $form["nazione"];?>
			<?php echo $form["indirizzo"];?>
			<?php echo $form["cap"];?>
			<?php echo $form["provincia"];?>
			<?php echo $form["citta"];?>
			<?php echo $form["telefono"];?>
			<?php echo $form["telefono_2"];?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<div class="submit_entry">
				<span class="submit_entry_Salva">
					<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
			</div>
		</div>
		<div class='col-md-4'>
			<?php if (v("attiva_classi_sconto")) { ?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Scontistica
				</div>
				<div class="panel-body">
					<?php echo $form["id_classe"];?>
				</div>
			</div>
			<?php } ?>
			
			<div class="panel panel-info">
				<div class="panel-heading">
					Fattura elettronica
				</div>
				<div class="panel-body">
					<?php echo $form["pec"];?>
					<?php echo $form["codice_destinatario"];?>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					Account
				</div>
				<div class="panel-body">
					<?php echo $form["username"];?>
					<?php echo $form["has_confirmed"];?>
					<?php echo $form["password"];?>
					<?php echo $form["confirmation"];?>
					<?php echo $form["lingua"];?>
				</div>
			</div>
		</div>
	</form>
</div>
