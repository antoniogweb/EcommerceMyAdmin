<?php
if (isset($filtri))
	echo $filtri;
?>

<?php
$fornitori = array(0 => gtext("Seleziona un fornitore")) + FornitoriModel::g()->clear()->select("id_fornitore,ragione_sociale")->orderBy("ragione_sociale")->toList("id_fornitore", "ragione_sociale")->send();
?>

<div class="blocco_crea_ordine_acquisto_da_ordinare" style="display:none;margin-top:10px;">
	<div class="form-inline">
		<div class="form-group">
			<?php echo Html_Form::select("id_fornitore_ordine", "", $fornitori, "form-control", null, "yes");?>
		</div>
		<button type="button" class="btn btn-success crea_ordine_acquisto_da_ordinare">
			<i class="fa fa-plus"></i> <?php echo gtext("Crea ordine");?>
		</button>
	</div>
</div>
