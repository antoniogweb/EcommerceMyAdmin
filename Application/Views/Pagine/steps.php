<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <?php if (v("contenuti_in_pagine")) { ?>
  <li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$this->viewStatus;?>">Contenuti</a></li>
  <?php } ?>
  <?php if (v("fasce_in_pagine")) { ?>
  <li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id_page".$this->viewStatus;?>">Fasce</a></li>
  <?php } ?>
  <li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>">Meta</a></li>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
