<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "layer") { ?>

<p><a class="iframe btn btn-success pull-right" href="<?php echo $this->baseUrl."/layer/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>">Aggiungi layer</a></p>

<?php } ?>
