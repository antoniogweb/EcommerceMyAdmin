<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "documenti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi file</a></p>

<?php } ?>
