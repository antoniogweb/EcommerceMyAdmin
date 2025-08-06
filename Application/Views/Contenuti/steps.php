<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<?php if ($this->action == "figli" && v("attiva_contenuti_figli") && $this->viewArgs["id_tipo_figlio"] != "tutti" && isset($recordTipo["tipo"]) && $recordTipo["tipo"] == "FASCIA") { ?>
	<li <?php echo $posizioni['figli'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/figli/$id".$this->viewStatus;?>"><?php echo gtext("Elementi");?></a></li>
	<?php } else { ?>
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<?php } ?>
	
	<?php if (v("attiva_gruppi_contenuti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>">Accessi</a></li>
	<?php } ?>
	<?php if (!isset($recordTipo["tipo"]) || $recordTipo["tipo"] != "FASCIA") { ?>
		<?php $traduz = ContenutitradottiModel::getTraduzioni("id_cont", $id);?>
		<?php foreach ($traduz as $tr) {
			if (!in_array($tr["lingua"], BaseController::$traduzioni))
				continue;
		?>
		<li <?php if (isset($id_ct) && (int)$tr["id_ct"] === (int)$id_ct) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/contenuti/traduzione/$id/".$tr["id_ct"].$this->viewStatus;?>"><?php echo strtoupper($tr["lingua"]);?></a></li>
		<?php } ?>
	<?php } ?>
	
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
