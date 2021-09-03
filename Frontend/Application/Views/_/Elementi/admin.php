<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($adminUser) { ?>

<link href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Css/admin.css?v=<?php echo rand(1,10000);?>" rel="stylesheet">

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
			<?php if (count(Tema::getElencoTemi()) > 1) { ?>
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
	
// 	function aggiornaOrdinamento()
// 	{
// 		var id_cont = "";
// 		var order = "";
// 		
// 		$(".fascia_contenuto").each(function(){
// 		
// 			var id_cont = $(this).attr("id");
// 		
// 			order += id_cont + ",";
// 		
// 		});
// 		
// 		var post_data = "order="+order+"&ordinaPagine=Y";
// 		
// 		$.ajax({
// 			type: "POST",
// 			data: post_data,
// 			url: "<?php echo $this->baseUrlSrc.'/admin/pages/ordinacontenuti';?>",
// 			async: false,
// 			cache:false,
// 			success: function(html){
// 				
// 			}
// 		});
// 	}

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
		
		$(".edit_blocco_testo").click(function(e){
			
			e.preventDefault();
			
			var id_t = $(this).attr("rel");
			
			$.colorbox({
				iframe:true,
				width:"95%",
				height:"95%",
				href:"<?php echo $this->baseUrlSrc;?>/admin/testi/form/update/" + id_t + "?part=Y&nobuttons=Y",
				onClosed: function(){
					location.reload();
				}
			});
			
		});
		
		$(".iframe").click(function(e){
			
			e.preventDefault();
			
			var url = $(this).attr("href");
			
			$.colorbox({
				iframe:true,
				width:"95%",
				height:"95%",
				href:url,
				onClosed: function(){
					location.reload();
				}
			});
			
		});
		
		$("body").on("click",".edit_traduzione", function(e){
		
			e.preventDefault();
			
			var id_t = $(this).attr("data-id");
			
			child = window.open("<?php echo $this->baseUrlSrc;?>/admin/traduzioni/form/update/" + id_t, 'newwindow', 'width=800, height=600, top=100, left=250, scrollbars=1');
			timer = setInterval(checkChild, 500);
			
		});
		
// 		$( ".blocco_fasce_contenuto" ).sortable({
// 			stop: function( event, ui ) {
// 				aggiornaOrdinamento();
// 			}
// 		});
	});
</script>
<?php } ?>
