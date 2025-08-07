<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['regioni'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/regioni/$id".$this->viewStatus;?>"><?php echo gtext("Regioni");?></a></li>
	<?php if (v("attiva_clienti_nazioni")) { ?>
	<li <?php echo $posizioni['regusers'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/regusers/$id".$this->viewStatus;?>"><?php echo gtext("Clienti")?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
