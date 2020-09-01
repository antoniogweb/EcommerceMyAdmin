<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>">Dettagli</a></li>
  <li <?php echo $posizioni['documenti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/documenti/$id_page".$this->viewStatus;?>">File</a></li>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
