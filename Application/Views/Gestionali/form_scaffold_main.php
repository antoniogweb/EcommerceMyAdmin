<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').tinymce(tiny_editor_config);
});
</script>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-4'>
			<?php foreach ($fields as $f) { 
				echo $form[$f];
			} ?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<?php include($this->viewPath("form_submit_button"));?>
		</div>
		<div class='col-md-8'>
			<?php if (GestionaliModel::getModulo($record["codice"])->isAttiva() && GestionaliModel::getModulo($record["codice"])->metodo("info")) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="ajlink pull-right make_spinner badge" href="<?php echo $this->baseUrl."/gestionali/infoaccount"; ?>"><i class="fa fa-refresh"></i> <?php echo gtext("aggiorna");?></a>
					<?php echo gtext("Info gestionale");?>
				</div>
				<div class="panel-body">
					<?php if (trim($record["info_account"])) { ?>
					<pre>
					<?php echo sanitizeHtml(json_encode(json_decode(htmlentitydecode($record["info_account"]),true),JSON_PRETTY_PRINT));?>
					</pre>
					<?php } else { ?>
					<?php echo gtext("Info account non presente, si prega di generarlo con il pulsante aggiorna.");?>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</form>
</div>
