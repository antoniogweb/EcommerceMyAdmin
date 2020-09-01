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
				<iframe class="iframe_dialog" src="" frameborder="0" height="500px" width="99.6%"></iframe>
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
	window.opener.closedd = true;
	window.closed = true;
	window.close();
</script>
<?php } ?>

</body>
</html>
<?php
$ff = new FattureModel();
$ff->checkFiles();

// $mysqli = Db_Mysqli::getInstance();
// print_r($mysqli->queries);
?>
