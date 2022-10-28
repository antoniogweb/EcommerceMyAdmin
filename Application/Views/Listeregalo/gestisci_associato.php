<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "pagine") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y&nobuttons=Y&id_lista_regalo=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi prodotti");?></a></p>

<?php } ?>
