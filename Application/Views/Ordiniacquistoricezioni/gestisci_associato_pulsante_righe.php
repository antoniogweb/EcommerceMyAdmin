<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<span style="display:inline-block;" select2="">
	<?php echo Html_Form::select("id_ordine_acquisto","",$ordiniDaRicevere,"select_ordine_acquisto_da_ricevere","","yes", "style='min-width:400px;' url-riga-ordine-acquisto='ordiniacquistorighe/main'");?>
</span>

<span style="display:inline-block;" select2="">
	<?php echo Html_Form::select("id_ordine_acquisto_riga","",array("0" => gtext("Seleziona riga ordine")),"form-control select_riga_ordine_acquisto_da_ricevere","","yes", "style='min-width:200px;'");?>
</span>

<button url-aggiungi="ordiniacquistorighe/main" class="submit_file btn btn-success btn-sm aggiungi_riga_a_ordine_acquisto_ricezione" type="submit" name="insertAction" value="<?php echo gtext("Aggiungi riga")?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi riga");?></button>

<button url-aggiungi="ordiniacquisto/main" class="submit_file btn btn-primary btn-sm aggiungi_ordine_acquisto_a_ordine_acquisto_ricezione" type="submit" name="insertAction" value="<?php echo gtext("Aggiungi ordine")?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi ordine");?></button>