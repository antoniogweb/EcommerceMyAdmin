<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "pagine") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y&nobuttons=Y&id_lista_regalo=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi prodotti");?></a></p>

<?php } ?>

<?php if ($this->action === "inviti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/listeregalolink/form/insert/0";?>?partial=Y&nobuttons=Y&cl_on_sv=Y&id_lista_regalo=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Invia link");?></a></p>

<?php } ?>
