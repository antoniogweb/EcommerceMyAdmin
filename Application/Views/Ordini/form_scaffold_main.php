<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/anagrafiche_js.php")?>

<div class='row' style="position:relative;">
	<div id="fragment_form" style="position:absolute;top:-120px;"></div>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-12'>
			<?php if (v("permetti_ordini_offline") && $id && !OrdiniModel::g()->isDeletable($id)) {
				$tipoOrdine = OrdiniModel::tipoOrdine((int)$id);
			?>
			<div class="callout callout-info">
				<?php if ($tipoOrdine == "W") { ?>
				<?php echo gtext("I totali non verranno modificati al salvataggio in quanto è un ordine di tipo");?> <b><?php echo OrdiniModel::getLabelTipoOrdine($tipoOrdine);?></b></div>
				<?php } else { ?>
				<?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più ad uno dei seguenti stati");?>: <b><?php echo StatiordineModel::getTitoliStati(v("stati_ordine_editabile_ed_eliminabile"));?></b></div>
				<?php } ?>
			<?php } ?>
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-user"></i> <?php echo gtext("Fatturazione");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<?php if (isset($form["id_user"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_user"];?>
				</div>
				<?php } ?>
				<div class='col-md-3'>
					<?php echo $form["tipo_cliente"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["nome"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["cognome"];?>
				</div>
				<div class='col-md-3 ragione_sociale'>
					<?php echo $form["ragione_sociale"];?>
				</div>
				<div class='col-md-3 p_iva'>
					<?php echo $form["p_iva"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["codice_fiscale"];?>
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
				<div class='col-md-3'>
					<?php echo $form["email"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["pec"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["codice_destinatario"];?>
				</div>
			</div>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<div class="submit_entry">
				<span class="submit_entry_Salva">
					<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva");?></button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
			</div>
		</div>
		<div class='col-md-12'>
			<br />
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-truck"></i> <?php echo gtext("Spedizione");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<?php if (isset($form["id_spedizione"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_spedizione"];?>
				</div>
				<?php } ?>
				<div class='col-md-3'>
					<?php echo $form["indirizzo_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["cap_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["nazione_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["provincia_spedizione"];?>
					<?php echo $form["dprovincia_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["citta_spedizione"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["telefono_spedizione"];?>
				</div>
				<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione")) { ?>
				<div class='col-md-3'>
					<?php echo $form["destinatario_spedizione"];?>
				</div>
				<?php } ?>
				<?php if (isset($form["id_corriere"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_corriere"];?>
				</div>
				<?php } ?>
				<?php if (isset($form["id_spedizioniere"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_spedizioniere"];?>
				</div>
				<?php } ?>
				<?php if (isset($form["link_tracking"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["link_tracking"];?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class='col-md-12'>
			<br />
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-cogs"></i> <?php echo gtext("Opzioni ordine");?></h4>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<div class='col-md-3'>
					<?php echo $form["stato"];?>
				</div>
				<div class='col-md-3'>
					<?php echo $form["pagamento"];?>
				</div>
				<?php if (isset($form["id_iva"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_iva"];?>
				</div>
				<?php } ?>
				<?php if (isset($form["id_p"])) { ?>
				<div class='col-md-3'>
					<?php echo $form["id_p"];?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class='col-md-6'>
			<br />
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-font"></i> <?php echo gtext("Note");?></h4>
			<div class='row'>
				<div class='col-md-12'>
					<?php echo $form["note"];?>
					<?php echo $form["note_interne"];?>
				</div>
			</div>
		</div>
		<?php if (v("attiva_liste_regalo") && (isset($form["dedica"]) || isset($form["firma"]))) { ?>
		<div class='col-md-6'>
			<br />
			<h4 class="text-bold" style="padding-bottom:10px;"><i class="fa fa-gift"></i> <?php echo gtext("Dedica e firma");?></h4>
			<div class='row'>
				<div class='col-md-12'>
					<?php echo $form["dedica"] ?? "";?>
					<?php echo $form["firma"] ?? "";?>
				</div>
			</div>
		</div>
		<?php } ?>
	</form>
</div>
