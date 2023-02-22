<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (ControllersModel::checkAccessoAlController(array("combinazioni"))) { ?>
	<li <?php echo $posizioni['pagine'];?>><a class="help_pagine_lista" href="<?php echo $this->baseUrl."/".$this->controller."/pagine/$id".$this->viewStatus;?>"><?php echo gtext("Prodotti");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['inviti'];?>><a class="help_pagine_lista_link" href="<?php echo $this->baseUrl."/".$this->controller."/inviti/$id".$this->viewStatus;?>"><?php echo gtext("Link");?></a></li>
	<?php if (ControllersModel::checkAccessoAlController(array("ordini"))) { ?>
	<li <?php echo $posizioni['ordini'];?>><a class="help_pagine_lista_ordini" href="<?php echo $this->baseUrl."/".$this->controller."/ordini/$id".$this->viewStatus;?>"><?php echo gtext("Ordini");?></a></li>
	<li <?php echo $posizioni['righe'];?>><a class="help_pagine_lista_righe" href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['invii'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/invii/$id".$this->viewStatus;?>"><?php echo gtext("Mail con dedica");?></a></li>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
