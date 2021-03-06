<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["tipocontenuto"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>">Dettagli</a></li>
	<?php if (v("contenuti_in_prodotti")) { ?>
	<li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$viewStatusTutti;?>">Contenuti</a></li>
	<?php } ?>
	<?php if (v("fasce_in_prodotti")) { ?>
	<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$viewStatusTutti;?>">Fasce</a></li>
	<?php } ?>
	<?php if (v("scaglioni_in_prodotti")) { ?>
	<li <?php echo $posizioni['scaglioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/scaglioni/$id_page".$viewStatusTutti;?>">Sconti quantità</a></li>
	<?php } ?>
	
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$viewStatusTutti;?>">Meta</a></li>
	<li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$viewStatusTutti;?>">Immagini</a></li>
	<?php if (v("correlati_in_prodotti")) { ?>
	<li <?php echo $posizioni['prod_corr'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/correlati/$id_page".$viewStatusTutti;?>">Prodotti correlati</a></li>
	<?php } ?>
	<?php if (v("accessori_in_prodotti")) { ?>
	<li <?php echo $posizioni['accessori'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/accessori/$id_page".$viewStatusTutti;?>">Accessori</a></li>
	<?php } ?>
	<?php if (v("caratteristiche_in_prodotti")) { ?>
	<li <?php echo $posizioni['caratteristiche'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/caratteristiche/$id_page".$viewStatusTutti;?>">Caratteristiche</a></li>
	<?php } ?>
	<?php if (v("combinazioni_in_prodotti")) { ?>
	<li <?php echo $posizioni['attributi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/attributi/$id_page".$viewStatusTutti;?>">Varianti</a></li>
	<?php } ?>
	<?php if (v("attiva_personalizzazioni")) { ?>
	<li <?php echo $posizioni['personalizzazioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/personalizzazioni/$id_page".$viewStatusTutti;?>">Personalizzazioni</a></li>
	<?php } ?>
	<?php if (v("usa_tag")) { ?>
	<li <?php echo $posizioni['tag'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/tag/$id_page".$viewStatusTutti;?>">Tag</a></li>
	<?php } ?>
	<?php if (v("documenti_in_prodotti")) { ?>
	<li <?php echo $posizioni['documenti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/documenti/$id_page".$viewStatusTutti;?>"><?php echo gtext("Documenti");?></a></li>
	<?php } ?>
	<?php foreach ($tabContenuti as $idTipoCont => $titoloTipo) {
		$temp = $this->viewArgs;
		$temp["tipocontenuto"] = (int)$idTipoCont;
	?>
	<li <?php if ($this->viewArgs["tipocontenuto"] == $idTipoCont) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".Url::createUrl($temp);?>"><?php echo ucfirst(strtolower($titoloTipo));?></a></li>
	<?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
