<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php foreach ($accessori as $acc) { ?>
<div class="box_accessorio box_accessorio_figlio" id-page="<?php echo $acc["pages"]["id_page"];?>" id-p="0">
	<input type="checkbox" name="<?php echo $acc["pages"]["id_page"];?>" value="<?php echo $acc["pages"]["id_page"];?>" <?php if (accessorioInCarrello($acc["pages"]["id_page"])) { ?>checked<?php } ?> class="input_attivo" />
	<?php echo field($acc, "title");?>
	<?php
		list($lista_attributi_acc, $lista_valori_attributi_acc) = selectAttributi($acc["pages"]["id_page"]);
		$personalizzazioni_acc = selectPersonalizzazioni($acc["pages"]["id_page"]);
	?>
	
	<div class="box_accessorio_inner">
		<?php if (count($lista_valori_attributi_acc) > 0) { ?>
		<div class="lista_attributi_prodotto">
			<table class="variations">
				<?php foreach ($lista_valori_attributi_acc as $col => $valori_attributo) { ?>
				<tr>
					<td class="label" style="width:40%;"><label class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl($lista_attributi_acc[$col]);?>"><?php echo $lista_attributi_acc[$col];?></label></td>
					
					<td>
						<?php if (!PagesModel::isRadioAttributo($acc["pages"]["id_page"], $col)) { ?>
							<?php echo Html_Form::select($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),$valori_attributo,"form_select_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]),null,"yes","style='width:100%;' col='".$col."' rel='".$lista_attributi_acc[$col]."'");?>
						<?php } else { ?>
							<?php echo Html_Form::radio($col.$acc["pages"]["id_page"],getAttributoDaCarrello($col, $acc["pages"]["id_page"]),$valori_attributo,"form_radio_attributo form_select_attributo_".encodeUrl($lista_attributi_acc[$col]), "after", null, "yes", "col='".$col."' rel='".$lista_attributi_acc[$col]."'");?>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
			
			<?php
			$el = $acc;
			include(ROOT."/Application/Views/Contenuti/Elementi/Pagine/dati_variante.php");
			?>
		</div>
		<?php } ?>
		
		<?php if (count($personalizzazioni_acc) > 0) { ?>
		<div class="lista_personalizzazioni_prodotto">
			<table class="variations">
				<?php foreach ($personalizzazioni_acc as $pers) { ?>
				<tr>
					<td class="label" style="width:40%;"><label class="pa_size nome_attributo nome_attributo_<?php echo encodeUrl(persfield($pers, "titolo"));?>"><?php echo persfield($pers, "titolo");?></label></td>
					<td>
						<?php
						$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
						echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"],$acc["pages"]["id_page"] ),"form_input_personalizzazione",null,"style='width:100%;' $maxLength rel='".persfield($pers, "titolo")."'");?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
		
		<div class="errore_combinazione"></div>
		
		<div class="blocco-prezzo-accessorio">
			<p class="price">
				<span class="amount">
					<?php if (inPromozioneTot($acc["pages"]["id_page"])) { echo "<del>€ <span class='price_full_accessorio'>€ ".setPriceReverse(calcolaPrezzoIvato($acc["pages"]["id_page"], $acc["pages"]["price"]))."</span></del> € <span class='price_value_accessorio'>".setPriceReverse(calcolaPrezzoFinale($acc["pages"]["id_page"], $acc["pages"]["price"]))."</span>"; } else { echo "€ <span class='price_value_accessorio'>".setPriceReverse(calcolaPrezzoFinale($acc["pages"]["id_page"], $acc["pages"]["price"]))."</span>";}?>
				</span>
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
					<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
				<?php } ?>
			</p>
			<p class="giacenza_acc">
				<?php $qtaAcc = giacenzaPrincipale($acc["pages"]["id_page"]);?>
				<span class="valore_giacenza_acc"><?php echo $qtaAcc;?></span>
				<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo rimasto", false);?></span>
				<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi rimasti", false);?></span>
			</p>
		</div>
	</div>
</div>
<?php } ?>
