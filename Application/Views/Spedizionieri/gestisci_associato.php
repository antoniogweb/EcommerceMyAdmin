<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "lettere") { ?>

<p>
	<a class="iframe btn btn-primary" href="<?php echo $this->baseUrl."/spedizionieriletterevettura/form/insert";?>?partial=Y&nobuttons=Y&id_spedizioniere=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi template")?></a>
</p>

<?php } ?>
