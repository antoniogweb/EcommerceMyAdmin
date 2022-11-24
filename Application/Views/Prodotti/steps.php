<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$isGiftCard = ProdottiModel::isGiftCart((int)$id_page);
?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["tipocontenuto"] = "tutti";
	$temp["id_tipo_car"] = "tutti";
	$temp["pcorr_sec"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a class="help_dettagli" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id_page".$viewStatusTutti;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("attiva_categorie_in_prodotto")) { ?>
	<li <?php echo $posizioni['categorie'];?>><a class="help_dettagli" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/categorie/$id_page".$viewStatusTutti;?>"><?php echo gtext("Categorie");?></a></li>
	<?php } ?>
	<?php if (v("contenuti_in_prodotti")) { ?>
	<li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/testi/$id_page".$viewStatusTutti;?>"><?php echo gtext("Contenuti");?></a></a></li>
	<?php } ?>
	<?php if (v("fasce_in_prodotti")) { ?>
	<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/contenuti/$id_page".$viewStatusTutti;?>"><?php echo gtext("Fasce");?></a></li>
	<?php } ?>
	<?php if (v("scaglioni_in_prodotti")) { ?>
	<li <?php echo $posizioni['scaglioni'];?> ><a class="help_scaglioni" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/scaglioni/$id_page".$viewStatusTutti;?>"><?php echo gtext("Sconti quantità");?></a></li>
	<?php } ?>
	
	<li <?php echo $posizioni['meta'];?> ><a class="help_meta" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/meta/$id_page".$viewStatusTutti;?>"><?php echo gtext("Meta");?></a></li>
	<li <?php echo $posizioni['immagini'];?> ><a class="help_immagini" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/immagini/$id_page".$viewStatusTutti;?>"><?php echo gtext("Immagini");?></a></li>
	<?php if (v("correlati_in_prodotti") && !$isGiftCard) { ?>
	<li <?php echo $posizioni['prod_corr'];?> ><a class="help_correlati" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/correlati/$id_page".$viewStatusTutti;?>"><?php echo gtext("Prodotti correlati");?></a></li>
	<?php } ?>
	<?php if (v("accessori_in_prodotti") && !$isGiftCard) { ?>
	<li <?php echo $posizioni['accessori'];?>><a class="help_accessori" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/accessori/$id_page".$viewStatusTutti;?>"><?php echo gtext("Accessori");?></a></li>
	<?php } ?>
	<?php if (v("caratteristiche_in_prodotti")) { ?>
		<?php if (!v("caratteristiche_in_tab_separate")) { ?>
		<li <?php echo $posizioni['caratteristiche'];?>><a class="help_caratteristiche" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/caratteristiche/$id_page".$viewStatusTutti;?>"><?php echo gtext("Caratteristiche");?></a></li>
		<?php } else {
			foreach ($tabCaratteristiche as $idTipoCar => $titoloCar)
			{
				$temp = $this->viewArgs;
				$temp["id_tipo_car"] = (int)$idTipoCar;
				$temp["tipocontenuto"] = "tutti";
				$temp["pcorr_sec"] = "tutti";
			?>
			<li <?php if ($this->viewArgs["id_tipo_car"] == $idTipoCar) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/caratteristiche/$id_page".Url::createUrl($temp);?>"><?php echo ucfirst(strtolower($titoloCar));?></a></li>
			<?php
			}
		} ?>
	<?php } ?>
	<?php if (v("combinazioni_in_prodotti") && !$isGiftCard) { ?>
	<li <?php echo $posizioni['attributi'];?>><a class="help_varianti" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/attributi/$id_page".$viewStatusTutti;?>"><?php echo gtext("Varianti");?></a></li>
	<?php } ?>
	<?php if (v("attiva_personalizzazioni")) { ?>
	<li <?php echo $posizioni['personalizzazioni'];?>><a class="help_personalizzazioni" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/personalizzazioni/$id_page".$viewStatusTutti;?>"><?php echo gtext("Personalizzazioni");?></a></li>
	<?php } ?>
	<?php if (v("usa_tag") && v("tag_in_prodotti") && !$isGiftCard) { ?>
	<li <?php echo $posizioni['tag'];?>><a class="help_tag_prodotto" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/tag/$id_page".$viewStatusTutti;?>"><?php echo gtext("Tag");?></a></li>
	<?php } ?>
	<?php if (v("documenti_in_prodotti") && !$isGiftCard) { ?>
	<li <?php echo $posizioni['documenti'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/documenti/$id_page".$viewStatusTutti;?>"><?php echo gtext("Documenti");?></a></li>
	<?php } ?>
	<?php foreach ($tabContenuti as $idTipoCont => $titoloTipo) {
		$temp = $this->viewArgs;
		$temp["tipocontenuto"] = (int)$idTipoCont;
		$temp["id_tipo_car"] = "tutti";
		$temp["pcorr_sec"] = "tutti";
	?>
	<li class="<?php if ($this->viewArgs["tipocontenuto"] == $idTipoCont) { ?>active<?php } ?> <?php echo "help_".encodeUrl($titoloTipo);?>"><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/testi/$id_page".Url::createUrl($temp);?>"><?php echo ucfirst(strtolower($titoloTipo));?></a></li>
	<?php } ?>
	
	<?php foreach ($tabSezioni as $sec => $titleSection) {
		$temp = $this->viewArgs;
		$temp["tipocontenuto"] = "tutti";
		$temp["id_tipo_car"] = "tutti";
		$temp["pcorr_sec"] = $sec;
	?>
	<li class="<?php if ($this->viewArgs["pcorr_sec"] == $sec) { ?>active<?php } ?> <?php echo "help_".encodeUrl($titleSection);?>"><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/paginecorrelate/$id_page".Url::createUrl($temp);?>"><?php echo $titleSection;?></a></li>
	<?php } ?>
	
	<?php if (v("abilita_feedback")) { ?>
	<li <?php echo $posizioni['feedback'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/feedback/$id_page".$viewStatusTutti;?>"><?php echo gtext("Feedback");?></a></li>
	<?php } ?>
	<?php if (v("attiva_localizzazione_prodotto")) { ?>
	<li <?php echo $posizioni['regioni'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/regioni/$id_page".$viewStatusTutti;?>"><?php echo gtext("Regioni");?></a></li>
	<?php } ?>
	<?php if (v("abilita_visibilita_pagine")) { ?>
	<li <?php echo $posizioni['lingue'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/lingue/$id_page".$viewStatusTutti;?>"><?php echo gtext("Lingue");?></a></li>
	<?php } ?>
</ul>

<?php } ?>

<div style="clear:left;"></div>
