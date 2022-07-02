<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>">Dettagli</a></li>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$viewStatusTutti;?>">Meta</a></li>
</ul>

<?php } ?>

<div style="clear:left;"></div>
