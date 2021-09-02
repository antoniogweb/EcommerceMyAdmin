<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($adminUser) { ?>
<style>
	.blocco_traduzione, .blocco_testo
	{
		display:inline;
		position:relative;
	}

	.blocco_traduzione img
	{
		width:15px !important;
		min-width:15px !important;
		height: 15px !important;
	}
	
	.fascia_contenuto
	{
		position:relative;
	}
	
	.fascia_contenuto .titolo_fascia
	{
		display:none;
		position:absolute;
		right:-2px;
		top:-36px;
		background-color:#FFF;
		padding:5px 10px;
		color:#333;
		border:2px solid #333;
		border-bottom:none;
	}
	
	.fascia_contenuto .titolo_fascia a img
	{
		background-color:#FFF;
/* 		width:15px; */
		margin-left:10px;
	}
	
	.fascia_contenuto:hover
	{
/* 		background-color:#EFEFEF; */
		border:2px solid #333;
	}
	
	.fascia_contenuto:hover .titolo_fascia
	{
		display:block;
	}
	
	.edit_traduzione, .edit_blocco_testo
	{
		cursor:pointer;
		position:absolute;
		left:0px;
		top:0px;
		z-index:999;
	}
	.edit_blocco_testo i
	{
		display:none !important;
		padding:3px;
		background-color:red;
/* 		border:1px solid #CCC; */
		color:#FFF;
	}
	.blocco_testo:hover .edit_blocco_testo i, .fascia_contenuto:hover .edit_blocco_testo i
	{
		width:20px;
		display:block !important;
	}
</style>
<link href="<?php echo $this->baseUrlSrc;?>/admin/Public/Css/icons/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Public/Js/colorbox-master/example1/colorbox.css">
<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/admin/Public/Js/colorbox-master/jquery.colorbox.js"></script>
<!-- <script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/admin/Public/Js/jquery/ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<script>
	$ = jQuery;
	
	var child;
	var timer;
	
	function aggiornaOrdinamento()
	{
		var id_cont = "";
		var order = "";
		
		$(".fascia_contenuto").each(function(){
		
			var id_cont = $(this).attr("id");
		
			order += id_cont + ",";
		
		});
		
		var post_data = "order="+order+"&ordinaPagine=Y";
		
		$.ajax({
			type: "POST",
			data: post_data,
			url: "<?php echo $this->baseUrlSrc.'/admin/pages/ordinacontenuti';?>",
			async: false,
			cache:false,
			success: function(html){
				
			}
		});
	}

	function checkChild() {
		if (child.closed) {
			clearInterval(timer);
			
// 				window.location=window.location;
			
			location.reload();
		}
	}

	$(document).ready(function() {

		$(".edit_blocco_testo").click(function(e){
			
			e.preventDefault();
			
			var id_t = $(this).attr("rel");
			
			$.colorbox({
				iframe:true,
				width:"90%",
				height:"90%",
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
				width:"90%",
				height:"90%",
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
