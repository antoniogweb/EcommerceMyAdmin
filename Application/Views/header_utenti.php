<?php if (!defined('EG')) die('Direct access not allowed!');
include($this->viewPath("header"));
?>
	<?php if (!partial()) { ?>
	<aside class="main-sidebar">
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
		<ul class="sidebar-menu">
			<li class="header"><?php echo gtext("MENÙ GENSTIONE PREFERENZE");?></li>
			<?php
			$vociMenu = App::caricaMenu("utenti");
			
			foreach ($vociMenu as $pathVoce) {
				include($pathVoce);
			}
			?>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>
