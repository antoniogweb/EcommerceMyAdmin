<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "prezzi") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/corrierispese/form/insert";?>?partial=Y&nobuttons=Y&id_corriere=<?php echo $id;?>">Aggiungi scaglione prezzo</a></p>

<?php } ?>
