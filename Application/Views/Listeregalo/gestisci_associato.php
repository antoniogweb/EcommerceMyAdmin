<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "pagine") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y&nobuttons=Y&id_lista_regalo=<?php echo $id;?>"><?php echo gtext("Aggiungi prodotti");?></a></p>

<?php } ?>
