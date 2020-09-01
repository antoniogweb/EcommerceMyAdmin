<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->controller === "pages" && $this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success pull-right" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi contenuto</a></p>

<?php } ?>
