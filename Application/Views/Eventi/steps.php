<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$this->viewStatus;?>">Immagini</a></li>
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
</ul>

<?php } ?>

<div style="clear:left;"></div>
