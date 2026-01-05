<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->viewArgs["id_lista_regalo_ordine"] === "tutti") { ?>
	<?php if (v("permetti_aggiunta_listino_estero")) { ?>
	<a title="<?php echo gtext("Aggiungi listino");?>" style="margin-left:10px;" href="<?php echo $this->baseUrl."/combinazioni/aggiungilistino?partial=Y";?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i></a>
	<?php } ?>
	<?php $listini = CombinazionilistiniModel::elencoListini();?>
	<?php foreach ($listini as $l) {
		$temp = $this->viewArgs;
		$temp["listino"] = $l;
		$titoloListino = $l == "W" ? "Mondo" : findTitoloDaCodice($l);
	?>
	<a style="margin-left:10px;" href="<?php echo $this->baseUrl."/combinazioni/main".Url::createUrl($temp);?>" class="btn btn-<?php if ($this->viewArgs["listino"] == $l) { ?>info<?php } else { ?>default<?php } ?> pull-right"><?php echo gtext("Listino");?> <?php echo $titoloListino;?></a>
	<?php } ?>

	<?php
	$temp = $this->viewArgs;
	$temp["listino"] = "tutti";
	?>
	<?php if (count($listini) > 0) { ?>
	<a href="<?php echo $this->baseUrl."/combinazioni/main".Url::createUrl($temp);?>" class="btn btn-<?php if ($this->viewArgs["listino"] == "tutti") { ?>info<?php } else { ?>default<?php } ?> pull-right"><?php echo gtext("Listino");?> <?php echo findTitoloDaCodice(v("nazione_default"));?></a>
	<?php } ?>
<?php } ?>

<?php echo $menu; ?>

<?php if (v("immagine_in_varianti")) { ?>
<script>
$(document).ready(function(){

	$("body").on("click", ".immagine_variante", function(){
		if ($(".immagine_corrente").length > 0)
			$(".immagine_corrente").removeClass("immagine_corrente");
		
		$(this).addClass("immagine_corrente");
		
		var htmlBody = $(this).parent().find(".box_immagini_varianti").html();
		
		$('#modaleImmagini').on('shown.bs.modal',function() {
			$(this).find('.modal-body').html(htmlBody);
		});
		
		$('#modaleImmagini').modal({show:true});
	});
	
	$("body").on("click", ".seleziona_immagine_variante", function(){
		
		var dataImg = $(this).attr("data-img");
		var dataSrc = $(this).attr("src");
		
		if ($(".immagine_corrente").length > 0)
		{
			$(".immagine_corrente").attr("src", dataSrc);
			$(".immagine_corrente").parent().find("input").val(dataImg);
			$(".immagine_corrente").removeClass("immagine_corrente");
		}
		
		$('#modaleImmagini').modal("hide");
	});
	
});
</script>

<div class="modal" id="modaleImmagini" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php echo gtext("Seleziona immagine variante");?></h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo gtext("Chiudi");?></button>
			</div>
		</div>
	</div>
</div>
<?php } ?>
