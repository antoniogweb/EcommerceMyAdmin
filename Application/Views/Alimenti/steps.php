<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["pcorr_sec"] = "tutti";
	$temp["tipocontenuto"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$viewStatusTutti;?>"><?php echo gtext("Meta");?></a></li>
	<?php if (v("contenuti_in_pagine") && empty($tabContenuti)) { ?>
	<li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$viewStatusTutti;?>"><?php echo gtext("Contenuti");?></a></li>
	<?php } ?>
	<?php if (v("fasce_in_pagine")) { ?>
	<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$viewStatusTutti;?>"><?php echo gtext("Fasce");?></a></li>
	<?php } ?>
	<?php foreach ($tabSezioni as $sec => $titleSection) {
		$temp = $this->viewArgs;
		$temp["pcorr_sec"] = $sec;
		$temp["tipocontenuto"] = "tutti";
	?>
	<li class="<?php if ($this->viewArgs["pcorr_sec"] == $sec) { ?>active<?php } ?> <?php echo "help_".encodeUrl($titleSection);?>"><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/paginecorrelate/$id_page".Url::createUrl($temp);?>"><?php echo $titleSection;?></a></li>
	<?php } ?>
	<?php foreach ($tabContenuti as $idTipoCont => $titoloTipo) {
		$temp = $this->viewArgs;
		$temp["tipocontenuto"] = (int)$idTipoCont;
		$temp["pcorr_sec"] = "tutti";
	?>
	<li class="<?php if ($this->viewArgs["tipocontenuto"] == $idTipoCont) { ?>active<?php } ?> <?php echo "help_".encodeUrl($titoloTipo);?>"><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/testi/$id_page".Url::createUrl($temp);?>"><?php echo ucfirst(strtolower($titoloTipo));?></a></li>
	<?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
