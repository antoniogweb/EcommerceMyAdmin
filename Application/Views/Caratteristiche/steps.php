<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$temp = $this->viewArgs;
	$temp["page_fgl"] = 1;
?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<li <?php echo $posizioni['valori'];?>><a class="help_valori" href="<?php echo $this->baseUrl."/".$this->controller."/valori/$id".$this->getViewStatusUsingVariables($temp);?>">Valori</a></li>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
