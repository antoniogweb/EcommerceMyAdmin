<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($labelIdEl))
	$labelIdEl = "id_c";

	$idAiRichiesta = $id ?? $id_page;
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Assistente testi IA");?>
	</div>
	<div class="panel-body">
		<a href="<?php echo $this->baseUrl."/airichieste/form/insert/0?$labelIdEl=$idAiRichiesta&partial=Y";?>" class="btn btn-info iframe"><i class="fa  fa-commenting-o"></i> <?php echo gtext("Apri l'assistente IA")?></a>
	</div>
</div>
