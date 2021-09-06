<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "elementi") { ?>
<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/helpitem/form/insert";?>?partial=Y&nobuttons=N&id_help=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi elemento")?></a></p>
<?php } ?>
