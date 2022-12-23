<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($this->action == "controllers") { ?>

<p>
	<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/controllers/main?id_group=$id&partial=Y&cl_on_sv=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi sezione");?></a>
</p>

<?php } ?> 
