<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

$(document).ready(function(){

	var clicked_element = "";
	
// 	$(".td_val_attr").mouseenter(function(){
// 
// 		var agg_img = $(this).find(".img_attributo_aggiorna");
// 		
// 		if (agg_img.attr("rel") != "N")
// 		{
// 			agg_img.css("display","inline-block");
// 		}
// 		
// 	}).mouseleave(function(){
// 	
// 		$(this).find(".img_attributo_aggiorna").css("display","none");
// 		
// 	});

	$(".immagine_event").click(function(){
	
		clicked_element = $(this);
		
		var t_lista_immagini = $("#lista_immagini").html();
		
		open_lightbox("Scegli l'immagine da associare alla variante",t_lista_immagini);
		
		$(".lista_immagini_item").click(function(){
	
			$(".lista_immagini_item").unbind();
			
			clicked_element.parent().find(".attributo_loading").css("display","inline-block");
			
			var t_image = $(this).attr("rel");
			
			clicked_element.parent().find(".immagine_variante").attr("src",baseUrl + "/thumb/immagineinlistaprodotti/0/" + t_image);
			
			close_lightbox();
			
			var t_id = clicked_element.attr("id");
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
	
// 	$(".attributo_event").click(function(){
// 	
// 		var t_val = $(this).parent().find(".valore_attributo").text();
// 		$(this).parent().find(".update_attributo").val(t_val);
// 		
// 		$(this).attr("rel","N").css("display","none");
// 		
// 		$(this).parent().find(".valore_attributo").css("display","none");
// 		$(this).parent().find(".edit_attrib_box").css("display","inline-block");
// 		
// 	});
// 	
// 	$(".attributo_close").click(function(){
// 	
// 		$(this).parent().parent().find(".img_attributo_aggiorna").attr("rel","Y");
// 		$(this).parent().parent().find(".valore_attributo").css("display","inline-block");
// 		$(this).parent().parent().find(".edit_attrib_box").css("display","none");
// 		
// 	});
// 	
// 	$(".attributo_edit").click(function(){
// 	
// 		var t_val = $(this).parent().find(".update_attributo").val();
// 		var t_id = $(this).attr("id");
// 		var t_field = $(this).attr("rel");
// 		
// 		var that = $(this).parent().parent().find(".valore_attributo");
// 		
// 		var post_data = "id_c="+encodeURIComponent(t_id)+"&value="+encodeURIComponent(t_val)+"&field="+encodeURIComponent(t_field);
// 		
// 		$(this).parent().find(".attributo_loading").css("display","inline-block");
// 		
// 		$.ajax({
// 			type: "POST",
// 			url: "<?php echo $this->baseUrl."/pages/updatevalue";?>",
// 			data: post_data,
// 			async: true,
// 			cache:false,
// 			dataType: "html",
// 			success: function(content){
// 				
// 				if (jQuery.trim(content) == "OK")
// 				{
// 					that.text(t_val).css("display","inline-block");
// 					that.parent().find(".edit_attrib_box").css("display","none");
// 					that.parent().find(".img_attributo_aggiorna").attr("rel","Y");
// 				}
// 				else
// 				{
// 					alert(content);
// 				}
// 				
// 				that.parent().find(".attributo_loading").css("display","none");
// 				
// 			}
// 		});
// 	
// 	});
	
});

</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
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
					<div id="form_opzioni">Varianti associate a questo prodotto</div>
					
					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/attributi/$id_page".$this->viewStatus;?>' method='POST'>
						
						<?php echo Html_Form::select("id_a","",$listaAttributi,'form_select form-control help_select_attributo',null,"yes");?>
						<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
						
					</form>
					<br />
					<!-- show the table -->
					<div class='recordsBox help_elenco_varianti_associate'>
						<?php if ($numeroAttributi > 0) { ?>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">Non è stato associato alcun attributo</span>
						<?php } ?>
					</div>

					<div id="refresh_link"></div>

					<?php echo $noticeComb;?>
				</div>
			</div>
			<div class="box">
				<div class="box-header with-border main">
					<div class="box_lista_combinazioni help_elenco_combinazioni">	
						<div id="form_opzioni">Combinazioni di questo prodotto</div>
						
						<a class="link_aggiorna_combinazioni btn btn-warning" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/attributi/$id_page".$this->viewStatus;?>&action=aggiorna"><i class="fa fa-refresh"></i> Aggiorna combinazioni</a>
						
						<a class="iframe btn btn-primary help_modifica_combinazioni" href="<?php echo $this->baseUrl."/combinazioni/main/1?partial=Y&id_page=$id_page";?>"><i class="fa fa-edit"></i> Gestisci combinazioni</a>
						
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
		</div>
	</div>
</section>
