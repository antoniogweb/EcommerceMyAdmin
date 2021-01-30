<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>
<ul class="nav_dettaglio nav nav-tabs">
	<?php
	$temp = $this->viewArgs;
	$temp["tipocontenuto"] = "tutti";
	$viewStatusTutti = Url::createUrl($temp);
	?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$viewStatusTutti;?>">Dettagli</a></li>
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
