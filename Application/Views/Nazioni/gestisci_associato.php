<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "regioni") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/regioni/form/insert";?>?partial=Y&nobuttons=Y&id_nazione=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi regione")?></a></p>

<?php } ?>
