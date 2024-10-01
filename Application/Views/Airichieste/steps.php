<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['messaggi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/messaggi/$id".$this->viewStatus;?>"><?php echo gtext("Chat");?></a></li>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['contesti'];?>><a class="help_scaglioni" href="<?php echo $this->baseUrl."/".$this->controller."/contesti/$id".$this->viewStatus;?>"><?php echo gtext("Contesto");?></a></li>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
