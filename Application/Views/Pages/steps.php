<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$this->viewStatus;?>">Contenuti</a></li>
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
  <li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$this->viewStatus;?>">Immagini</a></li>
  <li <?php echo $posizioni['prod_corr'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/correlati/$id_page".$this->viewStatus;?>">Prodotti correlati</a></li>
  <li <?php echo $posizioni['caratteristiche'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/caratteristiche/$id_page".$this->viewStatus;?>">Caratteristiche</a></li>
  <li <?php echo $posizioni['attributi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/attributi/$id_page".$this->viewStatus;?>">Attributi</a></li>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
