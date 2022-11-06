<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="filtri-categoria" <?php if (User::$isMobile) { ?>uk-offcanvas<?php } ?> class="<?php if (User::$isMobile) { ?>uk-offcanvas<?php } ?> uk-text-left uk-width-1-4 uk-padding-remove uk-margin-remove <?php if (!User::$isMobile) { ?>uk-overflow-auto<?php } ?>" uk-accordion="multiple: true; targets: &gt; .js-accordion-section" style="flex-basis: auto">
	<?php if (User::$isMobile) { ?>
	<div class="uk-offcanvas-bar">
	<div class="uk-margin-large-bottom">
		<button class="uk-offcanvas-close" type="button" uk-close></button>
	</div>
	<?php } ?>
	
	<?php if (v("attiva_localizzazione_prodotto")) { ?>
		<?php if (isset($filtriNazioni) && count($filtriNazioni) > 0) {
			$filtriUrlLocTutti = RegioniModel::getUrlCaratteristicheTutti($nazioneAlias);
			$filtroLocTuttiSelezionato = RegioniModel::filtroTuttiSelezionato($nazioneAlias);
		?>
		<section class="js-accordion-section uk-open">
			<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Nazione")?></h4>
			<div class="uk-accordion-content">
				<ul class="uk-list uk-list-divider">
					<li class="cat-item cat-item-49">
						<a class="uk-text-meta uk-text-xsmall <?php if ($filtroLocTuttiSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTutti,$filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
					</li>
					<?php foreach ($filtriNazioni as $n) {
						$filtriUrlLoc = RegioniModel::getArrayUrlCaratteristiche($nazioneAlias, $n["nazioni"]["iso_country_code"]);
						$filtroLocSelezionato = RegioniModel::filtroSelezionato($nazioneAlias, $n["nazioni"]["iso_country_code"]);
					?>
					<li class="">
						<a class="uk-text-meta uk-text-xsmall <?php if ($filtroLocSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLoc,$filtriUrlAltriTuttiAltri);?>"><?php echo $n["nazioni"]["titolo"];?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</section>
		<?php } ?>
		
		<?php if (isset($filtriRegioni) && count($filtriRegioni) > 0) {
			$filtriUrlLocTutti = RegioniModel::getUrlCaratteristicheTutti($regioneAlias);
			$filtroLocTuttiSelezionato = RegioniModel::filtroTuttiSelezionato($regioneAlias);
		?>
		<section class="uk-margin-large-top js-accordion-section uk-open">
			<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Regione")?></h4>
			<div class="uk-accordion-content">
				<ul class="uk-list uk-list-divider">
					<li class="cat-item cat-item-49">
						<a class="uk-text-meta uk-text-xsmall <?php if ($filtroLocTuttiSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTutti,$filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
					</li>
					<?php foreach ($filtriRegioni as $n) {
						$filtriUrlLoc = RegioniModel::getArrayUrlCaratteristiche($regioneAlias, $n["regioni"]["alias"]);
						$filtroLocSelezionato = RegioniModel::filtroSelezionato($regioneAlias, $n["regioni"]["alias"]);
					?>
					<li class="">
						<a class="uk-text-meta uk-text-xsmall <?php if ($filtroLocSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLoc,$filtriUrlAltriTuttiAltri);?>"><?php echo $n["regioni"]["titolo"];?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</section>
		<?php } ?>
	<?php } ?>
	
	<section class="<?php if (v("attiva_localizzazione_prodotto")) { ?>uk-margin-large-top<?php } ?> js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Categoria")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="<?php if (isset($datiCategoria) && $datiCategoria["categories"]["id_c"] == $idShop) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $idShop, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>">
						<?php echo gtext("Tutti");?>
						<?php if (false && v("attiva_filtri_successivi")) { ?>
						<span class="uk-text-small uk-text-meta">(<?php echo numeroProdottiCategoriaFull($idShop, v("attiva_filtri_successivi"));?>)</span>
						<?php } ?>
					</a>
					
				</li>
				<?php foreach ($elencoCategorieFull as $c) {
					$figlie = categorieFiglie($c["categories"]["id_c"]);
					$figlieIds = CategoriesModel::resultToIdList($figlie);
					$numeroProdottiCategoria = numeroProdottiCategoriaFull($c["categories"]["id_c"], v("attiva_filtri_successivi"));
					
					if (!$numeroProdottiCategoria)
						continue;
				?>
				<li <?php if (count($figlie) > 0) { ?>uk-accordion<?php } ?>>
					<?php if (count($figlie) > 0) { ?>
						<div class="<?php if (in_array($id_categoria,$figlieIds)) { ?>uk-open<?php } else { ?>uk-close<?php } ?>">
					<?php } ?>
						<a class="<?php if (isset($datiCategoria) && $datiCategoria["categories"]["id_c"] == $c["categories"]["id_c"]) { ?>uk-text-bold<?php } ?> uk-text-meta uk-text-xsmall <?php if (count($figlie) > 0) { ?>uk-accordion-title"<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $c["categories"]["id_c"], "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>">
							<?php echo cfield($c, "title");?>
							<?php if (v("attiva_filtri_successivi")) { ?>
							<span class=" uk-text-small uk-text-meta">(<?php echo $numeroProdottiCategoria;?>)</span>
							<?php } ?>
						</a>
						
						<?php if (count($figlie) > 0) { ?>
						<ul class='uk-list uk-margin-left uk-accordion-content'>
							<?php foreach ($figlie as $fg) {
								$numeroProdottiCategoriaFiglia = numeroProdottiCategoriaFull($fg["categories"]["id_c"], v("attiva_filtri_successivi"));
								
								if (!$numeroProdottiCategoriaFiglia)
									continue;
							?>
							<li class="<?php if (isset($datiCategoria) && $datiCategoria["categories"]["id_c"] == $fg["categories"]["id_c"]) { ?>uk-text-bold<?php } ?>">
								<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $fg["categories"]["id_c"], "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>">
									<?php echo cfield($fg, "title");?>
									<?php if (v("attiva_filtri_successivi")) { ?>
									<span class="uk-text-small uk-text-meta">(<?php echo $numeroProdottiCategoriaFiglia;?>)</span>
									<?php } ?>
								</a>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					<?php if (count($figlie) > 0) { ?></div><?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	
	<?php if (isset($elencoMarchiFullFiltri) && count($elencoMarchiFullFiltri) > 0) { ?>
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Marchio")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="cat-item cat-item-49 <?php if ($idMarchio == 0) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, 0, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($elencoMarchiFullFiltri as $m) { ?>
				<li class="<?php if ($m["marchi"]["id_marchio"] == $idMarchio) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $m["marchi"]["id_marchio"], $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>">
						<?php echo mfield($m,"titolo");?>
						<?php if (v("attiva_filtri_successivi")) { ?>
						<span class="uk-text-small uk-text-meta">(<?php echo $m["aggregate"]["numero_prodotti"];?>)</span>
						<?php } ?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php } ?>
	
	<?php if (isset($elencoTagFullFiltri) && count($elencoTagFullFiltri) > 0) { ?>
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Linea")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="cat-item cat-item-49 <?php if ($idTag == 0) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio(0, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($elencoTagFullFiltri as $m) { ?>
				<li class="<?php if ($m["tag"]["id_tag"] == $idTag) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($m["tag"]["id_tag"], $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>">
						<?php echo tagfield($m,"titolo");?>
						<?php if (v("attiva_filtri_successivi")) { ?>
						<span class="uk-text-small uk-text-meta">(<?php echo $m["aggregate"]["numero_prodotti"];?>)</span>
						<?php } ?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php } ?>
	
	<?php
	// Filtri caratteristiche
	if (isset($filtriCaratteristiche)) { 
		if (count($filtriCaratteristiche) > 0) {
			$lastIdCar = $filtriCaratteristiche[0]["caratteristiche"]["id_car"];
			$carAlias = carfield($filtriCaratteristiche[0], "alias");
			$filtriUrlTutti = CaratteristicheModel::getUrlCaratteristicheTutti($carAlias);
			$filtroTuttiSelezionato = CaratteristicheModel::filtroTuttiSelezionato($carAlias);
		?>
			<section class="uk-margin-large-top js-accordion-section uk-open">
				<h4 class="uk-accordion-title uk-margin-remove"><?php echo carfield($filtriCaratteristiche[0], "titolo");?></h4>
				<div class="uk-accordion-content">
					<ul class="uk-list uk-list-divider">
						<li class="cat-item cat-item-49">
							<a class="uk-text-meta uk-text-xsmall <?php if ($filtroTuttiSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTutti, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
						</li>
		<?php } ?>
		
		<?php
		foreach ($filtriCaratteristiche as $fc) {
			$carAlias = carfield($fc, "alias");
			$carVAlias = carvfield($fc, "alias");
			
			$filtriUrl = CaratteristicheModel::getArrayUrlCaratteristiche($carAlias, $carVAlias);
			$filtroSelezionato = CaratteristicheModel::filtroSelezionato($carAlias, $carVAlias);
			
			$numeroProdottiCaratteristica = CaratteristichevaloriModel::numeroProdotti($carAlias, $carVAlias, v("attiva_filtri_successivi"));
			
			if ($fc["caratteristiche"]["id_car"] != $lastIdCar) {
				$lastIdCar = $fc["caratteristiche"]["id_car"];
				$filtriUrlTutti = CaratteristicheModel::getUrlCaratteristicheTutti($carAlias);
				$filtroTuttiSelezionato = CaratteristicheModel::filtroTuttiSelezionato($carAlias);
			?>
					</ul>
				</div>
			</section>
			
			<section class="uk-margin-large-top js-accordion-section uk-open">
				<h4 class="uk-accordion-title uk-margin-remove"><?php echo carfield($fc, "titolo");?></h4>
				<div class="uk-accordion-content">
					<ul class="uk-list uk-list-divider">
						<li class="cat-item cat-item-49">
							<a class="uk-text-meta uk-text-xsmall <?php if ($filtroTuttiSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTutti, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>"><?php echo gtext("Tutti");?></a>
						</li>
			<?php } ?>
			<?php if ($numeroProdottiCaratteristica > 0) { ?>
						<li class="">
							<a class="uk-text-meta uk-text-xsmall <?php if ($filtroSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrl, $filtriUrlLocTuttiAltri, $filtriUrlAltriTuttiAltri);?>"><?php echo carvfield($fc, "titolo");?></a>
								<?php if (v("attiva_filtri_successivi")) { ?>
								<span class="uk-text-small uk-text-meta">(<?php echo $numeroProdottiCaratteristica;?>)</span>
							<?php } ?>
						</li>
			<?php } ?>
		<?php } ?>
		
		<?php if (count($filtriCaratteristiche) > 0) { ?>
					</ul>
				</div>
			</section>
		<?php } ?>
	<?php } ?>
	
	<?php if (v("mostra_fasce_prezzo") && isset($fascePrezzo) && count($fascePrezzo) > 0) {
		$filtriUrlAltriTutti = AltriFiltri::getUrlCaratteristicheTutti(AltriFiltri::$altriFiltriTipi["fascia-prezzo"]);
		$filtroAltriTuttiSelezionato = AltriFiltri::filtroTuttiSelezionato(AltriFiltri::$altriFiltriTipi["fascia-prezzo"]);
	?>
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Fascia prezzo")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="cat-item cat-item-49 <?php if ($filtroAltriTuttiSelezionato) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriTutti);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($fascePrezzo as $p) {
					$filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["fascia-prezzo"], fpfield($p,"alias"));
					$filtroSelezionato = AltriFiltri::filtroSelezionato(AltriFiltri::$altriFiltriTipi["fascia-prezzo"], fpfield($p,"alias"));
				?>
				<li class="">
					<a class="uk-text-meta uk-text-xsmall <?php if ($filtroSelezionato) { ?>uk-text-bold<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriFiltri);?>">
						<?php echo fpfield($p,"titolo");?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php } ?>
	
	<?php if (v("filtro_prezzo_slider") && isset($prezzoMinimoElenco) && isset($prezzoMassimoElenco) && isset($pages) && count($pages) > 1) {
		$curMin = isset($fasciaPrezzo) ? floor($fasciaPrezzo["fasce_prezzo"]["da"]) : floor($prezzoMinimoElenco);
		$curMax = isset($fasciaPrezzo) ? ceil($fasciaPrezzo["fasce_prezzo"]["a"]) :  ceil($prezzoMassimoElenco);
		
		$prezzoMinimoElenco = min($curMin, $prezzoMinimoElenco);
		$prezzoMassimoElenco = max($curMax, $prezzoMassimoElenco);
	?>
	<section class="uk-margin-large-top">
		<h4 class="uk-margin-remove"><?php echo gtext("Prezzo")?></h4>
		<div class="uk-margin-top">
			<div class="nstSlider" data-range_min="<?php echo floor($prezzoMinimoElenco);?>" data-range_max="<?php echo ceil($prezzoMassimoElenco);?>" data-cur_min="<?php echo $curMin;?>"    data-cur_max="<?php echo $curMax;?>">
				<div class="bar"></div>
				<div class="leftGrip">
					<div class="uk-margin-top uk-text-small leftLabel"></div>
				</div>
				<div class="rightGrip">
					<div class="uk-margin-top uk-text-small rightLabel"></div>
				</div>
			</div>
		</div>
		<?php $filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["fascia-prezzo"], gtext("da")."-[DA]-".gtext("a")."-[A]");?>
		<div class="uk-hidden url_slider_prezzo"><?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriFiltri);?></div>
	</section>
	<?php } ?>
	
<!-- 	<hr> -->
	<?php
	$numeroEvidenza = PagesModel::numeroStato("evidenza", v("attiva_filtri_successivi"));
	$numeroNuovi = PagesModel::numeroStato("nuovo", v("attiva_filtri_successivi"));
	$numeroPromo = PagesModel::numeroStato("promozione", v("attiva_filtri_successivi"));
	?>
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Offerte / Consigliati")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<?php if ($numeroEvidenza) { ?>
				<li>
					<?php $filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["stato-prodotto"], AltriFiltri::$aliasValoreTipoInEvidenza[0]); ?>
					<a class=" uk-text-meta  " href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriFiltri);?>">
						<?php echo gtext("In evidenza");?>
						<span class="uk-text-small uk-text-meta">(<?php echo $numeroEvidenza;?>)</span>
					</a>
				</li>
				<?php } ?>
				<?php if ($numeroPromo) { ?>
				<li>
					<?php $filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["stato-prodotto-promo"], AltriFiltri::$aliasValoreTipoPromo[0]); ?>
					<a class=" uk-text-meta  " href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriFiltri);?>">
						<?php echo gtext("In promozione");?>
						<span class="uk-text-small uk-text-meta">(<?php echo $numeroPromo;?>)</span>
					</a>
				</li>
				<?php } ?>
				<?php if ($numeroNuovi) { ?>
				<li>
					<?php $filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["stato-prodotto-nuovo"], AltriFiltri::$aliasValoreTipoNuovo[0]); ?>
					<a class=" uk-text-meta  " href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri, $filtriUrlAltriFiltri);?>">
						<?php echo gtext("Novità");?>
						<span class="uk-text-small uk-text-meta">(<?php echo $numeroNuovi;?>)</span>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	
	<?php if (User::$isMobile) { ?></div><?php } ?>
</div>
