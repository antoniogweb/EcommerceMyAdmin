<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "estensioni") { ?>
<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/tipidocumentoestensioni/form/insert";?>?partial=Y&nobuttons=N&id_tipo_doc=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi estensione")?></a></p>
<?php } ?>
