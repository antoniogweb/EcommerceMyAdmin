<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

function aggiornaOrdinamento()
{
	var id_page = "";
	var order = "";
	
	$(".record_id").each(function(){
	
		var id_page = $(this).text();
	
		order += id_page + ",";
	
	});
	
	var post_data = "order="+order+"&ordinaPagine=Y";
	
	$.ajax({
		type: "POST",
		data: post_data,
		url: "<?php echo $this->baseUrl.'/'.$this->controller.'/ordina/';?>",
		async: false,
		cache:false,
		success: function(html){
			
		}
	});
}

$(document).ready(function(){

	$(".ordina_submit").click(function(){
		
		var id_page = "";
		var id_order = "";
		var order = "";
		
		$(".input_ordinamento").each(function(){
		
			var id_page = $(this).attr("rel");
			var id_order = $(this).val();
		
			order += id_page + ":" + id_order + "|";
		
		});

		$(".ordina_order").val(order);

	});

	$(".attivo_checkbox").click(function(){

		var t_id = $(this).attr("id");

		if ($(this).is(":checked"))
		{
			var t_value = "Y";
		}
		else
		{
			var t_value = "N";
		}

		var t_gif = $(this).parent().find(".loading_gif_del img").css("visibility","visible");
		
		$.ajax({
			url: "<?php echo $this->baseUrl.'/'.$this->controller.'/pubblica/';?>"+t_id+"/"+t_value,
			async: false,
			cache:false,
			success: function(html){
				t_gif.css("visibility","hidden");
			}
		});

	});
	
	$(".in_evidenza_checkbox").click(function(){

		var t_id = $(this).attr("id");

		if ($(this).is(":checked"))
		{
			var t_value = "Y";
		}
		else
		{
			var t_value = "N";
		}

		var t_gif = $(this).parent().find(".loading_gif_del img").css("visibility","visible");
		
		$.ajax({
			url: "<?php echo $this->baseUrl.'/'.$this->controller.'/inevidenza/';?>"+t_id+"/"+t_value,
			async: false,
			cache:false,
			success: function(html){
				t_gif.css("visibility","hidden");
			}
		});

	});
});

<?php /*if (strcmp($this->viewArgs["id_c"],"tutti") !== 0 and strcmp($this->viewArgs["id_c"],"1") !== 0 and strcmp($this->viewArgs["id_c"],$sId) !== 0  and strstr($orderBy, "id_order")) {*/ ?>
<?php if (strstr($orderBy, "id_order")) { ?>
$(function() {
	$( ".table tbody" ).sortable({
		items: "tr:not(.listFilters,.listHead,.bulk_actions_tr)",
		stop: function( event, ui ) {
			aggiornaOrdinamento();
		}
	});
});
<?php } ?>
</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?></h1>
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
				
			<div class="box">
				<div class="box-header with-border">
					<!-- start the popup menù -->
					<div class="verticalMenu">
						<?php echo $popup;?>
					</div>

<!-- 					<div class="notice_box"> -->
						<?php echo $notice;?>
<!-- 					</div> -->

					<!-- show the table -->
<!-- 					<div class='recordsBox'> -->
						<?php echo $main;?>
<!-- 					</div> -->

					<!-- show the list of pages -->
					<div class="btn-group pull-right">
						<ul class="pagination no_vertical_margin">
							<?php echo $pageList;?>
						</ul>
					</div>

                </div>
			</div>
		</div>
	</div>
</section>



