<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (isset($accessori)) { ?>
	<?php foreach ($accessori as $acc) { ?>
	<div class="box_accessorio box_accessorio_figlio" id-page="<?php echo $acc["pages"]["id_page"];?>" id-p="0">
		<div class="uk-margin-small">
			<input type="checkbox" name="<?php echo $acc["pages"]["id_page"];?>" value="<?php echo $acc["pages"]["id_page"];?>" <?php if (accessorioInCarrello($acc["pages"]["id_page"])) { ?>checked<?php } ?> class="input_attivo" />
			<span class="uk-text-lead uk-text-small uk-margin-small-left"><?php echo htmlentitydecode(field($acc, "description"));?></span>
		</div>
		
		<?php
			list($lista_attributi_acc, $lista_valori_attributi_acc) = selectAttributi($acc["pages"]["id_page"]);
			$personalizzazioni_acc = selectPersonalizzazioni($acc["pages"]["id_page"]);
		?>
		
		<div class="box_accessorio_inner">
			<?php if (trim($acc["pages"]["sottotitolo"])) { ?>
			<div class="uk-text-muted uk-text-small uk-margin-small">
				<?php echo field($acc, "sottotitolo");?>
			</div>
			<?php } ?>
			
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
			
			<?php if (isset($personalizzazioni_acc) && count($personalizzazioni_acc) > 0) { ?>
			<div class="lista_personalizzazioni_prodotto">
				<?php foreach ($personalizzazioni_acc as $pers) { ?>
				<div class="uk-margin uk-width-1-2@m">
					<?php
					$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
					echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"],$acc["pages"]["id_page"] ),"uk-input form_input_personalizzazione",null,"style='width:100%;' $maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			
			<div class="uk-margin uk-text-small uk-text-danger errore_combinazione"></div>
			
			<div class="blocco-prezzo-accessorio" style="display:none;">
				<h5>
					<div class="price uk-text-small">
						<?php echo gtext("Prezzo");?>: <span class="amount">
							<?php if (inPromozioneTot($acc["pages"]["id_page"])) { echo "<del>€ <span class='price_full_accessorio'>".setPriceReverse(calcolaPrezzoIvato($acc["pages"]["id_page"], $acc["pages"]["price"]))."</span> €</del> <strong class='price_value_accessorio'>".setPriceReverse(calcolaPrezzoFinale($acc["pages"]["id_page"], $acc["pages"]["price"]))."</strong> €"; } else { echo "<strong class='price_value_accessorio'>".setPriceReverse(calcolaPrezzoFinale($acc["pages"]["id_page"], $acc["pages"]["price"]))."</strong> €";}?>
						</span>
						<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
							<span class="iva_inclusa"><?php echo gtext("Iva inclusa");?></span>
						<?php } ?>
					</div>
					<div class="giacenza_acc uk-text-small">
						<?php $qtaAcc = giacenzaPrincipale($acc["pages"]["id_page"]);?>
						<?php echo gtext("Disponibilità");?>: <span class="valore_giacenza_acc"><?php echo $qtaAcc;?></span>
						<span class="sng" style='display:<?php echo $qtaAcc==1 ? "inline" : "none"; ?>'><?php echo gtext("pezzo rimasto", false);?></span>
						<span class="plu" style='display:<?php echo $qtaAcc!=1 ? "inline" : "none"; ?>'><?php echo gtext("pezzi rimasti", false);?></span>
					</div>
				</h5>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>
