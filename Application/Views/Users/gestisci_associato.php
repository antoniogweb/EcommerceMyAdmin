<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($this->action == "gruppi") { ?>

<p>
	<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/groups/main?id_user=$id&partial=Y&cl_on_sv=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi gruppo");?></a>
</p>

<?php } ?> 
