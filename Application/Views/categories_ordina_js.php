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

function closeChildren(that)
{
	that.removeClass(fawe_folder_opened_class).addClass(fawe_folder_closed_class);
	that.closest("li").find("> ul").css("display", "none");
}

function openChildren(that)
{
	that.removeClass(fawe_folder_closed_class).addClass(fawe_folder_opened_class);
	that.closest("li").find("> ul").css("display", "block");
}

$(function() {
	$( ".ul_parent" ).sortable({
		stop: function( event, ui ) {
			aggiornaOrdinamento();
		},
		handle: '.ancora_ordinamento',
		helper: fixHelper
	});
	
	$( "body" ).on( "click", ".toogle_category_tree", function(e){
		
		var that = $(this);
		var id_record = that.parent().find(".record_id").text();
		
		if (that.hasClass(fawe_folder_opened_class))
		{
			closeChildren(that);
			salvaOpzione(id_record,fawe_folder_closed_class);
		}
		else
		{
			openChildren(that);
			salvaOpzione(id_record,fawe_folder_opened_class);
		}
	});
	
	$(".toogle_category_tree").each(function(){
		
		var that = $(this);
		
		if (that.hasClass(fawe_folder_opened_class))
			openChildren(that);
		else
			closeChildren(that);
	});
});
</script>
