<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $noteModel = new NoteModel(); ?>
<script>
$(document).ready(function(){
	
	
	
});
</script>
<?php if ($noteModel->withValore($this->viewArgs["tabella"])) {
	$euroTotali = $noteModel->getTotalePromoAssoluta($this->viewArgs["tabella"], $this->viewArgs["id_tabella"]);
	$euroUsati = $noteModel->numeroEuroUsati($this->viewArgs["tabella"], $this->viewArgs["id_tabella"]);
	$euroRimasti = $noteModel->numeroEuroRimasti($this->viewArgs["tabella"], $this->viewArgs["id_tabella"]);
	
	if ($euroTotali) { 
?>
	<div class="box box-widget">
		<div class="box-body">
			<div class="row">
				<div class="col-lg-6">
					<table style="margin-bottom:5px !important;" class="table table-striped">
						<tr>
							<td><?php echo gtext("Totale");?>:</td>
							<td><b><?php echo setPriceReverse($euroTotali);?> €</b></td>
						</tr>
						<tr>
							<td><?php echo gtext("Usato");?>:</td>
							<td><b><?php echo setPriceReverse($euroUsati);?> €</b></td>
						</tr>
						<tr>
							<td><?php echo gtext("Rimasto");?>:</td>
							<td><b><?php echo setPriceReverse($euroRimasti);?> €</b></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-6">
					
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>