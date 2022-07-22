<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "tag") { ?>

<?php if (false) { ?>
<form class="form-inline list_filter_form_top" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_tag","",$lista,"form-control",null,"yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>
<?php } ?>

<p>
<a class="pull-right btn btn-primary btn-sm iframe" href="<?php echo $this->baseUrl."/tag/main?partial=Y";?>"><i class="fa fa-pencil"></i> <?php echo gtext("Gestisci tag");?></a>

<a class="btn btn-success btn-sm iframe" href="<?php echo $this->baseUrl."/tag/main?partial=Y&cl_on_sv=Y&id_page=$id_page";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi tag");?></a></p>

<?php } ?>
