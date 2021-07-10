<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script>
function aggiornaOrdinamento()
{
	var id_page = "";
	var order = "";
	
	$(".record_id").each(function(){
	
		var id_page = $(this).text();
	
		order += id_page + ",";
	
	});
	
	var post_data = "order="+order+"&ordinaPagine=Y";
	
// 	console.log(post_data);
	
	$.ajaxQueue({
		type: "POST",
		data: post_data,
		url: "<?php echo $this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/ordina/';?>",
		async: true,
		cache:false,
		success: function(html){
			
		}
	});
}

var fixHelper = function (e, ui) {
    ui.children().each(function () {
        $(this).width($(this).width());
    });

    return ui;
};

$(function() {
	$( ".ul_parent" ).sortable({
		stop: function( event, ui ) {
			aggiornaOrdinamento();
		},
		handle: '.ancora_ordinamento',
		helper: fixHelper
	});
});
</script>
