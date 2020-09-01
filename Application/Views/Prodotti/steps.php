<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <?php if (v("contenuti_in_prodotti")) { ?>
  <li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$this->viewStatus;?>">Contenuti</a></li>
  <?php } ?>
  <?php if (v("fasce_in_prodotti")) { ?>
  <li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$this->viewStatus;?>">Fasce</a></li>
  <?php } ?>
  <?php if (v("scaglioni_in_prodotti")) { ?>
  <li <?php echo $posizioni['scaglioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/scaglioni/$id_page".$this->viewStatus;?>">Sconti quantità</a></li>
  <?php } ?>
  
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
  <li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$this->viewStatus;?>">Immagini</a></li>
  <?php if (v("correlati_in_prodotti")) { ?>
  <li <?php echo $posizioni['prod_corr'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/correlati/$id_page".$this->viewStatus;?>">Prodotti correlati</a></li>
  <?php } ?>
  <?php if (v("accessori_in_prodotti")) { ?>
  <li <?php echo $posizioni['accessori'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/accessori/$id_page".$this->viewStatus;?>">Accessori</a></li>
  <?php } ?>
  <?php if (v("caratteristiche_in_prodotti")) { ?>
  <li <?php echo $posizioni['caratteristiche'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/caratteristiche/$id_page".$this->viewStatus;?>">Caratteristiche</a></li>
  <?php } ?>
  <?php if (v("combinazioni_in_prodotti")) { ?>
  <li <?php echo $posizioni['attributi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/attributi/$id_page".$this->viewStatus;?>">Combinazioni</a></li>
  <?php } ?>
  <?php if (v("attiva_personalizzazioni")) { ?>
  <li <?php echo $posizioni['personalizzazioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/personalizzazioni/$id_page".$this->viewStatus;?>">Personalizzazioni</a></li>
  <?php } ?>
  <?php if (v("documenti_in_prodotti")) { ?>
  <li <?php echo $posizioni['documenti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/documenti/$id_page".$this->viewStatus;?>">Documenti</a></li>
  <?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
