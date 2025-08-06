<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($adminUser) { ?>

<?php if (file_exists(tpf("Public/Css/admin.css"))) { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/admin.css", true);?>?v=<?php echo rand(1,10000);?>" />
<?php } else { ?>
<link href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Css/admin.css?v=<?php echo rand(1,10000);?>" rel="stylesheet">
<?php } ?>

<link href="<?php echo $this->baseUrlSrc;?>/admin/Public/Css/icons/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Public/Js/colorbox-master/example1/colorbox.css">

<?php if (v("configurazione_frontend_attiva")) { ?>
<div class="sideslider" id="sideslider" style="margin-left: -265px;">
    <div class="sideslider-tab"> <?php /*echo gtext("Configura");*/?> <i class='fa fa-cogs'></i></div>
   
	<div id="sideslider-smartbutton">
		<div id="sideslider-text">
			<span class="header"><?php echo gtext("Pannello gestione");?></span>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/categorie/main?partial=Y";?>" class="iframe"><?php echo gtext("Categorie prodotti");?></a></span>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/prodotti/main?partial=Y";?>" class="iframe"><?php echo gtext("Prodotti ecommerce");?></a></span>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/blog/main?partial=Y";?>" class="iframe"><?php echo gtext("Blog");?></a></span>
			<?php if (v("mostra_slide")) { ?>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/slide/main?partial=Y";?>" class="iframe"><?php echo gtext("Slide principale");?></a></span>
			<?php } ?>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/menu/main?partial=Y";?>" class="iframe"><?php echo gtext("Menu navigazione");?></a></span>
			<?php if (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema")) { ?>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/impostazioni/tema?partial=Y";?>" class="iframe"><?php echo gtext("Seleziona tema");?></a></span>
			<?php } ?>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/testi/main?partial=Y";?>" class="iframe"><?php echo gtext("Elementi tema");?></a></span>
			<span class="line"><a href="<?php echo $this->baseUrlSrc."/admin/traduzioni/main?partial=Y";?>" class="iframe"><?php echo gtext("Traduzione testi");?></a></span>
			<span class="line"><a target="_blank" href="<?php echo $this->baseUrlSrc."/admin/panel/main";?>"><?php echo gtext("Dashboard amministrativa");?></a></span>
		</div>
		<div class="sideclear"></div>
	</div>
	
	<div class="sideslider-close sideslider-close_en"><?php echo gtext("Chiudi"); ?>&nbsp;</div>
</div>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/jquery.side-slider.js?v=<?php echo rand(1,10000);?>"></script>
<?php } ?>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/admin/Public/Js/colorbox-master/jquery.colorbox.js"></script>


<script>
	$ = jQuery;
	
	var child;
	var timer;
	
	function aggiornaFascia(idPagina, idFascia)
	{
		<?php if (v("permetti_di_aggiungere_blocchi_da_frontend")) { ?>
			if (idFascia != undefined)
				$.ajaxQueue({
					url: baseUrl + "/contenuti/fascia/" + idPagina + "/" + idFascia,
					async: true,
					cache:false,
					dataType: "html",
					success: function(content){
						$(".fascia_contenuto[id="+idFascia+"]").html(content);
					}
				});
			else
				location.reload();
		<?php } else { ?>
			location.reload();
		<?php } ?>
	}

	function checkChild() {
		if (child.closed) {
			clearInterval(timer);
			
// 				window.location=window.location;
			
			location.reload();
		}
	}

	$(document).ready(function() {
		
		<?php if (v("configurazione_frontend_attiva")) { ?>
		$('#sideslider').sideSlider();
		<?php } ?>
		
		$("body").on("click",".aggiungi_blocco_testo_context_element", function(e){
			
			var url = $(this).attr("url");
			var tag = "["+$(this).closest(".blocco_testo").find(".testo-tag").text()+"]";
			
			var fasciaObj = $(this).closest(".fascia_contenuto");
			
			var idPagina = fasciaObj.attr("id-pagina");
			var idFascia = fasciaObj.attr("id");
			
			$.ajaxQueue({
				url: "<?php echo $this->baseUrlSrc;?>/admin" + url,
				cache:false,
				async: true,
				dataType: "html",
				method: "POST",
				data: {
					tag: tag
				},
				success: function(content){
					aggiornaFascia(idPagina, idFascia);
				}
			});
		
		});
		
		$("body").on("click",".elimina_blocco_testo", function(e){
			
			var url = $(this).attr("url");
			var tag = "["+$(this).closest(".blocco_testo").find(".testo-tag").text()+"]";
			
			var fasciaObj = $(this).closest(".fascia_contenuto");
			
			var idPagina = fasciaObj.attr("id-pagina");
			var idFascia = fasciaObj.attr("id");
			
			console.log("<?php echo $this->baseUrlSrc;?>/admin" + url);
			
			$.ajaxQueue({
				url: "<?php echo $this->baseUrlSrc;?>/admin" + url,
				cache:false,
				async: true,
				dataType: "html",
				method: "POST",
				data: {
					tag: tag
				},
				success: function(content){
					aggiornaFascia(idPagina, idFascia);
				}
			});
		
		});
		
		$("body").on("click",".edit_blocco_testo", function(e){
		
			e.preventDefault();
			
			var id_t = $(this).attr("rel");
			
			var fasciaObj = $(this).closest(".fascia_contenuto");
			
			var idPagina = fasciaObj.attr("id-pagina");
			var idFascia = fasciaObj.attr("id");
			
			$.colorbox({
				iframe:true,
				width:"95%",
				height:"95%",
				href:"<?php echo $this->baseUrlSrc;?>/admin/testi/form/update/" + id_t + "?part=Y&nobuttons=Y",
				onClosed: function(){
// 					location.reload();
					aggiornaFascia(idPagina, idFascia);
				}
			});
			
		});
		
		$("body").on("click",".edit_blocco_custom", function(e){
		
			e.preventDefault();
			
			var idFasciaCustom = $(this).attr("id-fascia");
			var idTipoFiglio = $(this).attr("id-tipo-figlio");
			
			var fasciaObj = $(this).closest(".fascia_contenuto");
			
			var idPagina = fasciaObj.attr("id-pagina");
			var idFascia = fasciaObj.attr("id");
			
			$.colorbox({
				iframe:true,
				width:"95%",
				height:"95%",
				href:"<?php echo $this->baseUrlSrc;?>/admin/contenuti/figli/" + idFasciaCustom + "?partial=Y&nobuttons=Y&id_tipo_figlio=" + idTipoFiglio,
				onClosed: function(){
// 					location.reload();
					aggiornaFascia(idPagina, idFascia);
				}
			});
			
		});
		
		$("body").on("click",".iframe", function(e){
			
			e.preventDefault();
			
			var url = $(this).attr("href");
			
			var fasciaObj = $(this).closest(".fascia_contenuto");
			
			var idPagina = fasciaObj.attr("id-pagina");
			var idFascia = fasciaObj.attr("id");
			
			$.colorbox({
				iframe:true,
				width:"95%",
				height:"95%",
				href:url,
				onClosed: function(){
// 					location.reload();
					aggiornaFascia(idPagina, idFascia);
				}
			});
			
		});
		
		$("body").on("click",".edit_traduzione", function(e){
		
			e.preventDefault();
			
			var id_t = $(this).attr("data-id");
			
			child = window.open("<?php echo $this->baseUrlSrc;?>/admin/traduzioni/form/update/" + id_t, 'newwindow', 'width=800, height=600, top=100, left=250, scrollbars=1');
			timer = setInterval(checkChild, 500);
			
		});
		
		$(".fascia_contenuto").each(function(){
			
			if ($(this).width() <= 1200)
			{
				$(this).css("overflow", "visible");
				var height = $(this).find(".titolo_fascia").outerHeight();
				$(this).find(".titolo_fascia").css("top","-" + height + "px");
			}
		});
	});
</script>
<?php } ?>
