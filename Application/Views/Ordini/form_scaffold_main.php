<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/anagrafiche_js.php")?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-6'>
			<?php if (isset($form["id_user"])) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo gtext("Account");?>
				</div>
				<div class="panel-body">
					<?php echo $form["id_user"];?>
				</div>
			</div>
			<?php } ?>
			<!--<div class="panel panel-default">
				<div class="panel-heading">
					Fatturazione
				</div>
				<div class="panel-body">-->
					
					<?php echo $form["tipo_cliente"];?>
					<?php echo $form["nome"];?>
					<?php echo $form["cognome"];?>
					<?php echo $form["ragione_sociale"];?>
					<?php echo $form["p_iva"];?>
					<?php echo $form["codice_fiscale"];?>
					<?php echo $form["indirizzo"];?>
					<?php echo $form["cap"];?>
					<?php echo $form["nazione"];?>
					<?php echo $form["provincia"];?>
					<?php echo $form["citta"];?>
					<?php echo $form["telefono"];?>
					<?php echo $form["email"];?>
					
					<?php if ($type === "update") { ?>
					<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
					<?php } ?>
					
					<div class="submit_entry">
						<span class="submit_entry_Salva">
							<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
							<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
						</span>
					</div>
				<!--</div>
			</div>-->
		</div>
		<div class='col-md-6'>
			<div class="panel panel-default">
				<div class="panel-heading">
					Opzioni ordine
				</div>
				<div class="panel-body">
					<?php echo $form["stato"];?>
					
					<?php echo $form["pagamento"];?>
				</div>
			</div>
			
			<div class="panel panel-default">
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
					Spedizione
				</div>
				<div class="panel-body">
					<?php echo $form["indirizzo_spedizione"];?>
					<?php echo $form["cap_spedizione"];?>
					<?php echo $form["nazione_spedizione"];?>
					<?php echo $form["provincia_spedizione"];?>
					<?php echo $form["citta_spedizione"];?>
					<?php echo $form["telefono_spedizione"];?>
				</div>
			</div>
		</div>
	</form>
</div>
