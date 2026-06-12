<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<span style="display:inline-block;" select2="/magazzinoarticoli/main/1?esporta_json&formato_json=select2">
	<?php echo Html_Form::select("id_articolo","",array("0" => gtext("Seleziona articolo")),"select_articolo_ordine_acquisto","","yes", "style='min-width:400px;' url-combinazione='magazzinoarticoli/main'");?>
</span>

<span style="display:inline-block;" select2="">
	<?php echo Html_Form::select("id_c","",array("0" => gtext("Seleziona variante")),"form-control select_combinazione_ordine_acquisto","","yes", "style='min-width:200px;'");?>
</span>

<?php if (!isset($nascontiPulsanteAggiungiRiga)) { ?>
<button url-aggiungi="magazzinoarticoli/main" class="submit_file btn btn-success btn-sm aggiungi_articolo_a_ordine_acquisto" type="submit" name="insertAction" value="<?php echo gtext("Aggiungi articolo")?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi articolo");?></button>
<input type="hidden" name="insertAction" value="Aggiungi" />
<?php } ?>