<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

$(document).ready(function(){

	var clicked_element = "";

	$(".immagine_variante").click(function(){
	
		clicked_element = $(this);
		
		var t_lista_immagini = $("#lista_immagini").html();
		
		open_lightbox("Scegli l'immagine da associare alla variante",t_lista_immagini);
		
		$(".lista_immagini_item").click(function(){
	
			$(".lista_immagini_item").unbind();
			
			clicked_element.parent().find(".attributo_loading").css("display","inline-block");
			
			var t_image = $(this).attr("rel");
			
			clicked_element.parent().find(".immagine_variante").attr("src",baseUrl + "/thumb/immagineinlistaprodotti/0/" + t_image);
			
			close_lightbox();
			
			var t_id = clicked_element.parent().find(".immagine_event").attr("id");
			var post_data = "id_c="+encodeURIComponent(t_id)+"&value="+encodeURIComponent(t_image)+"&field=immagine";
			
			$.ajax({
				type: "POST",
				url: "<?php echo $this->baseUrl."/pages/updatevalue";?>",
				data: post_data,
				async: true,
				cache:false,
				dataType: "html",
				success: function(content){
					
					if (jQuery.trim(content) != "OK")
					{
						alert(content);
					}
					
					clicked_element.parent().find(".attributo_loading").css("display","none");
					
				}
			});
		
		});
		
	});
	
});

</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php echo gtext("Gestione");?> <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>

			<?php include($this->viewPath("steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<?php if (true || PagesModel::variantiModificabili((int)$id_page)) { ?>
						<a style="margin-bottom:10px;" class="iframe btn btn-success pull-right" href="<?php echo $this->baseUrl."/attributi/main";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>"><i class="fa fa-pencil"></i> <?php echo gtext("Gestisci varianti")?></a>
						
						<?php if (count($listaAttributi) > 0) { ?>
						<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/attributi/$id_page".$this->viewStatus;?>' method='POST'>
							<span select2=""><?php echo Html_Form::select("id_a","",$listaAttributi,'form_select form-control help_select_attributo',null,"yes","select2=''");?></span>
							<button class="submit_file btn btn-primary make_spinner" type="submit"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi");?></button>
							<input type="hidden" name="insertAction" value="Aggiungi"/>
						</form>
						<?php } ?>
					<?php } else { ?>
					<div class="callout callout-info"><i class="fa fa-exclamation-triangle"></i> <?php
					$stringaLista = v("attiva_liste_regalo") ? "o è inserito in qualche lista regalo." : ".";
					echo gtext("Non è possibile aggiungere o eliminare varianti a questo prodotto in quanto ha già ordini o movimentazioni $stringaLista");?></div>
					<?php } ?>
					
					<div class="notice_box">
						<?php echo $notice;?>
					</div>
					
					<?php if ($numeroAttributi > 0) { ?>
					<!-- show the table -->
					<div style="margin-top:10px;" class='recordsBox help_elenco_varianti_associate'>
						<?php echo $main;?>
					</div>
					<?php } else {  ?>
					<div style="margin-top:10px;">
						<span class="label label-info"><?php echo gtext("Non è stata associata alcuna variante");?></span>
					</div>
					<?php } ?>

					<div id="refresh_link"></div>
				</div>
			</div>
			<?php if (true || $numeroAttributi > 0) { ?>
			<div class="box">
				<div class="box-header with-border main">
					<div class="box_lista_combinazioni help_elenco_combinazioni">
						<?php echo $noticeComb;?>
						
						<a style="margin-bottom:10px;" class="pull-right iframe btn btn-primary help_modifica_combinazioni" href="<?php echo $this->baseUrl."/combinazioni/main/1?partial=Y&id_page=$id_page";?>"><i class="fa fa-edit"></i> <?php echo gtext("Gestisci combinazioni");?></a>
						
						<?php if ($numeroAttributi > 0) { ?>
						<a style="margin-bottom:10px;margin-right:10px;" class="pull-right link_aggiorna_combinazioni btn btn-warning make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/attributi/$id_page".$this->viewStatus;?>&action=aggiorna"><i class="fa fa-refresh"></i> <?php echo gtext("Aggiorna combinazioni");?></a>
						<?php } ?>
						
						<a style="margin-bottom:10px;margin-right:10px;display:none;" class="pull-right btn btn-success btn_modifica_attributi help_salva_combinazioni" href="#"><i class="fa fa-save"></i> <?php echo gtext("Salva combinazioni");?></a>
						
						<div id="form_opzioni">
							<b><?php echo gtext("Combinazioni di questo prodotto");?></b>
						</div>
						
						<div class="lista_combinazioni">
						<?php if ($numeroCombinazioni > 0) { ?>
						<?php echo $listaCombinazioni;?>
						<?php } else {  ?>
						<span class="empty_list">Non c'è alcuna combinazione</span>
						<?php } ?>
						</div>
					</div>

					<div id="lista_immagini">
						<?php foreach ($listaImmagini as $i) { ?>
						<img class="lista_immagini_item" rel="<?php echo $i;?>" src="<?php echo $this->baseUrl."/thumb/immagineinlistaprodotti/0/$i";?>" />
						<?php } ?>
					</div>
                </div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
