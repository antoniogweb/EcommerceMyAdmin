<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script>var sconto = <?php echo $p["pages"]["prezzo_promozione"];?></script>
<?php if (count($lista_valori_attributi) > 0) { ?>
<div class="lista_attributi_prodotto">

		<?php foreach ($lista_valori_attributi as $col => $valori_attributo) { ?>
			
			<label style="display:none;" class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl($lista_attributi[$col]);?>"><?php echo $lista_attributi[$col];?></label>
			<?php if (PagesModel::isRadioAttributo($p["pages"]["id_page"], $col)) { ?>
				<?php echo Html_Form::radio($col,getAttributoDaCarrello($col),$valori_attributo,"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]), "after", null, "yes", "col='".$col."' rel='".$lista_attributi[$col]."'");?>
			<?php } else if (PagesModel::isAttributoTipo($p["pages"]["id_page"], $col, "IMMAGINE")) { ?>
				<div class="uk-text-small uk-text-bold"><?php echo $lista_attributi[$col];?></div>
				<select class="image-picker uk-select form_select_attributo form_select_attributo_<?php echo encodeUrl($lista_attributi[$col]);?>" name="<?php echo $col;?>" col="<?php echo $col;?>" rel="<?php echo $lista_attributi[$col];?>">
					<?php
					$indice = 0;
					foreach ($valori_attributo as $v => $i) {
						if (!v("primo_attributo_selezionato") && $indice == 0)
						{
							$indice++;
							continue;
						}
					?>
					<option data-img-src="<?php echo $this->baseUrlSrc."/thumb/valoreattributo/".$i;?>" <?php if (getAttributoDaCarrello($col) == $v) { ?>selected="selected"<?php } ?> value="<?php echo $v;?>"><?php echo $i;?></option>
					<?php } ?>
				</select>
			<?php } else { ?>
				<div class="uk-margin-small uk-width-2-3@m">
					<?php echo Html_Form::select($col,getAttributoDaCarrello($col),$valori_attributo,"uk-select form_select_attributo form_select_attributo_".encodeUrl($lista_attributi[$col]),null,"yes","col='".$col."' rel='".$lista_attributi[$col]."'");?>
				</div>
			<?php } ?>
			
		<?php } ?>
	
	<?php
	$el = $p;
	include(tpf("/Elementi/Pagine/dati_variante.php"));
	?>
</div>
<?php } ?>
