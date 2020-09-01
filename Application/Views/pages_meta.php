<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/ace.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/theme-dreamweaver.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/mode-ruby.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/jquery-ace.min.js"></script>

<script>
$(document).ready(function() {
	$('[name="css"]').css("width","100%");
	$('[name="css"]').css("height","400px");
	$('[name="css"]').ace({ theme: 'dreamweaver', lang: 'ruby' })
	
});
</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
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

			<?php include($this->viewPath("steps"));?>
				
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>

					<div class="scaffold_form">
						<!-- show the table -->
						<div class='recordsBox'>
							<?php echo $main;?>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
