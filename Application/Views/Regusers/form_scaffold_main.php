<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/anagrafiche_js.php")?>

<?php if (isset($appLogin)) { ?>
<div class="callout callout-info"><?php echo gtext("Questo cliente si è registrato tramite");?> <b><?php echo $appLogin["titolo"];?></b>.</div>
<?php } ?>

<?php if ($queryType == "insert" && (int)$this->viewArgs["ticket"] === 1) { ?>
<div class="callout callout-info"><?php echo gtext("Dopo che avrai concluso la creazione del nuovo cliente, verrai reindirizzato al nuovo ticket legato a tale cliente.");?></div>
<?php } ?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data" autocomplete="new-password">
		<div class='col-md-12'>
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-user"></i> <?php echo gtext("Fatturazione");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["tipo_cliente"];?>
				</div>
				<?php if (isset($form["id_ruolo"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_ruolo"];?>
				</div>
				<?php } ?>
				<?php if (isset($form["id_tipo_azienda"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_tipo_azienda"];?>
				</div>
				<?php } ?>
				<div class='col-md-3 ragione_sociale'>
					<?php echo $form["ragione_sociale"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["nome"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["cognome"];?>
				</div>
				<?php if (isset($form["fattura"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["fattura"];?>
				</div>
				<?php } ?>
				<div class='col-md-3 p_iva'>
					<?php echo $form["p_iva"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["codice_fiscale"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["nazione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["indirizzo"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["cap"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["provincia"];?>
					<?php echo $form["dprovincia"];?>
				</div>
				<?php if (isset($form["id_regione"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_regione"];?>
				</div>
				<?php } ?>
				<div class='col-md-3'>
					<?php echo $form["citta"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["username"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["telefono"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["telefono_2"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["pec"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["codice_destinatario"];?>
				</div>
			</div>
			
			<?php include($this->viewPath("form_submit_button"));?><br />
		</div>
		<div class='col-md-12'>
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-lock"></i> <?php echo gtext("Gestione account");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["has_confirmed"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["password"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["confirmation"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["lingua"];?>
				</div>
				<?php if (isset($form["agente"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["agente"];?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php if (v("attiva_classi_sconto")) { ?>
		<div class='col-md-12'>
			<br /><h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-money"></i> <?php echo gtext("Scontistica");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["id_classe"];?>
				</div>
			</div>
		</div>
		<?php } ?>
	</form>
</div>
