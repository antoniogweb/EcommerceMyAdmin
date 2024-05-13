<?php if (!defined('EG')) die('Direct access not allowed!');
include($this->viewPath("header"));
?>
	
	<?php if (!partial()) { ?>
<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<?php if (User::$logged and strcmp($this->action,'logout') !== 0) { ?>
			<ul class="sidebar-menu">
				<li class="header"><?php echo gtext("MENÙ GENSTIONE MARKETING");?></li>
				<?php $vociMenu = App::caricaMenu("marketing");?>
				<?php foreach ($vociMenu as $pathVoce) {
					include($pathVoce);
				} ?>
				
				
				
				
				<?php if (defined("APPS")) {
					foreach (APPS as $app)
					{
						$path = ROOT."/Application/Apps/".ucfirst($app)."/Menu/marketing.php";
						
						if (file_exists($path))
							include($path);
					}
				} ?>
			</ul>
			<?php } ?>
		</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>

	
