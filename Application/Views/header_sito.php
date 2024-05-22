<?php if (!defined('EG')) die('Direct access not allowed!');
include($this->viewPath("header"));
?>

	<?php if (!partial()) { ?>
<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
		<ul class="sidebar-menu">
			<li class="header"><?php echo gtext("MENÙ GESTIONE SITO")?></li>
			<?php if (v("attiva_menu_db")) {
				echo MenuadminModel::creaMenu("sito");
			} else {
				$vociMenu = App::caricaMenu("sito");
				
				foreach ($vociMenu as $pathVoce) {
					include($pathVoce);
				}
			} ?>
			<?php
			$tipoMenu = "sito";
			include(ROOT."/Application/Views/header_menu_apps.php"); ?>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
       <div class="contenitore_generale content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>
