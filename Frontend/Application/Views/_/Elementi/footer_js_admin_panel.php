<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_fasce_frontend") && User::$adminLogged && isset($_GET[v("token_edit_frontend")]) && !User::$isPhone && !isset($_GET["em_edit_frontend"])) { ?>
	<script>
	$(document).ready(function(){
		$("body").on("click", "a:not(.iframe)", function(e){
			
			e.preventDefault();
			
			var href = "";
			
			href = $(this).attr("href") + "?<?php echo v("token_edit_frontend");?>";
			
			$(this).attr("href",href);
			
			location.href = href;
		});
	});
	</script>
	
	<?php if (isset($_SERVER['REQUEST_URI'])) {
		if( !session_id() )
			session_start();
		
		if (!isset($_SESSION["last_request_uri"]))
			$_SESSION["last_request_uri"] = $_SERVER['REQUEST_URI'];
		
		if ($_SESSION["last_request_uri"] != $_SERVER['REQUEST_URI'])
		{
			$_SESSION["last_request_uri"] = $_SERVER['REQUEST_URI'];
		?>
			<script>
			$(document).ready(function(){
				window.parent.location.href = "<?php echo $_SERVER['REQUEST_URI'];?>&em_edit_frontend";
			});
			</script>
		<?php } ?>
	<?php } ?>
<?php } ?>
