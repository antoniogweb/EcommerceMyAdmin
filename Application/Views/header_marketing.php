<?php if (!defined('EG')) die('Direct access not allowed!');
include(ROOT."/Application/Views/header.php");
?>
	
	<?php if (!partial()) { ?>
<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) { ?>
		<ul class="sidebar-menu">
			<li class="header"><?php echo gtext("MENÙ GENSTIONE MARKETING");?></li>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php echo tm($tm, "cart");?> treeview">
				<a href="#">
					<i class="fa fa-shopping-cart"></i>
					<span><?php echo gtext("Carrelli abbandonati")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/cart/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("attiva_sezione_contatti")) { ?>
			<li class="<?php echo tm($tm, "contatti");?> treeview">
				<a href="#">
					<i class="fa fa-user-o"></i>
					<span><?php echo gtext("Contatti")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/contatti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
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

	