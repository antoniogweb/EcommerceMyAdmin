<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y&nobuttons=Y&id_ordine=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi articoli")?></a></p>

<?php } ?>
