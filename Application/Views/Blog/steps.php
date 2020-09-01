<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <?php if (v("contenuti_in_blog")) { ?>
  <li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$this->viewStatus;?>">Contenuti</a></li>
  <?php } ?>
  <li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$this->viewStatus;?>">Immagini</a></li>
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
  <?php if (v("correlati_in_prodotti")) { ?>
  <li <?php echo $posizioni['prod_corr'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/correlati/$id_page".$this->viewStatus;?>">Notizie correlate</a></li>
  <?php } ?>
  <?php if (v("mostra_link_in_blog")) { ?>
  <li <?php echo $posizioni['link'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/link/$id_page".$this->viewStatus;?>">Link</a></li>
  <?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
