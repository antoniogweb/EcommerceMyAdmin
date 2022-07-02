<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
  <?php if (v("mostra_immagini_in_gallery")) { ?>
  <li <?php echo $posizioni['immagini'];?> ><a class="help_immagini" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/immagini/$id_page".$this->viewStatus;?>"><?php echo gtext("Immagini");?></a></li>
  <?php } ?>
</ul>

<?php } ?>

<div style="clear:left;"></div>
