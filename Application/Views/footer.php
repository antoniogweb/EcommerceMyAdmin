<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


			</div><!-- /.content-wrapper -->
		</div>
		
<!--			</div>
		</div>
	</div>-->

	<!-- lightbox -->
	<div id="light" class="white_content">
		<div id="lightbox_title"></div>
		<a class="close_lightbox" href="#"><img src="<?php echo $this->baseUrl."/Public/Img/close.png";?>" /></a>
		<div id="lightbox_content"></div>
	</div>
	<div id="fade" class="black_overlay"></div>

	<div id="my_modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<!--<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Gestisci elemento</h4>
			</div>-->
			<div class="modal-body">
				<iframe class="iframe_dialog" src="" frameborder="0" height="500px" width="100%"></iframe>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
			</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
<?php
if (isset($_GET["partial"]) and $queryResult) { ?>
<script>
if (window.opener)
{
	window.opener.closedd = true;
	window.closed = true;
	window.close();
}
</script>
<?php } ?>

<?php
if (partial() and $closeModal) { ?>
<script>
$(document).ready(function(){
	if (window.parent)
		window.parent.closeModal();
});
</script>
<?php } ?>

<?php if ($helpDaVedere && v("attiva_help_wizard")) { ?>
<style>
.joyride-tip-guide
{
	width: <?php echo $helpDaVedere[0]["help"]["larghezza"];?>px;
}
</style>
<ol id="joyRideTipContent">
	<?php foreach ($helpDaVedere as $hdv) { ?>
	<li data-options='tipLocation:<?php echo $hdv["help_item"]["posizione"];?>;' <?php if (trim($hdv["help_item"]["selettore"])) { ?>data-class="<?php echo $hdv["help_item"]["selettore"];?>"<?php } ?>>
		<div>
			<?php if ($hdv["help_item"]["mostra_titolo"] == "Y") { ?>
			<h1><?php echo htmlentitydecode($hdv["help_item"]["titolo"]);?></h1>
			<?php } ?>
			<?php echo htmlentitydecode($hdv["help_item"]["descrizione"]);?>
		</div>
		<br />
	</li>
	<?php } ?>
</ol>

<script>
$(window).load(function() {
	$("#joyRideTipContent").joyride({
		autoStart: true,
		preStepCallback: function(e, tip)
		{
			$(".joyride-next-tip").text("<?php echo gtext("Successivo")?>");
		},
		postRideCallback: function(e)
		{
			$.ajaxQueue({
				url: "<?php echo $this->baseUrl."/help/mostranascondi/".$helpDaVedere[0]["help_item"]["id_help"]."/0";?>",
				cache:false,
				async: true,
				dataType: "html",
				success: function(content){
					
				}
			});
		}
	});
});
</script>
<?php } ?>

</body>
</html>
<?php
// $mysqli = Db_Mysqli::getInstance();
// print_r($mysqli->queries);
?>
