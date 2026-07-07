<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "import") { ?>
	<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/fornitoriimport/form/insert";?>?partial=Y&nobuttons=Y&id_fornitore_insert=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi listino");?></a></p>
<?php } ?>
