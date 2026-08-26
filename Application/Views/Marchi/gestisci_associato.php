<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "documenti" && v("attiva_documenti_in_marchi")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=0&id_user=0&id_marchio=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi")?></a></p>

<?php } ?>