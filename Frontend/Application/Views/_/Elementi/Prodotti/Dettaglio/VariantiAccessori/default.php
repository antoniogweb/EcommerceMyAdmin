<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($lista_valori_attributi_acc) > 0) { ?>
<div class="lista_attributi_prodotto">
	<?php foreach ($lista_valori_attributi_acc as $col => $valori_attributo) { ?>
		<label style="display:none;" class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl($lista_attributi_acc[$col]);?>"><?php echo $lista_attributi_acc[$col];?></label>
			<?php if (PagesModel::isRadioAttributo($acc["pages"]["id_page"], $col)) { ?>
				<?php echo Html_Form::radio($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),$valori_attributo,"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]), "after", null, "yes", "col='".$col."' rel='".$lista_attributi_acc[$col]."'");?>
			<?php } else if (PagesModel::isAttributoTipo($acc["pages"]["id_page"], $col, "IMMAGINE")) { ?>
				<div class="uk-text-small uk-text-bold"><?php echo $lista_attributi_acc[$col];?></div>
				<select class="image-picker uk-select form_select_attributo form_select_attributo_<?php echo encodeUrl($lista_attributi_acc[$col]);?>" name="<?php echo $col.$acc["pages"]["id_page"];?>" col="<?php echo $col;?>" rel="<?php echo $lista_attributi_acc[$col];?>">
					<?php
					$indice = 0;
					foreach ($valori_attributo as $v => $i) {
						if (!v("primo_attributo_selezionato") && $indice == 0)
						{
							$indice++;
							continue;
						}
					?>
					<option data-img-src="<?php echo $this->baseUrlSrc."/thumb/valoreattributo/".$i;?>" <?php if (getAttributoDaCarrello($col, $acc["pages"]["id_page"]) == $v) { ?>selected="selected"<?php } ?> value="<?php echo $v;?>"><?php echo $i;?></option>
					<?php } ?>
				</select>
			<?php } else { ?>
				<div class="uk-margin uk-width-1-2@m">
					<?php echo Html_Form::select($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),$valori_attributo,"uk-select form_select_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]),null,"yes","style='width:100%;' col='".$col."' rel='".$lista_attributi_acc[$col]."'");?>
				</div>
			<?php } ?>
	<?php } ?>
	<?php
	$el = $acc;
	include(tpf("/Elementi/Pagine/dati_variante.php"));
	?>
</div>
<?php } ?> 
