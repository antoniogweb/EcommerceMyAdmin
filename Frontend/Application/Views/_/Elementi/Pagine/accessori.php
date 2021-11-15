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
			
			<?php include(tpf(ElementitemaModel::p("VARIANTI_ACCESSORI")));?>
			
			<?php include(tpf(ElementitemaModel::p("PERSONALIZZAZIONI_ACCESSORI")));?>
			
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
