<?php
if (!defined('EG')) die('Direct access not allowed!');

if (!isset($_COOKIE["ok_cookie"])) { ?>

<script>
$ = jQuery;

var myTimeOut;

$(document).ready(function(){
	
	$(".ok_cookies").click(function(e){
	
		e.preventDefault();
		
		<?php if (!v("attiva_blocco_cookie_terzi")) { ?>
		$("#segnalazione_cookies_ext").animate({bottom: "-250px"});
		<?php } ?>
		
		$.ajax({
			type: "GET",
			url: baseUrl + "/home/settacookie",
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){
				clearTimeout(myTimeOut);
				
				<?php if (v("attiva_blocco_cookie_terzi")) { ?>
				location.reload();
				<?php } ?>
			}
		});
		
	});
});
</script>

<?php include(tpf("Elementi/Cookie/".v("stile_popup_cookie").".php")); ?>
	
<?php } ?>
