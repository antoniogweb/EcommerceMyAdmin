<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "immagini") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/ticketfile/form/insert";?>?cl_on_sv=Y&partial=Y&nobuttons=N&id_ticket=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi immagine");?></a></p>

<?php } ?>
