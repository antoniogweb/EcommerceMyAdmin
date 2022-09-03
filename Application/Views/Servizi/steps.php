<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["pcorr_sec"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>">Dettagli</a></li>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$viewStatusTutti;?>">Meta</a></li>
	<?php if (v("contenuti_in_pagine")) { ?>
	<li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$viewStatusTutti;?>">Contenuti</a></li>
	<?php } ?>
	<?php if (v("fasce_in_pagine")) { ?>
	<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$viewStatusTutti;?>">Fasce</a></li>
	<?php } ?>
	<?php foreach ($tabSezioni as $sec => $titleSection) {
		$temp = $this->viewArgs;
		$temp["pcorr_sec"] = $sec;
	?>
	<li class="<?php if ($this->viewArgs["pcorr_sec"] == $sec) { ?>active<?php } ?> <?php echo "help_".encodeUrl($titleSection);?>"><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/paginecorrelate/$id_page".Url::createUrl($temp);?>"><?php echo $titleSection;?></a></li>
	<?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
