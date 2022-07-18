<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["pcorr_sec"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>">Dettagli</a></li>
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
