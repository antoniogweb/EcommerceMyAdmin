<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "valori") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/attributivalori/form/insert";?>?partial=Y&nobuttons=Y&id_a=<?php echo $id;?>">Aggiungi valore</a></p>

<?php } ?>
