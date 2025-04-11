<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>">Aggiungi fascia</a></p>

<?php } ?>
