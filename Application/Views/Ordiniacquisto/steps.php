<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($id) {
	$ordine = OrdiniacquistoModel::g()->selectId((int)$id);
?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			<a class="pull-right btn btn-xs btn-warning btn-rounded make_spinner" href="<?php echo $this->baseUrl."/impegni/ordiniimpegni/inviapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-envelope"></i> <?php echo gtext("Invia PDF");?></a>
			
			<a target="_blank" class="pull-right btn btn-xs btn-success btn-rounded" href="<?php echo $this->baseUrl."/impegni/ordiniimpegni/stampapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-file-pdf-o"></i> <?php echo gtext("Stampa PDF");?></a>
		</div>
		<?php echo gtext("N° Ordine");?> #<b><?php echo $ordine["numero_ordine"];?></b></b>
		</div>
	<div class="text-left panel-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					
				</table>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					
				</table>
			</div>
		</div>
	</div>
</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<li <?php echo $posizioni['inviipdf'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/inviipdf/$id".$this->viewStatus;?>"><?php echo gtext("Storico PDF");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
