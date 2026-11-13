<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($classeTendinaVarianti))
	$classeTendinaVarianti = "uk-margin uk-width-1-2@m";
?>
<?php if (count($lista_valori_attributi_acc) > 0) { ?>
<div class="lista_attributi_prodotto">
	<?php foreach ($lista_valori_attributi_acc as $col => $valori_attributo) {
		$tipoAttributo = PagesModel::getAttributoDaCol($acc["pages"]["id_page"], $col);
		$tipoAttributo = isset($tipoAttributo["tipo"]) ? $tipoAttributo["tipo"] : "TENDINA";
		$idVarianteAcc = "variante-accessorio-".(int)$acc["pages"]["id_page"]."-".encodeUrl($col);
		$idDescrizioneVarianteAcc = "descrizione-variante-accessorio-".(int)$acc["pages"]["id_page"]."-".encodeUrl($col);
	?>
		<span id="<?php echo $idDescrizioneVarianteAcc;?>" class="accessibility-visually-hidden"><?php echo $lista_attributi_acc[$col];?></span>
			<?php if ($tipoAttributo == "RADIO") { ?>
				<span role="group" aria-labelledby="<?php echo $idDescrizioneVarianteAcc;?>">
				<?php foreach ($valori_attributo as $valoreVarianteAcc => $titoloVarianteAcc) {
					$idRadioVarianteAcc = "variante-radio-accessorio-".(int)$acc["pages"]["id_page"]."-".encodeUrl($col)."-".encodeUrl($valoreVarianteAcc);
					echo Html_Form::radio($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),array($titoloVarianteAcc => $valoreVarianteAcc),"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]), "none", $idRadioVarianteAcc, null, "col='".$col."' rel='".$lista_attributi_acc[$col]."'");
				?><label for="<?php echo $idRadioVarianteAcc;?>"><?php echo $titoloVarianteAcc;?></label><?php } ?>
				</span>
			<?php } else if ($tipoAttributo == "IMMAGINE" || $tipoAttributo == "COLORE") {
				$actionImmagine = ($tipoAttributo == "IMMAGINE") ? "valoreattributo" : "colore";
			?>
				<div class="box_attributo_immagine_colore box_attributo_<?php echo strtolower($tipoAttributo);?>">
					<label for="<?php echo $idVarianteAcc;?>" class="uk-text-small uk-text-bold">
						<?php echo $lista_attributi_acc[$col];?>
						<?php if ($tipoAttributo == "COLORE") { ?>
						: <span class="uk-text-meta label_variante_colore"></span>
						<?php } ?>
					</label>
					<select id="<?php echo $idVarianteAcc;?>" class="image-picker uk-select form_select_attributo form_select_attributo_<?php echo encodeUrl($lista_attributi_acc[$col]);?>" name="<?php echo $col.$acc["pages"]["id_page"];?>" col="<?php echo $col;?>" rel="<?php echo $lista_attributi_acc[$col];?>">
						<?php
						$indice = 0;
						foreach ($valori_attributo as $v => $i) {
							if (!v("primo_attributo_selezionato") && $indice == 0)
							{
								$indice++;
								continue;
							}
							
							if ($tipoAttributo == "COLORE")
								$i = str_replace("#","",$i);
						?>
						<option data-img-title='<?php echo AttributivaloriModel::g(false)->getName($v);?>' data-img-src="<?php echo $this->baseUrlSrc."/thumb/$actionImmagine/".$i;?>" <?php if (getAttributoDaCarrello($col, $acc["pages"]["id_page"]) == $v) { ?>selected="selected"<?php } ?> value="<?php echo $v;?>"><?php echo $i;?></option>
						<?php } ?>
					</select>
				</div>
			<?php } else { ?>
				<div class="<?php echo $classeTendinaVarianti;?>">
					<label for="<?php echo $idVarianteAcc;?>" class="accessibility-visually-hidden"><?php echo $lista_attributi_acc[$col];?></label>
					<?php echo Html_Form::select($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),$valori_attributo,"uk-select form_select_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]),$idVarianteAcc,"yes","style='width:100%;' col='".$col."' rel='".$lista_attributi_acc[$col]."'");?>
				</div>
			<?php } ?>
	<?php } ?>
	<?php
	$el = $acc;
	include(tpf("/Elementi/Pagine/dati_variante.php"));
	?>
</div>
<?php } ?> 
