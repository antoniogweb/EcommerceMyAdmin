<?php
if (!defined('EG')) die('Direct access not allowed!');

if (!isset($_COOKIE["ok_cookie"]) && !VariabiliModel::checkToken("var_query_string_no_cookie")) { ?>

<script>
$ = jQuery;

var myTimeOut;

$(document).ready(function(){
	
	$(".ok_cookies").click(function(e){
	
		e.preventDefault();
		
		<?php if (true || $this->controller != "home") { ?>
		$("#segnalazione_cookies_ext").animate({bottom: "-1250px"});
		<?php } ?>
		
		var url = $(this).attr("href");
		
		$.ajax({
			type: "GET",
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){
				clearTimeout(myTimeOut);
				
				<?php if (true || $this->controller == "home") { ?>
				location.reload();
				<?php } ?>
			}
		});
		
	});
});
</script>

<?php include(tpf("Elementi/Cookie/".v("stile_popup_cookie").".php")); ?>
	
<?php } ?>
