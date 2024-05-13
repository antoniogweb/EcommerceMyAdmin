<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (IntegrazioniModel::getElencoIntegrazioni($this->controller) && $tipoSteps == "vedi") { ?>
<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/vedi/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['integrazioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/integrazioni/$id".$this->viewStatus;?>">Invii a piattaforme esterne</a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>

<?php if ($tipoSteps == "modifica" && $id) {
	$nascondiStatoPagamento = $nascondiMetodoDiPagamento = $nascondiTipoOrdine = $nascondiVediLatoCliente = $nascondiCreaSpedizione = true;
?>

<div class="box">
	<div class="box-header with-border main help_resoconto">
		<div class="row">
			<div class="col-lg-6">
				<?php include($this->viewPath("vedi_top_left"));?>
			</div>
			<div class="col-lg-6">
				<?php include($this->viewPath("vedi_top_right"));?>
			</div>
		</div>
	</div>
</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("permetti_ordini_offline") && OrdiniModel::tipoOrdine((int)$id) != "W") { ?>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<?php } ?>
</ul>

<div style="clear:left;"></div>
<?php } ?>
