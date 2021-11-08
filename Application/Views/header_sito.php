<?php if (!defined('EG')) die('Direct access not allowed!');
include(ROOT."/Application/Views/header.php");
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
				include(ROOT."/Application/Views/header_sito_static.php");
			} ?>
			<?php if (defined("APPS")) {
				foreach (APPS as $app)
				{
					$path = ROOT."/Application/Apps/".ucfirst($app)."/Menu/sito.php";
					
					if (file_exists($path))
						include($path);
				}
			} ?>
			<?php if (v("attiva_standard_cms_menu")) { ?>
			<li class="<?php echo tm($tm, "menu1");?> treeview help_menu">
				<a href="#">
					<i class="fa fa-list"></i>
					<span><?php echo gtext("Menù");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/menu/main?lingua=it";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/menu/form/insert/0?lingua=it";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
				</ul>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
       <div class="content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>
